@extends('layouts.admin')
@section('title', 'নতুন সার্ভিস আবেদন')

@section('content')
<div class="w-full max-w-6xl mx-auto px-4">

    @php
        $documentTypes = [
            'nid'              => 'জাতীয় পরিচয়পত্র (এনআইডি)',
            'passport'         => 'পাসপোর্ট',
            'trade_license'    => 'ট্রেড লাইসেন্স',
            'driving_license'  => 'ড্রাইভিং লাইসেন্স',
            'birth_certificate'=> 'জন্ম নিবন্ধন সনদ',
            'other'            => 'অন্যান্য',
        ];
    @endphp

    {{-- Page Header --}}
    <div class="flex items-center gap-3.5 mb-2 border-slate-200">
        <div class="p-3 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 shrink-0">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">ফ্রন্ট ডেস্ক — আবেদনকারীর তথ্য সংগ্রহ</h1>
            <p class="text-xs text-slate-500 mt-0.5">সংশ্লিষ্ট ডেস্কে প্রেরণের পূর্বে সরাসরি আগত আবেদনকারীর তথ্য সংরক্ষণ করুন।</p>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if (session('success_message'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg p-3.5 flex items-center gap-2.5 text-sm">
            <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success_message') }}</span>
        </div>
    @endif

    @if (session('error_message'))
        <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg p-3.5 flex items-center gap-2.5 text-sm">
            <svg class="w-5 h-5 shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span>{{ session('error_message') }}</span>
        </div>
    @endif

    {{-- Main Form Card --}}
    <form method="POST"
          id="applicant-form"
          action="{{ route('services.applicationNewStore')}}"
          enctype="multipart/form-data"
          class="bg-white rounded-xl border border-slate-200 shadow-sm px-5 sm:p-7 space-y-2">
        @csrf

        {{-- Section 1: Applicant Identity --}}
        <div>
            <div class="flex items-center gap-2 pb-2 mb-4 border-b border-slate-100">
                <span class="w-1.5 h-4 bg-emerald-600 rounded-full"></span>
                <h2 class="text-base font-semibold text-slate-800">আবেদনকারীর পরিচিতি</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1" for="applicant_name">
                        আবেদনকারীর নাম <span class="text-rose-500">*</span>
                    </label>
                    <input id="applicant_name" name="applicant_name" type="text" required
                           class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500/20 py-2 px-3 text-slate-800 placeholder:text-slate-400"
                           placeholder="এনআইডি অনুযায়ী পূর্ণ নাম"
                           value="{{ old('applicant_name') }}">
                    @error('applicant_name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1" for="mobile_number">
                        মোবাইল নম্বর <span class="text-rose-500">*</span>
                    </label>
                    <input id="mobile_number" name="mobile_number" type="text" inputmode="numeric" required
                           class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500/20 py-2 px-3 text-slate-800 placeholder:text-slate-400"
                           placeholder="উদাহরণ: ০১৭১২৪৫৬৭৮৯"
                           value="{{ old('mobile_number') }}">
                    @error('mobile_number') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1" for="nid">
                        জাতীয় পরিচয়পত্র (এনআইডি) নম্বর <span class="text-rose-500">*</span>
                    </label>
                    <input id="nid" name="nid" type="text" inputmode="numeric" required
                           class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500/20 py-2 px-3 text-slate-800 placeholder:text-slate-400"
                           placeholder="উদাহরণ: ১২৩৪৫৬৭৮৯০১২৩"
                           value="{{ old('nid') }}">
                    @error('nid') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-semibold text-slate-700 mb-1" for="email">
                        ইমেইল ঠিকানা <span class="text-slate-400 font-normal">(ঐচ্ছিক)</span>
                    </label>
                    <input id="email" name="email" type="email"
                           class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500/20 py-2 px-3 text-slate-800 placeholder:text-slate-400"
                           placeholder="name@example.com"
                           value="{{ old('email') }}">
                    @error('email') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Section 2: Organization Details --}}
        <div>
            <div class="flex items-center gap-2 pb-2 mb-4 border-b border-slate-100">
                <span class="w-1.5 h-4 bg-emerald-600 rounded-full"></span>
                <h2 class="text-base font-semibold text-slate-800">প্রতিষ্ঠানের তথ্য</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1" for="company_name">প্রতিষ্ঠানের নাম</label>
                    <input id="company_name" name="company_name" type="text"
                           class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500/20 py-2 px-3 text-slate-800 placeholder:text-slate-400"
                           placeholder="উদাহরণ: বেক্সিমকো মেডিকেল লিমিটেড"
                           value="{{ old('company_name') }}">
                    @error('company_name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1" for="designation">পদবি</label>
                    <input id="designation" name="designation" type="text"
                           class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500/20 py-2 px-3 text-slate-800 placeholder:text-slate-400"
                           placeholder="উদাহরণ: ম্যানেজার"
                           value="{{ old('designation') }}">
                    @error('designation') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1" for="tin">টিআইএন (TIN)</label>
                    <input id="tin" name="tin" type="text" inputmode="numeric"
                           class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500/20 py-2 px-3 text-slate-800 placeholder:text-slate-400"
                           placeholder="করদাতা শনাক্তকরণ নম্বর"
                           value="{{ old('tin') }}">
                    @error('tin') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Section 3: Document Verification --}}
        <div>
            <div class="flex items-center gap-2 pb-2 mb-4 border-b border-slate-100">
                <span class="w-1.5 h-4 bg-emerald-600 rounded-full"></span>
                <h2 class="text-base font-semibold text-slate-800">কাগজপত্র যাচাই</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1" for="document_type">
                        কাগজপত্রের ধরন <span class="text-rose-500">*</span>
                    </label>
                    <select id="document_type" name="document_type" required
                            class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500/20 py-2 px-3 text-slate-800">
                        <option value="" disabled selected>নির্বাচন করুন</option>
                        @foreach ($documentTypes as $value => $label)
                            <option value="{{ $value }}" @selected(old('document_type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1" for="document_number">কাগজপত্রের নম্বর</label>
                    <input id="document_number" name="document_number" type="text"
                           class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500/20 py-2 px-3 text-slate-800 placeholder:text-slate-400"
                           placeholder="কাগজপত্রে মুদ্রিত অনুযায়ী লিখুন"
                           value="{{ old('document_number') }}">
                    @error('document_number') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">কাগজপত্রের কপি আপলোড করুন</label>
                    <label for="document_file"
                           class="flex items-center justify-between gap-3 rounded-lg border border-dashed border-slate-300 bg-slate-50/50 hover:bg-slate-50 px-4 py-3 cursor-pointer hover:border-emerald-500/60 transition group">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <span class="text-xs text-slate-500 truncate" id="document_file_name">পিডিএফ অথবা ছবি, সর্বোচ্চ ১০ এমবি</span>
                        </div>
                        <span class="shrink-0 text-xs font-medium text-emerald-700 bg-emerald-50 group-hover:bg-emerald-100 border border-emerald-200/60 rounded-md px-3 py-1.5 transition">
                            ফাইল বেছে নিন
                        </span>
                    </label>
                    <input id="document_file" name="document_file" type="file" class="hidden"
                           accept="application/pdf,image/*"
                           onchange="document.getElementById('document_file_name').innerText = this.files[0]?.name ?? 'পিডিএফ অথবা ছবি, সর্বোচ্চ ১০ এমবি'">
                </div>
            </div>
        </div>

        {{-- Section 4: Additional Remarks --}}
        <div>
            <div class="flex items-center gap-2 pb-2 mb-4 border-b border-slate-100">
                <span class="w-1.5 h-4 bg-emerald-600 rounded-full"></span>
                <h2 class="text-base font-semibold text-slate-800">অতিরিক্ত তথ্য</h2>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1" for="remarks">মন্তব্য / অন্যান্য তথ্য</label>
                <textarea id="remarks" name="remarks" rows="2"
                          class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500/20 py-2 px-3 text-slate-800 placeholder:text-slate-400 resize-none"
                          placeholder="প্রয়োজনীয় যেকোনো অতিরিক্ত তথ্য লিখুন...">{{ old('remarks') }}</textarea>
                @error('remarks') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Actions Toolbar --}}
        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3">
            <button type="reset"
                    class="w-full sm:w-auto text-xs font-semibold text-slate-600 border border-slate-200 px-4 py-2.5 rounded-lg hover:bg-slate-50 hover:text-slate-800 transition">
                ফর্ম মুছুন
            </button>
            <button type="button" onclick="openReviewModal()"
                    class="w-full sm:w-auto text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 rounded-lg shadow-sm transition">
                আবেদনকারীর তথ্য সংরক্ষণ করুন
            </button>
        </div>

        {{-- Application Review Modal --}}
        <div id="review-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeReviewModal()"></div>

            <div class="relative bg-white w-full max-w-lg rounded-xl border border-slate-200 shadow-xl max-h-[90vh] flex flex-col overflow-hidden">
                <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center gap-3">
                    <div class="p-2 bg-amber-100 text-amber-700 rounded-lg shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">আবেদন পর্যালোচনা করুন</h3>
                        <p class="text-xs text-slate-500">তথ্য জমা দেওয়ার আগে একবার ভালো করে যাচাই করে নিন।</p>
                    </div>
                </div>

                <div id="review-modal-body" class="p-5 overflow-y-auto space-y-2.5 text-xs">
                    {{-- Populated by JavaScript --}}
                </div>

                <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeReviewModal()"
                            class="text-xs font-semibold text-slate-600 border border-slate-200 bg-white px-4 py-2 rounded-lg hover:bg-slate-100 transition">
                        সম্পাদনা করুন
                    </button>
                    <button type="button" onclick="confirmReviewSubmit()"
                            class="text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded-lg shadow-sm transition">
                        নিশ্চিত করুন ও জমা দিন
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const reviewFields = {
        'আবেদনকারীর নাম':          'applicant_name',
        'জাতীয় পরিচয়পত্র নম্বর':  'nid',
        'মোবাইল নম্বর':            'mobile_number',
        'ইমেইল ঠিকানা':            'email',
        'প্রতিষ্ঠানের নাম':        'company_name',
        'পদবি':                    'designation',
        'টিআইএন':                 'tin',
        'কাগজপত্রের ধরন':          'document_type',
        'কাগজপত্রের নম্বর':        'document_number',
        'মন্তব্য':                 'remarks',
    };

    function openReviewModal() {
        const form = document.getElementById('applicant-form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const body = document.getElementById('review-modal-body');
        body.innerHTML = '';

        for (const [label, id] of Object.entries(reviewFields)) {
            const el = document.getElementById(id);
            if (!el) continue;

            let value = el.tagName === 'SELECT'
                ? (el.options[el.selectedIndex]?.text ?? '')
                : el.value;

            value = value?.trim() || '—';

            body.insertAdjacentHTML('beforeend', `
                <div class="flex items-start justify-between gap-3 pb-2 border-b border-slate-100">
                    <span class="text-slate-500 shrink-0 font-medium">${label}</span>
                    <span class="text-slate-800 font-semibold text-right">${escapeHtml(value)}</span>
                </div>
            `);
        }

        const fileInput = document.getElementById('document_file');
        const fileName = fileInput?.files[0]?.name ?? 'আপলোড করা হয়নি';
        body.insertAdjacentHTML('beforeend', `
            <div class="flex items-start justify-between gap-3 pt-1">
                <span class="text-slate-500 shrink-0 font-medium">আপলোডকৃত কাগজপত্র</span>
                <span class="text-slate-800 font-semibold text-right truncate max-w-[200px]">${escapeHtml(fileName)}</span>
            </div>
        `);

        document.getElementById('review-modal').classList.remove('hidden');
    }

    function closeReviewModal() {
        document.getElementById('review-modal').classList.add('hidden');
    }

    function confirmReviewSubmit() {
        document.getElementById('applicant-form').submit();
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
</script>
@endsection
