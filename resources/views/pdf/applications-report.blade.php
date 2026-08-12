<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: kalpurush, sans-serif; font-size: 12px; color: #1e293b; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        p.sub { color: #64748b; margin-top: 0; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 6px 8px; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; font-size: 10px; text-transform: uppercase; color: #64748b; }
    </style>
</head>
<body>
    <h1>আবেদন প্রতিবেদন</h1>
    <p class="sub">তৈরি হয়েছে {{ now()->format('d M, Y H:i') }}</p>
    <table>
        <thead><tr><th>আবেদন নং</th><th>মডিউল</th><th>আবেদনকারী</th><th>ধাপ</th><th>স্ট্যাটাস</th><th>জমাকৃত</th></tr></thead>
        <tbody>
            @foreach($applications as $app)
            <tr class="hover:bg-slate-50 transition-colors">
                <td>{{ $app->application_no }}</td>
                <td>{{ \App\Support\Bengali::label($app->workflowConfig?->module) }}</td>
                <td>{{ $app->applicant->name }}</td>
                <td>{{ $app->currentStep->step_name ?? '-' }}</td>
                <td>{{ \App\Support\Bengali::label($app->status) }}</td>
                <td>{{ $app->submitted_at?->format('Y-m-d') ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
