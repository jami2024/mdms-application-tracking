@php
    $c = $company ?? null;
    $divisionsJson = $divisions->map(fn($d) => [
        'id' => $d->id, 'name' => $d->name,
        'districts' => $d->districts->map(fn($dist) => [
            'id' => $dist->id, 'name' => $dist->name,
            'upazilas' => $dist->upazilas->map(fn($u) => ['id' => $u->id, 'name' => $u->name]),
        ]),
    ]);
@endphp

<div x-data="{
    divisions: {{ $divisionsJson->toJson() }},
    divisionId: {{ old('division_id', $c?->division_id ?? 'null') }},
    districtId: {{ old('district_id', $c?->district_id ?? 'null') }},
    upazilaId: {{ old('upazila_id', $c?->upazila_id ?? 'null') }},
    get districts() { return this.divisions.find(d => d.id == this.divisionId)?.districts ?? []; },
    get upazilas() { return this.districts.find(d => d.id == this.districtId)?.upazilas ?? []; },
    step: 1,
    previews: {},
    preview(field, event) {
        const file = event.target.files[0];
        if (!file) { delete this.previews[field]; return; }
        if (file.type.startsWith('image/')) {
            this.previews[field] = { type: 'image', url: URL.createObjectURL(file), name: file.name };
        } else {
            this.previews[field] = { type: 'file', name: file.name };
        }
    }
}">

    {{-- Step indicator --}}
    <div class="flex items-center mb-8">
        @foreach(['১' => 'আবেদনকারীর পরিচয়', '২' => 'প্রাতিষ্ঠানিক তথ্য', '৩' => 'আইনি অঙ্গীকারনামা'] as $num => $label)
        <div class="flex-1 flex flex-col items-center relative">
            @if(!$loop->first)
                <div class="absolute top-4 right-1/2 w-full h-0.5 bg-slate-200" :class="step > {{ $loop->iteration - 1 }} ? 'bg-emerald-500' : 'bg-slate-200'" style="right: 50%; width: 100%;"></div>
            @endif
            <button type="button" @click="step = {{ $loop->iteration }}"
                    class="relative z-10 h-8 w-8 rounded-full flex items-center justify-center text-xs font-semibold transition"
                    :class="step === {{ $loop->iteration }} ? 'bg-ink-900 text-white ring-4 ring-ink-900/15' : (step > {{ $loop->iteration }} ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 border-2 border-slate-200')">
                {{ $num }}
            </button>
            <p class="text-[11px] font-medium mt-2 text-center" :class="step === {{ $loop->iteration }} ? 'text-slate-800' : 'text-slate-400'">{{ $label }}</p>
        </div>
        @endforeach
    </div>

    {{-- Step 1: Applicant Identity --}}
    <div x-show="step === 1" x-cloak>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 rounded-none p-5 border border-slate-100">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">আবেদনকারীর ধরন</label>
                <select name="applicant_type" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 outline-none hover:border-slate-400 shadow-sm transition">
                    <option value="">— নির্বাচন করুন —</option>
                    @foreach(['corporate'=>'করপোরেট','direct_importer'=>'সরাসরি আমদানিকারক','local_agent'=>'স্থানীয় প্রতিনিধি','foreign_enterprise'=>'বিদেশি প্রতিষ্ঠান'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('applicant_type', $c?->applicant_type ?? '') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">সম্বোধন</label>
                    <select name="name_prefix" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 outline-none hover:border-slate-400 shadow-sm transition">
                        <option value="">—</option>
                        @foreach(['mr'=>'জনাব','ms'=>'জনাবা','dr'=>'ডাঃ'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('name_prefix', $c?->name_prefix ?? '') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">পূর্ণ নাম</label>
                    <input type="text" name="applicant_full_name" value="{{ old('applicant_full_name', $c?->applicant_full_name ?? '') }}"
                           class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">মোবাইল নম্বর</label>
                <div class="flex items-center gap-2">
                    <input type="text" name="mobile_number" value="{{ old('mobile_number', $c?->mobile_number ?? '') }}" placeholder="01XXXXXXXXX"
                           class="flex-1 rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                    @if($c?->mobile_verified_at)
                        <span class="shrink-0 text-xs px-2.5 py-1 rounded-none bg-emerald-50 text-emerald-700 font-medium">✓ যাচাইকৃত</span>
                    @endif
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">প্রাথমিক ইমেইল</label>
                <div class="flex items-center gap-2">
                    <input type="email" name="primary_email" value="{{ old('primary_email', $c?->primary_email ?? '') }}"
                           class="flex-1 rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                    @if($c?->email_verified_at)
                        <span class="shrink-0 text-xs px-2.5 py-1 rounded-none bg-emerald-50 text-emerald-700 font-medium">✓ যাচাইকৃত</span>
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">জাতীয় পরিচয়পত্র নং (এনআইডি)</label>
                <input type="text" name="national_id" value="{{ old('national_id', $c?->national_id ?? '') }}"
                       class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">জন্ম তারিখ</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($c?->date_of_birth)->format('Y-m-d')) }}"
                       class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">লিঙ্গ</label>
                <select name="gender" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 outline-none hover:border-slate-400 shadow-sm transition">
                    <option value="">— নির্বাচন করুন —</option>
                    @foreach(['male'=>'পুরুষ','female'=>'নারী','other'=>'অন্যান্য'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('gender', $c?->gender ?? '') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">জাতীয়তা</label>
                <input type="text" name="nationality" value="{{ old('nationality', $c?->nationality ?? 'Bangladesh') }}"
                       class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">পদবি (প্রতিষ্ঠানে)</label>
                <input type="text" name="applicant_designation" value="{{ old('applicant_designation', $c?->applicant_designation ?? '') }}"
                       class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">এনআইডি ছবি</label>
                <input type="file" name="nid_photo" accept="image/*" @change="preview('nid_photo', $event)"
                       class="w-full text-sm file:mr-3 file:py-2 file:px-3 file:rounded-none file:border-0 file:bg-slate-100 file:text-slate-600 file:text-xs">
                <template x-if="previews.nid_photo">
                    <img :src="previews.nid_photo.url" class="mt-2 h-20 w-20 object-cover rounded-none border border-slate-200">
                </template>
                @if($c?->nid_photo)
                    <div class="mt-2" x-show="!previews.nid_photo">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($c->nid_photo) }}" class="h-20 w-20 object-cover rounded-none border border-slate-200">
                        <p class="text-xs text-emerald-600 mt-1">✓ আপলোড করা আছে</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="flex justify-end mt-4">
            <button type="button" @click="step = 2" class="px-6 py-2.5 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition flex items-center gap-2">
                পরবর্তী ধাপ
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
            </button>
        </div>
    </div>

    {{-- Step 2: Statutory Business Credentials --}}
    <div x-show="step === 2" x-cloak>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 rounded-none p-5 border border-slate-100">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">প্রতিষ্ঠানের নাম</label>
                <input type="text" name="name" value="{{ old('name', $c?->name ?? '') }}" required
                       class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">প্রতিষ্ঠানের ধরন</label>
                <select name="organization_type" class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/40 outline-none hover:border-slate-400 shadow-sm transition">
                    <option value="">— নির্বাচন করুন —</option>
                    @foreach(['private_limited'=>'প্রাইভেট লিমিটেড','public_ltd'=>'পাবলিক লিমিটেড','proprietorship'=>'একক মালিকানা','partnership'=>'অংশীদারি','hospital_institute'=>'হাসপাতাল/প্রতিষ্ঠান'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('organization_type', $c?->organization_type ?? '') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">কর্পোরেট যোগাযোগ নং</label>
                <input type="text" name="corporate_contact" value="{{ old('corporate_contact', $c?->corporate_contact ?? '') }}"
                       class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">ঠিকানা (লাইন ১)</label>
                <input type="text" name="address_line_1" value="{{ old('address_line_1', $c?->address_line_1 ?? '') }}"
                       class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">ঠিকানা (লাইন ২)</label>
                <input type="text" name="address_line_2" value="{{ old('address_line_2', $c?->address_line_2 ?? '') }}"
                       class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">বিভাগ</label>
                <select name="division_id" x-model.number="divisionId" @change="districtId = null; upazilaId = null"
                        class="w-full rounded-none border-2 border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">— নির্বাচন করুন —</option>
                    <template x-for="d in divisions" :key="d.id">
                        <option :value="d.id" x-text="d.name" :selected="d.id == divisionId"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">জেলা</label>
                <select name="district_id" x-model.number="districtId" @change="upazilaId = null" :disabled="!divisionId"
                        class="w-full rounded-none border-2 border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none disabled:bg-slate-100">
                    <option value="">— নির্বাচন করুন —</option>
                    <template x-for="d in districts" :key="d.id">
                        <option :value="d.id" x-text="d.name" :selected="d.id == districtId"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">উপজেলা</label>
                <select name="upazila_id" x-model.number="upazilaId" :disabled="!districtId"
                        class="w-full rounded-none border-2 border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none disabled:bg-slate-100">
                    <option value="">— নির্বাচন করুন —</option>
                    <template x-for="u in upazilas" :key="u.id">
                        <option :value="u.id" x-text="u.name" :selected="u.id == upazilaId"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">পোস্ট কোড</label>
                <input type="text" name="post_code" value="{{ old('post_code', $c?->post_code ?? '') }}"
                       class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">ফ্যাক্স নম্বর</label>
                <input type="text" name="fax_number" value="{{ old('fax_number', $c?->fax_number ?? '') }}"
                       class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">যোগাযোগকারী</label>
                <input type="text" name="contact_person" value="{{ old('contact_person', $c?->contact_person ?? '') }}"
                       class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">যোগাযোগের ফোন</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $c?->contact_phone ?? '') }}"
                       class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            {{-- Business documents --}}
            <div class="sm:col-span-2 border-t border-slate-200 pt-4 mt-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">ব্যবসায়িক ডকুমেন্ট</p>
            </div>

            @foreach([
                ['no' => 'trade_license_no', 'file' => 'trade_license_file', 'label' => 'ট্রেড লাইসেন্স নং'],
                ['no' => 'tin_no', 'file' => 'tin_file', 'label' => 'টিন নং'],
                ['no' => 'bin_no', 'file' => 'bin_file', 'label' => 'বিন নং'],
                ['no' => 'rjsc_registration_number', 'file' => 'rjsc_file', 'label' => 'আরজেএসসি নিবন্ধন নং'],
                ['no' => 'irc_number', 'file' => 'irc_file', 'label' => 'আইআরসি নং'],
            ] as $doc)
            <div class="bg-white rounded-none border border-slate-200 p-3">
                <label class="block text-xs font-medium text-slate-500 mb-1">{{ $doc['label'] }}</label>
                <input type="text" name="{{ $doc['no'] }}" value="{{ old($doc['no'], $c?->{$doc['no']} ?? '') }}"
                       class="w-full rounded-none border border-slate-300 px-3 py-2 text-sm mb-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                <input type="file" name="{{ $doc['file'] }}" accept="application/pdf,image/*" @change="preview('{{ $doc['file'] }}', $event)"
                       class="w-full text-xs file:mr-2 file:py-1.5 file:px-2.5 file:rounded-none file:border-0 file:bg-slate-100 file:text-slate-600 file:text-xs">
                <template x-if="previews.{{ $doc['file'] }}?.type === 'image'">
                    <img :src="previews.{{ $doc['file'] }}.url" class="mt-2 h-16 w-16 object-cover rounded-none border border-slate-200">
                </template>
                <template x-if="previews.{{ $doc['file'] }}?.type === 'file'">
                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span x-text="previews.{{ $doc['file'] }}.name"></span>
                    </p>
                </template>
                @if($c?->{$doc['file']})<p class="text-xs text-emerald-600 mt-1" x-show="!previews.{{ $doc['file'] }}">✓ আপলোড করা আছে</p>@endif
            </div>
            @endforeach
        </div>
        <div class="flex justify-between mt-4">
            <button type="button" @click="step = 1" class="px-6 py-2.5 rounded-none border border-slate-300 bg-white text-sm hover:bg-slate-50 transition">← পূর্ববর্তী</button>
            <button type="button" @click="step = 3" class="px-6 py-2.5 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition flex items-center gap-2">
                পরবর্তী ধাপ
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
            </button>
        </div>
    </div>

    {{-- Step 3: Legal Undertaking --}}
    <div x-show="step === 3" x-cloak>
        <div class="bg-slate-50 rounded-none p-5 border border-slate-100">
            <label class="block text-sm font-medium text-slate-700 mb-1.5">স্বাক্ষরিত অঙ্গীকারনামা (পিডিএফ/ছবি)</label>
            <input type="file" name="signed_declaration_file" accept="application/pdf,image/*" @change="preview('signed_declaration_file', $event)"
                   class="w-full text-sm file:mr-3 file:py-2 file:px-3 file:rounded-none file:border-0 file:bg-slate-100 file:text-slate-600 file:text-xs">
            <template x-if="previews.signed_declaration_file?.type === 'image'">
                <img :src="previews.signed_declaration_file.url" class="mt-3 h-28 w-28 object-cover rounded-none border border-slate-200">
            </template>
            <template x-if="previews.signed_declaration_file?.type === 'file'">
                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span x-text="previews.signed_declaration_file.name"></span>
                </p>
            </template>
            @if($c?->signed_declaration_file)
                <p class="text-xs text-emerald-600 mt-2" x-show="!previews.signed_declaration_file">✓ আপলোড করা আছে — {{ $c->declaration_signed_at?->format('d M, Y') }} তারিখে স্বাক্ষরিত</p>
            @endif
        </div>
        <div class="flex justify-between mt-4">
            <button type="button" @click="step = 2" class="px-6 py-2.5 rounded-none border border-slate-300 bg-white text-sm hover:bg-slate-50 transition">← পূর্ববর্তী</button>
            <button type="submit" class="px-6 py-2.5 rounded-none bg-emerald-600 text-white text-sm hover:bg-emerald-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                সংরক্ষণ করুন
            </button>
        </div>
    </div>
</div>
