@php $u = $user ?? null; @endphp


{{-- Profile Photo --}}
<div class="mb-2">
    <label class="block text-sm font-medium text-slate-700 mb-2">প্রোফাইল ছবি</label>
    <div class="flex items-center gap-4">
        <div class="relative">
            <div id="avatar-preview-wrap"
                class="h-16 w-16 rounded-full overflow-hidden bg-brand-100 text-brand-700 flex items-center justify-center text-xl font-semibold">
                @if ($u?->profile_photo_path)
                    <img id="avatar-preview" src="{{ asset('storage/' . $u->profile_photo_path) }}"
                        alt="{{ $u->name }}" class="h-full w-full object-cover">
                @else
                    <span id="avatar-initial">{{ strtoupper(substr($u->name ?? 'U', 0, 1)) }}</span>
                    <img id="avatar-preview" src="" alt="" class="h-full w-full object-cover hidden">
                @endif
            </div>

            <label for="profile_photo"
                class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-ink-900 text-white flex items-center justify-center cursor-pointer hover:bg-slate-800 transition">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </label>
            <input type="file" name="profile_photo" id="profile_photo" accept="image/png,image/jpeg,image/webp"
                class="hidden">
        </div>

        <div>
            @if ($u?->profile_photo_path)
                <label class="inline-flex items-center gap-2 text-xs text-slate-500 cursor-pointer">
                    <input type="checkbox" name="remove_photo" id="remove_photo" value="1"
                        class="rounded-none border-slate-300">
                    বর্তমান ছবি সরান
                </label>
            @endif
            <p id="photo-error" class="text-xs text-red-500 mt-1 hidden"></p>
        </div>
    </div>
    @error('profile_photo')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">নাম</label>
        <input type="text" name="name" value="{{ old('name', $u->name ?? '') }}" required
            class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        @error('name')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">ইমেইল</label>
        <input type="email" name="email" value="{{ old('email', $u->email ?? '') }}" required
            class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        @error('email')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">ফোন</label>
        <input type="text" name="phone" value="{{ old('phone', $u->phone ?? '') }}"
            class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
    </div>

    @unless ($u)
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">পাসওয়ার্ড</label>
            <input type="password" name="password" required minlength="8"
                class="w-full rounded-none border-2 border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        </div>
    @endunless

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">ব্যবহারকারীর ধরন</label>
        <select name="user_type"
            class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            @foreach (['admin', 'staff', 'applicant'] as $t)
                <option value="{{ $t }}" @selected(old('user_type', $u->user_type ?? '') === $t)>{{ \App\Support\Bengali::label($t) }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">স্ট্যাটাস</label>
        <select name="status"
            class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            @foreach (['active', 'inactive', 'suspended', 'pending'] as $s)
                <option value="{{ $s }}" @selected(old('status', $u->status ?? 'pending') === $s)>{{ \App\Support\Bengali::label($s) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">পদবি</label>
        <select name="designation_id"
            class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            <option value="">— কোনোটি না —</option>
            @foreach ($designations as $d)
                <option value="{{ $d->id }}" @selected(old('designation_id', $u->designation_id ?? '') == $d->id)>{{ $d->title }}
                    ({{ $d->short_code }})
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">সংস্থা</label>
        <select name="organization_id"
            class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            <option value="">— কোনোটি না —</option>
            @foreach ($organizations as $o)
                <option value="{{ $o->id }}" @selected(old('organization_id', $u->organization_id ?? '') == $o->id)>{{ $o->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">ভূমিকা</label>
        <select name="role" required
            class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 outline-none hover:border-slate-400 shadow-sm transition">
            @foreach ($roles as $r)
                <option value="{{ $r }}" @selected(old('role', $u?->roles->first()?->name ?? '') === $r)>{{ $r }}</option>
            @endforeach
        </select>
    </div>
</div>

<script>
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const errorEl = document.getElementById('photo-error');
        errorEl.classList.add('hidden');
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            errorEl.textContent = 'Image must be under 2MB.';
            errorEl.classList.remove('hidden');
            e.target.value = '';
            return;
        }

        const img = document.getElementById('avatar-preview');
        const initial = document.getElementById('avatar-initial');
        const reader = new FileReader();
        reader.onload = ev => {
            img.src = ev.target.result;
            img.classList.remove('hidden');
            if (initial) initial.classList.add('hidden');
        };
        reader.readAsDataURL(file);

        // If a new photo is chosen, uncheck "remove photo" so they don't fight each other
        const removeCheckbox = document.getElementById('remove_photo');
        if (removeCheckbox) removeCheckbox.checked = false;
    });
</script>