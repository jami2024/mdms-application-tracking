@extends('layouts.admin')
@section('title', 'নতুন এমআরপি আবেদন')
@section('content')
<div class="max-w-lg">
    <h2 class="text-lg font-semibold text-slate-800 mb-1">নতুন এমআরপি আবেদন</h2>
    <p class="text-sm text-slate-500 mb-4">Under {{ $company->name }}</p>

    @if($devices->isEmpty())
        <div class="bg-white rounded-none border border-slate-200 shadow-sm p-6 text-sm text-slate-500">
            No registered devices yet. A device must be registered and approved before an MRP application can be filed against it.
        </div>
    @else
    <form method="POST" action="{{ route('companies.mrp-applications.store', $company) }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">ডিভাইস</label>
            <select name="device_id" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                @foreach($devices as $d)<option value="{{ $d->id }}">{{ $d->device_name }} ({{ $d->registration_no }})</option>@endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">প্রস্তাবিত এমআরপি (টাকা)</label>
            <input type="number" step="0.01" min="0" name="proposed_mrp" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        </div>
        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">খসড়া হিসেবে সংরক্ষণ করুন</button>
    </form>
    @endif
</div>
@endsection
