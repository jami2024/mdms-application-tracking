@extends('layouts.admin')
@section('title', 'নতুন ওয়ার্কফ্লো')
@section('content')
<div class="max-w-lg">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">নতুন ওয়ার্কফ্লো</h2>
    <form method="POST" action="{{ route('admin.workflow-configs.store') }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">নাম</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">মডিউল</label>
            <select name="module" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                @foreach(['company'=>'Company Registration','establishment'=>'Establishment License','device'=>'Device Registration', 'package'=>'Package Registration', 'final_package_approval'=>'Final Package Approval','mrp'=>'MRP Application', 'service'=>'Service Application Approval'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('module') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">বিবরণ</label>
            <textarea name="description" rows="2" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">{{ old('description') }}</textarea>
        </div>
        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">তৈরি করুন ও ধাপ যোগ করুন</button>
    </form>
</div>
@endsection
