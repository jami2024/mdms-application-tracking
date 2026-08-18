@extends('layouts.admin')
@section('title', 'নতুন ভূমিকা')

@section('content')
<div class="max-w-2xl">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">নতুন ভূমিকা</h2>
    <form method="POST" action="{{ route('admin.roles.store') }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf
        @include('admin.roles._form', ['role' => null, 'assigned' => []])
        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">ভূমিকা তৈরি করুন</button>
    </form>
</div>
@endsection
