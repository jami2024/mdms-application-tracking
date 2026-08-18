<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>লগইন · এমডিএমএস</title>
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
                    <div class="hidden w-full h-full items-center justify-center bg-brand-500 rounded-full">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l7 3v6c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V5l7-3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
                    </div>
                </div>
                <p class="text-[14px] tracking-[0.25em] text-brand-400 font-semibold uppercase mb-2">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</p>
                <h1 class="text-2xl font-semibold text-white leading-snug">{{ config('app.app_manage_name') }}</h1>
                <p class="text-slate-400 text-sm mt-1">{{ config('app.app_name_bn') }}</p>

                <div class="mt-10 grid grid-cols-3 gap-4 text-left">
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                        <svg class="w-5 h-5 text-brand-400 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                        <p class="text-[11px] text-slate-300 leading-snug">ওয়ার্কফ্লো ব্যবস্থাপনা</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                        <svg class="w-5 h-5 text-brand-400 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        <p class="text-[11px] text-slate-300 leading-snug">সার্ভিস আবেদন</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                        <svg class="w-5 h-5 text-brand-400 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17V9m6 8V5M4 21h16M4 21V10m16 11V7"/></svg>
                        <p class="text-[11px] text-slate-300 leading-snug">রিয়েল-টাইম ট্র্যাকিং</p>
                    </div>
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
                        <div class="hidden w-full h-full items-center justify-center bg-brand-500 rounded-full">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l7 3v6c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V5l7-3z"/></svg>
                        </div>
                    </div>
                    <h1 class="text-lg font-semibold text-slate-800">{{ config('app.app_manage_name') }}</h1>
                    <p class="text-xs text-slate-500">{{ config('app.app_name_bn') }}</p>
                </div>

                <div class="mb-7 hidden lg:block">
                    <h2 class="text-2xl font-semibold text-slate-900">স্বাগতম</h2>
                    <p class="text-sm text-slate-500 mt-1">পোর্টালে প্রবেশ করতে আপনার তথ্য দিন</p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 rounded-xl bg-red-50 text-red-600 text-sm px-4 py-3 border border-red-100 flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 8v5M12 16h.01"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">ইমেইল</label>
                        <div class="relative">
                            <svg class="w-[18px] h-[18px] text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4V6zm0 0l8 7 8-7"/></svg>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com"
                                   class="w-full rounded-xl border border-slate-300 pl-11 pr-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition">
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-medium text-slate-700">পাসওয়ার্ড</label>
                            <a href="{{ route('password.request') }}" class="text-xs text-brand-600 hover:text-brand-700 hover:underline">ভুলে গেছেন?</a>
                        </div>
                        <div class="relative" x-data="{ show: false }">
                            <svg class="w-[18px] h-[18px] text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="10" width="16" height="10" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 10V7a4 4 0 118 0v3"/></svg>
                            <input :type="show ? 'text' : 'password'" name="password" required placeholder="••••••••"
                                   class="w-full rounded-xl border border-slate-300 pl-11 pr-11 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition">
                            <button type="button" @click="show = !show" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg x-show="!show" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg x-show="show" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.94 10.94 0 0112 19c-7 0-11-7-11-7a21.6 21.6 0 015.06-5.94M9.9 4.24A10.94 10.94 0 0112 4c7 0 11 7 11 7a21.6 21.6 0 01-2.34 3.16M14.12 14.12a3 3 0 11-4.24-4.24M1 1l22 22"/></svg>
                            </button>
                        </div>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        মনে রাখুন
                    </label>
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-ink-900 hover:bg-ink-800 text-white text-sm font-medium py-3.5 rounded-full transition shadow-lg shadow-ink-900/20 hover:shadow-xl hover:-translate-y-0.5">
                        প্রবেশ করুন
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                    </button>
                </form>

                <div class="mt-10 text-center space-y-3">
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

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
