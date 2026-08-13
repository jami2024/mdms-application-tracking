@extends('layouts.admin')
@section('title', 'আবেদন ' . $application->application_no)
@section('content')
    @php
        $statusColors = [
            'submitted' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            'in_review' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
            'returned' => 'bg-orange-50 text-orange-700 ring-orange-600/20',
            'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            'rejected' => 'bg-red-50 text-red-600 ring-red-600/20',
            'draft' => 'bg-slate-100 text-slate-500 ring-slate-500/20',
        ];
        $entityName = $application->applicable->name ?? ($application->applicable->device_name ?? '');
        $canAct =
            in_array($application->status, ['submitted', 'in_review', 'returned']) &&
            $application->applicant_id !== auth()->id() &&
            !$application->logs->contains('acted_by', auth()->id()) &&
            (auth()->user()->hasRole('Admin') ||
                ($application->assigned_to && $application->assigned_to === auth()->id()) ||
                (!$application->assigned_to &&
                    $application->currentStep &&
                    $application->currentStep->designation_id === auth()->user()->designation_id));
        $paidPayment = $application->payments->whereIn('status', ['paid', 'reconciled'])->first();
        $actionIcons = [
            'forward' => ['bg-blue-100 text-blue-600', 'M13 7l5 5-5 5M6 12h12'],
            'backward' => ['bg-orange-100 text-orange-600', 'M11 17l-5-5 5-5M18 12H6'],
            'approve' => ['bg-emerald-100 text-emerald-600', 'M5 13l4 4L19 7'],
            'reject' => ['bg-red-100 text-red-600', 'M6 18L18 6M6 6l12 12'],
            'submit' => ['bg-slate-200 text-slate-600', 'M12 4v16m8-8H4'],
        ];
        $isFinalStep =
            $application->currentStep &&
            !$application->workflowConfig->steps
                ->where('step_order', '>', $application->currentStep->step_order)
                ->count();
    @endphp

    <div class="max-w-6xl" x-data="{
        modalOpen: false,
        decision: 'forward',
        urls: {
            forward: '{{ route('applications.forward', $application) }}',
            approve: '{{ route('applications.approve', $application) }}',
            backward: '{{ route('applications.backward', $application) }}',
            reject: '{{ route('applications.reject', $application) }}',
        }
    }">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 mb-6 flex-wrap">
            <div class="flex items-start gap-4">
                <div class="h-12 w-12 rounded-none bg-ink-900 flex items-center justify-center text-white shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6M9 8h6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-2xl font-bold text-slate-900 font-mono">{{ $application->application_no }}</h2>
                        <span
                            class="text-xs font-medium px-3 py-1 rounded-none ring-1 ring-inset {{ $statusColors[$application->status] ?? 'bg-slate-100 text-slate-500' }}">{{ \App\Support\Bengali::label($application->status) }}</span>
                    </div>
                    <p class="text-sm text-slate-500 mt-1">
                        {{ \App\Support\Bengali::label($application->workflowConfig?->module) }} · <span
                            class="font-medium text-slate-700">{{ $entityName }}</span> · আবেদনকারী:
                        {{ $application->applicant->name }}
                    </p>
                </div>
            </div>

            @if ($canAct)
                <button @click="modalOpen = true"
                    class="px-5 py-2.5 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition shadow-lg flex items-center gap-2 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    সিদ্ধান্ত নিন
                </button>
            @endif
        </div>

        @if (session('status'))
            <div
                class="mb-5 rounded-none bg-emerald-50 text-emerald-700 text-sm px-4 py-3 border border-emerald-100 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div
                class="mb-5 rounded-none bg-red-50 text-red-600 text-sm px-4 py-3 border border-red-100 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" />
                    <path stroke-linecap="round" d="M12 8v5M12 16h.01" />
                </svg>{{ session('error') }}</div>
        @endif

        @unless ($canAct)
            @if (in_array($application->status, ['submitted', 'in_review', 'returned']))
                <div
                    class="mb-5 bg-slate-50 border border-slate-200 rounded-none p-4 text-sm text-slate-500 flex items-start gap-3">
                    <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" d="M12 8v5M12 16h.01" />
                    </svg>
                    <span>
                        @if ($application->applicant_id === auth()->id())
                            আপনি নিজের জমা দেওয়া আবেদনে অ্যাকশন নিতে পারবেন না।
                        {{-- @elseif($application->logs->contains('acted_by', auth()->id()))
                            আপনি এই আবেদনে আগের কোনো ধাপে ইতিমধ্যে কাজ করেছেন — একই আবেদনে দুইবার রিভিউ করা যায় না। --}}
                        @elseif($application->assigned_to && $application->assigned_to !== auth()->id())
                            এই আবেদনটি নির্দিষ্টভাবে <strong>{{ $application->assignedTo->name }}</strong>-কে বরাদ্দ করা
                            হয়েছে।
                        @else
                            এই মুহূর্তে এটি {{ $application->currentStep->designation->title ?? 'অন্য একটি' }}
                            ({{ $application->currentStep->designation->short_code ?? '' }}) পদের পর্যালোচনার অপেক্ষায় আছে —
                            আপনার কার্যক্ষেত্রে নেই।
                        @endif
                    </span>
                </div>
            @endif
        @endunless

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-4">
            {{-- Workflow stepper --}}
            <div class="bg-white rounded-none border border-slate-200 shadow-sm p-6">
                <p class="text-sm font-semibold text-slate-800 mb-5">ওয়ার্কফ্লো অগ্রগতি</p>
                <div class="flex items-start">
                    @foreach ($application->workflowConfig->steps as $step)
                        @php
                            $isCurrent = $application->current_step_id === $step->id;
                            $isPast =
                                $step->step_order < ($application->currentStep->step_order ?? 999) ||
                                $application->status === 'approved';
                            $isDone = $isPast && !$isCurrent;
                        @endphp
                        <div class="flex-1 flex flex-col items-center relative">
                            @if (!$loop->first)
                                <div class="absolute top-4 right-1/2 w-full h-0.5 {{ $isDone || $isCurrent ? 'bg-emerald-500' : 'bg-slate-200' }}"
                                    style="right: 50%; width: 100%;"></div>
                            @endif
                            <div
                                class="relative z-10 h-8 w-8 rounded-full flex items-center justify-center text-xs font-semibold shrink-0
                                    {{ $isDone ? 'bg-emerald-500 text-white' : ($isCurrent ? 'bg-ink-900 text-white ring-4 ring-ink-900/15' : 'bg-slate-100 text-slate-400 border-2 border-slate-200') }}">
                                @if ($isDone)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </div>
                            <p
                                class="text-[11px] font-medium mt-2 text-center {{ $isCurrent ? 'text-slate-800' : 'text-slate-400' }}">
                                {{ $step->step_name }}</p>
                            <p class="text-[10px] text-slate-400">{{ $step->designation->short_code }}</p>
                        </div>
                    @endforeach
                </div>
                @if ($application->assigned_to && in_array($application->status, ['submitted', 'in_review']))
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-500">
                        <div
                            class="h-6 w-6 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-[10px] font-semibold">
                            {{ strtoupper(substr($application->assignedTo->name, 0, 1)) }}</div>
                        <span>নির্দিষ্টভাবে বরাদ্দকৃত: <strong
                                class="text-slate-700">{{ $application->assignedTo->name }}</strong></span>
                    </div>
                @endif
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main column --}}
            <div class="lg:col-span-2 space-y-6">


                {{-- Full entity details --}}
                @include('applications._entity-details')

                @if ($packageApplication)
                    {{-- Packaging application details --}}
                    @include('applications._packaging-details', ['packageApplication' => $packageApplication])

                @endif

                {{-- History timeline --}}
                <div class="bg-white rounded-none border border-slate-200 shadow-sm p-6">
                    <p class="text-sm font-semibold text-slate-800 mb-4">ইতিহাস ও মন্তব্য</p>
                    <div class="space-y-0">
                        @forelse($application->logs as $log)
                            @php $icon = $actionIcons[$log->action] ?? ['bg-slate-100 text-slate-500', 'M12 8v4l3 3']; @endphp
                            <div class="flex gap-3 pb-5 last:pb-0 relative">
                                @if (!$loop->last)
                                    <div class="absolute left-4 top-9 bottom-0 w-px bg-slate-100"></div>
                                @endif
                                <div
                                    class="h-8 w-8 rounded-full {{ $icon[0] }} flex items-center justify-center shrink-0 z-10">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon[1] }}" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-sm"><span
                                                class="font-medium text-slate-800">{{ \App\Support\Bengali::label($log->action) }}</span>
                                            <span class="text-slate-400">— {{ $log->actor->name }}</span></p>
                                        <p class="text-[11px] text-slate-400 whitespace-nowrap shrink-0">
                                            {{ $log->acted_at->format('d M, H:i') }}</p>
                                    </div>
                                    @if ($log->remarks)
                                        <p class="text-xs text-slate-500 mt-1 bg-slate-50 rounded-none px-3 py-2">
                                            {{ $log->remarks }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400 text-center py-4">এখনো কোনো সিদ্ধান্ত রেকর্ড হয়নি।</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-white rounded-none border border-slate-200 shadow-sm p-5 space-y-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">সারসংক্ষেপ</p>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-xs text-slate-400">মডিউল</p>
                            <p class="font-medium text-slate-800">
                                {{ \App\Support\Bengali::label($application->workflowConfig?->module) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">সত্তার নাম</p>
                            <p class="font-medium text-slate-800">{{ $entityName ?: '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">আবেদনকারী</p>
                            <p class="font-medium text-slate-800">{{ $application->applicant->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">বর্তমান ধাপ</p>
                            <p class="font-medium text-slate-800">{{ $application->currentStep->step_name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">জমার তারিখ</p>
                            <p class="font-medium text-slate-800">
                                {{ $application->submitted_at?->format('d M, Y') ?? '—' }}</p>
                        </div>
                        @if ($application->decided_at)
                            <div>
                                <p class="text-xs text-slate-400">সিদ্ধান্তের তারিখ</p>
                                <p class="font-medium text-slate-800">{{ $application->decided_at->format('d M, Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($application->status === 'approved')
                    <div class="bg-white rounded-none border border-slate-200 shadow-sm p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">আবেদন ফি</p>
                        <div class="flex items-center gap-3 mb-3">
                            <div
                                class="h-9 w-9 rounded-full {{ $paidPayment ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8a5 5 0 100 10 5 5 0 000-10zM3 8v10a5 5 0 005 5M3 8a5 5 0 015-5" />
                                </svg>
                            </div>
                            <p class="text-xs text-slate-500">
                                {{ $paidPayment ? 'পরিশোধিত — ' . $paidPayment->method : 'এখনো পরিশোধ করা হয়নি' }}</p>
                        </div>
                        @if ($paidPayment)
                            <a href="{{ route('payments.show', $paidPayment) }}"
                                class="block text-center px-4 py-2 rounded-none border border-slate-300 bg-white text-sm hover:bg-slate-50 transition">রসিদ
                                দেখুন</a>
                        @elseif($application->applicant_id === auth()->id())
                            <a href="{{ route('payments.create', $application) }}"
                                class="block text-center px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">ফি
                                প্রদান করুন</a>
                        @endif
                    </div>

                    <div class="bg-white rounded-none border border-slate-200 shadow-sm p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">সার্টিফিকেট</p>
                        <div class="flex items-center gap-3 mb-3">
                            <div
                                class="h-9 w-9 rounded-full {{ $application->certificate ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="8" r="5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 13L7 22l5-3 5 3-1.5-9" />
                                </svg>
                            </div>
                            <p class="text-xs text-slate-500">
                                {{ $application->certificate ? $application->certificate->certificate_no : 'এখনো ইস্যু করা হয়নি' }}
                            </p>
                        </div>
                        @if ($application->certificate)
                            <a href="{{ route('certificates.show', $application->certificate) }}"
                                class="block text-center px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">সার্টিফিকেট
                                দেখুন</a>
                        @else
                            <a href="{{ route('applications.certificate.create', $application) }}"
                                class="block text-center px-4 py-2 rounded-none border border-slate-300 bg-white text-sm hover:bg-slate-50 transition">সার্টিফিকেট
                                ইস্যু করুন</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Decision Modal --}}
        @if ($canAct)
            <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="modalOpen = false"></div>
                <div x-show="modalOpen" x-transition
                    class="relative bg-white rounded-none shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                    <div
                        class="px-6 py-4 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                        <p class="text-base font-semibold text-slate-800">সিদ্ধান্ত নিন</p>
                        <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" :action="urls[decision]" class="p-6 space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">সিদ্ধান্তের ধরন</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label
                                    class="flex items-center gap-2 border rounded-none px-3 py-2.5 cursor-pointer text-sm transition"
                                    :class="decision === 'forward' ? 'border-blue-500 bg-blue-50 text-blue-700' :
                                        'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                    <input type="radio" x-model="decision" value="forward" class="text-blue-600">
                                    ফরওয়ার্ড
                                </label>
                                @if ($isFinalStep)
                                    <label
                                        class="flex items-center gap-2 border rounded-none px-3 py-2.5 cursor-pointer text-sm transition"
                                        :class="decision === 'approve' ? 'border-emerald-500 bg-emerald-50 text-emerald-700' :
                                            'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                        <input type="radio" x-model="decision" value="approve"
                                            class="text-emerald-600"> চূড়ান্ত অনুমোদন
                                    </label>
                                @else
                                    <label
                                        class="flex items-center gap-2 border rounded-none px-3 py-2.5 cursor-pointer text-sm transition"
                                        :class="decision === 'approve' ? 'border-emerald-500 bg-emerald-50 text-emerald-700' :
                                            'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                        <input type="radio" x-model="decision" value="approve"
                                            class="text-emerald-600"> অনুমোদন (পরের ধাপে)
                                    </label>
                                @endif
                                @if ($application->currentStep?->can_send_back)
                                    <label
                                        class="flex items-center gap-2 border rounded-none px-3 py-2.5 cursor-pointer text-sm transition"
                                        :class="decision === 'backward' ? 'border-orange-500 bg-orange-50 text-orange-700' :
                                            'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                        <input type="radio" x-model="decision" value="backward"
                                            class="text-orange-600"> ফেরত পাঠান
                                    </label>
                                @endif
                                @if ($application->currentStep?->can_reject)
                                    <label
                                        class="flex items-center gap-2 border rounded-none px-3 py-2.5 cursor-pointer text-sm transition"
                                        :class="decision === 'reject' ? 'border-red-500 bg-red-50 text-red-700' :
                                            'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                        <input type="radio" x-model="decision" value="reject" class="text-red-600">
                                        প্রত্যাখ্যান
                                    </label>
                                @endif
                            </div>
                        </div>

                        {{-- Next-desk user picker — only relevant when forwarding --}}
                        @if ($nextDeskUsers->isNotEmpty())
                            <div x-show="decision === 'forward'" x-cloak>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">পরবর্তী ডেস্কে নির্দিষ্ট
                                    ব্যক্তি (ঐচ্ছিক)</label>
                                <select name="assigned_to"
                                    class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 outline-none hover:border-slate-400 shadow-sm transition">
                                    <option value="">— যেকোনো একজন (ডিজিগনেশন অনুযায়ী) —</option>
                                    @foreach ($nextDeskUsers as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}
                                            ({{ $u->designation->short_code ?? '' }})</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-slate-400 mt-1">নির্বাচন না করলে ঐ পদের যেকোনো ব্যবহারকারী এটি দেখতে
                                    ও অ্যাকশন নিতে পারবেন।</p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                মন্তব্য / সিদ্ধান্তের কারণ
                                <span x-show="decision === 'backward' || decision === 'reject'"
                                    class="text-red-500">*</span>
                            </label>
                            <textarea name="remarks" rows="3" :required="decision === 'backward' || decision === 'reject'"
                                placeholder="আপনার সিদ্ধান্তের কারণ লিখুন…"
                                class="w-full rounded-none border-2 border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="modalOpen = false"
                                class="px-4 py-2.5 rounded-none border border-slate-300 bg-white text-sm hover:bg-slate-50 transition">বাতিল</button>
                            <button type="submit"
                                class="px-6 py-2.5 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">নিশ্চিত
                                করুন</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
