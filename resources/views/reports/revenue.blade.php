@extends('layouts.admin')
@section('title', 'রাজস্ব প্রতিবেদন')
@section('content')
<div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-lg font-semibold text-slate-800">রাজস্ব প্রতিবেদন</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.revenue.excel', request()->query()) }}" class="text-sm px-4 py-2 rounded-none border border-slate-300 bg-white hover:bg-slate-50 transition">এক্সেল এক্সপোর্ট</a>
            <a href="{{ route('reports.revenue.pdf', request()->query()) }}" class="text-sm px-4 py-2 rounded-none border border-slate-300 bg-white hover:bg-slate-50 transition">পিডিএফ এক্সপোর্ট</a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-none border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-400 uppercase">সংগৃহীত</p>
            <p class="text-2xl font-semibold text-slate-800 mt-1">৳ {{ number_format($summary['total']) }}</p>
        </div>
        <div class="bg-white rounded-none border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-400 uppercase">অপেক্ষমাণ</p>
            <p class="text-2xl font-semibold text-slate-800 mt-1">৳ {{ number_format($summary['pending']) }}</p>
        </div>
        <div class="bg-white rounded-none border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-400 uppercase">মোট লেনদেন</p>
            <p class="text-2xl font-semibold text-slate-800 mt-1">{{ $summary['count'] }}</p>
        </div>
    </div>

    <form method="GET" class="flex flex-wrap items-center gap-2 bg-white p-3 rounded-none border border-slate-200">
        <select name="method" class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            <option value="">সব পদ্ধতি</option>
            @foreach(['SSLCOMMERZ','bKash','Nagad','Rocket','TR Challan'] as $m)
                <option value="{{ $m }}" @selected(request('method') === $m)>{{ $m }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            <option value="">সব স্ট্যাটাস</option>
            @foreach(['pending','paid','reconciled','failed'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ \App\Support\Bengali::label($s) }}</option>
            @endforeach
        </select>
        <button class="text-sm px-4 py-2 rounded-none bg-ink-900 text-white hover:bg-slate-800 transition">ফিল্টার</button>
    </form>

    <div class="bg-white rounded-none border border-slate-200 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-[16px] uppercase tracking-wide text-slate-400 border-b border-slate-200">
                <tr><th class="text-left px-5 py-3.5">রেফারেন্স</th><th class="text-left px-5 py-3.5">প্রদানকারী</th><th class="text-left px-5 py-3.5">পদ্ধতি</th><th class="text-left px-5 py-3.5">পরিমাণ</th><th class="text-left px-5 py-3.5">স্ট্যাটাস</th><th class="text-left px-5 py-3.5">পরিশোধের তারিখ</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($payments as $p)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 font-mono text-xs text-slate-700">{{ $p->reference }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $p->user->name }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $p->method }}</td>
                    <td class="px-5 py-3.5 text-slate-800 font-medium">৳ {{ number_format($p->amount, 2) }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ \App\Support\Bengali::label($p->status) }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $p->paid_at?->format('Y-m-d') ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">এই ফিল্টারে কোনো পেমেন্ট পাওয়া যায়নি।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $payments->links() }}
</div>
@endsection
