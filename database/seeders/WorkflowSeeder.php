<?php

namespace Database\Seeders;

use App\Models\Designation;
use App\Models\WorkflowConfig;
use Illuminate\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            'company' => 'Company Registration Workflow',
            'establishment' => 'Establishment License Workflow',
            'device' => 'Device Registration Workflow',
            'mrp' => 'MRP Application Workflow',
        ];

        foreach ($configs as $module => $name) {
            $config = WorkflowConfig::firstOrCreate(['module' => $module], ['name' => $name, 'is_active' => true]);

            if ($config->steps()->exists()) {
                continue;
            }

            // Standard GD -> SD -> DD -> AD chain; AD is the final approving authority.
            $chain = [
                ['code' => 'GD', 'name' => 'Initial Desk Review', 'action' => 'review'],
                ['code' => 'SD', 'name' => 'Sub-Director Review', 'action' => 'review'],
                ['code' => 'DD', 'name' => 'Deputy Director Review', 'action' => 'review'],
                ['code' => 'AD', 'name' => 'Assistant Director Approval', 'action' => 'approve'],
            ];

            foreach ($chain as $i => $step) {
                $designation = Designation::where('short_code', $step['code'])->first();
                if (! $designation) {
                    continue;
                }
                $config->steps()->create([
                    'step_order' => $i + 1,
                    'step_name' => $step['name'],
                    'designation_id' => $designation->id,
                    'action_type' => $step['action'],
                    'can_reject' => true,
                    'can_send_back' => true,
                ]);
            }
        }
    }
}
