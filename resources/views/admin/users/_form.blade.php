@php $u = $user ?? null; @endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">নাম</label>
        <input type="text" name="name" value="{{ old('name', $u->name ?? '') }}" required
               class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">ইমেইল</label>
        <input type="email" name="email" value="{{ old('email', $u->email ?? '') }}" required
               class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">ফোন</label>
        <input type="text" name="phone" value="{{ old('phone', $u->phone ?? '') }}"
               class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
    </div>

    @unless($u)
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">পাসওয়ার্ড</label>
        <input type="password" name="password" required minlength="8"
               class="w-full rounded-none border-2 border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
    </div>
    @endunless

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">ব্যবহারকারীর ধরন</label>
        <select name="user_type" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            @foreach(['admin','staff','applicant'] as $t)
                <option value="{{ $t }}" @selected(old('user_type', $u->user_type ?? '') === $t)>{{ \App\Support\Bengali::label($t) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">স্ট্যাটাস</label>
        <select name="status" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            @foreach(['active','inactive','suspended','pending'] as $s)
                <option value="{{ $s }}" @selected(old('status', $u->status ?? 'pending') === $s)>{{ \App\Support\Bengali::label($s) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">পদবি</label>
        <select name="designation_id" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            <option value="">— কোনোটি না —</option>
            @foreach($designations as $d)
                <option value="{{ $d->id }}" @selected(old('designation_id', $u->designation_id ?? '') == $d->id)>{{ $d->title }} ({{ $d->short_code }})</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">সংস্থা</label>
        <select name="organization_id" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            <option value="">— কোনোটি না —</option>
            @foreach($organizations as $o)
                <option value="{{ $o->id }}" @selected(old('organization_id', $u->organization_id ?? '') == $o->id)>{{ $o->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">ভূমিকা</label>
        <select name="role" required class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            @foreach($roles as $r)
                <option value="{{ $r }}" @selected(old('role', $u?->roles->first()?->name ?? '') === $r)>{{ $r }}</option>
            @endforeach
        </select>
    </div>
</div>
