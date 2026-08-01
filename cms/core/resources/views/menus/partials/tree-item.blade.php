<li class="menu-item-node" data-type="{{ $item->type }}" data-object-id="{{ $item->object_id }}">
    <div class="menu-item-row flex items-center gap-2 bg-white dark:bg-[#1f2130] border border-[#E8EDF2] dark:border-[#313442] rounded-lg px-3 py-2">
        <span class="drag-handle cursor-grab select-none text-gray-300 dark:text-gray-dark-500" title="Drag to reorder">⠿⠿</span>
        <input type="text" class="mi-label flex-1 bg-transparent text-sm text-gray-800 dark:text-white focus:outline-none" value="{{ $item->label }}">
        <span class="text-[10px] uppercase text-gray-400 dark:text-gray-dark-500">{{ $item->type }}</span>
        <button type="button" class="mi-toggle-settings text-gray-400 hover:text-gray-700 dark:hover:text-gray-dark-1100 text-xs px-1">&#9881;</button>
        <button type="button" class="mi-remove text-red-500 hover:text-red-700 text-xs px-1">&#10005;</button>
    </div>
    <div class="mi-settings hidden mt-2 ml-7 grid grid-cols-2 gap-2 text-xs bg-gray-50 dark:bg-[#151722] rounded-lg p-3">
        <input type="text" class="mi-url col-span-2 border rounded px-2 py-1 bg-transparent dark:border-[#313442] {{ $item->type !== 'custom' ? 'hidden' : '' }}" placeholder="URL" value="{{ $item->url }}">
        <input type="text" class="mi-css-class border rounded px-2 py-1 bg-transparent dark:border-[#313442]" placeholder="CSS Class" value="{{ $item->css_class }}">
        <label class="flex items-center gap-1"><input type="checkbox" class="mi-target-blank" {{ $item->target_blank ? 'checked' : '' }}> Open in new tab</label>
        <label class="flex items-center gap-1"><input type="checkbox" class="mi-rel-nofollow" {{ $item->rel_nofollow ? 'checked' : '' }}> rel=nofollow</label>
    </div>
    <ul class="mi-children nested-sortable pl-7 mt-2 flex flex-col gap-2">
        @foreach($item->children as $child)
            @include('cms-core::menus.partials.tree-item', ['item' => $child])
        @endforeach
    </ul>
</li>
