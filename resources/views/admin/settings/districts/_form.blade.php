@php $d = $district ?? null; @endphp
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">বিভাগ</label>
    <select name="division_id" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        @foreach($divisions as $div)<option value="{{ $div->id }}" @selected(old('division_id', $d->division_id ?? '') == $div->id)>{{ $div->name }}</option>@endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">নাম</label>
    <input type="text" name="name" value="{{ old('name', $d->name ?? '') }}" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">বাংলা নাম</label>
    <input type="text" name="bn_name" value="{{ old('bn_name', $d->bn_name ?? '') }}" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">কোড</label>
    <input type="text" name="code" value="{{ old('code', $d->code ?? '') }}" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">স্ট্যাটাস</label>
    <select name="status" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        @foreach(['active','inactive'] as $s)<option value="{{ $s }}" @selected(old('status', $d->status ?? 'active') === $s)>{{ \App\Support\Bengali::label($s) }}</option>@endforeach
    </select>
</div>
