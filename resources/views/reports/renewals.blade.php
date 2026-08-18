@extends('layouts.admin')
@section('title', 'নবায়ন প্রতিবেদন')
@section('content')
<div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">নবায়নযোগ্য</h2>
            <p class="text-sm text-slate-500">৯০ দিনের মধ্যে মেয়াদোত্তীর্ণ হতে যাওয়া সক্রিয় সার্টিফিকেট।</p>
        </div>
        <a href="{{ route('reports.renewals.excel') }}" class="text-sm px-4 py-2 rounded-none border border-slate-300 bg-white hover:bg-slate-50 transition">এক্সেল এক্সপোর্ট</a>
    </div>

    <div class="bg-white rounded-none border border-slate-200 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-[16px] uppercase tracking-wide text-slate-400 border-b border-slate-200">
                <tr><th class="text-left px-5 py-3.5">সার্টিফিকেট নং</th><th class="text-left px-5 py-3.5">মডিউল</th><th class="text-left px-5 py-3.5">আবেদনকারী</th><th class="text-left px-5 py-3.5">মেয়াদ শেষের তারিখ</th><th class="text-left px-5 py-3.5">বাকি দিন</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($certificates as $c)
                @php $daysLeft = (int) round(now()->diffInDays($c->expiry_date, false)); @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $c->certificate_no }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ \App\Support\Bengali::label($c->template->module) }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $c->application->applicant->name }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $c->expiry_date->format('Y-m-d') }}</td>
                    <td class="px-5 py-3.5">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-none {{ $daysLeft <= 30 ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-700' }}">{{ $daysLeft }} days</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">নবায়নের জন্য কোনো সার্টিফিকেট নেই।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $certificates->links() }}
</div>
@endsection
