@extends('layouts.admin')
@section('title', $establishment->name)
@section('content')
<div class="max-w-xl space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-800">{{ $establishment->name }}</h2>
        <span class="text-xs px-3 py-1 rounded-none bg-slate-100 text-slate-500">{{ \App\Support\Bengali::label($establishment->status) }}</span>
    </div>
    @if(session('status'))<div class="rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5">{{ session('status') }}</div>@endif
    @if(session('error'))<div class="rounded-none bg-red-50 text-red-600 text-sm px-5 py-3.5">{{ session('error') }}</div>@endif

    <div class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-2 text-sm">
        <div class="flex justify-between"><span class="text-slate-500">প্রতিষ্ঠান</span><span class="font-medium text-slate-800">{{ $establishment->company->name }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">লাইসেন্স নং</span><span class="font-medium text-slate-800">{{ $establishment->license_no ?? '—' }}</span></div>
    </div>

    <div class="flex gap-3">
        @if(in_array($establishment->status, ['draft', 'rejected']))
            <a href="{{ route('establishments.edit', $establishment) }}" class="px-4 py-2 rounded-none border border-slate-300 bg-white text-sm hover:bg-slate-50 transition">সম্পাদনা</a>
            <form method="POST" action="{{ route('establishments.submit', $establishment) }}">@csrf<button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">পর্যালোচনার জন্য জমা দিন</button></form>
            <form method="POST" action="{{ route('establishments.destroy', $establishment) }}" onsubmit="return confirm('Delete this draft?')">@csrf @method('DELETE')<button class="px-4 py-2 rounded-none border border-red-200 text-red-600 text-sm hover:bg-red-50">মুছুন</button></form>
        @elseif($establishment->application)
            <a href="{{ route('applications.show', $establishment->application) }}" class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">আবেদনের অবস্থা দেখুন</a>
        @endif
    </div>
</div>
@endsection
