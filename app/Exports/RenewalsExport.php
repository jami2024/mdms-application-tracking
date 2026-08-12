<?php

namespace App\Exports;

use App\Models\Certificate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RenewalsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Certificate::with('application.applicant', 'template')
            ->where('status', 'active')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(90))
            ->orderBy('expiry_date')
            ->get();
    }

    public function headings(): array
    {
        return ['Certificate No', 'Module', 'Applicant', 'Issue Date', 'Expiry Date', 'Days Remaining'];
    }

    public function map($cert): array
    {
        return [
            $cert->certificate_no,
            ucfirst($cert->template->module),
            $cert->application->applicant->name,
            $cert->issue_date->format('Y-m-d'),
            $cert->expiry_date->format('Y-m-d'),
            now()->diffInDays($cert->expiry_date, false),
        ];
    }
}
