@extends('layouts.admin')
@section('title', 'আমার প্রোফাইল')

@section('content')
<div class="max-w-xl">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">আমার প্রোফাইল</h2>

    @if(session('status'))<div class="mb-4 rounded-none bg-emerald-50 text-emerald-700 text-sm px-5 py-3.5">{{ session('status') }}</div>@endif

    @if ($errors->any())
        <div class="mb-4 rounded-none bg-red-50 text-red-700 text-sm px-5 py-3.5">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
          class="bg-white rounded-none border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf @method('PUT')

        {{-- Avatar + Photo Upload --}}
        <div class="flex items-center gap-4 mb-2">
            <div class="relative">
                <div id="avatar-preview-wrap" class="h-16 w-16 rounded-full overflow-hidden bg-brand-100 text-brand-700 flex items-center justify-center text-xl font-semibold">
                    @if ($user->profile_photo_path)
                        <img id="avatar-preview" src="{{ asset('storage/' . $user->profile_photo_path) }}"
                             alt="{{ $user->name }}" class="h-full w-full object-cover">
                    @else
                        <span id="avatar-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        <img id="avatar-preview" src="" alt="{{ $user->name }}" class="h-full w-full object-cover hidden">
                    @endif
                </div>

                <label for="profile_photo"
                       class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-ink-900 text-white flex items-center justify-center cursor-pointer hover:bg-slate-800 transition">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </label>
                <input type="file" name="profile_photo" id="profile_photo" accept="image/png,image/jpeg,image/webp" class="hidden">
            </div>

            <div>
                <p class="text-sm font-medium text-slate-800">{{ $user->name }}</p>
                <p class="text-xs text-slate-400">{{ $user->roles->pluck('name')->join(', ') }} · {{ $user->designation?->title }}</p>
                <p id="photo-error" class="text-xs text-red-500 mt-1 hidden"></p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">নাম</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">ইমেইল</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">ফোন</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                   class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        </div>

        {{-- Designation --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">পদবি</label>
            <select name="designation_id"
                    class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                <option value="">-- নির্বাচন করুন --</option>
                @foreach ($designations as $designation)
                    <option value="{{ $designation->id }}"
                        {{ (int) old('designation_id', $user->designation_id) === $designation->id ? 'selected' : '' }}>
                        {{ $designation->title }} @if($designation->short_code) ({{ $designation->short_code }}) @endif
                    </option>
                @endforeach
            </select>
        </div>

        <button class="px-4 py-2 rounded-none bg-ink-900 text-white text-sm hover:bg-slate-800 transition">সংরক্ষণ</button>
    </form>

    <div class="mt-4 bg-white rounded-none border border-slate-200 shadow-sm p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-slate-800">দুই-স্তর যাচাইকরণ</p>
            <p class="text-xs text-slate-500">{{ $user->two_factor_confirmed_at ? 'সক্রিয়' : 'সক্রিয় করা হয়নি' }}</p>
        </div>
        <a href="{{ url('/user/two-factor-authentication') }}" class="text-sm px-4 py-2 rounded-none border border-slate-300 bg-white hover:bg-slate-50 transition">ব্যবস্থাপনা</a>
    </div>
</div>

<script>
    document.getElementById('profile_photo').addEventListener('change', function (e) {
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

        reader.onload = function (ev) {
            img.src = ev.target.result;
            img.classList.remove('hidden');
            if (initial) initial.classList.add('hidden');
        };

        reader.readAsDataURL(file);
    });
</script>
@endsection