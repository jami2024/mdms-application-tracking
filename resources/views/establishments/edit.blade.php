@extends('layouts.admin')
@section('title', 'এস্টাবলিশমেন্ট সম্পাদনা')
@section('content')
<div class="max-w-lg">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">এস্টাবলিশমেন্ট সম্পাদনা</h2>
    <form method="POST" action="{{ route('establishments.update', $establishment) }}" class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf @method('PUT')
        @include('establishments._form')
        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">পরিবর্তন সংরক্ষণ করুন</button>
    </form>
</div>
@endsection
