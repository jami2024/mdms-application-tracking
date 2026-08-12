<?php

namespace Database\Seeders;

use App\Models\ProductGrade;
use Illuminate\Database\Seeder;

class ProductGradeSeeder extends Seeder
{
    public function run(): void
    {
        $grades = [
            ['name' => 'Grade A', 'code' => 'A', 'description' => 'Low risk devices'],
            ['name' => 'Grade B', 'code' => 'B', 'description' => 'Low-moderate risk devices'],
            ['name' => 'Grade C', 'code' => 'C', 'description' => 'Moderate-high risk devices'],
            ['name' => 'Grade D', 'code' => 'D', 'description' => 'High risk devices'],
        ];

        foreach ($grades as $grade) {
            ProductGrade::firstOrCreate(['code' => $grade['code']], $grade);
        }
    }
}
