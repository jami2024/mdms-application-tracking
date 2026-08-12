<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>নতুন পাসওয়ার্ড · এমডিএমএস</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <script>tailwind.config = { theme: { extend: {
        fontFamily: { sans: ['Kalpurush', 'ui-sans-serif'] },
        colors: { ink: { 900: '#0b1220', 800: '#111c30' }, brand: { 400: '#34d399', 500: '#10b981', 600: '#059669' } },
    } } }</script>
    <style>
        html { font-size: 18px; }
        body { font-family: 'Kalpurush', ui-sans-serif; }
        .anim-in { animation: fadeUp .5s ease both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="min-h-screen bg-ink-900 flex items-center justify-center p-4 relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 26px 26px;"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-brand-600/20 rounded-full blur-3xl"></div>

    <div class="w-full max-w-sm relative anim-in">
        <div class="text-center mb-6">
            <div class="h-16 w-16 rounded-full bg-white flex items-center justify-center mx-auto mb-4 shadow-2xl">
                <svg class="w-8 h-8 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="10" width="16" height="10" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 10V7a4 4 0 118 0v3"/><path stroke-linecap="round" d="M12 14v3"/></svg>
            </div>
            <h1 class="text-xl font-semibold text-white">নতুন পাসওয়ার্ড সেট করুন</h1>
            <p class="text-sm text-slate-400 mt-1">একটি শক্তিশালী পাসওয়ার্ড বেছে নিন।</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-7">
            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-red-50 text-red-600 text-sm px-4 py-3">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <input type="hidden" name="email" value="{{ $request->email }}">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">নতুন পাসওয়ার্ড</label>
                    <div class="relative">
                        <svg class="w-[18px] h-[18px] text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="10" width="16" height="10" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 10V7a4 4 0 118 0v3"/></svg>
                        <input type="password" name="password" required
                               class="w-full rounded-xl border border-slate-300 pl-11 pr-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">পাসওয়ার্ড নিশ্চিত করুন</label>
                    <div class="relative">
                        <svg class="w-[18px] h-[18px] text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <input type="password" name="password_confirmation" required
                               class="w-full rounded-xl border border-slate-300 pl-11 pr-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition">
                    </div>
                </div>
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-ink-900 hover:bg-ink-800 text-white text-sm font-medium py-3.5 rounded-full shadow-lg transition hover:-translate-y-0.5">
                    পাসওয়ার্ড রিসেট করুন
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                </button>
            </form>
        </div>

        <div class="mt-6 text-center">
            <a href="https://mysoftheaven.com/" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-white/10 hover:border-white/20 transition">
                <span class="text-[11px] text-slate-400">কারিগরি সহযোগিতায়</span>
                <img src="https://mysoftheaven.com/fwedget/img/mysoft-logo.png" alt="Mysoftheaven (BD) Ltd." class="h-4 bg-white rounded px-1 py-0.5">
            </a>
        </div>
    </div>
</body>
</html>
