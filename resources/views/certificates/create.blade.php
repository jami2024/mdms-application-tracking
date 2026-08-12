@extends('layouts.admin')
@section('title', 'সার্টিফিকেট ইস্যু করুন')
@section('content')
<div class="max-w-lg">
    <h2 class="text-lg font-semibold text-slate-800 mb-1">সার্টিফিকেট ইস্যু করুন</h2>
    <p class="text-sm text-slate-500 mb-4">For application {{ $application->application_no }}</p>

    @if($templates->isEmpty())
        <div class="bg-white rounded-none border border-slate-200 shadow-sm p-6 text-sm text-slate-500">
            No active certificate template exists for this module yet.
            <a href="{{ route('admin.certificate-templates.create') }}" class="text-brand-600 hover:underline">একটি তৈরি করুন</a> first.
        </div>
    @else
    <form method="POST" action="{{ route('applications.certificate.store', $application) }}" enctype="multipart/form-data"
          class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4" x-data="{ sigType: 'uploaded' }">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">টেমপ্লেট</label>
            <select name="certificate_template_id" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                @foreach($templates as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">ইস্যুর তারিখ</label>
            <input type="date" name="issue_date" value="{{ old('issue_date', now()->format('Y-m-d')) }}" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">মেয়াদ শেষের তারিখ</label>
            <input type="date" name="expiry_date" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">স্বাক্ষরের ধরন</label>
            <select name="signature_type" x-model="sigType" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                <option value="uploaded">আপলোড করা স্বাক্ষরের ছবি</option>
                <option value="digital">ডিজিটাল স্বাক্ষর (সিস্টেম-জেনারেটেড)</option>
            </select>
        </div>
        <div x-show="sigType === 'uploaded'">
            <label class="block text-sm font-medium text-slate-700 mb-1">স্বাক্ষরের ছবি</label>
            <input type="file" name="signature_file" accept="image/*" class="w-full text-sm">
        </div>
        <button class="w-full px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">সার্টিফিকেট তৈরি করুন</button>
    </form>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
