@extends('layouts.admin')
@section('title', 'সার্টিফিকেট টেমপ্লেট সম্পাদনা')
@section('content')
<div class="max-w-3xl">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">সার্টিফিকেট টেমপ্লেট সম্পাদনা</h2>
    <form method="POST" action="{{ route('admin.certificate-templates.update', $template) }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf @method('PUT')
        @include('admin.certificate-templates._form')
        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">পরিবর্তন সংরক্ষণ করুন</button>
    </form>
</div>
@endsection
