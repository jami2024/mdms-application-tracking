<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>পাসওয়ার্ড রিসেট · এমডিএমএস</title>
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
    </style>
</head>
<body class="min-h-screen bg-slate-100 flex items-stretch">

    <div class="w-full grid grid-cols-1 lg:grid-cols-2">

        {{-- Left brand panel --}}
        <div class="hidden lg:flex relative bg-ink-900 flex-col items-center justify-center px-12 overflow-hidden">
            <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 26px 26px;"></div>
            <div class="absolute -top-32 -left-24 w-96 h-96 bg-brand-600/25 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -right-24 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 text-center max-w-sm anim-in">
                <div class="h-24 w-24 rounded-full bg-white flex items-center justify-center mx-auto mb-6 shadow-2xl p-3 ring-4 ring-white/10">
                    <img src="https://dgda.gov.bd/site-assets/images/logo.png" alt="বাংলাদেশ সরকার" class="w-full h-full object-contain"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="hidden w-full h-full items-center justify-center bg-brand-500 rounded-md">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="10" width="16" height="10" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 10V7a4 4 0 118 0v3"/></svg>
                    </div>
                </div>
                <p class="text-[14px] tracking-[0.25em] text-brand-400 font-semibold uppercase mb-2">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</p>
                <h1 class="text-2xl font-semibold text-white leading-snug">ঔষধ প্রশাসন অধিদপ্তর</h1>
                <p class="text-slate-400 text-sm mt-1">ট্র্যাকিং ম্যনেজমেন্ট সিস্টেম</p>

                <div class="mt-10 bg-white/5 border border-white/10 rounded-md p-5 text-left">
                    <svg class="w-6 h-6 text-brand-400 mb-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <p class="text-sm text-slate-200 leading-relaxed">
                        আপনার অ্যাকাউন্টের ইমেইলে আমরা একটি নিরাপদ রিসেট লিংক পাঠাবো। লিংকটি সীমিত সময়ের জন্য সক্রিয় থাকবে।
                    </p>
                </div>
            </div>
        </div>

        {{-- Right form panel --}}
        <div class="flex flex-col items-center justify-center px-6 py-10 bg-white relative">
            <div class="w-full max-w-sm anim-in">

                {{-- Mobile-only header --}}
                <div class="lg:hidden text-center mb-8">
                    <div class="h-16 w-16 rounded-full bg-white border border-slate-200 flex items-center justify-center mx-auto mb-3 shadow-sm p-2">
                        <img src="{{ asset('images/gov-emblem.svg') }}" alt="বাংলাদেশ সরকার" class="w-full h-full object-contain"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="hidden w-full h-full items-center justify-center bg-brand-500 rounded-md">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="10" width="16" height="10" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 10V7a4 4 0 118 0v3"/></svg>
                        </div>
                    </div>
                    <h1 class="text-lg font-semibold text-slate-800">ঔষধ প্রশাসন অধিদপ্তর</h1>
                    <p class="text-xs text-slate-500">ট্র্যাকিং ম্যনেজমেন্ট সিস্টেম</p>
                </div>

                <div class="mb-7">
                    <h2 class="text-2xl font-semibold text-slate-900">পাসওয়ার্ড ভুলে গেছেন?</h2>
                    <p class="text-sm text-slate-500 mt-1">চিন্তা নেই — আপনার ইমেইলে রিসেট লিংক পাঠিয়ে দিচ্ছি।</p>
                </div>

                @if (session('status'))
                    <div class="mb-5 rounded-md bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5 border border-emerald-100 flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-5 rounded-md bg-red-50 text-red-600 text-sm px-5 py-3.5 border border-red-100 flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 8v5M12 16h.01"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">ইমেইল</label>
                        <div class="relative">
                            <svg class="w-[18px] h-[18px] text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4V6zm0 0l8 7 8-7"/></svg>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com"
                                   class="w-full rounded-md border border-slate-300 pl-11 pr-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition">
                        </div>
                    </div>
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-ink-900 hover:bg-ink-800 text-white text-sm font-medium py-3.5 rounded-md transition shadow-lg shadow-ink-900/20 hover:shadow-xl hover:-translate-y-0.5">
                        রিসেট লিংক পাঠান
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                    </button>
                </form>

                <a href="{{ route('login') }}" class="mt-5 flex items-center justify-center gap-1.5 text-sm text-brand-600 hover:text-brand-700 hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5 5-5M18 12H6"/></svg>
                    লগইনে ফিরে যান
                </a>

                <div class="mt-10 text-center space-y-3">

                    <a href="https://mysoftheaven.com/" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition">
                        <span class="text-[13px] text-slate-500">কারিগরি সহযোগিতায়</span>
                        <img src="https://mysoftheaven.com/fwedget/img/mysoft-logo.png" alt="Mysoftheaven (BD) Ltd." class="h-8">
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
