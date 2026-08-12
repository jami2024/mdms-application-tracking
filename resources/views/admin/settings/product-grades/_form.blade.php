@php $g = $grade ?? null; @endphp
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">নাম</label>
    <input type="text" name="name" value="{{ old('name', $g->name ?? '') }}" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">কোড</label>
    <input type="text" name="code" value="{{ old('code', $g->code ?? '') }}" required placeholder="A / B / C" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">বিবরণ</label>
    <textarea name="description" rows="2" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">{{ old('description', $g->description ?? '') }}</textarea>
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">স্ট্যাটাস</label>
    <select name="status" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        @foreach(['active','inactive'] as $s)<option value="{{ $s }}" @selected(old('status', $g->status ?? 'active') === $s)>{{ \App\Support\Bengali::label($s) }}</option>@endforeach
    </select>
</div>
