<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Establishment;
use App\Services\WorkflowSubmissionService;
use Illuminate\Http\Request;

class EstablishmentController extends Controller
{
    public function __construct(private WorkflowSubmissionService $workflow)
    {
    }

    public function create(Company $company)
    {
        $this->authorizeCompany($company);
        return view('establishments.create', compact('company'));
    }

    public function store(Request $request, Company $company)
    {
        $this->authorizeCompany($company);
        $data = $this->validated($request);

        $establishment = $company->establishments()->create([...$data, 'status' => 'draft']);

        return redirect()->route('companies.show', $company)->with('status', 'Establishment saved as draft.');
    }

    public function show(Establishment $establishment)
    {
        $this->authorizeCompany($establishment->company);
        $establishment->load('company', 'division', 'district', 'application');

        return view('establishments.show', compact('establishment'));
    }

    public function edit(Establishment $establishment)
    {
        $this->authorizeCompany($establishment->company);
        return view('establishments.edit', ['establishment' => $establishment, 'company' => $establishment->company]);
    }

    public function update(Request $request, Establishment $establishment)
    {
        $this->authorizeCompany($establishment->company);

        if (! in_array($establishment->status, ['draft', 'rejected'])) {
            return back()->with('error', 'Cannot edit while under review.');
        }

        $establishment->update($this->validated($request));

        return redirect()->route('establishments.show', $establishment)->with('status', 'Establishment updated.');
    }

    public function submit(Request $request, Establishment $establishment)
    {
        $this->authorizeCompany($establishment->company);

        if ($establishment->status !== 'draft') {
            return back()->with('error', 'Only a draft establishment can be submitted.');
        }

        $application = $this->workflow->submit($establishment, 'establishment', $request->user()->id);

        return redirect()->route('applications.show', $application)->with('status', 'Establishment submitted for review.');
    }

    public function destroy(Establishment $establishment)
    {
        $this->authorizeCompany($establishment->company);

        if ($establishment->status !== 'draft') {
            return back()->with('error', 'Only a draft establishment can be deleted.');
        }

        $company = $establishment->company;
        $establishment->delete();

        return redirect()->route('companies.show', $company)->with('status', 'Draft deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'license_no' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'upazila_id' => ['nullable', 'exists:upazilas,id'],
            'license_issue_date' => ['nullable', 'date'],
            'license_expiry_date' => ['nullable', 'date', 'after:license_issue_date'],
        ]);
    }

    private function authorizeCompany(Company $company): void
    {
        $user = auth()->user();
        abort_unless($user->user_type !== 'applicant' || $company->owner_user_id === $user->id, 403);
    }
}
