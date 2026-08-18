@extends('layouts.admin')
@section('title', 'পণ্যের গ্রেডসমূহ')
@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-800">পণ্যের গ্রেডসমূহ</h2>
        <a href="{{ route('admin.product-grades.create') }}" class="text-sm px-4 py-2 rounded-none bg-ink-900 text-white hover:bg-slate-800 transition">+ নতুন</a>
    </div>
    @if(session('status'))<div class="rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5">{{ session('status') }}</div>@endif
    <div class="bg-white rounded-none border border-slate-200 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-[16px] uppercase tracking-wide text-slate-400 border-b border-slate-200"><tr><th class="text-left px-5 py-3.5">নাম</th><th class="text-left px-5 py-3.5">কোড</th><th class="text-left px-5 py-3.5">বিবরণ</th><th class="text-left px-5 py-3.5">স্ট্যাটাস</th><th class="text-right px-5 py-3.5">কার্যক্রম</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($grades as $g)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $g->name }}</td>
                    <td class="px-5 py-3.5"><span class="text-xs font-medium px-2.5 py-1 rounded-none bg-brand-50 text-brand-700">{{ $g->code }}</span></td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $g->description ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ \App\Support\Bengali::label($g->status) }}</td>
                    <td class="px-5 py-3.5 text-right space-x-2 whitespace-nowrap">
                        <a href="{{ route('admin.product-grades.edit', $g) }}" class="text-brand-600 hover:underline">সম্পাদনা</a>
                        <form method="POST" action="{{ route('admin.product-grades.destroy', $g) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-red-500 hover:underline">মুছুন</button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $grades->links() }}
</div>
@endsection
