<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">ভূমিকার নাম</label>
    <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}" required
           class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">বিবরণ</label>
    <input type="text" name="description" value="{{ old('description', $role->description ?? '') }}"
           class="w-full rounded-none border border-slate-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-2">অনুমতি</label>
    <div class="space-y-4">
        @foreach($permissions as $group => $items)
        <div class="border border-slate-200 rounded-none p-3">
            <p class="text-xs font-semibold uppercase text-slate-400 mb-2">{{ $group }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach($items as $permission)
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                           class="rounded border-slate-300"
                           @checked(in_array($permission->name, old('permissions', $assigned ?? [])))>
                    {{ $permission->name }}
                </label>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
