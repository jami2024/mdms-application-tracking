@extends('layouts.admin')
@section('title', 'অর্গানোগ্রাম')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">অর্গানোগ্রাম</h2>
            <p class="text-sm text-slate-500">Reporting hierarchy used to route workflow approvals.</p>
        </div>
        <a href="{{ route('admin.organogram.create') }}" class="text-sm px-4 py-2 rounded-none bg-ink-900 text-white hover:bg-slate-800 transition">+ পদ যোগ করুন</a>
    </div>

    @if(session('status'))<div class="rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5">{{ session('status') }}</div>@endif
    @if(session('error'))<div class="rounded-none bg-red-50 text-red-600 text-sm px-5 py-3.5">{{ session('error') }}</div>@endif

    <div class="bg-white rounded-none border border-slate-200 shadow-sm p-6">
        @forelse($roots as $root)
            @include('admin.organogram._node', ['node' => $root, 'depth' => 0])
        @empty
            <p class="text-sm text-slate-400 text-center py-8">No organogram positions yet. Add the top-level post to get started.</p>
        @endforelse
    </div>
</div>
@endsection
