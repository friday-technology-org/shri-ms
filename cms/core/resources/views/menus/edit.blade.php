@extends('cms-core::layouts.admin')

@section('title', 'Edit Menu - LaraCMS')

@section('content')
<div>
    <div class="flex flex-col gap-y-2 mb-[36px]">
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100">Edit Menu</h2>
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <a href="{{ route('cms.menus.index') }}" class="capitalize text-color-brands">Menus</a>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <span class="capitalize text-color-brands">{{ $menu->name }}</span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('cms.menus.update', $menu) }}" method="POST" id="menu-builder-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="items_json" id="items_json">

        <div class="flex items-end gap-4 mb-6 flex-wrap">
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Menu Name</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                    <input type="text" name="name" value="{{ $menu->name }}" required class="input bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] w-56">
                </div>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Location</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                    <select name="location" class="select bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px] w-48">
                        <option value="" class="bg-white dark:bg-dark-neutral-bg">— None —</option>
                        @foreach($locations as $key => $label)
                            <option value="{{ $key }}" class="bg-white dark:bg-dark-neutral-bg" {{ $menu->location === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-sm py-[10px] px-[20px]">Save Menu</button>
        </div>

        <div class="flex gap-5 flex-col">

            {{-- Left: Add items panel --}}
            <div class="w-full border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl overflow-hidden h-fit">
                <div class="flex border-b border-[#E8EDF2] dark:border-[#313442] overflow-x-auto">
                    <button type="button" class="menu-src-tab whitespace-nowrap py-3 px-4 text-sm font-medium border-b-2 border-color-brands text-color-brands" data-source="pages">Pages</button>
                    <button type="button" class="menu-src-tab whitespace-nowrap py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 dark:text-gray-dark-500" data-source="posts">Posts</button>
                    @foreach($postTypes as $postType)
                        <button type="button" class="menu-src-tab whitespace-nowrap py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 dark:text-gray-dark-500" data-source="cpt:{{ $postType->name }}">{{ $postType->plural_label }}</button>
                    @endforeach
                    @foreach($taxonomies as $taxonomy)
                        <button type="button" class="menu-src-tab whitespace-nowrap py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 dark:text-gray-dark-500" data-source="terms:{{ $taxonomy->id }}">{{ $taxonomy->name }}</button>
                    @endforeach
                    <button type="button" class="menu-src-tab whitespace-nowrap py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 dark:text-gray-dark-500" data-source="custom">Custom Link</button>
                </div>

                {{-- Source list (Pages/Posts/CPT/Taxonomy) --}}
                <div id="menu-source-list" class="p-4 max-h-96 overflow-y-auto">
                    <p class="text-xs text-gray-400 dark:text-gray-dark-500">Loading…</p>
                </div>

                {{-- Custom link form (hidden unless the Custom Link tab is active) --}}
                <div id="menu-custom-link-form" class="hidden p-4 flex flex-col gap-3">
                    <input type="text" id="custom-link-label" placeholder="Link Text" class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] bg-transparent text-sm text-gray-800 dark:text-white py-2 px-3 focus:outline-none">
                    <input type="text" id="custom-link-url" placeholder="https://example.com" class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] bg-transparent text-sm text-gray-800 dark:text-white py-2 px-3 focus:outline-none">
                    <button type="button" id="add-custom-link" class="btn normal-case h-fit min-h-fit self-start border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-xs py-[8px] px-[14px]">Add to Menu</button>
                </div>
            </div>

            {{-- Right: Menu structure --}}
            <div class="w-full border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] py-[25px]">
                <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px] mb-2">Menu Structure</p>
                <p class="text-xs text-gray-400 dark:text-gray-dark-500 mb-4">Drag items to reorder. Drag an item onto another to nest it as a sub-item.</p>

                <ul id="menu-tree" class="menu-tree-root flex flex-col gap-2 min-h-[80px]">
                    @foreach($tree as $node)
                        @include('cms-core::menus.partials.tree-item', ['item' => $node])
                    @endforeach
                </ul>
                <p id="menu-tree-empty" class="text-sm text-gray-400 dark:text-gray-dark-500 text-center py-6" style="{{ $tree->isEmpty() ? '' : 'display:none' }}">No items yet — add some from the left panel.</p>
            </div>
        </div>
    </form>
</div>

{{-- Template for a newly-added menu item (cloned by menu-builder.js) --}}
<template id="menu-item-template">
    @include('cms-core::menus.partials.tree-item-shell')
</template>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1/Sortable.min.js"></script>
@push('scripts')
<script>
    window.cmsMenuLookupUrl = '{{ route('cms.menus.item-lookup') }}';
</script>
<script src="{{ asset('assets/scripts/menu-builder.js') }}?v=1.0"></script>
@endpush
@endsection
