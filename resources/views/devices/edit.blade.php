@extends('layouts.admin')
@section('title', 'ডিভাইস সম্পাদনা')
@section('content')
<div class="max-w-lg">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">ডিভাইস সম্পাদনা</h2>
    <form method="POST" action="{{ route('devices.update', $device) }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf @method('PUT')
        @include('devices._form')
        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">পরিবর্তন সংরক্ষণ করুন</button>
    </form>
</div>
@endsection
