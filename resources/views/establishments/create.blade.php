@extends('layouts.admin')
@section('title', 'নতুন এস্টাবলিশমেন্ট')
@section('content')
<div class="max-w-lg">
    <h2 class="text-lg font-semibold text-slate-800 mb-1">নতুন এস্টাবলিশমেন্ট</h2>
    <p class="text-sm text-slate-500 mb-4">Under {{ $company->name }}</p>
    <form method="POST" action="{{ route('companies.establishments.store', $company) }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf
        @include('establishments._form', ['establishment' => null])
        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">খসড়া হিসেবে সংরক্ষণ করুন</button>
    </form>
</div>
@endsection
