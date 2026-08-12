@extends('layouts.admin')
@section('title', 'নতুন প্রতিষ্ঠান')
@section('content')
<div class="max-w-4xl">
    <h2 class="text-2xl font-bold text-slate-900 mb-1">নতুন প্রতিষ্ঠান নিবন্ধন</h2>
    <p class="text-sm text-slate-500 mb-6">সব তথ্য একসাথে পূরণ করার দরকার নেই — খসড়া হিসেবে সংরক্ষণ করে পরে সম্পাদনা করতে পারবেন।</p>
    <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data" onkeydown="if(event.key==='Enter' && event.target.tagName!=='TEXTAREA'){event.preventDefault();}"
          class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-6">
        @csrf
        @include('companies._form', ['company' => null])
        <button class="px-6 py-3 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition shadow-lg">খসড়া হিসেবে সংরক্ষণ করুন</button>
    </form>
</div>
@endsection
