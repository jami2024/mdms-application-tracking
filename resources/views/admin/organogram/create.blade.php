@extends('layouts.admin')
@section('title', 'অর্গানোগ্রাম পদ যোগ করুন')

@section('content')
<div class="max-w-xl">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">অর্গানোগ্রাম পদ যোগ করুন</h2>
    <form method="POST" action="{{ route('admin.organogram.store') }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">সংস্থা</label>
            <select name="organization_id" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                @foreach($organizations as $o)
                    <option value="{{ $o->id }}">{{ $o->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">পদবি</label>
            <select name="designation_id" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                @foreach($designations as $d)
                    <option value="{{ $d->id }}">{{ $d->title }} ({{ $d->short_code }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">যার অধীনে (ঊর্ধ্বতন পদ)</label>
            <select name="parent_id" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                <option value="">— শীর্ষ পর্যায় —</option>
                @foreach($positions as $p)
                    <option value="{{ $p->id }}">{{ $p->designation->title ?? '' }} ({{ $p->designation->short_code ?? '' }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">বর্তমান পদধারী (এই পদে থাকা ব্যবহারকারী)</label>
            <select name="user_id" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
                <option value="">— শূন্য —</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">পদ যোগ করুন</button>
    </form>
</div>
@endsection
