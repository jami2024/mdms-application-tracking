@extends('layouts.admin')
@section('title', 'অ্যাক্টিভিটি লগ')
@section('content')
<div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">অ্যাক্টিভিটি লগ</h2>
            <p class="text-sm text-slate-500">সিস্টেম জুড়ে প্রতিটি তৈরি/হালনাগাদ/মুছে ফেলা/ওয়ার্কফ্লো অ্যাকশন।</p>
        </div>
        <a href="{{ route('admin.activity-log.pdf', request()->query()) }}" class="text-sm px-4 py-2 rounded-none border border-slate-300 bg-white hover:bg-slate-50 transition">পিডিএফ এক্সপোর্ট</a>
    </div>

    <form method="GET" class="flex flex-wrap items-end gap-2 bg-white p-3 rounded-none border border-slate-200">
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">মডিউল</label>
            <select name="log_name" class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                <option value="">সব</option>
                @foreach($logNames as $name)
                    <option value="{{ $name }}" @selected(request('log_name') === $name)>{{ \App\Support\Bengali::label($name) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">ইভেন্ট</label>
            <select name="event" class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                <option value="">সব</option>
                @foreach(['created','updated','deleted'] as $e)
                    <option value="{{ $e }}" @selected(request('event') === $e)>{{ \App\Support\Bengali::label($e) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">কারণকারী</label>
            <input type="text" name="causer" value="{{ request('causer') }}" placeholder="ব্যবহারকারীর নাম…" class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">খুঁজুন</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="বিবরণ…" class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">থেকে</label>
            <input type="date" name="from" value="{{ request('from') }}" class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">পর্যন্ত</label>
            <input type="date" name="to" value="{{ request('to') }}" class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        </div>
        <button class="text-sm px-4 py-2 rounded-none bg-ink-900 text-white hover:bg-slate-800 transition">ফিল্টার</button>
        <a href="{{ route('admin.activity-log.index') }}" class="text-sm px-4 py-2 rounded-none border border-slate-300 bg-white hover:bg-slate-50 transition">রিসেট</a>
    </form>

    <div class="bg-white rounded-none border border-slate-200 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-[16px] uppercase tracking-wide text-slate-400 border-b border-slate-200">
                <tr><th class="text-left px-5 py-3.5">কখন</th><th class="text-left px-5 py-3.5">মডিউল</th><th class="text-left px-5 py-3.5">বিবরণ</th><th class="text-left px-5 py-3.5">কারণকারী</th><th class="text-left px-5 py-3.5">বিষয়</th><th class="text-right px-5 py-3.5">বিস্তারিত</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($activities as $a)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 text-slate-500 whitespace-nowrap">{{ $a->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-5 py-3.5"><span class="text-xs font-medium px-2.5 py-1 rounded-none bg-brand-50 text-brand-700">{{ \App\Support\Bengali::label($a->log_name) }}</span></td>
                    <td class="px-5 py-3.5 text-slate-700">{{ $a->description }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $a->causer->name ?? 'সিস্টেম' }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ class_basename($a->subject_type ?? '') }} #{{ $a->subject_id }}</td>
                    <td class="px-5 py-3.5 text-right"><a href="{{ route('admin.activity-log.show', $a) }}" class="text-brand-600 hover:underline">দেখুন</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">এখনো কোনো অ্যাক্টিভিটি রেকর্ড হয়নি।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $activities->links() }}
</div>
@endsection
