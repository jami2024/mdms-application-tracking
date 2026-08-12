@php $d = $device ?? null; @endphp
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">ডিভাইসের নাম</label>
    <input type="text" name="device_name" value="{{ old('device_name', $d->device_name ?? '') }}" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">মডেল নং</label>
    <input type="text" name="model_no" value="{{ old('model_no', $d->model_no ?? '') }}" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">প্রস্তুতকারক</label>
    <input type="text" name="manufacturer" value="{{ old('manufacturer', $d->manufacturer ?? '') }}" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">উৎপত্তির দেশ</label>
    <input type="text" name="country_of_origin" value="{{ old('country_of_origin', $d->country_of_origin ?? '') }}" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">পণ্যের গ্রেড</label>
    <select name="product_grade_id" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        <option value="">— নির্বাচন করুন —</option>
        @foreach($grades as $g)<option value="{{ $g->id }}" @selected(old('product_grade_id', $d->product_grade_id ?? '') == $g->id)>{{ $g->name }}</option>@endforeach
    </select>
</div>
