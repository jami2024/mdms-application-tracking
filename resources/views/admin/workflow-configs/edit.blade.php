@extends('layouts.admin')
@section('title', 'ওয়ার্কফ্লো ব্যবস্থাপনা')
@section('content')
<div class="max-w-3xl space-y-6">

    <div>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Workflow: {{ $config->name }}</h2>
        <form method="POST" action="{{ route('admin.workflow-configs.update', $config) }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">নাম</label>
                    <input type="text" name="name" value="{{ old('name', $config->name) }}" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">মডিউল</label>
                    <select name="module" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                        @foreach(['company'=>'Company Registration','establishment'=>'Establishment License','device'=>'Device Registration','mrp'=>'MRP Application', 'service'=>'Service Application Approval'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('module', $config->module) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">বিবরণ</label>
                <textarea name="description" rows="2" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">{{ old('description', $config->description) }}</textarea>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300" @checked($config->is_active)>
                Active
            </label>
            <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">সংরক্ষণ</button>
        </form>
    </div>

    <div>
        <h3 class="text-sm font-semibold text-slate-800 mb-2">Steps ({{ $steps->count() }})</h3>

        @if(session('status'))<div class="mb-3 rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5">{{ session('status') }}</div>@endif

        <div class="bg-white rounded-none border border-slate-200 shadow-sm divide-y divide-slate-100">
            @forelse($steps as $step)
            <div class="flex items-center justify-between px-5 py-3.5">
                <div class="flex items-center gap-3">
                    <span class="h-7 w-7 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-semibold">{{ $step->step_order }}</span>
                    <div>
                        <p class="text-sm font-medium text-slate-800">{{ $step->step_name }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $step->designation->short_code ?? '—' }} · {{ ['review'=>'পর্যালোচনা','approve'=>'অনুমোদন','reject'=>'প্রত্যাখ্যান','forward'=>'ফরওয়ার্ড','sign'=>'স্বাক্ষর'][$step->action_type] ?? $step->action_type }}
                            @if($step->sla_days) · SLA {{ $step->sla_days }}d @endif
                            @unless($step->can_reject) · প্রত্যাখ্যান নিষিদ্ধ @endunless
                            @unless($step->can_send_back) · ফেরত-পাঠানো নিষিদ্ধ @endunless
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('admin.workflow-configs.steps.reorder', [$config, $step]) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="direction" value="up">
                        <button class="text-slate-400 hover:text-slate-700" title="Move up">↑</button>
                    </form>
                    <form method="POST" action="{{ route('admin.workflow-configs.steps.reorder', [$config, $step]) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="direction" value="down">
                        <button class="text-slate-400 hover:text-slate-700" title="Move down">↓</button>
                    </form>
                    <form method="POST" action="{{ route('admin.workflow-configs.steps.destroy', [$config, $step]) }}" onsubmit="return confirm('Remove this step?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-500 hover:underline">অপসারণ করুন</button>
                    </form>
                </div>
            </div>
            @empty
                <p class="text-sm text-slate-400 text-center py-8">এখনো কোনো ধাপ নেই। নিচে প্রথমটি যোগ করুন।</p>
            @endforelse
        </div>

        <form method="POST" action="{{ route('admin.workflow-configs.steps.store', $config) }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 mt-3 space-y-4">
            @csrf
            <p class="text-sm font-medium text-slate-800">ধাপ যোগ করুন</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">ধাপের নাম</label>
                    <input type="text" name="step_name" required placeholder="যেমন: এসডি পর্যালোচনা" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">পদবি</label>
                    <select name="designation_id" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                        @foreach($designations as $d)<option value="{{ $d->id }}">{{ $d->title }} ({{ $d->short_code }})</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">অ্যাকশনের ধরন</label>
                    <select name="action_type" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                        @foreach(['review','approve','reject','forward','sign'] as $t)<option value="{{ $t }}">{{ \App\Support\Bengali::label($t) }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">এসএলএ (দিন)</label>
                    <input type="number" name="sla_days" min="1" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                </div>
            </div>
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="can_reject" value="1" checked class="rounded border-slate-300"> প্রত্যাখ্যান করতে পারবে</label>
                <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="can_send_back" value="1" checked class="rounded border-slate-300"> ফেরত পাঠাতে পারবে</label>
            </div>
            <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">ধাপ যোগ করুন</button>
        </form>
    </div>
</div>
@endsection
