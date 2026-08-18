@extends('layouts.admin')
@section('title', $company->name)
@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-start justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">{{ $company->name }}</h2>
            <p class="text-sm text-slate-500 mt-0.5">{{ $company->division?->name }} {{ $company->district ? '· '.$company->district->name : '' }}</p>
        </div>
        <div class="flex flex-col items-end gap-1.5">
            @php $colors = ['draft'=>'bg-slate-100 text-slate-500','submitted'=>'bg-amber-50 text-amber-700','active'=>'bg-emerald-50 text-emerald-700','suspended'=>'bg-orange-50 text-orange-700','rejected'=>'bg-red-50 text-red-600']; @endphp
            <span class="text-xs font-medium px-3 py-1 rounded-none {{ $colors[$company->status] ?? '' }}">{{ \App\Support\Bengali::label($company->status) }}</span>
            @if($company->verification_status === 'verified')
                <span class="text-xs font-medium px-3 py-1 rounded-none bg-blue-50 text-blue-700">✓ পরিচয় যাচাইকৃত</span>
            @elseif($company->verification_status === 'pending')
                <span class="text-xs font-medium px-3 py-1 rounded-none bg-slate-100 text-slate-500">পরিচয় যাচাই বাকি</span>
            @endif
        </div>
    </div>

    @if(session('status'))<div class="rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5 border border-emerald-100">{{ session('status') }}</div>@endif
    @if(session('error'))<div class="rounded-none bg-red-50 text-red-600 text-sm px-5 py-3.5 border border-red-100">{{ session('error') }}</div>@endif

    <div class="flex gap-3">
        @if(in_array($company->status, ['draft', 'rejected']))
            <a href="{{ route('companies.edit', $company) }}" class="px-4 py-2 rounded-none border border-slate-300 bg-white text-sm hover:bg-slate-50 transition">সম্পাদনা</a>
            <form method="POST" action="{{ route('companies.submit', $company) }}">
                @csrf
                <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">পর্যালোচনার জন্য জমা দিন</button>
            </form>
            <form method="POST" action="{{ route('companies.destroy', $company) }}" onsubmit="return confirm('এই খসড়াটি মুছে ফেলবেন?')">
                @csrf @method('DELETE')
                <button class="px-4 py-2 rounded-none border border-red-200 text-red-600 text-sm hover:bg-red-50 transition">মুছুন</button>
            </form>
        @elseif($company->application)
            <a href="{{ route('applications.show', $company->application) }}" class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">আবেদনের অবস্থা দেখুন</a>
        @endif
    </div>

    {{-- Applicant identity summary --}}
    <div class="bg-white rounded-none border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <span class="h-6 w-6 rounded-full bg-brand-600 text-white text-[11px] font-semibold flex items-center justify-center">১</span>
            <p class="text-sm font-semibold text-slate-800">আবেদনকারীর পরিচয়</p>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div class="flex justify-between"><span class="text-slate-500">নাম</span><span class="font-medium text-slate-800">{{ collect([$company->name_prefix ? \App\Support\Bengali::label($company->name_prefix) : null, $company->applicant_full_name])->filter()->implode(' ') ?: '—' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">পদবি</span><span class="font-medium text-slate-800">{{ $company->applicant_designation ?? '—' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">মোবাইল</span><span class="font-medium text-slate-800">{{ $company->mobile_number ?? '—' }} {{ $company->mobile_verified_at ? '✓' : '' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">ইমেইল</span><span class="font-medium text-slate-800">{{ $company->primary_email ?? '—' }} {{ $company->email_verified_at ? '✓' : '' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">এনআইডি</span><span class="font-medium text-slate-800">{{ $company->national_id ?? '—' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">জন্ম তারিখ</span><span class="font-medium text-slate-800">{{ $company->date_of_birth?->format('d M, Y') ?? '—' }}</span></div>
        </div>
    </div>

    {{-- Business credentials summary --}}
    <div class="bg-white rounded-none border border-slate-200 shadow-sm overflow-hidden" x-data="{ lightbox: null }">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <span class="h-6 w-6 rounded-full bg-brand-600 text-white text-[11px] font-semibold flex items-center justify-center">২</span>
            <p class="text-sm font-semibold text-slate-800">প্রাতিষ্ঠানিক আইনি তথ্য</p>
        </div>
        <div class="p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div class="flex justify-between"><span class="text-slate-500">প্রতিষ্ঠানের ধরন</span><span class="font-medium text-slate-800">{{ $company->organization_type ? \App\Support\Bengali::label($company->organization_type) : '—' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">যোগাযোগ</span><span class="font-medium text-slate-800">{{ $company->contact_person ?? '—' }} · {{ $company->contact_phone ?? '—' }}</span></div>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">ব্যবসায়িক ডকুমেন্ট</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                @foreach([
                    ['no' => $company->trade_license_no, 'file' => $company->trade_license_file, 'label' => 'ট্রেড লাইসেন্স'],
                    ['no' => $company->tin_no, 'file' => $company->tin_file, 'label' => 'টিন'],
                    ['no' => $company->bin_no, 'file' => $company->bin_file, 'label' => 'বিন'],
                    ['no' => $company->rjsc_registration_number, 'file' => $company->rjsc_file, 'label' => 'আরজেএসসি'],
                    ['no' => $company->irc_number, 'file' => $company->irc_file, 'label' => 'আইআরসি'],
                ] as $doc)
                @php
                    $isImage = $doc['file'] && preg_match('/\.(jpe?g|png|gif|webp)$/i', $doc['file']);
                    $isPdf = $doc['file'] && preg_match('/\.pdf$/i', $doc['file']);
                    $url = document_url($doc['file']);
                @endphp
                <div class="border border-slate-200 rounded-none overflow-hidden hover:border-slate-300 hover:shadow-sm transition group">
                    @if($isImage)
                        <button type="button" @click="lightbox = { url: '{{ $url }}', label: '{{ $doc['label'] }}' }" class="block w-full h-20 bg-slate-50 relative">
                            <img src="{{ $url }}" class="h-full w-full object-cover group-hover:scale-105 transition"
                                 onerror="this.closest('.group').querySelector('.doc-fallback').style.display='flex'; this.style.display='none';">
                            <div class="doc-fallback hidden absolute inset-0 items-center justify-center text-slate-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </button>
                    @elseif($isPdf)
                        <a href="{{ $url }}" target="_blank" class="flex h-20 w-full items-center justify-center bg-red-50/50 text-red-400 hover:bg-red-50 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </a>
                    @else
                        <div class="h-20 w-full flex items-center justify-center bg-slate-50 text-slate-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/></svg>
                        </div>
                    @endif
                    <div class="px-2.5 py-2 text-center border-t border-slate-100">
                        <p class="text-[11px] text-slate-400 truncate">{{ $doc['label'] }}</p>
                        <p class="text-xs font-medium text-slate-700 truncate">{{ $doc['no'] ?? '—' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="text-sm {{ $company->signed_declaration_file ? 'text-emerald-700' : 'text-slate-400' }} flex items-center gap-1.5 border-t border-slate-100 pt-4">
            @if($company->signed_declaration_file)
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                আইনি অঙ্গীকারনামা স্বাক্ষরিত — {{ $company->declaration_signed_at?->format('d M, Y') }}
            @else
                আইনি অঙ্গীকারনামা এখনো অপেক্ষমাণ
            @endif
        </div>
        </div>

        {{-- Lightbox --}}
        <div x-show="lightbox" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-6" @keydown.escape.window="lightbox = null">
            <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" @click="lightbox = null"></div>
            <div x-show="lightbox" x-transition class="relative max-w-3xl max-h-[85vh] w-full">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm text-white font-medium" x-text="lightbox?.label"></p>
                    <button @click="lightbox = null" class="text-white/70 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <img :src="lightbox?.url" class="w-full h-auto max-h-[75vh] object-contain rounded-none bg-white">
            </div>
        </div>
    </div>

    {{-- Establishments --}}
    <div class="bg-white rounded-none border border-slate-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-slate-800">এস্টাবলিশমেন্ট</p>
            @if($company->status === 'active')
            <a href="{{ route('companies.establishments.create', $company) }}" class="text-xs text-brand-600 hover:underline">+ এস্টাবলিশমেন্ট যোগ করুন</a>
            @endif
        </div>
        <div class="space-y-2">
            @forelse($company->establishments as $e)
                <a href="{{ route('establishments.show', $e) }}" class="flex items-center justify-between text-sm py-2 border-b border-slate-50 last:border-0 hover:bg-slate-50 -mx-2 px-2 rounded">
                    <span class="text-slate-700">{{ $e->name }}</span>
                    <span class="text-xs text-slate-400">{{ \App\Support\Bengali::label($e->status) }}</span>
                </a>
            @empty
                <p class="text-sm text-slate-400">{{ $company->status === 'active' ? 'এখনো কোনো এস্টাবলিশমেন্ট নেই।' : 'প্রতিষ্ঠান অনুমোদিত হলে উপলব্ধ হবে।' }}</p>
            @endforelse
        </div>
    </div>

    {{-- Devices --}}
    <div class="bg-white rounded-none border border-slate-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-slate-800">মেডিকেল ডিভাইস</p>
            @if($company->status === 'active')
            <a href="{{ route('companies.devices.create', $company) }}" class="text-xs text-brand-600 hover:underline">+ ডিভাইস যোগ করুন</a>
            @endif
        </div>
        <div class="space-y-2">
            @forelse($company->devices as $d)
                <a href="{{ route('devices.show', $d) }}" class="flex items-center justify-between text-sm py-2 border-b border-slate-50 last:border-0 hover:bg-slate-50 -mx-2 px-2 rounded">
                    <span class="text-slate-700">{{ $d->device_name }}</span>
                    <span class="text-xs text-slate-400">{{ \App\Support\Bengali::label($d->status) }}</span>
                </a>
            @empty
                <p class="text-sm text-slate-400">{{ $company->status === 'active' ? 'এখনো কোনো ডিভাইস নেই।' : 'প্রতিষ্ঠান অনুমোদিত হলে উপলব্ধ হবে।' }}</p>
            @endforelse
        </div>
    </div>

    @if($company->status === 'active')
    <div class="bg-white rounded-none border border-slate-200 shadow-sm p-6 flex items-center justify-between">
        <p class="text-sm font-medium text-slate-800">এমআরপি আবেদনসমূহ</p>
        <a href="{{ route('companies.mrp-applications.create', $company) }}" class="text-xs text-brand-600 hover:underline">+ এমআরপি আবেদন জমা দিন</a>
    </div>
    @endif
</div>
@endsection
