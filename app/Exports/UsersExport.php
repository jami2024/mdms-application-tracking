<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::with('designation', 'roles')->get();
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone', 'Designation', 'Role', 'Status', 'Created At'];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->phone,
            $user->designation?->title,
            $user->roles->pluck('name')->join(', '),
            $user->status,
            $user->created_at?->format('Y-m-d'),
        ];
    }
}
