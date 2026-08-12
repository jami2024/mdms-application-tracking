<?php

namespace App\Services;

use App\Models\Application;
use App\Models\WorkflowConfig;
use Illuminate\Database\Eloquent\Model;

class WorkflowSubmissionService
{
    // Wraps any applicant-module record (Company/Establishment/Device/MrpApplication)
    // in an `applications` row and drops it on the first step of that module's
    // active workflow. Shared by all four submission controllers so the
    // application-number format and starting-step logic live in exactly one place.
    // If the record was previously sent back (and is being resubmitted), the
    // existing application row is reset and reused rather than duplicated.
    public function submit(Model $applicable, string $module, int $applicantId): Application
    {
        $workflowConfig = WorkflowConfig::where('module', $module)->where('is_active', true)->first();
        $firstStep = $workflowConfig?->steps()->orderBy('step_order')->first();

        $applicable->update(['status' => 'submitted']);

        $existing = Application::where('applicable_type', get_class($applicable))
            ->where('applicable_id', $applicable->id)
            ->first();

        if ($existing) {
            $existing->update([
                'workflow_config_id' => $workflowConfig?->id,
                'current_step_id' => $firstStep?->id,
                'status' => 'submitted',
                'submitted_at' => now(),
                'decided_at' => null,
            ]);

            return $existing;
        }

        return Application::create([
            'application_no' => $this->generateApplicationNo($module),
            'applicable_type' => get_class($applicable),
            'applicable_id' => $applicable->id,
            'workflow_config_id' => $workflowConfig?->id,
            'current_step_id' => $firstStep?->id,
            'applicant_id' => $applicantId,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
    }

    private function generateApplicationNo(string $module): string
    {
        $prefix = strtoupper(substr($module, 0, 3));
        $year = now()->year;
        $sequence = Application::whereYear('created_at', $year)->count() + 1;

        return sprintf('APP-%s-%d-%05d', $prefix, $year, $sequence);
    }
}
