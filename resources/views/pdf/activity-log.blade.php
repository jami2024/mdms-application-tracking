<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: kalpurush, sans-serif; font-size: 11px; color: #1e293b; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        p.sub { color: #64748b; margin-top: 0; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 5px 7px; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; font-size: 9px; text-transform: uppercase; color: #64748b; }
    </style>
</head>
<body>
    <h1>অ্যাক্টিভিটি লগ</h1>
    <p class="sub">তৈরি হয়েছে {{ now()->format('d M, Y H:i') }} · {{ $activities->count() }}টি এন্ট্রি</p>
    <table>
        <thead><tr><th>কখন</th><th>মডিউল</th><th>বিবরণ</th><th>কারণকারী</th><th>বিষয়</th></tr></thead>
        <tbody>
            @foreach($activities as $a)
            <tr class="hover:bg-slate-50 transition-colors">
                <td>{{ $a->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ \App\Support\Bengali::label($a->log_name) }}</td>
                <td>{{ $a->description }}</td>
                <td>{{ $a->causer->name ?? 'সিস্টেম' }}</td>
                <td>{{ class_basename($a->subject_type ?? '') }} #{{ $a->subject_id }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
