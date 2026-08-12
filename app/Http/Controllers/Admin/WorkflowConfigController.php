<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\WorkflowConfig;
use App\Models\WorkflowStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkflowConfigController extends Controller
{
    public function index()
    {
        $configs = WorkflowConfig::withCount('steps')->orderBy('name')->paginate(15);
        return view('admin.workflow-configs.index', compact('configs'));
    }

    public function create()
    {
        return view('admin.workflow-configs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'module' => ['required', 'in:company,establishment,device,mrp,package,final_package_approval'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $config = WorkflowConfig::create([...$data, 'is_active' => true]);

        return redirect()->route('admin.workflow-configs.edit', $config)->with('status', 'Workflow created — now add its steps.');
    }

    public function edit(WorkflowConfig $workflowConfig)
    {
        $designations = Designation::where('status', 'active')->orderBy('grade_level')->get();
        $steps = $workflowConfig->steps()->with('designation')->get();

        return view('admin.workflow-configs.edit', ['config' => $workflowConfig, 'designations' => $designations, 'steps' => $steps]);
    }

    public function update(Request $request, WorkflowConfig $workflowConfig)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'module' => ['required', 'in:company,establishment,device,mrp'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $workflowConfig->update([...$data, 'is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.workflow-configs.edit', $workflowConfig)->with('status', 'Workflow updated.');
    }

    public function destroy(WorkflowConfig $workflowConfig)
    {
        $workflowConfig->delete();
        return redirect()->route('admin.workflow-configs.index')->with('status', 'Workflow deleted.');
    }

    // --- Steps (nested under a config) ---

    public function storeStep(Request $request, WorkflowConfig $workflowConfig)
    {
        $data = $request->validate([
            'step_name' => ['required', 'string', 'max:255'],
            'designation_id' => ['required', 'exists:designations,id'],
            'action_type' => ['required', 'in:review,approve,reject,forward,sign'],
            'sla_days' => ['nullable', 'integer', 'min:1'],
            'can_reject' => ['nullable', 'boolean'],
            'can_send_back' => ['nullable', 'boolean'],
        ]);

        $nextOrder = ($workflowConfig->steps()->max('step_order') ?? 0) + 1;

        WorkflowStep::create([
            ...$data,
            'workflow_config_id' => $workflowConfig->id,
            'step_order' => $nextOrder,
            'can_reject' => $request->boolean('can_reject', true),
            'can_send_back' => $request->boolean('can_send_back', true),
        ]);

        return back()->with('status', 'Step added.');
    }

    public function destroyStep(WorkflowConfig $workflowConfig, WorkflowStep $step)
    {
        $step->delete();
        return back()->with('status', 'Step removed.');
    }

    // public function reorderStep(Request $request, WorkflowConfig $workflowConfig, WorkflowStep $step)
    // {
    //     $data = $request->validate(['direction' => ['required', 'in:up,down']]);

    //     $swapWith = $data['direction'] === 'up'
    //         ? $workflowConfig->steps()->where('step_order', '<', $step->step_order)->orderByDesc('step_order')->first()
    //         : $workflowConfig->steps()->where('step_order', '>', $step->step_order)->orderBy('step_order')->first();

    //     if ($swapWith) {
    //         [$a, $b] = [$step->step_order, $swapWith->step_order];
    //         $step->update(['step_order' => $b]);
    //         $swapWith->update(['step_order' => $a]);
    //     }

    //     return back();
    // }

    public function reorderStep(Request $request, WorkflowConfig $workflowConfig, WorkflowStep $step)
    {
        $data = $request->validate(['direction' => ['required', 'in:up,down']]);

        $swapWith = $data['direction'] === 'up'
            ? $workflowConfig->steps()->where('step_order', '<', $step->step_order)->orderByDesc('step_order')->first()
            : $workflowConfig->steps()->where('step_order', '>', $step->step_order)->orderBy('step_order')->first();

        if ($swapWith) {
            DB::transaction(function () use ($workflowConfig, $step, $swapWith) {
                [$a, $b] = [$step->step_order, $swapWith->step_order];

                // Park $step at a value guaranteed not to collide with any real step_order
                $tempOrder = $workflowConfig->steps()->max('step_order') + 1;

                $step->update(['step_order' => $tempOrder]);
                $swapWith->update(['step_order' => $a]);
                $step->update(['step_order' => $b]);
            });
        }

        return back();
    }
    
}
