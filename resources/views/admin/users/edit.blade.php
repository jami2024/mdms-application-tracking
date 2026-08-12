@extends('layouts.admin')
@section('title', 'ব্যবহারকারী সম্পাদনা')

@section('content')
<div class="max-w-2xl space-y-6">
    <div>
        <h2 class="text-lg font-semibold text-slate-800 mb-4">ব্যবহারকারী সম্পাদনা</h2>
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
            @csrf @method('PUT')
            @include('admin.users._form')
            <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">পরিবর্তন সংরক্ষণ করুন</button>
        </form>
    </div>

    <div>
        <h3 class="text-sm font-semibold text-slate-800 mb-2">পাসওয়ার্ড রিসেট</h3>
        <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 flex items-end gap-3">
            @csrf @method('PUT')
            <div class="flex-1">
                <label class="block text-xs font-medium text-slate-500 mb-1">নতুন পাসওয়ার্ড</label>
                <input type="password" name="password" required minlength="8"
                       class="w-full rounded-none border-2 border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>
            <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">রিসেট</button>
        </form>
    </div>
</div>
@endsection
