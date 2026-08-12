@php $d = $designation ?? null; @endphp
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">শিরোনাম</label>
    <input type="text" name="title" value="{{ old('title', $d->title ?? '') }}" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">সংক্ষিপ্ত কোড</label>
    <input type="text" name="short_code" value="{{ old('short_code', $d->short_code ?? '') }}" required placeholder="SD / AD / DD / GD / Admin" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">সংস্থা</label>
    <select name="organization_id" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        <option value="">— কোনোটি না —</option>
        @foreach($organizations as $o)<option value="{{ $o->id }}" @selected(old('organization_id', $d->organization_id ?? '') == $o->id)>{{ $o->name }}</option>@endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">গ্রেড লেভেল (১ = সর্বোচ্চ কর্তৃত্ব)</label>
    <input type="number" name="grade_level" value="{{ old('grade_level', $d->grade_level ?? '') }}" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">স্ট্যাটাস</label>
    <select name="status" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        @foreach(['active','inactive'] as $s)<option value="{{ $s }}" @selected(old('status', $d->status ?? 'active') === $s)>{{ \App\Support\Bengali::label($s) }}</option>@endforeach
    </select>
</div>
