<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>সার্টিফিকেট যাচাই · এমডিএমএস</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: { sans: ['Kalpurush', 'ui-sans-serif'] },
            colors: { ink: { 900: '#0b1220' }, brand: { 400: '#34d399', 500: '#10b981', 600: '#059669' } },
        } } }
    </script>
    <style>
        html { font-size: 18px; }
        body { font-family: 'Kalpurush', ui-sans-serif; }
        .anim-in { animation: fadeUp .5s ease both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="min-h-screen bg-ink-900 flex items-center justify-center p-4 relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 26px 26px;"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-600/20 rounded-full blur-3xl"></div>

    <div class="w-full max-w-sm relative anim-in">
        <div class="text-center mb-6">
            <div class="h-16 w-16 rounded-full bg-white flex items-center justify-center mx-auto mb-4 shadow-2xl p-2">
                <img src="{{ asset('images/gov-emblem.svg') }}" alt="বাংলাদেশ সরকার" class="w-full h-full object-contain"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="hidden w-full h-full items-center justify-center bg-brand-500 rounded-none">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="5"/><path stroke-linecap="round" stroke-linejoin="round" d="M8.5 13L7 22l5-3 5 3-1.5-9"/></svg>
                </div>
            </div>
            <h1 class="text-lg font-semibold text-white">সার্টিফিকেট যাচাইকরণ</h1>
            <p class="text-sm text-slate-400 font-mono mt-1">{{ $certificateNo }}</p>
        </div>

        <div class="bg-white rounded-none shadow-2xl p-7">
            @if(! $certificate)
                <div class="text-center py-4">
                    <div class="h-14 w-14 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <p class="font-semibold text-red-600">পাওয়া যায়নি</p>
                    <p class="text-sm text-slate-500 mt-1">এই নম্বরে কোনো সার্টিফিকেট পাওয়া যায়নি। এটি অবৈধ বা জাল হতে পারে।</p>
                </div>
            @elseif($certificate->status === 'revoked')
                <div class="text-center py-4">
                    <div class="h-14 w-14 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <p class="font-semibold text-red-600">বাতিলকৃত</p>
                    <p class="text-sm text-slate-500 mt-1">এই সার্টিফিকেটটি বাতিল করা হয়েছে এবং এটি আর বৈধ নয়।</p>
                </div>
            @elseif($certificate->expiry_date && $certificate->expiry_date->isPast())
                <div class="text-center py-4">
                    <div class="h-14 w-14 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 8v5M12 16h.01"/></svg>
                    </div>
                    <p class="font-semibold text-amber-600">মেয়াদোত্তীর্ণ</p>
                    <p class="text-sm text-slate-500 mt-1">{{ $certificate->expiry_date->format('d M, Y') }} তারিখে মেয়াদোত্তীর্ণ হয়েছে।</p>
                </div>
            @else
                <div class="text-center py-2">
                    <div class="h-14 w-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="font-semibold text-emerald-600">বৈধ সার্টিফিকেট</p>
                </div>
                <dl class="space-y-2.5 text-sm mt-5 border-t border-slate-100 pt-4">
                    <div class="flex justify-between"><dt class="text-slate-500">আবেদনকারী</dt><dd class="font-medium text-slate-800">{{ $certificate->application->applicant->name }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">মডিউল</dt><dd class="font-medium text-slate-800">{{ \App\Support\Bengali::label($certificate->template->module) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">ইস্যুর তারিখ</dt><dd class="font-medium text-slate-800">{{ $certificate->issue_date->format('d M, Y') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">মেয়াদ শেষ</dt><dd class="font-medium text-slate-800">{{ $certificate->expiry_date?->format('d M, Y') ?? 'আজীবন' }}</dd></div>
                </dl>
            @endif
        </div>
        <p class="text-center text-xs text-slate-500 mt-5">{{ config('app.app_name_bn') }}</p>
    </div>
</body>
</html>
