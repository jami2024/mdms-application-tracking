@php $e = $establishment ?? null; @endphp
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">নাম</label>
    <input type="text" name="name" value="{{ old('name', $e->name ?? '') }}" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">লাইসেন্স নং</label>
    <input type="text" name="license_no" value="{{ old('license_no', $e->license_no ?? '') }}" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">ঠিকানা</label>
    <textarea name="address" rows="2" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">{{ old('address', $e->address ?? '') }}</textarea>
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">লাইসেন্স ইস্যুর তারিখ</label>
        <input type="date" name="license_issue_date" value="{{ old('license_issue_date', optional($e->license_issue_date ?? null)->format('Y-m-d')) }}" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">লাইসেন্সের মেয়াদ শেষের তারিখ</label>
        <input type="date" name="license_expiry_date" value="{{ old('license_expiry_date', optional($e->license_expiry_date ?? null)->format('Y-m-d')) }}" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
    </div>
</div>
