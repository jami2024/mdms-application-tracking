@extends('layouts.admin')
@section('title', 'আমার পর্যালোচনা তালিকা')
@section('content')
    <div class="space-y-4">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">আমার পর্যালোচনা তালিকা</h2>
            <p class="text-sm text-slate-500">Applications currently waiting at your designation's step.</p>
        </div>

        <form method="GET" class="flex flex-wrap items-center gap-2 bg-white p-3 rounded-none border border-slate-200">

            {{-- Application No --}}
            <input type="text" name="application_no" value="{{ request('application_no') }}" placeholder="আবেদন নং লিখুন"
                class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">


            {{-- Status --}}
            <select name="status"
                class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">

                <option value="">সব স্ট্যাটাস</option>

                @foreach (['submitted', 'in_review', 'returned', 'approved', 'rejected'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>
                        {{ \App\Support\Bengali::label($s) }}
                    </option>
                @endforeach

            </select>


            {{-- Module --}}
            <select name="module"
                class="rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">

                <option value="">সব মডিউল</option>

                @foreach ($modules as $module)
                    <option value="{{ $module }}" @selected(request('module') === $module)>
                        {{ \App\Support\Bengali::label($module) }}
                    </option>
                @endforeach

            </select>


            {{-- Filter --}}
            <button type="submit"
                class="text-sm px-4 py-2 rounded-none bg-ink-900 text-white hover:bg-slate-800 transition">
                ফিল্টার
            </button>


            {{-- Reset --}}
            @if (request('application_no') || request('status') || request('module'))
                <a href="{{ route('applications.index') }}"
                    class="text-sm px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 transition">
                    রিসেট
                </a>
            @endif

        </form>

        <div class="bg-white rounded-none border border-slate-200 shadow-sm overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-[16px] uppercase tracking-wide text-slate-400 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-5 py-3.5">আবেদন নং</th>
                        <th class="text-left px-5 py-3.5">মডিউল</th>
                        <th class="text-left px-5 py-3.5">আবেদনকারী</th>
                        <th class="text-left px-5 py-3.5">বর্তমান ধাপ</th>
                        <th class="text-left px-5 py-3.5">স্ট্যাটাস</th>
                        <th class="text-right px-5 py-3.5">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($applications as $app)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5 font-medium text-slate-800">{{ $app->application_no }}</td>
                            <td class="px-5 py-3.5 text-slate-500">
                                {{ \App\Support\Bengali::label($app->workflowConfig?->module) }}</td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $app->applicant->name }}</td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $app->currentStep->step_name ?? '—' }}</td>
                            <td class="px-5 py-3.5">
                                @php $colors = ['submitted'=>'bg-amber-50 text-amber-700','in_review'=>'bg-brand-50 text-brand-700','returned'=>'bg-orange-50 text-orange-700','approved'=>'bg-emerald-50 text-emerald-700','rejected'=>'bg-red-50 text-red-600','draft'=>'bg-slate-100 text-slate-500']; @endphp
                                <span
                                    class="text-xs font-medium px-2.5 py-1 rounded-none {{ $colors[$app->status] ?? 'bg-slate-100 text-slate-500' }}">{{ \App\Support\Bengali::label($app->status) }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right"><a href="{{ route('applications.show', $app) }}"
                                    class="text-brand-600 hover:underline">পর্যালোচনা</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400">আপনার তালিকায় কিছু অপেক্ষমাণ
                                নেই।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $applications->links() }}
    </div>
@endsection
