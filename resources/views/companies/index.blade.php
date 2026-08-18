@extends('layouts.admin')
@section('title', 'আমার প্রতিষ্ঠান')
@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-800">আমার প্রতিষ্ঠান</h2>
        <a href="{{ route('companies.create') }}" class="text-sm px-4 py-2 rounded-none bg-ink-900 text-white hover:bg-slate-800 transition">+ নতুন প্রতিষ্ঠান</a>
    </div>
    @if(session('status'))<div class="rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5">{{ session('status') }}</div>@endif
    @if(session('error'))<div class="rounded-none bg-red-50 text-red-600 text-sm px-5 py-3.5">{{ session('error') }}</div>@endif

    <div class="bg-white rounded-none border border-slate-200 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-[16px] uppercase tracking-wide text-slate-400 border-b border-slate-200"><tr><th class="text-left px-5 py-3.5">নাম</th><th class="text-left px-5 py-3.5">অবস্থান</th><th class="text-left px-5 py-3.5">স্ট্যাটাস</th><th class="text-right px-5 py-3.5">অ্যাকশন</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($companies as $c)
                @php $colors = ['draft'=>'bg-slate-100 text-slate-500','submitted'=>'bg-amber-50 text-amber-700','active'=>'bg-emerald-50 text-emerald-700','suspended'=>'bg-orange-50 text-orange-700','rejected'=>'bg-red-50 text-red-600']; @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $c->name }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $c->division?->name ?? '—' }}</td>
                    <td class="px-5 py-3.5"><span class="text-xs font-medium px-2.5 py-1 rounded-none {{ $colors[$c->status] ?? '' }}">{{ \App\Support\Bengali::label($c->status) }}</span></td>
                    <td class="px-5 py-3.5 text-right"><a href="{{ route('companies.show', $c) }}" class="text-brand-600 hover:underline">দেখুন</a></td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400">এখনো কোনো প্রতিষ্ঠান নেই।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $companies->links() }}
</div>
@endsection
