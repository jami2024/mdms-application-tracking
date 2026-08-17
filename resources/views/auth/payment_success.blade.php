
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>পেমেন্ট সফল · DGDA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: { sans: ['Kalpurush', 'ui-sans-serif'] },
            colors: { ink: { 900: '#0b1220', 800: '#111c30' }, brand: { 400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857' } },
        } } }
    </script>
    <style>
        html { font-size: 18px; }
        body { font-family: 'Kalpurush', ui-sans-serif; }
        .anim-in { animation: fadeUp .5s ease both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes checkPop { 0% { transform: scale(0); } 70% { transform: scale(1.15); } 100% { transform: scale(1); } }
        .check-pop { animation: checkPop .5s cubic-bezier(.34,1.56,.64,1) both .2s; }

        /* ---- Print / PDF receipt ---- */
        @media print {
            body * { visibility: hidden; }
            #receipt-print, #receipt-print * { visibility: visible; }
            #receipt-print { position: fixed; inset: 0; padding: 12px; }
            @page { margin: 10mm; }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 flex items-stretch">

    @php
        // $payment expected from controller — see notes below the code
        $trxId       = $payment['trx_id'] ?? '—';
        $trxDate     = $payment['paid_at'] ?? now()->format('d-m-Y h:i A');
        $payerName   = $payment['payer_name'] ?? '—';
        $trackingNo  = $payment['tracking_no'] ?? '—';
        $serviceName = $payment['service_name'] ?? '—';
        $amount      = $payment['amount'] ?? '0.00';
        $method      = $payment['method'] ?? '—';
        $refNo       = $payment['gateway_ref'] ?? '—';
    @endphp

    <div id="page-content" class="w-full grid grid-cols-1 lg:grid-cols-2">

        {{-- Left success panel --}}
        <div class="hidden lg:flex relative bg-ink-900 flex-col items-center justify-center px-12 overflow-hidden">
            <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 26px 26px;"></div>
            <div class="absolute -top-32 -left-24 w-96 h-96 bg-brand-600/25 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -right-24 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 text-center max-w-sm anim-in">
                <div class="h-24 w-24 rounded-full bg-brand-500 flex items-center justify-center mx-auto mb-6 shadow-2xl ring-4 ring-white/10 check-pop">
                    <svg class="w-11 h-11 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-[14px] tracking-[0.25em] text-brand-400 font-semibold uppercase mb-2">পেমেন্ট সম্পন্ন হয়েছে</p>
                <h1 class="text-2xl font-semibold text-white leading-snug">{{ config('app.app_manage_name') }}</h1>
                <p class="text-slate-400 text-sm mt-1">{{ config('app.app_name_bn') }}</p>

                <p class="text-slate-300 text-sm mt-6 leading-relaxed">
                    আপনার পেমেন্ট সফলভাবে সম্পন্ন হয়েছে। আবেদনটি এখন সংশ্লিষ্ট ডেস্কে যাচাইয়ের জন্য প্রেরণ করা হয়েছে।
                    ভবিষ্যতে ট্র্যাকিংয়ের জন্য রশিদটি সংরক্ষণ করুন।
                </p>

                <div class="mt-8 inline-flex items-center gap-2 bg-white/5 border border-white/10 rounded-full px-4 py-2">
                    <span class="text-[11px] text-slate-400">ট্র্যাকিং নম্বর</span>
                    <span class="text-sm font-semibold text-brand-400">{{ $trackingNo }}</span>
                </div>
            </div>
        </div>

        {{-- Right details panel --}}
        <div class="flex flex-col items-center justify-center px-6 py-10 bg-white relative">
            <div class="w-full max-w-sm anim-in">

                {{-- Mobile-only header --}}
                <div class="lg:hidden text-center mb-6">
                    <div class="h-16 w-16 rounded-full bg-brand-500 flex items-center justify-center mx-auto mb-3 shadow-sm check-pop">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h1 class="text-lg font-semibold text-slate-800">পেমেন্ট সফল হয়েছে</h1>
                    <p class="text-xs text-slate-500">{{ config('app.app_name_bn') }}</p>
                </div>

                <div class="mb-6 hidden lg:block">
                    <h2 class="text-2xl font-semibold text-slate-900">পেমেন্ট সফল হয়েছে</h2>
                    <p class="text-sm text-slate-500 mt-1">লেনদেনের বিস্তারিত নিচে দেওয়া হলো</p>
                </div>

                {{-- Transaction summary card --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 overflow-hidden mb-6">
                    <div class="px-4 py-3 border-b border-slate-200 bg-white flex items-center justify-between">
                        <span class="text-xs text-slate-500">পরিমাণ পরিশোধিত</span>
                        <span class="text-lg font-bold text-slate-900">৳ {{ $amount }}</span>
                    </div>
                    <dl class="divide-y divide-slate-200 text-xs">
                        <div class="flex items-center justify-between px-4 py-2.5">
                            <dt class="text-slate-500">লেনদেন আইডি</dt>
                            <dd class="font-semibold text-slate-800">{{ $trxId }}</dd>
                        </div>
                        <div class="flex items-center justify-between px-4 py-2.5">
                            <dt class="text-slate-500">তারিখ ও সময়</dt>
                            <dd class="font-semibold text-slate-800">{{ $trxDate }}</dd>
                        </div>
                        <div class="flex items-center justify-between px-4 py-2.5">
                            <dt class="text-slate-500">পরিশোধের মাধ্যম</dt>
                            <dd class="font-semibold text-slate-800">{{ $method }}</dd>
                        </div>
                        <div class="flex items-center justify-between px-4 py-2.5">
                            <dt class="text-slate-500">সার্ভিসের নাম</dt>
                            <dd class="font-semibold text-slate-800 text-right">{{ $serviceName }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="space-y-2.5">
                    <button type="button" onclick="downloadReceipt()"
                            class="w-full flex items-center justify-center gap-2 bg-ink-900 hover:bg-ink-800 text-white text-sm font-medium py-3.5 rounded-full transition shadow-lg shadow-ink-900/20 hover:shadow-xl hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H8a2 2 0 01-2-2V5a2 2 0 012-2h6l6 6v11a2 2 0 01-2 2z"/></svg>
                        রশিদ ডাউনলোড করুন (PDF)
                    </button>
                    <a href="{{ route('applications.show', encrypt($payment['application_id'] ?? '')) }}"
                       class="w-full flex items-center justify-center gap-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium py-3.5 rounded-full transition hover:bg-slate-50">
                        আবেদনের অবস্থা দেখুন
                    </a>
                </div>

                <div class="mt-8 text-center space-y-3">
                    <p class="text-xs text-slate-400">সংস্করণ ১.০ · {{ config('app.app_manage_name') }}</p>
                    <a href="https://mysoftheaven.com/" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition">
                        <span class="text-[13px] text-slate-500">কারিগরি সহযোগিতায়</span>
                        <img src="https://mysoftheaven.com/fwedget/img/mysoft-logo.png" alt="Mysoftheaven (BD) Ltd." class="h-8">
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Printable receipt — hidden on screen, shown only during window.print() --}}
    <div id="receipt-print" class="hidden bg-white">
        <div class="relative max-w-2xl mx-auto border border-slate-300 rounded-lg p-6 overflow-hidden" style="break-inside: avoid;">

            <img src="https://dgda.gov.bd/site-assets/images/logo.png" alt=""
                 aria-hidden="true"
                 class="pointer-events-none select-none absolute inset-0 m-auto w-56 h-56 object-contain opacity-[0.06] z-0"
                 style="-webkit-print-color-adjust: exact; print-color-adjust: exact; color-adjust: exact;"
                 onerror="this.remove()">

            <div class="relative z-10">

                <div class="flex flex-col items-center text-center gap-1.5 border-b border-slate-300 pb-3 mb-4">
                    <img src="https://dgda.gov.bd/site-assets/images/logo.png" alt="বাংলাদেশ সরকার"
                         class="w-12 h-12 object-contain"
                         onerror="this.style.display='none'">
                    <h2 class="text-lg font-bold text-slate-800">{{ config('app.app_manage_name') }}</h2>
                    <p class="text-lg text-slate-800 mt-1">পেমেন্ট রশিদ</p>
                </div>

                <div class="flex justify-between text-xs mb-4">
                    <span><strong>লেনদেন আইডি:</strong> {{ $trxId }}</span>
                    <span><strong>তারিখ ও সময়:</strong> {{ $trxDate }}</span>
                </div>

                <table class="w-full text-xs border-collapse">
                    <tbody>
                        @php
                            $rows = [
                                'আবেদনকারীর নাম'  => $payerName,
                                'ট্র্যাকিং নম্বর'    => $trackingNo,
                                'সার্ভিস নাম'        => $serviceName,
                                'পরিশোধের মাধ্যম'   => $method,
                                'গেটওয়ে রেফারেন্স' => $refNo,
                                'পরিমাণ পরিশোধিত'   => '৳ ' . $amount,
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
                    এটি একটি স্বয়ংক্রিয়ভাবে তৈরি রশিদ। ভবিষ্যতে যোগাযোগের জন্য লেনদেন আইডি ও ট্র্যাকিং নম্বরটি সংরক্ষণ করুন।
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
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
    </script>
</body>
</html>
