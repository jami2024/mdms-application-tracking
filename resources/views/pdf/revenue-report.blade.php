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
        .total { text-align: right; font-weight: bold; margin-top: 12px; }
    </style>
</head>
<body>
    <h1>রাজস্ব প্রতিবেদন</h1>
    <p class="sub">তৈরি হয়েছে {{ now()->format('d M, Y H:i') }}</p>
    <table>
        <thead><tr><th>রেফারেন্স</th><th>প্রদানকারী</th><th>পদ্ধতি</th><th>পরিমাণ</th><th>স্ট্যাটাস</th><th>পরিশোধের তারিখ</th></tr></thead>
        <tbody>
            @foreach($payments as $p)
            <tr class="hover:bg-slate-50 transition-colors">
                <td>{{ $p->reference }}</td>
                <td>{{ $p->user->name }}</td>
                <td>{{ $p->method }}</td>
                <td>{{ number_format($p->amount, 2) }}</td>
                <td>{{ \App\Support\Bengali::label($p->status) }}</td>
                <td>{{ $p->paid_at?->format('Y-m-d') ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">মোট: ৳ {{ number_format($payments->sum('amount'), 2) }}</p>
</body>
</html>
