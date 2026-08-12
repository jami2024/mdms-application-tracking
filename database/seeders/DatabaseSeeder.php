<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            DesignationSeeder::class,
            ProductGradeSeeder::class,
            GeographySeeder::class,
            WorkflowSeeder::class,
            CertificateTemplateSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
