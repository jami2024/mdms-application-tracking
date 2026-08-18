@extends('layouts.admin')
@section('title', 'পেমেন্ট রসিদ')
@section('content')
<div class="max-w-md space-y-4">
    <h2 class="text-lg font-semibold text-slate-800">Payment {{ \App\Support\Bengali::label($payment->status) }}</h2>
    @if(session('status'))<div class="rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5">{{ session('status') }}</div>@endif
    @if(session('error'))<div class="rounded-none bg-red-50 text-red-600 text-sm px-5 py-3.5">{{ session('error') }}</div>@endif

    <div class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-3 text-sm">
        <div class="flex justify-between"><span class="text-slate-500">রেফারেন্স</span><span class="font-mono text-slate-800">{{ $payment->reference }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">বিবরণ</span><span class="text-slate-800">{{ $payment->description }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">পদ্ধতি</span><span class="text-slate-800">{{ $payment->method }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">পরিমাণ</span><span class="font-semibold text-slate-800">৳ {{ number_format($payment->amount, 2) }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">স্ট্যাটাস</span>
            <span class="text-xs font-medium px-2.5 py-1 rounded-none {{ $payment->status === 'paid' || $payment->status === 'reconciled' ? 'bg-emerald-50 text-emerald-700' : ($payment->status === 'failed' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-700') }}">{{ \App\Support\Bengali::label($payment->status) }}</span>
        </div>
        @if($payment->paid_at)<div class="flex justify-between"><span class="text-slate-500">পরিশোধের তারিখ</span><span class="text-slate-800">{{ $payment->paid_at->format('d M, Y H:i') }}</span></div>@endif
    </div>

    @if(in_array($payment->status, ['paid', 'reconciled']) && $payment->application)
        <a href="{{ route('applications.show', $payment->application) }}" class="block text-center px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">আবেদনে ফিরে যান</a>
    @elseif($payment->status === 'failed' && $payment->application)
        <a href="{{ route('payments.create', $payment->application) }}" class="block text-center px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">আবার চেষ্টা করুন</a>
    @endif
</div>
@endsection
