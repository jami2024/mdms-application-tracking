@extends('layouts.admin')
@section('title', 'নতুন বিভাগ')
@section('content')
<div class="max-w-lg">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">নতুন বিভাগ</h2>
    <form method="POST" action="{{ route('admin.divisions.store') }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf
        @include('admin.settings.divisions._form', ['division' => null])
        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">তৈরি করুন</button>
    </form>
</div>
@endsection
