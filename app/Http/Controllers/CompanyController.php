<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Division;
use App\Services\WorkflowSubmissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function __construct(private WorkflowSubmissionService $workflow)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Applicants see only their own companies; staff/admin see everything
        // (they arrive here from the review queue, not this list).
        $companies = Company::with('division', 'district')
            ->when($user->user_type === 'applicant', fn ($q) => $q->where('owner_user_id', $user->id))
            ->latest()
            ->paginate(15);

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        $divisions = Division::with('districts.upazilas')->where('status', 'active')->get();
        return view('companies.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data = $this->handleUploads($request, $data);

        $company = Company::create([
            ...$data,
            'owner_user_id' => $request->user()->id,
            'status' => 'draft',
            'verification_status' => 'pending',
        ]);

        if (! $request->user()->company_id) {
            $request->user()->update(['company_id' => $company->id]);
        }

        return redirect()->route('companies.show', $company)->with('status', 'প্রতিষ্ঠান খসড়া হিসেবে সংরক্ষিত হয়েছে। বিস্তারিত পর্যালোচনা করে জমা দিন।');
    }

    public function show(Company $company)
    {
        $this->authorizeOwner($company);
        $company->load('division', 'district', 'upazila', 'establishments', 'devices');

        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        $this->authorizeOwner($company);
        $divisions = Division::with('districts.upazilas')->where('status', 'active')->get();

        return view('companies.edit', compact('company', 'divisions'));
    }

    public function update(Request $request, Company $company)
    {
        $this->authorizeOwner($company);

        if (! in_array($company->status, ['draft', 'rejected'])) {
            return back()->with('error', 'পর্যালোচনাধীন থাকা অবস্থায় প্রতিষ্ঠানের তথ্য সম্পাদনা করা যাবে না।');
        }

        $data = $this->validated($request, $company);
        $data = $this->handleUploads($request, $data, $company);

        $company->update($data);

        return redirect()->route('companies.show', $company)->with('status', 'প্রতিষ্ঠানের তথ্য হালনাগাদ হয়েছে।');
    }

    public function submit(Request $request, Company $company)
    {
        $this->authorizeOwner($company);

        if ($company->status !== 'draft') {
            return back()->with('error', 'শুধু খসড়া অবস্থায় থাকা প্রতিষ্ঠান জমা দেওয়া যায়।');
        }

        $application = $this->workflow->submit($company, 'company', $request->user()->id);

        return redirect()->route('applications.show', $application)->with('status', 'প্রতিষ্ঠান পর্যালোচনার জন্য জমা দেওয়া হয়েছে।');
    }

    public function destroy(Company $company)
    {
        $this->authorizeOwner($company);

        if ($company->status !== 'draft') {
            return back()->with('error', 'শুধু খসড়া অবস্থায় থাকা প্রতিষ্ঠান মুছে ফেলা যায়।');
        }

        $company->delete();
        return redirect()->route('companies.index')->with('status', 'খসড়া মুছে ফেলা হয়েছে।');
    }

    // Mock verification — same pattern as the mock payment gateway (Phase 8):
    // a real deployment would send an SMS/email OTP via a gateway and require
    // the applicant to enter the code. No SMS/email provider is configured in
    // this environment, so these mark verified immediately for demo purposes.
    public function verifyMobile(Company $company)
    {
        $this->authorizeOwner($company);
        $company->update(['mobile_verified_at' => now()]);

        return back()->with('status', 'মোবাইল নম্বর যাচাই করা হয়েছে। (ডেমো মোড — বাস্তব এসএমএস ওটিপি নয়)');
    }

    public function verifyEmail(Company $company)
    {
        $this->authorizeOwner($company);
        $company->update(['email_verified_at' => now()]);

        return back()->with('status', 'ইমেইল যাচাই করা হয়েছে। (ডেমো মোড — বাস্তব ইমেইল ওটিপি নয়)');
    }

    private function validated(Request $request, ?Company $company = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'upazila_id' => ['nullable', 'exists:upazilas,id'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'contact_email' => ['nullable', 'email', 'max:255'],

            // Section 1: Applicant Identity
            'applicant_type' => ['nullable', 'in:corporate,direct_importer,local_agent,foreign_enterprise'],
            'name_prefix' => ['nullable', 'in:mr,ms,dr'],
            'applicant_full_name' => ['nullable', 'string', 'max:255'],
            'mobile_number' => ['nullable', 'string', 'max:20'],
            'national_id' => ['nullable', 'string', 'max:20', 'unique:companies,national_id' . ($company ? ',' . $company->id : '')],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'nid_photo' => ['nullable', 'image', 'max:2048'],
            'gender' => ['nullable', 'in:male,female,other'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'applicant_designation' => ['nullable', 'string', 'max:255'],
            'primary_email' => ['nullable', 'email', 'max:255', 'unique:companies,primary_email' . ($company ? ',' . $company->id : '')],

            // Section 2: Statutory Business Credentials
            'organization_type' => ['nullable', 'in:private_limited,public_ltd,proprietorship,partnership,hospital_institute'],
            'address_line_1' => ['nullable', 'string', 'max:500'],
            'address_line_2' => ['nullable', 'string', 'max:500'],
            'post_code' => ['nullable', 'string', 'max:10'],
            'corporate_contact' => ['nullable', 'string', 'max:50'],
            'fax_number' => ['nullable', 'string', 'max:50'],

            'trade_license_no' => ['nullable', 'string', 'max:100'],
            'trade_license_file' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'tin_no' => ['nullable', 'string', 'max:100'],
            'tin_file' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'bin_no' => ['nullable', 'string', 'max:100'],
            'bin_file' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'rjsc_registration_number' => ['nullable', 'string', 'max:100'],
            'rjsc_file' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'irc_number' => ['nullable', 'string', 'max:100'],
            'irc_file' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],

            // Section 3: Legal Undertaking
            'signed_declaration_file' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
        ]);
    }

    // Uploads are stripped from $data (they arrive as UploadedFile objects) and
    // stored, with the resulting path substituted back in and a *_signed_at /
    // verified timestamp stamped where relevant.
    private function handleUploads(Request $request, array $data, ?Company $company = null): array
    {
        $fileFields = [
            'nid_photo', 'trade_license_file', 'tin_file', 'bin_file',
            'rjsc_file', 'irc_file', 'signed_declaration_file',
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Replace the old file if one exists, to avoid orphaning uploads.
                if ($company && $company->$field) {
                    Storage::disk('public')->delete($company->$field);
                }
                $data[$field] = $request->file($field)->store('company-documents', 'public');

                if ($field === 'signed_declaration_file') {
                    $data['declaration_signed_at'] = now();
                }
            } else {
                unset($data[$field]);
            }
        }

        return $data;
    }

    private function authorizeOwner(Company $company): void
    {
        $user = auth()->user();
        abort_unless($user->user_type !== 'applicant' || $company->owner_user_id === $user->id, 403);
    }
}
