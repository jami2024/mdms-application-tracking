<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $stats = [
            'pending_applications' => Application::whereIn('status', ['submitted', 'in_review', 'returned'])->count(),
            'total_revenue' => (float) Payment::whereIn('status', ['paid', 'reconciled'])->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'active_certificates' => Certificate::where('status', 'active')->count(),
        ];

        // Applications by status — feeds a small donut/bar chart.
        $applicationsByStatus = Application::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Revenue for the last 6 months — feeds a line/bar chart.
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i)->format('Y-m'));
        $monthlyRevenue = Payment::whereIn('status', ['paid', 'reconciled'])
            ->where('paid_at', '>=', now()->subMonths(6)->startOfMonth())
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as ym, SUM(amount) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $revenueChart = $months->mapWithKeys(fn ($m) => [$m => (float) ($monthlyRevenue[$m] ?? 0)]);

        $notifications = $user->unreadNotifications()->latest()->take(6)->get();

        $recentApplications = Application::with('applicant', 'currentStep')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact('user', 'stats', 'applicationsByStatus', 'revenueChart', 'notifications', 'recentApplications'));
    }
}
