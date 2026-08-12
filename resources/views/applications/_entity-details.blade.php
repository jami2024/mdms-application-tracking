@php $e = $application->applicable; $type = class_basename($application->applicable_type); @endphp

<div class="bg-white rounded-none border border-slate-200 shadow-sm overflow-hidden" x-data="{ lightbox: null }">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        <p class="text-sm font-semibold text-slate-800">আবেদনের সম্পূর্ণ তথ্য</p>
    </div>

    @if($type === 'Company')
    <div class="p-6 space-y-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">আবেদনকারীর পরিচয়</p>
            <div class="flex flex-col sm:flex-row gap-5">
                <div class="shrink-0">
                    <p class="text-[11px] text-slate-400 mb-1.5">এনআইডি ছবি</p>
                    @if($e->nid_photo)
                        <button type="button" @click="lightbox = { url: '{{ \Illuminate\Support\Facades\Storage::url($e->nid_photo) }}', label: 'জাতীয় পরিচয়পত্র' }"
                                class="block h-20 w-20 rounded-none border border-slate-200 overflow-hidden group relative">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($e->nid_photo) }}" class="h-full w-full object-cover group-hover:scale-105 transition"
                                 onerror="this.parentElement.innerHTML='<div class=\'h-full w-full flex items-center justify-center bg-slate-50 text-slate-300\'><svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.5\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg></div>'">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center">
                                <svg class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </button>
                    @else
                        <div class="h-20 w-20 rounded-none border border-dashed border-slate-200 flex items-center justify-center text-slate-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2.5 text-sm">
                    <div class="flex justify-between"><span class="text-slate-500">নাম</span><span class="font-medium text-slate-800">{{ collect([$e->name_prefix ? \App\Support\Bengali::label($e->name_prefix) : null, $e->applicant_full_name])->filter()->implode(' ') ?: '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">পদবি</span><span class="font-medium text-slate-800">{{ $e->applicant_designation ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">মোবাইল</span><span class="font-medium text-slate-800">{{ $e->mobile_number ?? '—' }} @if($e->mobile_verified_at)<span class="text-emerald-600">✓</span>@endif</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">ইমেইল</span><span class="font-medium text-slate-800">{{ $e->primary_email ?? '—' }} @if($e->email_verified_at)<span class="text-emerald-600">✓</span>@endif</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">এনআইডি</span><span class="font-medium text-slate-800">{{ $e->national_id ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">জাতীয়তা</span><span class="font-medium text-slate-800">{{ $e->nationality ?? '—' }}</span></div>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">প্রাতিষ্ঠানিক তথ্য</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2.5 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">প্রতিষ্ঠানের নাম</span><span class="font-medium text-slate-800">{{ $e->name }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">ধরন</span><span class="font-medium text-slate-800">{{ $e->organization_type ? \App\Support\Bengali::label($e->organization_type) : '—' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">ঠিকানা</span><span class="font-medium text-slate-800 text-right">{{ collect([$e->address_line_1, $e->address_line_2])->filter()->implode(', ') ?: ($e->address ?? '—') }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">অবস্থান</span><span class="font-medium text-slate-800">{{ collect([$e->upazila?->name, $e->district?->name, $e->division?->name])->filter()->implode(', ') ?: '—' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">যোগাযোগ</span><span class="font-medium text-slate-800">{{ $e->contact_person ?? '—' }} · {{ $e->contact_phone ?? '—' }}</span></div>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">ব্যবসায়িক ডকুমেন্ট</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                @foreach([
                    ['no' => $e->trade_license_no, 'file' => $e->trade_license_file, 'label' => 'ট্রেড লাইসেন্স'],
                    ['no' => $e->tin_no, 'file' => $e->tin_file, 'label' => 'টিন'],
                    ['no' => $e->bin_no, 'file' => $e->bin_file, 'label' => 'বিন'],
                    ['no' => $e->rjsc_registration_number, 'file' => $e->rjsc_file, 'label' => 'আরজেএসসি'],
                    ['no' => $e->irc_number, 'file' => $e->irc_file, 'label' => 'আইআরসি'],
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
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center">
                                <svg class="w-4 h-4 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
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

        @if($e->signed_declaration_file)
        <div class="border-t border-slate-100 pt-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">আইনি অঙ্গীকারনামা</p>
            <div class="flex items-center gap-3">
                @php
                    $isImage = preg_match('/\.(jpe?g|png|gif|webp)$/i', $e->signed_declaration_file);
                    $url = document_url($e->signed_declaration_file);
                @endphp
                @if($isImage)
                    <button type="button" @click="lightbox = { url: '{{ $url }}', label: 'আইনি অঙ্গীকারনামা' }"
                            class="h-14 w-14 rounded-none border border-slate-200 overflow-hidden shrink-0">
                        <img src="{{ $url }}" class="h-full w-full object-cover">
                    </button>
                @else
                    <a href="{{ $url }}" target="_blank" class="h-14 w-14 rounded-none border border-red-100 bg-red-50/50 flex items-center justify-center text-red-400 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </a>
                @endif
                <div class="flex items-center gap-1.5 text-sm text-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    স্বাক্ষরিত — {{ $e->declaration_signed_at?->format('d M, Y') }}
                </div>
            </div>
        </div>
        @endif
    </div>

    @elseif($type === 'Establishment')
    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2.5 text-sm">
        <div class="flex justify-between"><span class="text-slate-500">নাম</span><span class="font-medium text-slate-800">{{ $e->name }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">প্রতিষ্ঠান</span><span class="font-medium text-slate-800">{{ $e->company->name }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">লাইসেন্স নং</span><span class="font-medium text-slate-800">{{ $e->license_no ?? '—' }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">ঠিকানা</span><span class="font-medium text-slate-800">{{ $e->address ?? '—' }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">লাইসেন্স ইস্যু</span><span class="font-medium text-slate-800">{{ $e->license_issue_date?->format('d M, Y') ?? '—' }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">মেয়াদ শেষ</span><span class="font-medium text-slate-800">{{ $e->license_expiry_date?->format('d M, Y') ?? '—' }}</span></div>
    </div>

    @elseif($type === 'Device')
    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2.5 text-sm">
        <div class="flex justify-between"><span class="text-slate-500">ডিভাইসের নাম</span><span class="font-medium text-slate-800">{{ $e->device_name }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">প্রতিষ্ঠান</span><span class="font-medium text-slate-800">{{ $e->company->name }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">মডেল নং</span><span class="font-medium text-slate-800">{{ $e->model_no ?? '—' }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">প্রস্তুতকারক</span><span class="font-medium text-slate-800">{{ $e->manufacturer ?? '—' }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">উৎপত্তির দেশ</span><span class="font-medium text-slate-800">{{ $e->country_of_origin ?? '—' }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">গ্রেড</span><span class="font-medium text-slate-800">{{ $e->productGrade?->name ?? '—' }}</span></div>
    </div>

    @elseif($type === 'MrpApplication')
    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2.5 text-sm">
        <div class="flex justify-between"><span class="text-slate-500">প্রতিষ্ঠান</span><span class="font-medium text-slate-800">{{ $e->company->name }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">ডিভাইস</span><span class="font-medium text-slate-800">{{ $e->device->device_name }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">প্রস্তাবিত এমআরপি</span><span class="font-medium text-slate-800">৳ {{ number_format($e->proposed_mrp, 2) }}</span></div>
        @if($e->approved_mrp)
        <div class="flex justify-between"><span class="text-slate-500">অনুমোদিত এমআরপি</span><span class="font-medium text-emerald-700">৳ {{ number_format($e->approved_mrp, 2) }}</span></div>
        @endif
    </div>
    @endif

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
