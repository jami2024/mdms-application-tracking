@extends('layouts.admin')
@section('title', 'নতুন ব্যবহারকারী')

@section('content')
<div class="max-w-2xl">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">নতুন ব্যবহারকারী</h2>

    <form method="POST" action="{{ route('admin.users.store') }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4" enctype="multipart/form-data">
        @csrf
        @include('admin.users._form')
        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">ব্যবহারকারী তৈরি করুন</button>
    </form>
</div>
@endsection
