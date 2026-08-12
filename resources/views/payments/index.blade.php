@extends('layouts.admin')
@section('title', 'পেমেন্ট')
@section('content')
@php
    $all = $payments;
    $paidCount = $payments->getCollection()->whereIn('status', ['paid'])->count();
    $pendingCount = $payments->getCollection()->where('status', 'pending')->count();
    $reconciledCount = $payments->getCollection()->where('status', 'reconciled')->count();
    $statusBadge = ['paid' => 'bg-emerald-50 text-emerald-700', 'pending' => 'bg-amber-50 text-amber-700', 'reconciled' => 'bg-blue-50 text-blue-700', 'failed' => 'bg-red-50 text-red-600'];
    $statusLabel = ['paid' => 'পরিশোধিত', 'pending' => 'অপেক্ষমাণ', 'reconciled' => 'সমন্বিত', 'failed' => 'ব্যর্থ'];
@endphp

<div class="space-y-6">
    <p class="text-xs text-slate-400">হোম / পেমেন্ট</p>

    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">পেমেন্ট</h2>
            <p class="text-sm text-slate-500 mt-0.5">আপনার সকল আবেদনের পরিশোধিত ফি।</p>
        </div>
        <div class="flex items-center gap-2">
            <button class="flex items-center gap-1.5 text-sm px-4 py-2.5 rounded-none border border-slate-300 bg-white hover:bg-slate-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2"/></svg>
                এক্সপোর্ট
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-none border-t-4 border-t-emerald-500 border-x border-b border-slate-200 p-5 shadow-sm">
            <p class="text-[16px] font-semibold uppercase tracking-wide text-slate-400">মোট পরিশোধিত</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">৳ {{ number_format($payments->getCollection()->whereIn('status',['paid','reconciled'])->sum('amount')) }}</p>
        </div>
        <div class="bg-white rounded-none border-t-4 border-t-amber-400 border-x border-b border-slate-200 p-5 shadow-sm">
            <p class="text-[16px] font-semibold uppercase tracking-wide text-slate-400">অপেক্ষমাণ</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">৳ {{ number_format($payments->getCollection()->where('status','pending')->sum('amount')) }}</p>
        </div>
        <div class="bg-white rounded-none border-t-4 border-t-ink-900 border-x border-b border-slate-200 p-5 shadow-sm">
            <p class="text-[16px] font-semibold uppercase tracking-wide text-slate-400">মোট লেনদেন</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $payments->total() }}</p>
        </div>
        <div class="bg-white rounded-none border-t-4 border-t-blue-500 border-x border-b border-slate-200 p-5 shadow-sm">
            <p class="text-[16px] font-semibold uppercase tracking-wide text-slate-400">পদ্ধতি ব্যবহৃত</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $payments->getCollection()->pluck('method')->unique()->count() }}</p>
        </div>
    </div>

    <div class="inline-flex bg-slate-100 rounded-none p-1 text-sm">
        <span class="px-4 py-1.5 rounded-none bg-white shadow-sm font-medium text-slate-800">সব ({{ $payments->total() }})</span>
        <span class="px-4 py-1.5 rounded-none text-slate-500">পরিশোধিত ({{ $paidCount }})</span>
        <span class="px-4 py-1.5 rounded-none text-slate-500">অপেক্ষমাণ ({{ $pendingCount }})</span>
        <span class="px-4 py-1.5 rounded-none text-slate-500">সমন্বিত ({{ $reconciledCount }})</span>
    </div>

    <div class="bg-white rounded-none border border-slate-200 overflow-x-auto shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[16px] uppercase tracking-wide text-slate-400">
                    <th class="px-5 py-3 font-medium">রেফারেন্স</th>
                    <th class="px-5 py-3 font-medium">বিবরণ</th>
                    <th class="px-5 py-3 font-medium">পদ্ধতি</th>
                    <th class="px-5 py-3 font-medium">পরিমাণ</th>
                    <th class="px-5 py-3 font-medium">তারিখ</th>
                    <th class="px-5 py-3 font-medium">স্ট্যাটাস</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($payments as $p)
                <tr class="hover:bg-slate-50/60">
                    <td class="px-5 py-3.5 font-mono text-xs text-slate-500">{{ $p->reference }}</td>
                    <td class="px-5 py-3.5 text-slate-700">{{ $p->description }}</td>
                    <td class="px-5 py-3.5"><span class="text-xs px-2.5 py-1 rounded-none bg-slate-100 text-slate-600 border border-slate-200">{{ $p->method }}</span></td>
                    <td class="px-5 py-3.5 font-semibold text-slate-900">৳ {{ number_format($p->amount) }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ ($p->paid_at ?? $p->created_at)->format('Y-m-d') }}</td>
                    <td class="px-5 py-3.5"><span class="text-xs font-medium px-2.5 py-1 rounded-none {{ $statusBadge[$p->status] ?? 'bg-slate-100 text-slate-500' }}">{{ $statusLabel[$p->status] ?? \App\Support\Bengali::label($p->status) }}</span></td>
                    <td class="px-5 py-3.5 text-right"><a href="{{ route('payments.show', $p) }}" class="text-slate-400 hover:text-slate-700">···</a></td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-slate-400">এখনো কোনো পেমেন্ট নেই।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $payments->links() }}
</div>
@endsection
