<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RevenueExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private array $filters = [])
    {
    }

    public function collection()
    {
        return Payment::with('user', 'application')
            ->when($this->filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($this->filters['method'] ?? null, fn ($q, $v) => $q->where('method', $v))
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return ['Reference', 'Payer', 'Description', 'Method', 'Amount', 'Status', 'Paid At'];
    }

    public function map($payment): array
    {
        return [
            $payment->reference,
            $payment->user->name,
            $payment->description,
            $payment->method,
            number_format($payment->amount, 2),
            ucfirst($payment->status),
            $payment->paid_at?->format('Y-m-d'),
        ];
    }
}
