@extends('cms-core::layouts.admin')

@section('title', 'Widgets - LaraCMS')

@section('content')
<div>
    <div class="flex flex-col gap-y-2 mb-[36px]">
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100">Widgets</h2>
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <span class="capitalize text-color-brands">Appearance</span>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <span class="capitalize text-color-brands">Widgets</span>
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

    {{-- Add Widget --}}
    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] py-[25px] mb-9" x-data="{ type: 'recent_posts' }">
        <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px] mb-5">Add a Widget</p>
        <form action="{{ route('cms.widgets.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Widget Area</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                        <select name="area_key" required class="select w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            @foreach($areas as $area)
                                <option value="{{ $area->key }}" class="bg-white dark:bg-dark-neutral-bg">{{ $area->label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Widget Type</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                        <select name="type" x-model="type" required class="select w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" class="bg-white dark:bg-dark-neutral-bg">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Title (optional)</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                        <input type="text" name="title" class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px]">
                    </div>
                </div>
            </div>

            @foreach(array_keys($types) as $typeKey)
                <div x-show="type === '{{ $typeKey }}'" x-cloak>
                    @include('cms-core::widgets.settings.' . str_replace('_', '-', $typeKey), ['settings' => [], 'menus' => $menus])
                </div>
            @endforeach

            <button type="submit" class="btn normal-case h-fit min-h-fit self-start border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-sm py-[9px] px-[16px]">Add Widget</button>
        </form>
    </div>

    {{-- Save layout (reorder / move between areas) --}}
    <form action="{{ route('cms.widgets.reorder') }}" method="POST" id="widgets-reorder-form" class="mb-4">
        @csrf
        <input type="hidden" name="items_json" id="widgets_items_json">
        <button type="submit" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-sm py-[9px] px-[16px]">Save Layout</button>
        <span class="text-xs text-gray-400 dark:text-gray-dark-500 ml-2">Drag widgets between areas or to reorder, then click Save Layout.</span>
    </form>

    {{-- Widget Areas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($areas as $area)
        <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl overflow-hidden">
            <div class="bg-neutral py-[12px] pl-[18px] dark:bg-dark-neutral-border">
                <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[13px]">{{ $area->label }}</p>
            </div>
            <ul class="widget-area-list p-3 flex flex-col gap-3 min-h-[80px]" data-area-key="{{ $area->key }}">
                @foreach($area->widgets as $widget)
                <li class="widget-card border border-[#E8EDF2] dark:border-[#313442] rounded-lg bg-white dark:bg-[#1f2130]" data-widget-id="{{ $widget->id }}" x-data="{ open: false }">
                    <div class="flex items-center gap-2 px-3 py-2">
                        <span class="drag-handle cursor-grab select-none text-gray-300 dark:text-gray-dark-500">&#9776;</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-dark-1100 truncate">{{ $widget->title ?: $types[$widget->type] ?? $widget->type }}</p>
                            <p class="text-[10px] uppercase text-gray-400 dark:text-gray-dark-500">{{ $types[$widget->type] ?? $widget->type }}</p>
                        </div>
                        <button type="button" @click="open = !open" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-dark-1100 text-xs px-1">&#9998;</button>
                        <form action="{{ route('cms.widgets.destroy', $widget) }}" method="POST" onsubmit="return confirm('Remove this widget?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs px-1">&#10005;</button>
                        </form>
                    </div>
                    <div x-show="open" x-cloak class="px-3 pb-3 border-t border-[#E8EDF2] dark:border-[#313442] pt-3">
                        <form action="{{ route('cms.widgets.update', $widget) }}" method="POST" class="flex flex-col gap-3">
                            @csrf
                            @method('PUT')
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-1">Title</p>
                                <input type="text" name="title" value="{{ $widget->title }}" class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] bg-transparent text-sm text-gray-800 dark:text-white py-2 px-3 w-full focus:outline-none">
                            </div>
                            @include('cms-core::widgets.settings.' . str_replace('_', '-', $widget->type), ['settings' => $widget->settings ?? [], 'menus' => $menus])
                            <button type="submit" class="btn normal-case h-fit min-h-fit self-start border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-xs py-[6px] px-[12px]">Save Widget</button>
                        </form>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1/Sortable.min.js"></script>
@push('scripts')
<script src="{{ asset('assets/scripts/widgets-builder.js') }}?v=1.0"></script>
@endpush
@endsection
