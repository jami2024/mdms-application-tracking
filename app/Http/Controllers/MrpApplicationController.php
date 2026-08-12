<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\MrpApplication;
use App\Services\WorkflowSubmissionService;
use Illuminate\Http\Request;

class MrpApplicationController extends Controller
{
    public function __construct(private WorkflowSubmissionService $workflow)
    {
    }

    public function create(Company $company)
    {
        $this->authorizeCompany($company);
        // Only registered devices can have an MRP filed against them.
        $devices = $company->devices()->where('status', 'registered')->get();

        return view('mrp-applications.create', compact('company', 'devices'));
    }

    public function store(Request $request, Company $company)
    {
        $this->authorizeCompany($company);
        $data = $request->validate([
            'device_id' => ['required', 'exists:devices,id'],
            'proposed_mrp' => ['required', 'numeric', 'min:0'],
        ]);

        $mrp = MrpApplication::create([
            'company_id' => $company->id,
            'device_id' => $data['device_id'],
            'proposed_mrp' => $data['proposed_mrp'],
            'currency' => 'BDT',
            'status' => 'draft',
        ]);

        return redirect()->route('companies.show', $company)->with('status', 'MRP application saved as draft.');
    }

    public function show(MrpApplication $mrpApplication)
    {
        $this->authorizeCompany($mrpApplication->company);
        $mrpApplication->load('company', 'device', 'application');

        return view('mrp-applications.show', ['mrp' => $mrpApplication]);
    }

    public function submit(Request $request, MrpApplication $mrpApplication)
    {
        $this->authorizeCompany($mrpApplication->company);

        if ($mrpApplication->status !== 'draft') {
            return back()->with('error', 'Only a draft MRP application can be submitted.');
        }

        $application = $this->workflow->submit($mrpApplication, 'mrp', $request->user()->id);

        return redirect()->route('applications.show', $application)->with('status', 'MRP application submitted for review.');
    }

    public function destroy(MrpApplication $mrpApplication)
    {
        $this->authorizeCompany($mrpApplication->company);

        if ($mrpApplication->status !== 'draft') {
            return back()->with('error', 'Only a draft MRP application can be deleted.');
        }

        $company = $mrpApplication->company;
        $mrpApplication->delete();

        return redirect()->route('companies.show', $company)->with('status', 'Draft deleted.');
    }

    private function authorizeCompany(Company $company): void
    {
        $user = auth()->user();
        abort_unless($user->user_type !== 'applicant' || $company->owner_user_id === $user->id, 403);
    }
}
