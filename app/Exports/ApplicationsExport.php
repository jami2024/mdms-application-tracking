<?php

namespace App\Exports;

use App\Models\Application;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApplicationsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private array $filters = [])
    {
    }

    public function collection()
    {
        return Application::with('applicant', 'workflowConfig', 'currentStep')
            ->when($this->filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($this->filters['module'] ?? null, fn ($q, $v) => $q->whereHas('workflowConfig', fn ($q) => $q->where('module', $v)))
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return ['Application No', 'Module', 'Applicant', 'Current Step', 'Status', 'Submitted', 'Decided'];
    }

    public function map($app): array
    {
        return [
            $app->application_no,
            ucfirst($app->workflowConfig?->module),
            $app->applicant->name,
            $app->currentStep->step_name ?? '—',
            ucfirst(str_replace('_', ' ', $app->status)),
            $app->submitted_at?->format('Y-m-d'),
            $app->decided_at?->format('Y-m-d'),
        ];
    }
}
