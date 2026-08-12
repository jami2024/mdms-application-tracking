<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'users.manage', 'roles.manage', 'organogram.manage',
            'workflow.manage', 'settings.manage',
            'applications.review', 'applications.approve', 'applications.reject',
            'certificates.issue', 'payments.manage',
            'reports.view', 'reports.export', 'activity-log.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Roles mirror the workflow designations from the spec: SD, AD, DD, GD, Admin
        $roles = [
            'Admin' => $permissions, // full access
            'GD' => ['applications.review', 'reports.view'],
            'SD' => ['applications.review', 'applications.approve', 'applications.reject', 'reports.view'],
            'DD' => ['applications.review', 'applications.approve', 'applications.reject', 'certificates.issue', 'reports.view'],
            'AD' => ['applications.review', 'applications.approve', 'applications.reject', 'certificates.issue', 'payments.manage', 'reports.view', 'reports.export'],
            'Applicant' => [],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
