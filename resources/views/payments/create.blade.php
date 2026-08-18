@extends('layouts.admin')
@section('title', 'আবেদন ফি প্রদান করুন')
@section('content')
<div class="max-w-md">
    <h2 class="text-lg font-semibold text-slate-800 mb-1">আবেদন ফি প্রদান করুন</h2>
    <p class="text-sm text-slate-500 mb-4">{{ $application->application_no }}</p>

    <div class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between">
            <span class="text-sm text-slate-500">প্রদেয় পরিমাণ</span>
            <span class="text-2xl font-semibold text-slate-800">৳ {{ number_format($amount, 2) }}</span>
        </div>

        <form method="POST" action="{{ route('payments.store', $application) }}" class="space-y-3 pt-2 border-t border-slate-100">
            @csrf
            <label class="block text-sm font-medium text-slate-700">পেমেন্ট পদ্ধতি</label>
            @foreach(['SSLCOMMERZ' => 'Card / Net Banking (SSLCOMMERZ)', 'bKash' => 'bKash', 'Nagad' => 'Nagad', 'Rocket' => 'Rocket', 'TR Challan' => 'Treasury Challan'] as $val => $label)
                <label class="flex items-center gap-3 rounded-none border border-slate-200 px-3 py-2.5 text-sm cursor-pointer hover:bg-slate-50">
                    <input type="radio" name="method" value="{{ $val }}" required class="text-brand-600">
                    {{ $label }}
                </label>
            @endforeach
            <button class="w-full px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition mt-2">পেমেন্ট করুন</button>
        </form>
    </div>
</div>
@endsection
