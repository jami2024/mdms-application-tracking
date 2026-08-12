<div style="margin-left: {{ $depth * 24 }}px" class="{{ $depth > 0 ? 'border-l border-slate-200 pl-4 mt-2' : 'mt-2' }}">
    <div class="flex items-center justify-between bg-slate-50 rounded-none px-4 py-2.5">
        <div class="flex items-center gap-3">
            <span class="h-8 w-8 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-semibold">
                {{ $node->designation->short_code ?? '?' }}
            </span>
            <div>
                <p class="text-sm font-medium text-slate-800">{{ $node->designation->title ?? 'অনির্ধারিত' }}</p>
                <p class="text-xs text-slate-500">{{ $node->incumbent->name ?? 'Vacant' }} · {{ $node->organization->name ?? '' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.organogram.destroy', $node) }}" onsubmit="return confirm('Remove this position?')">
            @csrf @method('DELETE')
            <button class="text-xs text-red-500 hover:underline">অপসারণ করুন</button>
        </form>
    </div>

    @foreach($node->children as $child)
        @include('admin.organogram._node', ['node' => $child, 'depth' => $depth + 1])
    @endforeach
</div>
