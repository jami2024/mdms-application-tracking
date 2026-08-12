<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\PdfGenerator;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $activities = $this->filtered($request)->latest()->paginate(20)->withQueryString();

        $logNames = Activity::select('log_name')->distinct()->pluck('log_name')->filter();

        return view('admin.activity-log.index', compact('activities', 'logNames'));
    }

    public function show(Activity $activityLog)
    {
        return view('admin.activity-log.show', ['activity' => $activityLog]);
    }

    public function exportPdf(Request $request)
    {
        $activities = $this->filtered($request)->latest()->limit(500)->get();

        $filename = 'activity-log-' . now()->format('Y-m-d') . '.pdf';
        return PdfGenerator::download('pdf.activity-log', compact('activities'), $filename);
    }

    private function filtered(Request $request)
    {
        return Activity::with('causer', 'subject')
            ->when($request->log_name, fn ($q, $v) => $q->where('log_name', $v))
            ->when($request->event, fn ($q, $v) => $q->where('event', $v))
            ->when($request->causer, fn ($q, $v) => $q->whereHasMorph('causer', ['App\Models\User'], fn ($q) => $q->where('name', 'like', "%{$v}%")))
            ->when($request->search, fn ($q, $v) => $q->where('description', 'like', "%{$v}%"))
            ->when($request->from, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($request->to, fn ($q, $v) => $q->whereDate('created_at', '<=', $v));
    }
}
