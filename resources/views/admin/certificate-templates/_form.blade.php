@php $t = $template ?? null; @endphp
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">নাম</label>
    <input type="text" name="name" value="{{ old('name', $t->name ?? '') }}" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">মডিউল</label>
    <select name="module" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
        @foreach(['company'=>'Company Registration','establishment'=>'Establishment License','device'=>'Device Registration','package'=>'Package Registration', 'final_package_approval'=>'Final Package Approval','mrp'=>'MRP Application'] as $val => $label)
            <option value="{{ $val }}" @selected(old('module', $t->module ?? '') === $val)>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">এইচটিএমএল কনটেন্ট</label>
    <p class="text-xs text-slate-400 mb-2">
        Placeholders: <code>@{{ '{{certificate_no}}' }}</code> <code>@{{ '{{issue_date}}' }}</code>
        <code>@{{ '{{expiry_date}}' }}</code> <code>@{{ '{{validity_period}}' }}</code>
        <code>@{{ '{{applicant_name}}' }}</code> <code>@{{ '{{entity_name}}' }}</code>
        <code>@{{ '{{organization_type_label}}' }}</code> <code>@{{ '{{address}}' }}</code>
        <code>@{{ '{{tin_no}}' }}</code> <code>@{{ '{{bin_no}}' }}</code> <code>@{{ '{{trade_license_no}}' }}</code>
        <code>@{{ '{{qr_code}}' }}</code> <code>@{{ '{{signature}}' }}</code> <code>@{{ '{{signed_by}}' }}</code>
        <code>@{{ '{{module_label}}' }}</code> <code>@{{ '{{org_name}}' }}</code>
        <code>@{{ '{{gov_name}}' }}</code> <code>@{{ '{{gov_emblem}}' }}</code>
    </p>
    <textarea name="html_content" rows="14" required
              class="w-full rounded-none border-2 border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none font-mono"
              placeholder="<div style='text-align:center;padding:40px;border:4px solid #1f4380'>
  <h1>নিবন্ধন সার্টিফিকেট</h1>
  <p>এই মর্মে সনদ দেওয়া যাচ্ছে যে <b>@{{entity_name}}</b> নিবন্ধিত হয়েছে।</p>
  <p>সার্টিফিকেট নং: @{{certificate_no}}</p>
  <p>ইস্যুর তারিখ: @{{issue_date}} &nbsp; মেয়াদ: @{{expiry_date}}</p>
  @{{qr_code}}
  <div>@{{signature}}<br>@{{signed_by}}</div>
</div>">{{ old('html_content', $t->html_content ?? '') }}</textarea>
</div>
@if($t)
<label class="flex items-center gap-2 text-sm text-slate-600">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300" @checked($t->is_active)>
    Active
</label>
@endif
