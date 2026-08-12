<?php

namespace App\Http\Controllers;

use App\Exports\ApplicationsExport;
use App\Exports\RenewalsExport;
use App\Exports\RevenueExport;
use App\Models\Application;
use App\Models\Certificate;
use App\Models\Payment;
use App\Support\PdfGenerator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function applications(Request $request)
    {
        $applications = Application::with('applicant', 'workflowConfig', 'currentStep')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->module, fn ($q) => $q->whereHas('workflowConfig', fn ($q) => $q->where('module', $request->module)))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('reports.applications', compact('applications'));
    }

    public function applicationsExportExcel(Request $request)
    {
        return Excel::download(new ApplicationsExport($request->only('status', 'module')), 'applications-report-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function applicationsExportPdf(Request $request)
    {
        $applications = Application::with('applicant', 'workflowConfig', 'currentStep')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->module, fn ($q) => $q->whereHas('workflowConfig', fn ($q) => $q->where('module', $request->module)))
            ->latest()
            ->get();

        $filename = 'applications-report-' . now()->format('Y-m-d') . '.pdf';
        return PdfGenerator::download('pdf.applications-report', compact('applications'), $filename);
    }

    public function revenue(Request $request)
    {
        $payments = Payment::with('user', 'application')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->method, fn ($q) => $q->where('method', $request->method))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total' => (float) Payment::whereIn('status', ['paid', 'reconciled'])->sum('amount'),
            'pending' => (float) Payment::where('status', 'pending')->sum('amount'),
            'count' => Payment::count(),
        ];

        return view('reports.revenue', compact('payments', 'summary'));
    }

    public function revenueExportExcel(Request $request)
    {
        return Excel::download(new RevenueExport($request->only('status', 'method')), 'revenue-report-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function revenueExportPdf(Request $request)
    {
        $payments = Payment::with('user', 'application')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->method, fn ($q) => $q->where('method', $request->method))
            ->latest()
            ->get();

        $filename = 'revenue-report-' . now()->format('Y-m-d') . '.pdf';
        return PdfGenerator::download('pdf.revenue-report', compact('payments'), $filename);
    }

    // Certificates expiring within 90 days — the "Renew" report.
    public function renewals()
    {
        $certificates = Certificate::with('application.applicant', 'template')
            ->where('status', 'active')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(90))
            ->orderBy('expiry_date')
            ->paginate(20);

        return view('reports.renewals', compact('certificates'));
    }

    public function renewalsExportExcel()
    {
        return Excel::download(new RenewalsExport, 'renewals-report-' . now()->format('Y-m-d') . '.xlsx');
    }
}
