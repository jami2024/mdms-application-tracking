@extends('layouts.admin')
@section('title', 'অ্যাক্টিভিটি বিস্তারিত')
@section('content')
<div class="max-w-2xl space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-800">Activity #{{ $activity->id }}</h2>
        <button onclick="window.print()" class="text-sm px-4 py-2 rounded-none border border-slate-300 bg-white hover:bg-slate-50 transition print:hidden">প্রিন্ট / ডাউনলোড</button>
    </div>

    <div class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4" id="printable">
        <div class="flex items-center gap-2">
            <span class="text-xs font-medium px-2.5 py-1 rounded-none bg-brand-50 text-brand-700">{{ \App\Support\Bengali::label($activity->log_name) }}</span>
            <span class="text-xs font-medium px-2.5 py-1 rounded-none bg-slate-100 text-slate-500">{{ \App\Support\Bengali::label($activity->event) }}</span>
        </div>

        <p class="text-slate-800 font-medium">{{ $activity->description }}</p>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm border-t border-slate-100 pt-4">
            <div><dt class="text-slate-400 text-xs uppercase mb-1">কখন</dt><dd class="text-slate-700">{{ $activity->created_at->format('d M, Y H:i:s') }}</dd></div>
            <div><dt class="text-slate-400 text-xs uppercase mb-1">কারণকারী</dt><dd class="text-slate-700">{{ $activity->causer->name ?? 'সিস্টেম' }} {{ $activity->causer->email ? '('.$activity->causer->email.')' : '' }}</dd></div>
            <div><dt class="text-slate-400 text-xs uppercase mb-1">বিষয়</dt><dd class="text-slate-700">{{ class_basename($activity->subject_type ?? '') }} #{{ $activity->subject_id }}</dd></div>
            <div><dt class="text-slate-400 text-xs uppercase mb-1">আইপি ঠিকানা</dt><dd class="text-slate-700">{{ $activity->ip_address ?? '—' }}</dd></div>
        </dl>

        @if($activity->properties && $activity->properties->isNotEmpty())
        <div class="border-t border-slate-100 pt-4">
            <p class="text-xs text-slate-400 uppercase mb-2">বৈশিষ্ট্য</p>
            <pre class="bg-slate-50 rounded-none p-4 text-xs overflow-x-auto">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif
    </div>
</div>
@endsection
