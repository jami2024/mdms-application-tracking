<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        $designations = [
            ['title' => 'General Desk', 'short_code' => 'GD', 'grade_level' => 5],
            ['title' => 'Sub-Director', 'short_code' => 'SD', 'grade_level' => 4],
            ['title' => 'Deputy Director', 'short_code' => 'DD', 'grade_level' => 3],
            ['title' => 'Assistant Director', 'short_code' => 'AD', 'grade_level' => 2],
            ['title' => 'System Administrator', 'short_code' => 'Admin', 'grade_level' => 1],
        ];

        foreach ($designations as $designation) {
            Designation::firstOrCreate(['short_code' => $designation['short_code']], $designation);
        }
    }
}
