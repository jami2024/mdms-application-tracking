@extends('layouts.admin')
@section('title', 'ওয়ার্কফ্লো কনফিগ')
@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">ওয়ার্কফ্লো কনফিগ</h2>
            <p class="text-sm text-slate-500">One workflow per applicant module; each has an ordered chain of SD → AD → DD → GD → Admin steps.</p>
        </div>
        <a href="{{ route('admin.workflow-configs.create') }}" class="text-sm px-4 py-2 rounded-none bg-ink-900 text-white hover:bg-slate-800 transition">+ নতুন ওয়ার্কফ্লো</a>
    </div>
    @if(session('status'))<div class="rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5">{{ session('status') }}</div>@endif
    <div class="bg-white rounded-none border border-slate-200 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-[16px] uppercase tracking-wide text-slate-400 border-b border-slate-200"><tr><th class="text-left px-5 py-3.5">নাম</th><th class="text-left px-5 py-3.5">মডিউল</th><th class="text-left px-5 py-3.5">ধাপসমূহ</th><th class="text-left px-5 py-3.5">স্ট্যাটাস</th><th class="text-right px-5 py-3.5">কার্যক্রম</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($configs as $c)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $c->name }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ \App\Support\Bengali::label($c->module) }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $c->steps_count }}</td>
                    <td class="px-5 py-3.5">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-none {{ $c->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $c->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-right space-x-2 whitespace-nowrap">
                        <a href="{{ route('admin.workflow-configs.edit', $c) }}" class="text-brand-600 hover:underline">ধাপ ব্যবস্থাপনা</a>
                        <form method="POST" action="{{ route('admin.workflow-configs.destroy', $c) }}" class="inline" onsubmit="return confirm('Delete this workflow and all its steps?')">@csrf @method('DELETE')<button class="text-red-500 hover:underline">মুছুন</button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $configs->links() }}
</div>
@endsection
