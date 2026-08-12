<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Device;
use App\Models\ProductGrade;
use App\Services\WorkflowSubmissionService;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function __construct(private WorkflowSubmissionService $workflow)
    {
    }

    public function create(Company $company)
    {
        $this->authorizeCompany($company);
        $grades = ProductGrade::where('status', 'active')->get();

        return view('devices.create', compact('company', 'grades'));
    }

    public function store(Request $request, Company $company)
    {
        $this->authorizeCompany($company);
        $data = $this->validated($request);

        $device = $company->devices()->create([...$data, 'status' => 'draft']);

        return redirect()->route('companies.show', $company)->with('status', 'Device saved as draft.');
    }

    public function show(Device $device)
    {
        $this->authorizeCompany($device->company);
        $device->load('company', 'productGrade', 'application');

        return view('devices.show', compact('device'));
    }

    public function edit(Device $device)
    {
        $this->authorizeCompany($device->company);
        $grades = ProductGrade::where('status', 'active')->get();

        return view('devices.edit', ['device' => $device, 'company' => $device->company, 'grades' => $grades]);
    }

    public function update(Request $request, Device $device)
    {
        $this->authorizeCompany($device->company);

        if (! in_array($device->status, ['draft', 'rejected'])) {
            return back()->with('error', 'Cannot edit while under review.');
        }

        $device->update($this->validated($request));

        return redirect()->route('devices.show', $device)->with('status', 'Device updated.');
    }

    public function submit(Request $request, Device $device)
    {
        $this->authorizeCompany($device->company);

        if ($device->status !== 'draft') {
            return back()->with('error', 'Only a draft device can be submitted.');
        }

        $application = $this->workflow->submit($device, 'device', $request->user()->id);

        return redirect()->route('applications.show', $application)->with('status', 'Device submitted for review.');
    }

    public function destroy(Device $device)
    {
        $this->authorizeCompany($device->company);

        if ($device->status !== 'draft') {
            return back()->with('error', 'Only a draft device can be deleted.');
        }

        $company = $device->company;
        $device->delete();

        return redirect()->route('companies.show', $company)->with('status', 'Draft deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'device_name' => ['required', 'string', 'max:255'],
            'model_no' => ['nullable', 'string', 'max:100'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'country_of_origin' => ['nullable', 'string', 'max:100'],
            'product_grade_id' => ['nullable', 'exists:product_grades,id'],
        ]);
    }

    private function authorizeCompany(Company $company): void
    {
        $user = auth()->user();
        abort_unless($user->user_type !== 'applicant' || $company->owner_user_id === $user->id, 403);
    }
}
