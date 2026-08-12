@extends('layouts.admin')
@section('title', 'প্রতিষ্ঠান সম্পাদনা')
@section('content')
<div class="max-w-4xl space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 mb-1">প্রতিষ্ঠান সম্পাদনা</h2>
        <p class="text-sm text-slate-500 mb-6">{{ $company->name }}</p>
        <form method="POST" action="{{ route('companies.update', $company) }}" enctype="multipart/form-data" onkeydown="if(event.key==='Enter' && event.target.tagName!=='TEXTAREA'){event.preventDefault();}"
              class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-6">
            @csrf @method('PUT')
            @include('companies._form')
            <button class="px-6 py-3 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition shadow-lg">পরিবর্তন সংরক্ষণ করুন</button>
        </form>
    </div>

    {{-- Mock verification actions — only meaningful once the record exists --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-none border border-slate-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-800">মোবাইল যাচাইকরণ</p>
                <p class="text-xs text-slate-500">{{ $company->mobile_verified_at ? 'যাচাইকৃত — ' . $company->mobile_verified_at->format('d M, Y') : 'এখনো যাচাই করা হয়নি' }}</p>
            </div>
            @unless($company->mobile_verified_at)
            <form method="POST" action="{{ route('companies.verify-mobile', $company) }}">
                @csrf
                <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-xs hover:bg-slate-800 transition">যাচাই করুন</button>
            </form>
            @endunless
        </div>
        <div class="bg-white rounded-none border border-slate-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-800">ইমেইল যাচাইকরণ</p>
                <p class="text-xs text-slate-500">{{ $company->email_verified_at ? 'যাচাইকৃত — ' . $company->email_verified_at->format('d M, Y') : 'এখনো যাচাই করা হয়নি' }}</p>
            </div>
            @unless($company->email_verified_at)
            <form method="POST" action="{{ route('companies.verify-email', $company) }}">
                @csrf
                <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-xs hover:bg-slate-800 transition">যাচাই করুন</button>
            </form>
            @endunless
        </div>
    </div>
</div>
@endsection
