@extends('layouts.admin')
@section('title', 'আমার প্রোফাইল')

@section('content')
<div class="max-w-xl">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">আমার প্রোফাইল</h2>

    @if(session('status'))<div class="mb-4 rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5">{{ session('status') }}</div>@endif

    <form method="POST" action="{{ route('profile.update') }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf @method('PUT')
        <div class="flex items-center gap-4 mb-2">
            <div class="h-16 w-16 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xl font-semibold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-medium text-slate-800">{{ $user->name }}</p>
                <p class="text-xs text-slate-400">{{ $user->roles->pluck('name')->join(', ') }} · {{ $user->designation?->title }}</p>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">নাম</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">ইমেইল</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">ফোন</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                   class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        </div>
        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">সংরক্ষণ</button>
    </form>

    <div class="mt-4 bg-white rounded-none border border-slate-200 shadow-sm p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-slate-800">দুই-স্তর যাচাইকরণ</p>
            <p class="text-xs text-slate-500">{{ $user->two_factor_confirmed_at ? 'সক্রিয়' : 'সক্রিয় করা হয়নি' }}</p>
        </div>
        <a href="{{ url('/user/two-factor-authentication') }}" class="text-sm px-4 py-2 rounded-none border border-slate-300 bg-white hover:bg-slate-50 transition">ব্যবস্থাপনা</a>
    </div>
</div>
@endsection
