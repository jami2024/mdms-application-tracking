@php $u = $upazila ?? null; @endphp
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">জেলা</label>
    <select name="district_id" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        @foreach($districts as $dist)<option value="{{ $dist->id }}" @selected(old('district_id', $u->district_id ?? '') == $dist->id)>{{ $dist->name }} ({{ $dist->division->name }})</option>@endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">নাম</label>
    <input type="text" name="name" value="{{ old('name', $u->name ?? '') }}" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">বাংলা নাম</label>
    <input type="text" name="bn_name" value="{{ old('bn_name', $u->bn_name ?? '') }}" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">কোড</label>
    <input type="text" name="code" value="{{ old('code', $u->code ?? '') }}" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">স্ট্যাটাস</label>
    <select name="status" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        @foreach(['active','inactive'] as $s)<option value="{{ $s }}" @selected(old('status', $u->status ?? 'active') === $s)>{{ \App\Support\Bengali::label($s) }}</option>@endforeach
    </select>
</div>
