<!DOCTYPE html>
<html lang="bn" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ড্যাশবোর্ড') · {{ config('app.app_name_bn') }}</title>
    <link rel="canonical" href="http://dgda.gov.bd/"/>
    <link rel="icon" type="image/x-icon" href="/site-assets/images/favicon.ico"/>
    <link rel="shortcut icon" type="image/x-icon" href="/site-assets/images/favicon.ico"/>
    <link rel="shortcut icon" type="image/png" href="/site-assets/images/favicon.png"/>
    <link rel="shortcut icon" type="image/png" href="http://dgda.gov.bd/site-assets/images/favicon.png"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet"><link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Kalpurush', 'ui-sans-serif', 'system-ui'] },
                    colors: {
                        ink: { 800: '#111827', 900: '#0b1220' },
                        brand: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 700: '#047857' },
                    },
                },
            },
        }
    </script>
    <style>
        html { font-size: 18px; }
        body { font-family: 'Kalpurush', ui-sans-serif, system-ui; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        @media print {
            aside, header { display: none !important; }
            main { padding: 0 !important; }
            body { background: white !important; }
        }

        .field-label { @apply block text-sm font-medium text-ink/70 mb-1.5; }
        .field-input {
            @apply w-full rounded-lg border border-ink/15 bg-white px-4 py-2.5 text-sm
                   focus:outline-none focus:ring-2 focus:ring-forest focus:border-forest transition;
        }
        .field-hint { @apply mt-1 text-xs text-ink/40; }
        .section-title { @apply font-display text-base font-semibold text-ink; }
    </style>
</head>
<body class="bg-gradient-to-b from-emerald-50/50 to-white text-slate-800 antialiased">
    <div class="min-h-screen flex">

        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-40 w-62 bg-ink-900 text-slate-300 flex flex-col transform transition-transform duration-200 ease-in-out
                   lg:translate-x-0 lg:static lg:inset-auto"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="h-[72px] flex items-center gap-3 px-5 border-b border-white/10 shrink-0">
                <div class="h-11 w-11 rounded-full bg-white flex items-center justify-center shadow-lg shrink-0 p-1.5">
                    <img src="https://dgda.gov.bd/site-assets/images/logo.png" alt="বাংলাদেশ সরকার" class="w-full h-full object-contain"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="hidden w-full h-full items-center justify-center bg-brand-500 rounded-full">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l7 3v6c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V5l7-3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
                    </div>
                </div>
                <div class="leading-tight">
                    <p class="text-[14px] font-semibold text-white tracking-wide">{{ config('app.app_name_bn') }}</p>
                    <p class="text-[14px] font-semibold text-white tracking-wide">{{ config('app.app_manage_name') }}</p>
                    {{-- <p class="text-[16px] text-slate-400 tracking-wider uppercase">{{ config('app.app_manage_name') }}</p> --}}
                </div>
            </div>

            <nav class="flex-1 px-3 py-5 space-y-6 overflow-y-auto">
                @php
                    $navIcon = fn($d) => '<svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">'.$d.'</svg>';
                    $icons = [
                        'grid' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/>',
                        'inbox' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 12h4l2 3h4l2-3h4M4 12l1.5-6h13L20 12M4 12v6a1 1 0 001 1h14a1 1 0 001-1v-6"/>',
                        'building' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 21V5a1 1 0 011-1h6a1 1 0 011 1v16M4 21h16M12 21V9a1 1 0 011-1h6a1 1 0 011 1v12M8 8h.01M8 12h.01M8 16h.01"/>',
                        'doc' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 3h6l4 4v14H5V3h4zM14 3v4h4M9 12h6M9 16h6"/>',
                        'device' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 2v4M6 6h12a1 1 0 011 1v13a1 1 0 01-1 1H6a1 1 0 01-1-1V7a1 1 0 011-1zM9 13h6"/>',
                        'coins' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8a5 5 0 100 10 5 5 0 000-10zM3 8v10a5 5 0 005 5M3 8a5 5 0 015-5"/>',
                        'bag' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',
                        'wallet' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 12h.01M19 9V7a2 2 0 00-2-2H5"/>',
                        'award' => '<circle cx="12" cy="8" r="5"/><path stroke-linecap="round" stroke-linejoin="round" d="M8.5 13L7 22l5-3 5 3-1.5-9"/>',
                        'chart' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M8 17V10m5 7V5m5 12v-8"/>',
                        'users' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
                        'shield' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 2l7 3v6c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V5l7-3z"/>',
                        'sitemap' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v6M6 21v-4a2 2 0 012-2h8a2 2 0 012 2v4M6 21h4m-4 0H4m8 0h4m4 0h-4M12 9a3 3 0 100-6 3 3 0 000 6z"/>',
                        'workflow' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h4v4H4V6zm0 8h4v4H4v-4zm12-8h4v4h-4V6zm0 8h4v4h-4v-4zM8 8h8M8 16h8M12 10v4"/>',
                        'cert' => '<circle cx="12" cy="8" r="5"/><path stroke-linecap="round" stroke-linejoin="round" d="M8.5 13L7 22l5-3 5 3-1.5-9"/>',
                        'log' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>',
                        'map' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-6-3V4l6 3m0 13l6-3m-6 3V7m6 10l6 3V6l-6-3m0 17V4m0 3L9 4"/>',
                        'tag' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.59 13.41L11 3.83A2 2 0 009.59 3H4v5.59a2 2 0 00.59 1.41l9.58 9.58a2 2 0 002.82 0l3.6-3.6a2 2 0 000-2.82z"/><circle cx="7.5" cy="7.5" r="1"/>',
                    ];
                    $link = function($route, $label, $icon, $active) use ($navIcon, $icons) {
                        $cls = $active
                            ? 'bg-brand-600 text-white shadow-sm'
                            : 'text-slate-300 hover:bg-white/5 hover:text-white';
                        return '<a href="'.$route.'" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition '.$cls.'">'.$navIcon($icons[$icon]).'<span>'.$label.'</span></a>';
                    };
                @endphp

                <div>
                    <p class="px-3 mb-2 text-[16px] font-semibold uppercase tracking-widest text-slate-500">সারসংক্ষেপ</p>
                    {!! $link(route('dashboard'), 'ড্যাশবোর্ড', 'grid', request()->routeIs('dashboard')) !!}
                    {!! $link(route('applications.index'), 'আমার পর্যালোচনা তালিকা', 'inbox', request()->routeIs('applications.*')) !!}
                </div>

                <div>
                    <p class="px-3 mb-2 text-[16px] font-semibold uppercase tracking-widest text-slate-500">নিবন্ধন</p>
                    {{-- {!! $link(route('companies.index'), 'প্রতিষ্ঠান', 'building', request()->routeIs('companies.*')) !!} --}}
                    {{-- {!! $link('#', 'এস্টাবলিশমেন্ট', 'doc', false) !!} --}}
                    {{-- {!! $link('#', 'মেডিকেল ডিভাইস', 'device', false) !!} --}}
                    {{-- {!! $link(route('devices.packages.applications'), 'ডিভাইস প্যাকেজ আবেদন', 'bag', false, request()->routeIs('devices.packages.applications')) !!} --}}
                    {{-- {!! $link(route('devices.final-packages.applications'), 'ফাইনাল প্যাকেজ আবেদন', 'bag', false, request()->routeIs('devices.final-packages.applications')) !!} --}}
                    {{-- {!! $link('#', 'এমআরপি আবেদন', 'coins', false) !!} --}}
                    {!! $link(route('services.add-new'), 'সার্ভিস আবেদন', 'wallet', request()->routeIs('services.add-new')) !!}
                    {!! $link(route('services.application-track'), 'সার্ভিস আবেদন ট্র্যাক', 'wallet', request()->routeIs('services.application-track')) !!}
                </div>

                <div>
                    <p class="px-3 mb-2 text-[16px] font-semibold uppercase tracking-widest text-slate-500">অর্থ ও ডকুমেন্ট</p>
                    {{-- {!! $link(route('payments.index'), 'পেমেন্ট', 'wallet', request()->routeIs('payments.*')) !!} --}}
                    {{-- {!! $link('#', 'সার্টিফিকেট', 'award', false) !!} --}}
                    {!! $link(route('reports.applications'), 'আবেদন প্রতিবেদন', 'chart', request()->routeIs('reports.applications*')) !!}
                    {{-- {!! $link(route('reports.revenue'), 'রাজস্ব প্রতিবেদন', 'chart', request()->routeIs('reports.revenue*')) !!} --}}
                    {{-- {!! $link(route('reports.renewals'), 'নবায়ন প্রতিবেদন', 'chart', request()->routeIs('reports.renewals*')) !!} --}}
                </div>

                @role('Admin')
                <div>
                    <p class="px-3 mb-2 text-[16px] font-semibold uppercase tracking-widest text-slate-500">প্রশাসন</p>
                    {!! $link(route('admin.users.index'), 'ব্যবহারকারী', 'users', request()->routeIs('admin.users.*')) !!}
                    {!! $link(route('admin.roles.index'), 'ভূমিকা ও অনুমতি', 'shield', request()->routeIs('admin.roles.*')) !!}
                    {!! $link(route('admin.organogram.index'), 'অর্গানোগ্রাম', 'sitemap', request()->routeIs('admin.organogram.*')) !!}
                    {!! $link(route('admin.workflow-configs.index'), 'ওয়ার্কফ্লো কনফিগ', 'workflow', request()->routeIs('admin.workflow-configs.*')) !!}
                    {!! $link(route('admin.certificate-templates.index'), 'সার্টিফিকেট টেমপ্লেট', 'cert', request()->routeIs('admin.certificate-templates.*')) !!}
                    {!! $link(route('admin.activity-log.index'), 'অ্যাক্টিভিটি লগ', 'log', request()->routeIs('admin.activity-log.*')) !!}
                </div>

                <div>
                    <p class="px-3 mb-2 text-[16px] font-semibold uppercase tracking-widest text-slate-500">সাধারণ সেটিংস</p>
                    {!! $link(route('admin.designations.index'), 'পদবি', 'tag', request()->routeIs('admin.designations.*')) !!}
                    {!! $link(route('admin.divisions.index'), 'বিভাগ', 'map', request()->routeIs('admin.divisions.*')) !!}
                    {!! $link(route('admin.districts.index'), 'জেলা', 'map', request()->routeIs('admin.districts.*')) !!}
                    {!! $link(route('admin.upazilas.index'), 'উপজেলা', 'map', request()->routeIs('admin.upazilas.*')) !!}
                    {!! $link(route('admin.product-grades.index'), 'পণ্যের গ্রেড', 'tag', request()->routeIs('admin.product-grades.*')) !!}
                </div>
                @endrole
            </nav>

            <div class="px-1 py-2  border-white/10 shrink-0">

                <a href="https://mysoftheaven.com/" target="_blank" rel="noopener"
                   class="flex items-center gap-2 px-3 mt-4 pt-4 border-t border-white/10 group">
                    <span class="text-[16px] text-slate-500 leading-tight">কারিগরি সহযোগিতায়</span>
                    <img src="https://mysoftheaven.com/fwedget/img/mysoft-logo.png" alt="Mysoftheaven (BD) Ltd."
                         class="h-8 bg-white rounded px-2 py-0.5 group-hover:opacity-80 transition">
                </a>
            </div>
        </aside>

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

        {{-- Main --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Header --}}
            <header class="h-[72px] bg-white border-b border-slate-200 flex items-center gap-4 px-4 lg:px-8 sticky top-0 z-20">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="flex-1 max-w-md relative hidden sm:block">
                    <form action="{{ route('applications.searchWithTrackingNo') }}" method="GET"
                        class="flex-1 max-w-md relative hidden sm:block">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-3.5-3.5"/>
                        </svg>
                        <input type="text" name="tracking_no" value="{{ request('tracking_no') }}"
                            placeholder="আবেদন নম্বর দ্বারা ট্র্যাকিং করুন ..."
                            class="w-full rounded-full bg-slate-100 border-0 pl-10 pr-4 py-2.5 text-sm text-slate-600 placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500 focus:bg-white outline-none">
                    </form>
                </div>

                <div class="flex-1 sm:hidden"><h1 class="text-base font-semibold text-slate-800">@yield('title', 'ড্যাশবোর্ড')</h1></div>

                <div class="flex items-center gap-2 lg:gap-3 ml-auto" x-data="{ open: false, notifOpen: false }">
                    <button class="hidden sm:flex items-center gap-1.5 text-xs text-slate-500 px-3 py-2 rounded-full hover:bg-slate-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18"/></svg>
                        বাং
                    </button>

                    @php $unread = auth()->user()->unreadNotifications()->latest()->take(6)->get(); @endphp
                    <div class="relative">
                        <button @click="notifOpen = !notifOpen" class="relative p-2.5 rounded-full hover:bg-slate-100" title="বিজ্ঞপ্তি">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($unread->count())
                                <span class="absolute -top-0.5 -right-0.5 h-4 w-4 rounded-full bg-red-500 text-[10px] text-white flex items-center justify-center">{{ $unread->count() }}</span>
                            @endif
                        </button>
                        <div x-show="notifOpen" x-cloak @click.outside="notifOpen = false"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 py-1 text-sm max-h-96 overflow-y-auto">
                            <div class="flex items-center justify-between px-4 py-2.5 border-b border-slate-100">
                                <p class="font-medium text-slate-700">বিজ্ঞপ্তি</p>
                                @if($unread->count())
                                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                                    @csrf
                                    <button class="text-xs text-brand-600 hover:underline">সব পঠিত করুন</button>
                                </form>
                                @endif
                            </div>
                            @forelse($unread as $n)
                                <a href="{{ route('applications.show', $n->data['application_id']) }}" class="block px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0">
                                    <p class="text-slate-700">{{ $n->data['message'] }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $n->created_at->diffForHumans() }}</p>
                                </a>
                            @empty
                                <p class="px-4 py-6 text-center text-slate-400 text-sm">নতুন কোনো বিজ্ঞপ্তি নেই।</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="relative">
                        <button @click="open = !open" class="flex items-center gap-2.5 bg-ink-900 rounded-full pl-2 pr-3.5 py-1.5 hover:bg-ink-800 transition">
                            <div class="h-8 w-8 rounded-full bg-brand-500 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                                {{-- {{ strtoupper(substr(auth()->user()->name ?? 'ব', 0, 1)) }} --}}
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="" class="h-8 w-8 rounded-full">
                            </div>
                            <div class="hidden sm:block text-left leading-tight">
                                <p class="text-xs font-medium text-white">{{ auth()->user()->name ?? 'ব্যবহারকারী' }}</p>
                                <p class="text-[16px] text-slate-400">
                                    {{ auth()->user()->designation?->title ?? 'N/A' }}
                                </p>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak @click.outside="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-1 text-sm">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 hover:bg-slate-50">প্রোফাইল</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 hover:bg-slate-50 text-red-600">লগআউট</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-2 lg:p-4">
                @if (session('status'))
                    <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 text-sm px-4 py-3 border border-emerald-100">{{ session('status') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
