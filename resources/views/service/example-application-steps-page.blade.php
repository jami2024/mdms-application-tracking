
@extends('layouts.admin')
@section('title', 'আবেদনের ধাপ')
@section('content')

<div class="max-w-3xl mx-auto px-4 py-8">

    @php
        $steps = [
            ['label' => 'আবেদন ফ্রন্ট ডেস্ক সাবমিট', 'completed' => true, 'date' => '৩০/০৪/২০২৬'],
            ['label' => 'আবেদনের রশিদ', 'completed' => true, 'date' => '৩০/০৪/২০২৬'],
            ['label' => 'ডিপার্টমেন্ট সংশ্লিষ্ট বণ্টন', 'completed' => true, 'date' => '০৪/০৫/২০২৬'],
            ['label' => 'Section UD/AD', 'completed' => true, 'date' => '০৪/০৫/২০২৬'],
            ['label' => 'SD/AD', 'completed' => true, 'date' => '০৭/০৬/২০২৬'],
            ['label' => 'DD', 'completed' => true, 'date' => '১৮/০৪/২০২৬'],
            ['label' => 'Director', 'completed' => false, 'date' => null],
            ['label' => 'ADG', 'completed' => true, 'date' => '৩০/০৪/২০২৬'],
            ['label' => 'DG', 'completed' => true, 'date' => '৩০/০৪/২০২৬'],
            ['label' => 'সার্টিফিকেট প্রদান', 'completed' => true, 'date' => '০৭/০৬/২০২৬'],
            ['label' => 'সার্ভিস নিশ্চিতকরণ', 'completed' => true, 'date' => '৩০/০৪/২০২৬'],
        ];
    @endphp

    <x-application-steps :steps="$steps" />

</div>

@endsection
