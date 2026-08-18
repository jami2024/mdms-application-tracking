@extends('layouts.admin')
@section('title', 'ভূমিকা ও অনুমতি')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-800">ভূমিকা ও অনুমতি</h2>
        <a href="{{ route('admin.roles.create') }}" class="text-sm px-4 py-2 rounded-none bg-ink-900 text-white hover:bg-slate-800 transition">+ নতুন ভূমিকা</a>
    </div>

    @if(session('status'))<div class="rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5">{{ session('status') }}</div>@endif
    @if(session('error'))<div class="rounded-none bg-red-50 text-red-600 text-sm px-5 py-3.5">{{ session('error') }}</div>@endif

    <div class="bg-white rounded-none border border-slate-200 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-[16px] uppercase tracking-wide text-slate-400 border-b border-slate-200">
                <tr class="hover:bg-slate-50 transition-colors">
                    <th class="text-left px-5 py-3.5">ভূমিকা</th>
                    <th class="text-left px-5 py-3.5">বিবরণ</th>
                    <th class="text-left px-5 py-3.5">অনুমতি</th>
                    <th class="text-left px-5 py-3.5">ব্যবহারকারী</th>
                    <th class="text-right px-5 py-3.5">কার্যক্রম</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($roles as $role)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $role->name }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $role->description ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $role->permissions->count() }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $role->users_count }}</td>
                    <td class="px-5 py-3.5 text-right space-x-2 whitespace-nowrap">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="text-brand-600 hover:underline">সম্পাদনা</a>
                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="inline" onsubmit="return confirm('Delete this role?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline">মুছুন</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
