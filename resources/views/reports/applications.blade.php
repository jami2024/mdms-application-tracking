@extends('layouts.admin')
@section('title', 'আবেদন প্রতিবেদন')
@section('content')
<div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-lg font-semibold text-slate-800">আবেদন প্রতিবেদন</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.applications.excel', request()->query()) }}" class="text-sm px-4 py-2 rounded-none border border-slate-300 bg-white hover:bg-slate-50 transition">এক্সেল এক্সপোর্ট</a>
            <a href="{{ route('reports.applications.pdf', request()->query()) }}" class="text-sm px-4 py-2 rounded-none border border-slate-300 bg-white hover:bg-slate-50 transition">পিডিএফ এক্সপোর্ট</a>
        </div>
    </div>

    <form method="GET" class="flex flex-wrap items-center gap-2 bg-white p-3 rounded-none border border-slate-200">
        <select name="module" class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            <option value="">সব মডিউল</option>
            @foreach(['company','establishment','device','mrp'] as $m)
                <option value="{{ $m }}" @selected(request('module') === $m)>{{ \App\Support\Bengali::label($m) }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            <option value="">সব স্ট্যাটাস</option>
            @foreach(['draft','submitted','in_review','returned','approved','rejected'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ \App\Support\Bengali::label($s) }}</option>
            @endforeach
        </select>
        <button class="text-sm px-4 py-2 rounded-none bg-ink-900 text-white hover:bg-slate-800 transition">ফিল্টার</button>
    </form>

    <div class="bg-white rounded-none border border-slate-200 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-[16px] uppercase tracking-wide text-slate-400 border-b border-slate-200">
                <tr><th class="text-left px-5 py-3.5">আবেদন নং</th><th class="text-left px-5 py-3.5">মডিউল</th><th class="text-left px-5 py-3.5">আবেদনকারী</th><th class="text-left px-5 py-3.5">ধাপ</th><th class="text-left px-5 py-3.5">স্ট্যাটাস</th><th class="text-left px-5 py-3.5">জমাকৃত</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($applications as $app)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $app->application_no }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ \App\Support\Bengali::label($app->workflowConfig?->module) }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $app->applicant->name }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $app->currentStep->step_name ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ \App\Support\Bengali::label($app->status) }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $app->submitted_at?->format('Y-m-d') ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">এই ফিল্টারে কোনো আবেদন পাওয়া যায়নি।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $applications->links() }}
</div>
@endsection
