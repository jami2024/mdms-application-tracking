{{--
    আবেদনের ধাপ (Application Status Timeline)
    Save as resources/views/components/application-steps.blade.php and use as:

        <x-application-steps :steps="$steps" />

    Where $steps is an array like:

        $steps = [
            ['label' => 'আবেদন পেশোদার তারিখ',            'completed' => true,  'date' => '৩০/০৪/২০২৬'],
            ['label' => 'আবেদনপত্র গ্রহণযোগ্য',             'completed' => true,  'date' => '৩০/০৪/২০২৬'],
            ['label' => 'খসড়া খতিয়ান প্রস্তুত',            'completed' => true,  'date' => '০৪/০৫/২০২৬'],
            ['label' => 'ইউনিয়ন সহকারী ভূমি কর্মকর্তা কর্তৃক প্রতিবেদন প্রেরণ', 'completed' => true, 'date' => '০৪/০৫/২০২৬'],
            ['label' => 'স্বাধিনি জমা আপিল (বেলানির খায়ে উপজেলা ভূমি অফিস, বরগুনা সদর, বরগুনা)', 'completed' => true, 'date' => '০৭/০৬/২০২৬'],
            ['label' => 'কানুনগো/অফিস সহকারী কর্তৃক প্রতিবেদন প্রেরণ', 'completed' => true, 'date' => '১৮/০৪/২০২৬'],
            ['label' => 'সার্ভেয়ার কর্তৃক প্রতিবেদন প্রেরণ',   'completed' => false, 'date' => null],
            ['label' => 'নামজারি/জমাভাগ আবেদন মঞ্জুর/নামঞ্জুর', 'completed' => true, 'date' => '৩০/০৪/২০২৬'],
            ['label' => 'খতিয়ান প্রস্তুত',                  'completed' => true,  'date' => '৩০/০৪/২০২৬'],
            ['label' => 'ডি সি আর পেমেন্টের তারিখ',          'completed' => true,  'date' => '০৭/০৬/২০২৬'],
            ['label' => 'খতিয়ান প্রদান',                   'completed' => true,  'date' => '৩০/০৪/২০২৬'],
        ];
--}}

@props(['steps' => [], 'title' => 'আবেদনের অগ্রগতি'])

@php
    // Convert Western digits (0-9) to Bangla numerals (০-৯).
    // Move this to app/helpers.php or a Blade directive if you use it elsewhere too.
    if (! function_exists('bn_digits')) {
        function bn_digits($number) {
            return strtr((string) $number, ['0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪',
                                             '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯']);
        }
    }

    $total = count($steps);
    $completedCount = collect($steps)->filter(fn ($s) => $s['completed'] ?? false)->count();
    $percent = $total ? (int) round(($completedCount / $total) * 100) : 0;

    // The first not-yet-completed step is treated as the "current" (in-progress) step.
    $currentIndex = collect($steps)->search(fn ($s) => empty($s['completed']));
@endphp

{{-- Assumes 'Noto Sans Bengali' is already loaded globally by the parent layout --}}
<div class="bg-white rounded-xl border border-ink/10 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-3 px-4 py-3 border-b border-ink/10 bg-slate-50/70">
        <h2 class="text-sm font-semibold text-ink">{{ $title }}</h2>
        <span class="shrink-0 text-[11px] font-semibold px-2.5 py-1 rounded-full
            {{ $percent === 100 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
            {{ bn_digits($completedCount) }}/{{ bn_digits($total) }} ধাপ সম্পন্ন
        </span>
    </div>

    {{-- Progress bar --}}
    <div class="px-4 pt-3">
        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
        </div>
    </div>

    {{-- Timeline: step label left of the marker, date right of the marker --}}
    <div class="px-4 py-4">
        <ol>
            @foreach ($steps as $i => $step)
                @php
                    $isCompleted = (bool) ($step['completed'] ?? false);
                    $isCurrent = ! $isCompleted && $i === $currentIndex;
                    $isUpcoming = ! $isCompleted && ! $isCurrent;

                    $statusText = $isCompleted ? 'সম্পন্ন' : ($isCurrent ? 'চলমান' : 'অপেক্ষমাণ');
                    $statusColor = $isCompleted ? 'text-emerald-600' : ($isCurrent ? 'text-amber-600' : 'text-ink/35');
                    $labelColor = $isUpcoming ? 'text-ink/40' : 'text-ink';
                    $lineColor = $isCompleted ? 'bg-emerald-400' : 'bg-slate-200';
                @endphp

                <li class="grid grid-cols-[1fr_28px_1fr] items-stretch gap-x-3 {{ !$loop->last ? 'pb-1' : '' }}">

                    {{-- Left: step label + status --}}
                    <div class="text-right pt-0.5 pb-4">
                        <p class="text-sm font-medium leading-snug {{ $labelColor }}">{{ $step['label'] }}</p>
                        <p class="text-[11px] font-medium mt-0.5 {{ $statusColor }}">{{ $statusText }}</p>
                    </div>

                    {{-- Center: marker + connecting line --}}
                    <div class="relative flex flex-col items-center">
                        <div class="relative shrink-0 w-7 h-7">
                            @if ($isCurrent)
                                <span class="absolute inset-0 rounded-full bg-amber-400/30 animate-ping"></span>
                            @endif
                            <div class="relative w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold
                                {{ $isCompleted
                                    ? 'bg-emerald-500 text-white'
                                    : ($isCurrent
                                        ? 'bg-white border-2 border-amber-500 text-amber-600'
                                        : 'bg-slate-50 border border-slate-300 text-ink/35') }}">
                                @if ($isCompleted)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                                        <path d="M5 12.5l4.5 4.5L19 7" stroke="#ffffff" stroke-width="2.5"
                                              stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @else
                                    {{ bn_digits($i + 1) }}
                                @endif
                            </div>
                        </div>

                        @unless ($loop->last)
                            <span class="flex-1 w-px {{ $lineColor }}"></span>
                        @endunless
                    </div>

                    {{-- Right: date --}}
                    <div class="pt-1.5 pb-4">
                        @if (!empty($step['date']))
                            <span class="text-[11px] text-ink/40">{{ $step['date'] }}</span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</div>
