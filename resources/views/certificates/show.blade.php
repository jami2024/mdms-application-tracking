@extends('layouts.admin')
@section('title', 'সার্টিফিকেট ' . $certificate->certificate_no)
@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 font-mono">{{ $certificate->certificate_no }}</h2>
            <p class="text-sm text-slate-500 mt-0.5">{{ \App\Support\Bengali::label($certificate->template->module) }}</p>
        </div>
        <span class="text-xs font-medium px-3 py-1 rounded-none {{ $certificate->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
            {{ \App\Support\Bengali::label($certificate->status) }}
        </span>
    </div>

    @if(session('status'))<div class="rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5 border border-emerald-100">{{ session('status') }}</div>@endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Live PDF preview --}}
        <div class="lg:col-span-2 bg-white rounded-none border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
                <p class="text-sm font-semibold text-slate-800">প্রিভিউ</p>
                <a href="{{ route('certificates.download', $certificate) }}" class="text-xs text-brand-600 hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2"/></svg>
                    ডাউনলোড
                </a>
            </div>
            <iframe src="{{ route('certificates.preview', $certificate) }}#toolbar=0" class="w-full bg-slate-50" style="height: 560px; border: 0;"></iframe>
        </div>

        {{-- Details + QR + actions --}}
        <div class="space-y-6">
            <div class="bg-white rounded-none border border-slate-200 shadow-sm p-5 space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">আবেদনকারী</span><span class="font-medium text-slate-800">{{ $certificate->application->applicant->name }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">টেমপ্লেট</span><span class="font-medium text-slate-800">{{ $certificate->template->name }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">স্বাক্ষর</span><span class="font-medium text-slate-800">{{ \App\Support\Bengali::label($certificate->signature_type) }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">স্বাক্ষরকারী</span><span class="font-medium text-slate-800">{{ $certificate->signedBy->name ?? '—' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">ইস্যুর তারিখ</span><span class="font-medium text-slate-800">{{ $certificate->issue_date->format('d M, Y') }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">মেয়াদ শেষ</span><span class="font-medium text-slate-800">{{ $certificate->expiry_date?->format('d M, Y') ?? 'আজীবন' }}</span></div>
            </div>

            @if($certificate->qr_code_path)
            <div class="bg-white rounded-none border border-slate-200 shadow-sm p-5 flex flex-col items-center gap-3">
                <img src="{{ \Illuminate\Support\Facades\Storage::url($certificate->qr_code_path) }}" alt="যাচাইকরণ QR" class="h-32 w-32">
                <p class="text-xs text-slate-400 text-center">যাচাই করতে স্ক্যান করুন<br><span class="font-mono">{{ route('certificates.verify', $certificate->certificate_no) }}</span></p>
            </div>
            @endif

            <div class="space-y-2">
                <a href="{{ route('certificates.download', $certificate) }}" class="block text-center px-4 py-2.5 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">পিডিএফ ডাউনলোড</a>
                @if($certificate->status === 'active')
                <form method="POST" action="{{ route('certificates.revoke', $certificate) }}" onsubmit="return confirm('এই সার্টিফিকেটটি বাতিল করবেন?')">
                    @csrf
                    <button class="w-full px-4 py-2.5 rounded-none border border-red-200 text-red-600 text-sm hover:bg-red-50 transition">বাতিল করুন</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
