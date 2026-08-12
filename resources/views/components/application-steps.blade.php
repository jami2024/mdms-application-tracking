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

@props(['steps' => []])

@php
    // Convert Western digits (0-9) to Bangla numerals (০-৯).
    // Move this to app/helpers.php or a Blade directive if you use it elsewhere too.
    if (! function_exists('bn_digits')) {
        function bn_digits($number) {
            return strtr((string) $number, ['0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪',
                                             '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯']);
        }
    }
@endphp

<div class="bg-white rounded-xl border border-ink/10 overflow-hidden">

    {{-- Title --}}
    <div class="text-center py-3 border-b-2 border-emerald-500">
        <h2 class="text-base font-semibold text-ink">আবেদনের ধাপ</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b-2 border-emerald-500">
                    <th class="w-12 px-4 py-3 text-left font-semibold text-ink/70"></th>
                    <th class="px-4 py-3 text-left font-semibold text-ink/70">ধাপসমূহ</th>
                    <th class="w-28 px-4 py-3 text-center font-semibold text-ink/70">অবস্থা</th>
                    <th class="w-40 px-4 py-3 text-left font-semibold text-ink/70">বিবরণ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($steps as $i => $step)
                    <tr class="{{ !$loop->last ? 'border-b border-dashed border-ink/15' : '' }}">
                        <td class="px-4 py-3 align-top text-ink/50">{{ bn_digits($i + 1) }}</td>
                        <td class="px-4 py-3 align-top text-ink">{{ $step['label'] }}</td>
                        <td class="px-4 py-3 align-top text-center">
                            @if ($step['completed'])
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 inline-block">
                                    <circle cx="12" cy="12" r="10" fill="#16a34a" />
                                    <path d="M8 12.5l2.5 2.5L16 9.5" stroke="#ffffff" stroke-width="2"
                                          stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @endif
                        </td>
                        <td class="px-4 py-3 align-top text-ink/70">
                            {{ $step['date'] ?? '' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
