@extends('layouts.admin')
@section('title', 'এমআরপি আবেদন')
@section('content')
<div class="max-w-xl space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-800">এমআরপি আবেদন</h2>
        <span class="text-xs px-3 py-1 rounded-none bg-slate-100 text-slate-500">{{ \App\Support\Bengali::label($mrp->status) }}</span>
    </div>
    @if(session('status'))<div class="rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5">{{ session('status') }}</div>@endif
    @if(session('error'))<div class="rounded-none bg-red-50 text-red-600 text-sm px-5 py-3.5">{{ session('error') }}</div>@endif

    <div class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-2 text-sm">
        <div class="flex justify-between"><span class="text-slate-500">প্রতিষ্ঠান</span><span class="font-medium text-slate-800">{{ $mrp->company->name }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">ডিভাইস</span><span class="font-medium text-slate-800">{{ $mrp->device->device_name }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">প্রস্তাবিত এমআরপি</span><span class="font-medium text-slate-800">৳ {{ number_format($mrp->proposed_mrp, 2) }}</span></div>
        @if($mrp->approved_mrp)
        <div class="flex justify-between"><span class="text-slate-500">অনুমোদিত এমআরপি</span><span class="font-medium text-emerald-700">৳ {{ number_format($mrp->approved_mrp, 2) }}</span></div>
        @endif
    </div>

    <div class="flex gap-3">
        @if($mrp->status === 'draft')
            <form method="POST" action="{{ route('mrp-applications.submit', $mrp) }}">@csrf<button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">পর্যালোচনার জন্য জমা দিন</button></form>
            <form method="POST" action="{{ route('mrp-applications.destroy', $mrp) }}" onsubmit="return confirm('Delete this draft?')">@csrf @method('DELETE')<button class="px-4 py-2 rounded-none border border-red-200 text-red-600 text-sm hover:bg-red-50">মুছুন</button></form>
        @elseif($mrp->application)
            <a href="{{ route('applications.show', $mrp->application) }}" class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">আবেদনের অবস্থা দেখুন</a>
        @endif
    </div>
</div>
@endsection
