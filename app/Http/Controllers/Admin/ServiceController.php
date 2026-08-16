<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ServiceApplication;
use App\Models\WorkflowConfig;
use App\Support\PdfGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $configData = WorkflowConfig::get();
        return view('service.add_new')->with('configData');
    }

    public function applicationNewStore(Request $request){

        // dd($request->all());
        // "applicant_name" => "Stacey Larsen"
        // "mobile_number" => "3447555942"
        // "email" => "qybati@gmail.com"
        // "nid" => "74"
        // "company_name" => "Weeks and Fields Trading"
        // "designation" => "Facilis commodi aute"
        // "tin" => "47"
        // "document_type" => "driving_license"
        // "document_number" => "9753351246"
        // "remarks" => "Excepturi magna et q"

        $validator = Validator::make($request->all(), [
            'applicant_name' => 'required|string|max:100',
            'mobile_number' => ['required', 'regex:/^01[1-9][0-9]{8}$/'],
            'email' => 'nullable|email|max:100',
            'company_name' => 'required|string|max:100',
            'designation' => 'required|string|max:50',
            'remarks' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Process the valid data...
        $validatedData = $validator->validated();
        $validatedData['status'] = 'pending';
        $validatedData['created_by'] = Auth::user()->id;

        // Log the activity
        // activity()
        //     ->causedBy(auth()->user())
        //     ->withProperties($validatedData)
        //     ->log('New service application submitted');

        $applicationId = null;

        try {
            // Save the validated data to the database
            $lastId = ServiceApplication::insertGetId($validatedData);

            $applicationId = 'APP-SERVICE-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);

            // Create WF Application Instance
            $application = new Application();
            $application->application_no = $applicationId;
            $application->applicable_type = ServiceApplication::class;
            $application->applicable_id = $lastId;
            $application->workflow_config_id = 1; // Assuming workflow_config_id is 1
            $application->current_step_id = 29; // Assuming current_step_id is 1
            $application->assigned_to = 10; // Assuming assigned_to is null initially
            $application->applicant_id = 6; // Auth::user()->id; Assuming the applicant is the currently authenticated user
            $application->status = 'submitted'; // Assuming status is 'submitted' initially
            $application->submitted_at = now();
            $application->save();

        } catch (\Exception $e) {
            // Handle any exceptions that occur during the save operation
            return redirect()->back()
                ->with('error_message', 'An error occurred while submitting the application. Please try again.'. $e->getMessage())
                ->withInput();
        }

        return redirect()->route('services.add-new')->with([
            'success_message'=> 'সার্ভিস আবেদন সফলভাবে জমা দেওয়া হয়েছে। আপনার আবেদন নম্বর: ' . $application->application_no,
            'application_id' => $applicationId,
            'application_data' => $validatedData
        ]);
    }

    public function applicationTrack(Request $request)
    {
        // $applicationNo = $request->query('application_no');

        // $application = null;
        // if ($applicationNo) {
        //     $application = Application::where('application_no', $applicationNo)->first();
        // }

        return view('service.example-application-steps-page');
    }
}
