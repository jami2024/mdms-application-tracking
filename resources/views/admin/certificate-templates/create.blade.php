@extends('layouts.admin')
@section('title', 'নতুন সার্টিফিকেট টেমপ্লেট')
@section('content')
<div class="max-w-3xl">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">নতুন সার্টিফিকেট টেমপ্লেট</h2>
    <form method="POST" action="{{ route('admin.certificate-templates.store') }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf
        @include('admin.certificate-templates._form', ['template' => null])
        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">টেমপ্লেট তৈরি করুন</button>
    </form>
</div>
@endsection
