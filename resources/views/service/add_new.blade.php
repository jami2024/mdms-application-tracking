@extends('layouts.admin')
@section('title', 'নতুন সার্ভিস আবেদন')

@section('content')

{{-- Bengali font — for best performance, move this <link> into your main <head> in layouts.admin instead of loading it per-page --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    #applicant-page, #review-modal, #receipt-print {
        /* font-family: 'Noto Sans Bengali', 'Hind Siliguri', ui-sans-serif, system-ui, sans-serif; */
    }

    .field-invalid {
        border-color: #fb7185 !important; /* rose-400 */
        box-shadow: 0 0 0 3px rgba(251, 113, 133, 0.15);
    }

    kbd {
        font-family: ui-monospace, monospace;
        font-size: 10px;
        line-height: 1;
        padding: 2px 5px;
        border-radius: 4px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #64748b;
    }

    /* ---- Print / PDF receipt ---- */
    @media print {
        body * { visibility: hidden; }
        #receipt-print, #receipt-print * { visibility: visible; }
        #receipt-print { position: fixed; inset: 0; padding: 12px; }
        @page { margin: 10mm; }
    }
</style>

<div id="applicant-page" class="flex flex-col w-full max-w-5xl mx-auto px-4">

    @php
        $serviceTypes = [
            '1' => 'অনুমোদিত নতুন প্রকল্পের মেয়াদ বৃদ্ধির আবেদন',
            '2' => 'ঔষধ উৎপাদনের জন্য নতুন প্রকল্প অনুমোদনের আবেদন',
            '3' => 'নতুন ঔষধ উৎপাদন লাইসেন্স প্রদানের আবেদন',
        ];

        $receipt = session('application_data');
        $receiptId = session('application_id', now()->format('ymdHis'));
        $receiptDate = now()->format('d-m-Y h:i A');
        $oldServiceTypeOption = session('old_service_id_option');
    @endphp

    {{-- Everything below is hidden during printing so only the receipt (if triggered) prints --}}
    <div id="page-content" class="col">

        {{-- Page Header --}}
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2.5 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-800 tracking-tight">ফ্রন্ট ডেস্ক — আবেদনকারীর তথ্য সংগ্রহ</h1>
                <p class="text-xs text-slate-500">সংশ্লিষ্ট ডেস্কে প্রেরণের পূর্বে সরাসরি আগত আবেদনকারীর তথ্য সংরক্ষণ করুন।</p>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if (session('success_message'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg px-3.5 py-3 text-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success_message') }} </span>
                </div>
                @if ($receipt)
                    <div class="mt-2.5 pt-2.5 border-t border-emerald-200/70 flex items-center justify-between gap-3">
                        <span class="text-emerald-700">
                            ট্র্যাকিং নম্বর: <strong>{{ $receiptId }}</strong>
                            @if (session('payment_url'))
                                ,
                                <span class="text-emerald-700">  &nbsp;
                                    <a href="{{ session('payment_url') }}" target="_blank" class="text-emerald-700 underline"> পেমেন্ট করুন</a></span>
                            @endif
                        </span>

                        <button type="button" onclick="downloadReceipt()"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 px-3.5 py-1.5 rounded-lg shadow-sm transition shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H8a2 2 0 01-2-2V5a2 2 0 012-2h6l6 6v11a2 2 0 01-2 2z"/></svg>
                            রশিদ ডাউনলোড করুন (PDF)
                        </button>
                    </div>
                @endif
            </div>
        @endif

        @if (session('error_message'))
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg px-3.5 py-2.5 flex items-center gap-2 text-xs">
                <svg class="w-4 h-4 shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>{{ session('error_message') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 bg-white rounded-xl border-b-red-800 px-6 sm:px-8 py-2 sm:py-2">
                    @foreach ($errors->all() as $error)
                        <p class="mt-1 text-sm text-red-900">
                            <i class="fa fa-info-circle text-red-900"></i> {{ $error }}
                        </p>
                    @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">

            {{-- Main Form Card — one compact block, no heavy sections, built for fast counter entry --}}
            <form method="POST"
                id="applicant-form"
                novalidate
                action="{{ route('services.applicationNewStore')}}"
                class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm px-4 py-5 sm:px-6 sm:py-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1" for="service_id">
                            সার্ভিসের নাম <span class="text-rose-500">*</span>
                            <span class="text-slate-400 font-normal">(টাইপ করে খুঁজুন)</span>
                        </label>
                        <select id="service_id" name="service_id" required
                                class="w-full text-sm">
                            @if ($oldServiceTypeOption)
                                <option value="{{ $oldServiceTypeOption['id'] }}" selected>{{ $oldServiceTypeOption['text'] }}</option>
                            @endif
                        </select>
                        <p class="hidden text-rose-500 text-xs mt-1" id="err_service_id"></p>
                        @error('service_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1" for="applicant_name">
                            আবেদনকারীর নাম <span class="text-rose-500">*</span>
                        </label>
                        <input id="applicant_name" name="applicant_name" type="text" required autofocus
                            class="w-full text-sm rounded-lg border border-slate-300 shadow-sm hover:border-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 py-1.5 px-3 text-slate-800 placeholder:text-slate-400"
                            placeholder="পূর্ণ নাম লিখুন"
                            value="{{ old('applicant_name') }}">
                        <p class="hidden text-rose-500 text-xs mt-1" id="err_applicant_name"></p>
                        @error('applicant_name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1" for="mobile_number">
                            মোবাইল নম্বর <span class="text-rose-500">*</span>
                        </label>
                        <input id="mobile_number" name="mobile_number" type="text" inputmode="numeric" required
                            class="w-full text-sm rounded-lg border border-slate-300 shadow-sm hover:border-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 py-1.5 px-3 text-slate-800 placeholder:text-slate-400"
                            placeholder="০১৭XXXXXXXX"
                            value="{{ old('mobile_number') }}">
                        <p class="hidden text-rose-500 text-xs mt-1" id="err_mobile_number"></p>
                        @error('mobile_number') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1" for="email">
                            ইমেইল <span class="text-slate-400 font-normal">(ঐচ্ছিক)</span>
                        </label>
                        <input id="email" name="email" type="email"
                            class="w-full text-sm rounded-lg border border-slate-300 shadow-sm hover:border-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 py-1.5 px-3 text-slate-800 placeholder:text-slate-400"
                            placeholder="name@example.com"
                            value="{{ old('email') }}">
                        <p class="hidden text-rose-500 text-xs mt-1" id="err_email"></p>
                        @error('email') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1" for="company_name">
                            প্রতিষ্ঠানের নাম <span class="text-slate-400 font-normal">(ঐচ্ছিক)</span>
                        </label>
                        <input id="company_name" name="company_name" type="text"
                            class="w-full text-sm rounded-lg border border-slate-300 shadow-sm hover:border-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 py-1.5 px-3 text-slate-800 placeholder:text-slate-400"
                            placeholder="উদাহরণ: বেক্সিমকো মেডিকেল লিমিটেড"
                            value="{{ old('company_name') }}">
                        @error('company_name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1" for="designation">
                            পদবি <span class="text-slate-400 font-normal">(ঐচ্ছিক)</span>
                        </label>
                        <input id="designation" name="designation" type="text"
                            class="w-full text-sm rounded-lg border border-slate-300 shadow-sm hover:border-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 py-1.5 px-3 text-slate-800 placeholder:text-slate-400"
                            placeholder="উদাহরণ: ম্যানেজার"
                            value="{{ old('designation') }}">
                        @error('designation') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>


                </div>

                {{-- Actions Toolbar --}}
                <div class="pt-4 mt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5">
                    <button type="reset" onclick="clearAllErrors()"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-semibold text-slate-600 border border-slate-300 shadow-sm px-4 py-2 rounded-lg hover:bg-slate-50 hover:text-slate-800 transition">
                        ফর্ম মুছুন <kbd>Alt R</kbd>
                    </button>
                    <button type="button" id="save-btn" onclick="openReviewModal()" disabled
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 px-5 py-2 rounded-lg shadow-sm transition disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-emerald-600">
                        সংরক্ষণ করুন <kbd class="border-emerald-400 bg-emerald-700/40 text-emerald-50">Ctrl ⏎</kbd>
                    </button>
                </div>

                {{-- Application Review Modal --}}
                <div id="review-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeReviewModal()"></div>

                    <div class="relative bg-white w-full max-w-sm rounded-xl border border-slate-200 shadow-xl max-h-[85vh] flex flex-col overflow-hidden">
                        <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center gap-2.5">
                            <div class="p-1.5 bg-amber-100 text-amber-700 rounded-lg shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">আবেদন পর্যালোচনা করুন</h3>
                                <p class="text-xs text-slate-500">তথ্য জমা দেওয়ার আগে একবার ভালো করে যাচাই করে নিন।</p>
                            </div>
                        </div>

                        <div id="review-modal-body" class="p-4 overflow-y-auto space-y-2 text-xs">
                            {{-- Populated by JavaScript --}}
                        </div>

                        <div class="px-4 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2">
                            <button type="button" onclick="closeReviewModal()"
                                    class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 border border-slate-300 shadow-sm bg-white px-4 py-1.5 rounded-lg hover:bg-slate-100 transition">
                                সম্পাদনা করুন <kbd>Esc</kbd>
                            </button>
                            <button type="button" onclick="confirmReviewSubmit()"
                                    class="inline-flex items-center gap-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 px-4 py-1.5 rounded-lg shadow-sm transition">
                                নিশ্চিত করুন ও জমা দিন <kbd class="border-emerald-400 bg-emerald-700/40 text-emerald-50">Ctrl ⏎</kbd>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Checklist panel --}}
            <div id="checklist-panel"
                class="lg:col-span-1 bg-white rounded-xl border border-slate-200 shadow-sm p-4 lg:sticky lg:top-4">
                <p class="text-sm font-semibold text-slate-800 mb-1">প্রয়োজনীয় যাচাইবাছাই</p>
                <p class="text-xs text-slate-500 mb-3" id="checklist-empty">
                    সার্ভিস নির্বাচন করলে প্রাসঙ্গিক চেকলিস্ট এখানে প্রদর্শিত হবে।
                </p>

                <div id="checklist-items" class="space-y-2.5"></div>

                <div id="checklist-progress" class="hidden mt-3 pt-3 border-t border-slate-100 text-xs font-medium text-slate-500"></div>

                <p id="checklist-warning" class="hidden mt-3 text-[11px] text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-2.5 py-1.5">
                    সাবমিট সক্রিয় করতে সবগুলো আইটেম টিক দিন।
                </p>
            </div>
        </div>

    </div> {{-- /#page-content --}}

    {{-- Printable receipt — kept hidden on screen, shown only when downloadReceipt() triggers window.print() --}}
    @if ($receipt)
        <div id="receipt-print" class="hidden bg-white">
            <div class="relative max-w-2xl mx-auto border border-slate-300 rounded-lg p-6 overflow-hidden" style="break-inside: avoid;">

                {{-- Watermark: faint, centered, behind all content --}}
                <img src="https://dgda.gov.bd/site-assets/images/logo.png" alt=""
                     aria-hidden="true"
                     class="pointer-events-none select-none absolute inset-0 m-auto w-56 h-56 object-contain opacity-[0.06] z-0"
                     style="-webkit-print-color-adjust: exact; print-color-adjust: exact; color-adjust: exact;"
                     onerror="this.remove()">

                <div class="relative z-10">

                {{-- Logo + title, centered together as one group --}}
                <div class="flex flex-col items-center text-center gap-1.5 border-b border-slate-300 pb-3 mb-4">
                    <img src="https://dgda.gov.bd/site-assets/images/logo.png" alt="বাংলাদেশ সরকার"
                         class="w-12 h-12 object-contain"
                         onerror="this.style.display='none'">
                    <h2 class="text-lg font-bold text-slate-800">{{ config('app.app_manage_name') }}</h2>
                    <p class="text-lg text-slate-800">আবেদন গ্রহণের রশিদ</p>
                    <p class="text-xs text-slate-500">Application Acknowledgement Receipt</p>
                </div>

                <div class="flex justify-between text-xs mb-4">
                    <span><strong>ট্র্যাকিং নম্বর:</strong> {{ $receiptId }}</span>
                    <span><strong>তারিখ ও সময়:</strong> {{ $receiptDate }}</span>
                </div>

                <table class="w-full text-xs border-collapse">
                    <tbody>
                        @php
                            $rows = [
                                'আবেদনকারীর নাম'   => $receipt['applicant_name'] ?? '—',
                                'মোবাইল নম্বর'       => $receipt['mobile_number'] ?? '—',
                                'ইমেইল'             => $receipt['email'] ?? '—',
                                'প্রতিষ্ঠানের নাম'      => $receipt['company_name'] ?? '—',
                                'পদবি'              => $receipt['designation'] ?? '—',
                                'সার্ভিস নাম'         => $receipt['service_name'] ?? $receipt['service_id'] ?? '—',
                            ];
                        @endphp
                        @foreach ($rows as $label => $value)
                            <tr class="border-b border-slate-100">
                                <td class="py-1 pr-3 text-slate-500 font-medium w-1/3 align-top">{{ $label }}</td>
                                <td class="py-1 text-slate-800 font-semibold align-top">{{ $value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-5 pt-3 border-t border-slate-200 text-[10px] text-slate-400 text-center">
                    এটি একটি স্বয়ংক্রিয়ভাবে তৈরি রশিদ। ভবিষ্যতে যোগাযোগের জন্য ট্র্যাকিং নম্বরটি সংরক্ষণ করুন।
                </div>

                </div>
            </div>
        </div>
    @endif

</div>

<script>
    // ---------- Field-level validation ----------
    function toEnglishDigits(str) {
        const bn = '০১২৩৪৫৬৭৮৯';
        return String(str ?? '').replace(/[০-৯]/g, d => bn.indexOf(d)).trim();
    }

    function setFieldError(id, message) {
        const input = document.getElementById(id);
        const err = document.getElementById('err_' + id);
        if (input) input.classList.add('field-invalid');
        if (err) { err.textContent = message; err.classList.remove('hidden'); }
    }

    function clearFieldError(id) {
        const input = document.getElementById(id);
        const err = document.getElementById('err_' + id);
        if (input) input.classList.remove('field-invalid');
        if (err) { err.textContent = ''; err.classList.add('hidden'); }
    }

    const REQUIRED_FIELDS = ['applicant_name', 'mobile_number', 'email', 'service_id'];
    const FOCUS_ORDER = ['applicant_name', 'mobile_number', 'email', 'company_name', 'designation', 'service_id'];

    function clearAllErrors() {
        REQUIRED_FIELDS.forEach(clearFieldError);
    }

    function validateField(el) {
        const id = el.id;
        const value = el.value;

        switch (id) {
            case 'applicant_name':
                value.trim().length >= 3
                    ? clearFieldError(id)
                    : setFieldError(id, 'অনুগ্রহ করে আবেদনকারীর পূর্ণ নাম লিখুন (কমপক্ষে ৩ অক্ষর)।');
                break;

            case 'mobile_number': {
                const digits = toEnglishDigits(value);
                /^01[3-9]\d{8}$/.test(digits)
                    ? clearFieldError(id)
                    : setFieldError(id, 'সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (উদাহরণ: 01712345678)।');
                break;
            }

            case 'email':
                (value.trim() === '' || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim()))
                    ? clearFieldError(id)
                    : setFieldError(id, 'সঠিক ইমেইল ঠিকানা লিখুন।');
                break;

            case 'service_id':
                value ? clearFieldError(id) : setFieldError(id, 'সার্ভিস নাম নির্বাচন করুন।');
                break;
        }
    }

    function validateForm() {
        let firstInvalid = null;

        REQUIRED_FIELDS.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            validateField(el);
            if (el.classList.contains('field-invalid') && !firstInvalid) {
                firstInvalid = el;
            }
        });

        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
            return false;
        }
        return true;
    }


    // ---------- Keyboard shortcuts for fast counter entry ----------
    document.addEventListener('DOMContentLoaded', () => {
        // live-validate as the user types/selects, so errors clear as soon as they're fixed
        REQUIRED_FIELDS.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener(el.tagName === 'SELECT' ? 'change' : 'input', () => validateField(el));
        });

        // Enter moves to the next field instead of doing nothing / submitting.
        // service_id is skipped here — Select2 handles its own Enter behaviour once open.
        FOCUS_ORDER.forEach((id, index) => {
            const el = document.getElementById(id);
            if (!el || id === 'service_id') return;
            el.addEventListener('keydown', (e) => {
                if (e.key !== 'Enter' || e.shiftKey) return;
                e.preventDefault();
                const nextId = FOCUS_ORDER[index + 1];
                if (nextId === 'service_id') {
                    $('#service_id').select2('open');
                } else if (nextId) {
                    document.getElementById(nextId)?.focus();
                } else {
                    openReviewModal();
                }
            });
        });

        $('#service_id').select2({
            placeholder: 'সার্ভিসের নাম নির্বাচন করুন',
            allowClear: true,
            width: '100%',
            fontSize: 10,
            minimumInputLength: 0,
            ajax: {
                url: "{{ route('services.serviceTypes.search') }}",
                dataType: 'json',
                delay: 250,
                data: params => ({ q: params.term || '', page: params.page || 1 }),
                processResults: (data, params) => {
                    params.page = params.page || 1;
                    return { results: data.results, pagination: { more: data.pagination.more } };
                },
                cache: true,
            },
        }).on('select2:select', () => {
            validateField(document.getElementById('service_id'));
            openReviewModal(); // last field on the form — jump straight to review once chosen
        }).on('change', function () {
            const val = $(this).val();
            validateField(document.getElementById('service_id'));
            renderChecklist(val);
        });
    });

    // Global shortcuts: Ctrl/Cmd+Enter to review & save from anywhere, Esc to close modal, Alt+R to reset
    document.addEventListener('keydown', (e) => {
        const modalOpen = !document.getElementById('review-modal').classList.contains('hidden');

        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            modalOpen ? confirmReviewSubmit() : openReviewModal();
            return;
        }

        if (e.key === 'Escape' && modalOpen) {
            closeReviewModal();
            return;
        }

        if (e.altKey && e.key.toLowerCase() === 'r' && !modalOpen) {
            e.preventDefault();
            document.getElementById('applicant-form').reset();
            clearAllErrors();
            document.getElementById('applicant_name')?.focus();
        }
    });

    // ---------- Review modal ----------
    const reviewFields = {
        'সার্ভিস নাম':       'service_id',
        'আবেদনকারীর নাম':   'applicant_name',
        'মোবাইল নম্বর':     'mobile_number',
        'ইমেইল ঠিকানা':     'email',
        'প্রতিষ্ঠানের নাম': 'company_name',
        'পদবি':             'designation',
    };

    function openReviewModal() {
        if (!validateForm()) return;

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

    // ---------- Receipt download (native browser print-to-PDF, keeps Bengali text intact) ----------
    function downloadReceipt() {
        const receipt = document.getElementById('receipt-print');
        if (!receipt) return;
        receipt.classList.remove('hidden');
        window.print();
    }

    window.addEventListener('afterprint', () => {
        const receipt = document.getElementById('receipt-print');
        if (receipt) receipt.classList.add('hidden');
    });


    // ---------- Service checklist ----------
    // NOTE: hardcoded here for now. Ideally this comes from the backend —
    // e.g. embed as JSON via json ( serviceChecklists ) from a config/DB table keyed
    // by service_id, or fetch alongside the select2 ajax call — so non-devs can edit
    // checklist items without a code deploy.
    const SERVICE_CHECKLISTS = {
        '1': [
            'আবেদন পত্র',
            'প্রকল্প অনুমোদনের চিঠি',
            'প্রকল্পের বর্তমান অবস্থার সচিত্র ব্যাখা',
        ],
        '8': [
            'যথাযথভাবে পূরণকৃত ফরম নং-১২ এবং ফরম নং-১৫।',
            'নতুন লাইসেন্স ফিঃ',
            'প্রকল্পে প্রস্তাবিত পদসমূহের রেসিপি।',
            'হালনাগাদ ট্রেড লাইসেন্স-এর কপি।',
            'স্থাপিতযন্ত্রপাতির তালিকা (উৎপাদন ও মান-নিয়ন্ত্রণ বিভাগের পৃথক তালিকা)।',
            'কোয়ালিফাইড বাক্তিদের তালিকা (তালিকায় পদবী, শিক্ষাগত যোগ্যতা, পিতার নাম, সহায়ী ও বর্তমান ঠিকানা উল্লেখ থাকতে হবে)।',
            'কোয়ালিফাইড ব্যক্তিদের নিয়োগপত্র, যোগদানপত্র, সনদ পত্রের কপি ও অঙ্গীকারনামা।',
            'মালিক/পরিচালকের তালিকা (তালিকায় পিতার নাম, সহায়ী ও বর্তমান ঠিকানা উল্লেখ থাকতে হবে)।',
            'কারখানার লে-আউট প্ল‍্যান।',
            'উৎপাদন ও মান-নিয়ন্ত্রণের জন্য প্রয়োজনীয় রেফারেন্স বইয়ের তালিকা।',
            'প্রকল্প অনুমোদনের স্মারকের কপি।',
            'প্রতিষ্ঠানের অর্গানোগ্রাম।',
            'সাইট মাস্টার ফাইল।',
            'পরিবেশ অধিদপ্তর হতে পরিবেশগত সনদ।',
        ],
        '9': [
            'প্রকল্পের নাম',
            'প্রকল্পের ঠিকানা',
            'উদ্যোক্তার শিক্ষাগত যোগ্যতা এবং প্রকল্পের অর্গানোগ্রাম',
            'কোম্পানীর ধরণ (প্রাইভেট, পাবলিক লিঃ/ব্যক্তি মালিকানা)। (লিমিটেড কোম্পানীর ক্ষেত্রে জয়েন্ট স্টক কোম্পানীর সার্টিফিকেট অব ইনকর্পোরেশন দাখিল করতে হবে',
            'প্রকল্পের সর্বমোট বিনিয়োগ',
            'অর্থের উৎস',
            'ইকুইটি',
            'প্রস্তাবিত মেশিনারীজের মাধ্যমে বার্ষিক উৎপাদন ক্ষমতা',
            'প্রকল্পের বর্তমান অবস্থা',
            'অবকাঠামোগত সুবিধাদি',
            'অন্যান্য সুবিধাদি',
            'উৎপাদন মেশিনারীজের তালিকা',
            'মান-নিয়ন্ত্রণের যন্ত্রপাতির তালিকা',
            'প্রোডাকশন প্রোগ্রাম',
            'প্রয়োজনীয় কাঁচামাল ও মোড়কসামগ্রীর বিবরণ',
            'কারিগরী দক্ষতাসম্পন্ন জনবলের তালিকা',
            'প্রাক্কলিত বিক্রয়',
            'সম্ভাব্য আয়',
            'ব্রেক ইভেন এনালাইসিস',
            'যে সকল পদ উৎপাদন করা হবে তার যৌক্তিকতা',
            'প্রকল্পের পূর্ণাঙ্গ লে-আউট প্ল্যান',
            'উদ্যোক্তার জাতীয়তা সনদ',
            'উদ্যোক্তার ব্যাংক স্বচ্ছলতা সনদ',
            'ট্রেড লাইসেন্স',
            'পরিবেশ অধিদপ্তর হতে অনাপত্তি সনদ',
            'বিসিআইসি/বোর্ড অব ইনভেস্টমেন্ট হতে প্রজেক্ট নিবন্ধন সনদ',
            'পুঁজি বিনিয়োগকারী/উদ্যোক্তার টিআইএন সনদ',
        ],
    };

    function renderChecklist(serviceId) {
        const container = document.getElementById('checklist-items');
        const empty = document.getElementById('checklist-empty');
        const progress = document.getElementById('checklist-progress');
        const warning = document.getElementById('checklist-warning');
        container.innerHTML = '';

        const items = SERVICE_CHECKLISTS[serviceId] || [];

        if (items.length === 0) {
            empty.classList.remove('hidden');
            progress.classList.add('hidden');
            warning.classList.add('hidden');
            updateSubmitButtonState();
            return;
        }

        empty.classList.add('hidden');
        progress.classList.remove('hidden');

        items.forEach((label, idx) => {
            const id = `chk_item_${idx}`;
            container.insertAdjacentHTML('beforeend', `
                <label for="${id}" class="flex items-start gap-2.5 text-xs text-slate-600 cursor-pointer">
                    <input type="checkbox" id="${id}"
                        class="checklist-check mt-0.5 w-3.5 h-3.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/40 shrink-0">
                    <span class="checklist-label">${escapeHtml(label)}</span>
                </label>
            `);
        });

        container.querySelectorAll('.checklist-check').forEach(cb => {
            cb.addEventListener('change', () => {
                const span = cb.nextElementSibling;
                cb.checked
                    ? span.classList.add('text-slate-400', 'line-through')
                    : span.classList.remove('text-slate-400', 'line-through');
                updateSubmitButtonState();
            });
        });

        updateSubmitButtonState();
    }

    function updateSubmitButtonState() {
        const saveBtn = document.getElementById('save-btn');
        const progress = document.getElementById('checklist-progress');
        const warning = document.getElementById('checklist-warning');
        const checks = document.querySelectorAll('.checklist-check');

        const total = checks.length;
        const checked = document.querySelectorAll('.checklist-check:checked').length;

        if (!progress.classList.contains('hidden')) {
            progress.textContent = `${checked} / ${total} সম্পন্ন হয়েছে`;
        }

        const allChecked = total > 0 && checked === total;
        saveBtn.disabled = !allChecked;
        warning.classList.toggle('hidden', allChecked || total === 0);
    }

</script>
@endsection
