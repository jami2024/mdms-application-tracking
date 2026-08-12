<?php

namespace Database\Seeders;

use App\Models\Designation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminDesignation = Designation::where('short_code', 'Admin')->first();

        $admin = User::firstOrCreate(
            ['email' => 'admin@mdms.test'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'status' => 'active',
                'designation_id' => $adminDesignation?->id,
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('Admin');
    }
}
