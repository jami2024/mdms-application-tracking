<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>দুই-স্তর যাচাইকরণ · এমডিএমএস</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: { sans: ['Kalpurush', 'ui-sans-serif'] },
            colors: { ink: { 900: '#0b1220', 800: '#111c30' }, brand: { 400: '#34d399', 500: '#10b981', 600: '#059669' } },
        } } }
    </script>
    <style>
        html { font-size: 18px; }
        body { font-family: 'Kalpurush', ui-sans-serif; }
        .anim-in { animation: fadeUp .5s ease both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="min-h-screen bg-ink-900 flex items-center justify-center p-4 relative overflow-hidden" x-data="{ recovery: false }">
    <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 26px 26px;"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-600/20 rounded-full blur-3xl"></div>

    <div class="w-full max-w-sm relative anim-in">
        <div class="text-center mb-6">
            <div class="h-16 w-16 rounded-full bg-white flex items-center justify-center mx-auto mb-4 shadow-2xl">
                <svg class="w-8 h-8 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
            </div>
            <h1 class="text-xl font-semibold text-white">দুই-স্তর যাচাইকরণ</h1>
            <p class="text-sm text-slate-400 mt-1">
                <span x-show="!recovery">আপনার অথেনটিকেটর অ্যাপ থেকে ৬-সংখ্যার কোডটি লিখুন।</span>
                <span x-show="recovery" x-cloak>আপনার একটি রিকভারি কোড লিখুন।</span>
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-7">
            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-red-50 text-red-600 text-sm px-4 py-3">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4">
                @csrf
                <div x-show="!recovery">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">যাচাইকরণ কোড</label>
                    <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" placeholder="৬ ডিজিট কোড"
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-3 text-lg tracking-[0.4em] text-center focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition">
                </div>
                <div x-show="recovery" x-cloak>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">রিকভারি কোড</label>
                    <input type="text" name="recovery_code"
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition">
                </div>
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-ink-900 hover:bg-ink-800 text-white text-sm font-medium py-3.5 rounded-full shadow-lg transition hover:-translate-y-0.5">
                    যাচাই করুন
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>
            </form>

            <button @click="recovery = !recovery" class="mt-4 text-xs text-brand-600 hover:underline w-full text-center">
                <span x-show="!recovery">রিকভারি কোড ব্যবহার করুন</span>
                <span x-show="recovery" x-cloak>যাচাইকরণ কোড ব্যবহার করুন</span>
            </button>
        </div>

        <div class="mt-6 text-center">
            <a href="https://mysoftheaven.com/" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-white/10 hover:border-white/20 transition">
                <span class="text-[11px] text-slate-400">কারিগরি সহযোগিতায়</span>
                <img src="https://mysoftheaven.com/fwedget/img/mysoft-logo.png" alt="Mysoftheaven (BD) Ltd." class="h-4 bg-white rounded px-1 py-0.5">
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
