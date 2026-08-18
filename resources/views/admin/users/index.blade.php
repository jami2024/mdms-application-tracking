@extends('layouts.admin')
@section('title', 'ব্যবহারকারী ব্যবস্থাপনা')

@section('content')
<div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">ব্যবহারকারী</h2>
            <p class="text-sm text-slate-500">মোট {{ $users->total() }} জন ব্যবহারকারী</p>
        </div>
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" name="file" required class="text-xs">
                <button class="text-sm px-3 py-2 rounded-none border border-slate-300 bg-white hover:bg-slate-50 transition">ইমপোর্ট</button>
            </form>
            <a href="{{ route('admin.users.export') }}" class="text-sm px-3 py-2 rounded-none border border-slate-300 bg-white hover:bg-slate-50 transition">এক্সপোর্ট</a>
            <a href="{{ route('admin.users.create') }}" class="text-sm px-4 py-2 rounded-none bg-ink-900 text-white hover:bg-slate-800 transition">+ নতুন ব্যবহারকারী</a>
        </div>
    </div>

    <form method="GET" class="flex flex-wrap items-center gap-2 bg-white p-3 rounded-none border border-slate-200">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="নাম বা ইমেইল খুঁজুন…"
               class="flex-1 min-w-[180px] rounded-none border-2 border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        <select name="status" class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            <option value="">সব স্ট্যাটাস</option>
            @foreach(['active','inactive','suspended','pending'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ \App\Support\Bengali::label($s) }}</option>
            @endforeach
        </select>
        <select name="role" class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            <option value="">সব ভূমিকা</option>
            @foreach($roles as $r)
                <option value="{{ $r }}" @selected(request('role') === $r)>{{ $r }}</option>
            @endforeach
        </select>
        <button class="text-sm px-4 py-2 rounded-none bg-ink-900 text-white hover:bg-slate-800 transition">ফিল্টার</button>
    </form>

    @if(session('status'))<div class="rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5">{{ session('status') }}</div>@endif

    <div class="bg-white rounded-none border border-slate-200 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-[16px] uppercase tracking-wide text-slate-400 border-b border-slate-200">
                <tr class="hover:bg-slate-50 transition-colors">
                    <th class="text-left px-5 py-3.5">নাম</th>
                    <th class="text-left px-5 py-3.5">ইমেইল</th>
                    <th class="text-left px-5 py-3.5">ভূমিকা</th>
                    <th class="text-left px-5 py-3.5">পদবি</th>
                    <th class="text-left px-5 py-3.5">স্ট্যাটাস</th>
                    <th class="text-right px-5 py-3.5">কার্যক্রম</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $user->name }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $user->email }}</td>
                    <td class="px-5 py-3.5">
                        @foreach($user->roles as $role)
                            <span class="inline-block text-xs font-medium px-2.5 py-1 rounded-none bg-brand-50 text-brand-700">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $user->designation?->title ?? '—' }}</td>
                    <td class="px-5 py-3.5">
                        @php $colors = ['active'=>'bg-emerald-50 text-emerald-700','inactive'=>'bg-slate-100 text-slate-500','suspended'=>'bg-red-50 text-red-600','pending'=>'bg-amber-50 text-amber-700']; @endphp
                        <form method="POST" action="{{ route('admin.users.status', $user) }}" class="inline">
                            @csrf @method('PUT')
                            <select name="status" onchange="this.form.submit()"
                                    class="text-xs rounded-none px-2 py-1 border-0 {{ $colors[$user->status] }}">
                                @foreach(['active','inactive','suspended','pending'] as $s)
                                    <option value="{{ $s }}" @selected($user->status === $s)>{{ \App\Support\Bengali::label($s) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-5 py-3.5 text-right space-x-2 whitespace-nowrap">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-brand-600 hover:underline">সম্পাদনা</a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline">মুছুন</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">কোনো ব্যবহারকারী পাওয়া যায়নি।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
</div>
@endsection
