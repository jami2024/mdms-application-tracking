<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\Company;
use App\Models\Device;
use App\Models\DeviceApplications;
use App\Models\User;
use App\Models\WorkflowStep;
use App\Notifications\ApplicationStatusChanged;
use App\Support\PdfGenerator;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    // Review queue: applications currently sitting at a step owned by the
    // logged-in user's designation — either unassigned (open to anyone
    // holding that designation) or specifically assigned to this user.
    public function index(Request $request)
    {
        $user = Auth::user();

        $applications = Application::with([
            'applicant',
            'currentStep.designation',
            'workflowConfig',
            'assignedTo'
        ])
            ->where(function ($q) use ($user) {

                $q->where('assigned_to', $user->id)

                    ->orWhere(function ($q) use ($user) {

                        $q->whereNull('assigned_to')
                            ->when(
                                $user->designation_id,
                                fn($q) => $q->whereHas(
                                    'currentStep',
                                    fn($q) => $q->where(
                                        'designation_id',
                                        $user->designation_id
                                    )
                                )
                            );
                    });
            })

            // ->where('applicant_id', '=', $user->id)

            ->whereDoesntHave(
                'logs',
                fn($q) => $q->where('acted_by', $user->id)
            )

            // Application No filter
            ->when(
                $request->filled('application_no'),
                fn($q) => $q->where(
                    'application_no',
                    'like',
                    '%' . $request->application_no . '%'
                )
            )

            // Status filter
            ->when(
                $request->filled('status'),
                fn($q) => $q->where(
                    'status',
                    $request->status
                )
            )

            // Module filter
            ->when(
                $request->filled('module'),
                fn($q) => $q->whereHas(
                    'workflowConfig',
                    fn($q) => $q->where(
                        'module',
                        $request->module
                    )
                )
            )

            ->latest()
            ->paginate(15)
            ->withQueryString();

        // dd($applications, $user);


        // Available modules
        $modules = \App\Models\WorkflowConfig::query()
            ->whereNotNull('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');


        return view(
            'applications.index',
            compact(
                'applications',
                'modules'
            )
        );
    }

    public function show(Application $application)
    {
        $this->assertCanView($application);

        $application->load('applicant', 'applicable', 'currentStep.designation', 'assignedTo', 'workflowConfig.steps.designation', 'logs.actor', 'logs.fromStep', 'logs.toStep', 'comments.user', 'payments', 'certificate');

        $packageApplication = DB::table('applications')
            ->select(
                'packaging_applications.*',

                'devices.id as device_id',
                'devices.device_name',
                'devices.model_no',
                'devices.manufacturer',
                'devices.country_of_origin',
                'devices.product_grade_id',
                'devices.registration_no',
                'devices.registration_date',
                'devices.expiry_date'
            )
            ->join(
                'packaging_applications',
                'packaging_applications.id',
                '=',
                'applications.applicable_id'
            )
            ->join(
                'device_applications',
                'device_applications.id',
                '=',
                'packaging_applications.device_application_id'
            )
            ->join(
                'devices',
                'devices.id',
                '=',
                'device_applications.gmdn_code'
            )
            ->where(
                'packaging_applications.id',
                $application->applicable_id
            )
            ->where(
                'applications.applicable_type',
                'App\Models\PackagingApplication'
            )
            ->first();
        // dd($packageApplication);
        // $finalPackageApplication = DB::table('packaging_applications')
        //     ->select('packaging_applications.*')
        //     ->where('id', $application->applicable_id)
        //     ->where('applicable_type', 'App\Models\FinalRegistrationPackagingApplication')
        //     ->first();
        $nextDeskUsers = $this->nextDeskUsers($application);

        return view('applications.show', compact('application', 'nextDeskUsers', 'packageApplication'));
    }

    public function comment(Request $request, Application $application)
    {
        $this->assertCanView($application);

        $data = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'is_internal' => ['nullable', 'boolean'],
        ]);

        $application->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $data['comment'],
            'is_internal' => $request->boolean('is_internal', true),
        ]);

        return back()->with('status', 'মন্তব্য যোগ করা হয়েছে।');
    }

    public function forward(Request $request, Application $application)
    {
        $this->assertCanAct($application);
        $data = $request->validate([
            'remarks' => ['nullable', 'string', 'max:2000'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $currentStep = $application->currentStep;
        $nextStep = $application->workflowConfig->steps()
            ->where('step_order', '>', $currentStep?->step_order ?? 0)
            ->orderBy('step_order')
            ->first();

        if (! $nextStep) {
            return back()->with('error', 'এটি ইতিমধ্যে শেষ ধাপ — এর বদলে অনুমোদন করুন।');
        }

        // If a specific next-desk user was chosen, confirm they actually hold
        // that step's designation before assigning the case to them.
        $assignedTo = null;
        if (! empty($data['assigned_to'])) {
            $candidate = User::find($data['assigned_to']);
            if ($candidate && $candidate->designation_id === $nextStep->designation_id) {
                $assignedTo = $candidate->id;
            }
        }

        $this->logAction($application, $currentStep?->id, $nextStep->id, 'forward', $data['remarks'] ?? null);

        $application->update(['current_step_id' => $nextStep->id, 'assigned_to' => $assignedTo, 'status' => 'in_review']);

        $application->applicant->notify(new ApplicationStatusChanged($application, 'forward', $data['remarks'] ?? null));

        return back()->with('status', "{$nextStep->step_name}-এ ফরওয়ার্ড করা হয়েছে।");
    }

    public function backward(Request $request, Application $application)
    {
        $this->assertCanAct($application);
        $data = $request->validate(['remarks' => ['required', 'string', 'max:2000']]);

        $currentStep = $application->currentStep;

        if (! $currentStep || ! $currentStep->can_send_back) {
            return back()->with('error', 'এই ধাপ থেকে ফেরত পাঠানো যায় না।');
        }

        $prevStep = $application->workflowConfig->steps()
            ->where('step_order', '<', $currentStep->step_order)
            ->orderByDesc('step_order')
            ->first();

        $this->logAction($application, $currentStep->id, $prevStep?->id, 'backward', $data['remarks']);

        $application->update(['current_step_id' => $prevStep?->id, 'assigned_to' => null, 'status' => 'returned']);
        // Flip the underlying record back to draft so the applicant can edit and resubmit.
        $application->applicable?->update(['status' => 'draft']);

        $application->applicant->notify(new ApplicationStatusChanged($application, 'backward', $data['remarks']));

        return back()->with('status', 'আবেদনটি সংশোধনের জন্য ফেরত পাঠানো হয়েছে।');
    }

    public function approve(Request $request, Application $application)
    {
        try{
            DB::beginTransaction();
            $this->assertCanAct($application);
            $data = $request->validate([
                'remarks' => ['nullable', 'string', 'max:2000'],
                'assigned_to' => ['nullable', 'exists:users,id'],
            ]);
            $currentStep = $application->currentStep;

            $isFinalStep = ! $application->workflowConfig->steps()
                ->where('step_order', '>', $currentStep?->step_order ?? 0)
                ->exists();

            if (! $isFinalStep) {
                return $this->forward($request, $application);
            }

            $this->logAction($application, $currentStep?->id, $currentStep?->id, 'approve', $data['remarks'] ?? null);

            $application->update(['status' => 'approved', 'assigned_to' => null, 'decided_at' => now()]);

            DeviceApplications::where('application_no', $application->application_no)->update(['status' => 'Approved']);

            $approvedStatus = [
                'company' => 'active',
                'establishment' => 'active',
                'device' => 'registered',
                'mrp' => 'approved',
            ][$application->workflowConfig->module] ?? 'active';
            $application->applicable?->update(['status' => $approvedStatus]);
            // Generate certificate for final packaging registration approvals
            $certificate = null;
            if ($application->applicable_type == "App\Models\FinalRegistrationPackagingApplication") {
                $certificate = $this->generatePackagingCertificate($application);
            }

            $application->applicant->notify(new ApplicationStatusChanged($application, 'approve', $data['remarks'] ?? null));

            if ($certificate) {
                DB::commit();
                return redirect()->route('certificates.show', $certificate)
                    ->with('status', 'আবেদন অনুমোদিত হয়েছে — সার্টিফিকেট তৈরি করা হয়েছে।');
            }
            DB::commit();
            return back()->with('status', 'আবেদন অনুমোদিত হয়েছে — চূড়ান্ত সিদ্ধান্ত রেকর্ড করা হলো।');
        }catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return back()->with('error', 'An error occurred while approving the application: ' . $e->getMessage());
        }
    }

    /**
     * Build and persist a real Certificate record + PDF for an approved
     * final packaging registration application.
     */
    protected function generatePackagingCertificate(Application $application): Certificate
    {
        // packaging_applications row via applications.applicable_id
        $packaging = DB::table('packaging_applications')
            ->where('id', $application->applicable_id)
            ->first();
        if (! $packaging) {
            throw new \RuntimeException("Packaging application not found for application #{$application->id}");
        }

        $deviceApplication = DB::table('device_applications')
            ->where('id', $packaging->device_application_id)
            ->first();
        $device = $deviceApplication
            ? DB::table('devices')->where('id', $deviceApplication->gmdn_code)->first()
            : null;
        $category = $device
            ? DB::table('devices_category')->where('id', $device->category_id)->first()
            : null;

        $company = $deviceApplication
            ? DB::table('companies')->where('id', $deviceApplication->company_id)->first()
            : null;

        $certificateNo = $this->nextCertificateNo();

        // 1. QR code
        $qrUrl = route('certificates.verify', $certificateNo);
        $qrCode = new QrCode($qrUrl);
        $writer = new PngWriter();
        $qrResult = $writer->write($qrCode);
        $qrBase64 = base64_encode($qrResult->getString());

        $qrPath = 'qr/' . $certificateNo . '.png';
        Storage::disk('public')->put($qrPath, $qrResult->getString());

        // 2. Render certificate HTML
        $html = view('pdf.packaging_certificate', [
            'certificate' => [
                'certificate_no' => $certificateNo,
                'issue_date' => now(),
                'expiry_date' => now()->addYears(5),
                'memo_no' => 'DGDA/PKG/' . now()->format('Y') . '/' . $application->id,

                'product_name' => $device->device_name ?? 'N/A',

                'generic_nature' => trim(
                    ($category->category ?? '') . ', ' . ($deviceApplication->device_class ?? ''),
                    ', '
                ),

                // pack_size: no source column found — leaving blank until confirmed
                'pack_size' => $deviceApplication->device_sizes ?? 'N/A',

                'manufacturer_details' => trim(
                    ($deviceApplication->manufacturer_name ?? '') . ', '
                        . ($deviceApplication->manufacturer_address ?? '') . ', '
                        . ($deviceApplication->manufacturer_country ?? ''),
                    ', '
                ),

                'applicant_name' => $company->applicant_full_name ?? ($company->name ?? 'N/A'),

                'applicant_address' => trim(
                    ($company->address_line_1 ?? '') . ' ' . ($company->address_line_2 ?? '')
                ) ?: ($company->address ?? 'N/A'),
            ],
            'qrCode' => $qrBase64,
        ])->render();
        $pdfPath = 'certificates/' . $certificateNo . '.pdf';
        $pdfBytes = PdfGenerator::htmlToBytes($html);
        Storage::disk('public')->put($pdfPath, $pdfBytes);

        return Certificate::create([
            'application_id' => $application->id,
            'certificate_template_id' => 5,
            'certificate_no' => $certificateNo,
            'qr_code_path' => $qrPath,
            'signature_type' => 'digital',
            'signed_by' => auth()->id(),
            'pdf_path' => $pdfPath,
            'issue_date' => now(),
            'expiry_date' => now()->addYears(5),
            'status' => 'active',
        ]);
    }

    protected function nextCertificateNo(): string
    {
        $year = now()->format('Y');
        $prefix = "MDMS-FIN-{$year}-";

        $lastSeq = Certificate::where('certificate_no', 'like', $prefix . '%')
            ->orderByDesc('certificate_no')
            ->value('certificate_no');

        $nextNum = $lastSeq ? ((int) substr($lastSeq, strlen($prefix))) + 1 : 1;

        return $prefix . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
    }

    public function reject(Request $request, Application $application)
    {
        $this->assertCanAct($application);
        $data = $request->validate(['remarks' => ['required', 'string', 'max:2000']]);
        $currentStep = $application->currentStep;

        if ($currentStep && ! $currentStep->can_reject) {
            return back()->with('error', 'এই ধাপ থেকে প্রত্যাখ্যান করা যায় না।');
        }

        $this->logAction($application, $currentStep?->id, $currentStep?->id, 'reject', $data['remarks']);

        $application->update(['status' => 'rejected', 'assigned_to' => null, 'decided_at' => now()]);
        $application->applicable?->update(['status' => 'rejected']);

        $application->applicant->notify(new ApplicationStatusChanged($application, 'reject', $data['remarks']));

        return back()->with('status', 'আবেদন প্রত্যাখ্যাত হয়েছে।');
    }

    // Users holding the NEXT step's designation — for the "forward to a
    // specific person" dropdown. Empty when already on the final step.
    private function nextDeskUsers(Application $application): \Illuminate\Support\Collection
    {
        $currentStep = $application->currentStep;
        $nextStep = $application->workflowConfig?->steps
            ?->where('step_order', '>', $currentStep?->step_order ?? 0)
            ?->sortBy('step_order')
            ?->first();

        if (! $nextStep) {
            return collect();
        }

        return User::where('designation_id', $nextStep->designation_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    // Segregation of duties, enforced server-side (not just hidden in the UI):
    //   1. Nobody can act on an application they themselves submitted.
    //   2. If the case has been assigned to a specific person (via Forward's
    //      next-desk picker), only that person — or Admin — may act on it.
    //      Otherwise, anyone holding the current step's designation may.
    //   3. A four-eyes rule: whoever already acted on this application at an
    //      earlier step (GD, say) cannot also act on it at a later step (SD,
    //      DD, AD...) even if they happen to hold both designations. This
    //      applies to Admin too — the override in (2) is for unblocking a
    //      case assigned to someone else, not for reviewing your own earlier
    //      decision under a different hat.
    private function assertCanAct(Application $application): void
    {
        $user = auth()->user();

        abort_if($application->applicant_id === $user->id, 403, 'আপনি নিজের জমা দেওয়া আবেদনে অ্যাকশন নিতে পারবেন না।');

        // abort_if(
        //     $application->logs()->where('acted_by', $user->id)->exists(),
        //     403,
        //     'আপনি এই আবেদনে আগের কোনো ধাপে ইতিমধ্যে কাজ করেছেন — একই আবেদনে দুইবার রিভিউ করা যায় না।'
        // );

        if ($user->hasRole('Admin')) {
            return;
        }

        if ($application->assigned_to) {
            abort_if($application->assigned_to !== $user->id, 403, 'এই আবেদনটি নির্দিষ্টভাবে অন্য একজনকে বরাদ্দ করা হয়েছে।');
            return;
        }

        abort_if(
            ! $application->currentStep || $application->currentStep->designation_id !== $user->designation_id,
            403,
            'এই আবেদনটি এই মুহূর্তে আপনার পর্যালোচনার ধাপে নেই।'
        );
    }

    // Viewing is slightly wider than acting: the applicant, whoever is
    // currently assigned the step, whoever already acted on it earlier
    // (to see the outcome of their own decision), and Admin can all open it.
    private function assertCanView(Application $application): void
    {
        $user = auth()->user();

        $allowed = $user->hasRole('Admin')
            || $application->applicant_id === $user->id
            || $application->assigned_to === $user->id
            || ($application->currentStep && $application->currentStep->designation_id === $user->designation_id)
            || $application->logs()->where('acted_by', $user->id)->exists();

        abort_unless($allowed, 403, 'এই আবেদনে আপনার প্রবেশাধিকার নেই।');
    }

    private function logAction(Application $application, ?int $fromStepId, ?int $toStepId, string $action, ?string $remarks): void
    {
        $application->logs()->create([
            'from_step_id' => $fromStepId,
            'to_step_id' => $toStepId,
            'action' => $action,
            'remarks' => $remarks,
            'acted_by' => auth()->id(),
            'acted_at' => now(),
        ]);

        activity('application')->causedBy(auth()->user())->performedOn($application)
            ->withProperties(['action' => $action, 'remarks' => $remarks])
            ->log("Application {$action}ed");
    }
}
