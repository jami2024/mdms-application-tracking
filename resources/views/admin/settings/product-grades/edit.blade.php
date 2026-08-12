@extends('layouts.admin')
@section('title', 'পণ্যের গ্রেড সম্পাদনা')
@section('content')
<div class="max-w-lg">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">পণ্যের গ্রেড সম্পাদনা</h2>
    <form method="POST" action="{{ route('admin.product-grades.update', $grade) }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf @method('PUT')
        @include('admin.settings.product-grades._form')
        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">পরিবর্তন সংরক্ষণ করুন</button>
    </form>
</div>
@endsection
