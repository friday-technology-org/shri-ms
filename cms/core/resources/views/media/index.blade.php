@extends('cms-core::layouts.admin')

@section('title', 'Media Library - Shri-ms')

@section('content')
<div x-data="mediaLibrary()" x-init="init()">

    {{-- Page Header --}}
    <div class="flex flex-col gap-y-2 mb-[36px]">
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100">Media Library</h2>
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="→">
            <span class="capitalize text-color-brands">Media Library</span>
        </div>
    </div>

    {{-- Upload Zone --}}
    <div
        id="upload-zone"
        @dragover.prevent="dragOver = true"
        @dragleave.prevent="dragOver = false"
        @drop.prevent="handleDrop($event)"
        :class="dragOver ? 'border-color-brands bg-purple-50 dark:bg-[#1e1b3a]' : 'border-[#E8EDF2] dark:border-[#313442]'"
        class="border-2 border-dashed rounded-2xl p-10 text-center mb-8 transition-all duration-300 cursor-pointer bg-neutral-bg dark:bg-dark-neutral-bg hover:border-color-brands"
        @click="$refs.fileInput.click()"
    >
        <input type="file" x-ref="fileInput" multiple class="hidden" @change="handleFileSelect($event)">
        <div class="flex flex-col items-center gap-3">
            <div class="w-16 h-16 rounded-full bg-purple-100 dark:bg-[#2a1f4e] flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-color-brands" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="font-semibold text-gray-1100 dark:text-gray-dark-1100 text-base">Drop files here or click to upload</p>
                <p class="text-sm text-gray-500 dark:text-gray-dark-500 mt-1">Images, Documents, Video, Audio — up to 50 MB</p>
            </div>
        </div>

        {{-- Upload Progress --}}
        <div x-show="uploading" x-transition class="mt-6 max-w-md mx-auto">
            <div class="flex justify-between mb-1">
                <span class="text-xs text-gray-500 dark:text-gray-dark-500" x-text="uploadStatus"></span>
                <span class="text-xs text-color-brands font-bold" x-text="uploadProgress + '%'"></span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-[#313442] rounded-full h-2">
                <div class="bg-color-brands h-2 rounded-full transition-all duration-300" :style="'width: ' + uploadProgress + '%'"></div>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        {{-- Filter Tabs --}}
        <div class="flex items-center gap-2 flex-wrap">
            @foreach(['all' => 'All', 'images' => 'Images', 'videos' => 'Videos', 'audio' => 'Audio', 'documents' => 'Documents'] as $key => $label)
            <a href="{{ request()->fullUrlWithQuery(['type' => $key === 'all' ? null : $key, 'page' => null]) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ (request('type', 'all') === $key || ($key === 'all' && !request('type'))) ? 'bg-color-brands text-white' : 'bg-neutral-bg dark:bg-dark-neutral-bg text-gray-500 dark:text-gray-dark-500 border border-[#E8EDF2] dark:border-[#313442] hover:border-color-brands' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- Right: Search + View Toggle --}}
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center">
                @if(request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] flex items-center pr-3">
                    <input type="text" name="search" placeholder="Search files..." value="{{ request('search') }}"
                        class="input bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-2 focus:outline-none pl-[13px] placeholder:text-gray-400 dark:placeholder:text-gray-dark-500 w-48">
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="flex items-center border border-[#E8EDF2] dark:border-[#313442] rounded-lg overflow-hidden">
                <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-color-brands text-white' : 'text-gray-500 dark:text-gray-dark-500'" class="p-2 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1 2.5A1.5 1.5 0 012.5 1h3A1.5 1.5 0 017 2.5v3A1.5 1.5 0 015.5 7h-3A1.5 1.5 0 011 5.5v-3zm8 0A1.5 1.5 0 0110.5 1h3A1.5 1.5 0 0115 2.5v3A1.5 1.5 0 0113.5 7h-3A1.5 1.5 0 019 5.5v-3zm-8 8A1.5 1.5 0 012.5 9h3A1.5 1.5 0 017 10.5v3A1.5 1.5 0 015.5 15h-3A1.5 1.5 0 011 13.5v-3zm8 0A1.5 1.5 0 0110.5 9h3A1.5 1.5 0 0115 10.5v3A1.5 1.5 0 0113.5 15h-3A1.5 1.5 0 019 13.5v-3z"/>
                    </svg>
                </button>
                <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-color-brands text-white' : 'text-gray-500 dark:text-gray-dark-500'" class="p-2 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 01.5-.5h10a.5.5 0 010 1H3a.5.5 0 01-.5-.5zm0-4a.5.5 0 01.5-.5h10a.5.5 0 010 1H3a.5.5 0 01-.5-.5zm0-4a.5.5 0 01.5-.5h10a.5.5 0 010 1H3a.5.5 0 01-.5-.5z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Media Count --}}
    <p class="text-sm text-gray-500 dark:text-gray-dark-500 mb-4">{{ $media->total() }} {{ Str::plural('item', $media->total()) }}</p>

    {{-- Grid View --}}
    <div x-show="viewMode === 'grid'" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 xl:grid-cols-12 gap-2 mb-8">
        @forelse($media as $item)
        <div class="group relative cursor-pointer rounded-lg overflow-hidden border border-[#E8EDF2] dark:border-[#313442] bg-neutral-bg dark:bg-dark-neutral-bg hover:border-color-brands transition-all duration-200 hover:shadow-md"
             @click="openDetail({{ $item->id }})">
            {{-- Thumbnail: fixed 80×80 square --}}
            <div class="w-full aspect-square overflow-hidden bg-gray-100 dark:bg-[#1f2130]">
                @if($item->isImage())
                    <img src="{{ $item->thumbnailUrl('thumbnail') }}" alt="{{ $item->alt_text ?? $item->original_filename }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @elseif($item->isVideo())
                    <div class="w-full h-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                @elseif($item->isAudio())
                    <div class="w-full h-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
            </div>
            {{-- Minimal filename label --}}
            <div class="px-1 py-1">
                <p class="text-[10px] text-gray-500 dark:text-gray-dark-500 truncate leading-tight">{{ Str::limit($item->original_filename, 12) }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-20">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-dark-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-gray-400 dark:text-gray-dark-500 text-lg font-medium">No media found</p>
            <p class="text-sm text-gray-400 dark:text-gray-dark-500 mt-1">Upload some files to get started</p>
        </div>
        @endforelse
    </div>

    {{-- List View --}}
    <div x-show="viewMode === 'list'" class="mb-8">
        <div class="border border-[#E8EDF2] dark:border-[#313442] rounded-2xl overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-neutral-bg dark:bg-dark-neutral-bg border-b border-[#E8EDF2] dark:border-[#313442]">
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-4 py-3 w-12"></th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-4 py-3">File</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-4 py-3 hidden sm:table-cell">Type</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-4 py-3 hidden md:table-cell">Size</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-4 py-3 hidden lg:table-cell">Uploaded By</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-4 py-3 hidden lg:table-cell">Date</th>
                        <th class="text-right text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E8EDF2] dark:divide-[#313442]">
                    @forelse($media as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-[#1f2130] transition-colors cursor-pointer" @click="openDetail({{ $item->id }})">
                        <td class="px-4 py-3">
                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 dark:bg-[#313442] flex-shrink-0">
                                @if($item->isImage())
                                    <img src="{{ $item->thumbnailUrl('thumbnail') }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-dark-1100 truncate max-w-[200px]">{{ $item->original_filename }}</p>
                            @if($item->alt_text)<p class="text-xs text-gray-400 truncate max-w-[200px]">{{ $item->alt_text }}</p>@endif
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            <span class="text-xs text-gray-500 dark:text-gray-dark-500 uppercase">{{ $item->extension }}</span>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <span class="text-xs text-gray-500 dark:text-gray-dark-500">{{ $item->humanSize() }}</span>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="text-xs text-gray-500 dark:text-gray-dark-500">{{ $item->uploader?->name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="text-xs text-gray-500 dark:text-gray-dark-500">{{ $item->created_at->format('M j, Y') }}</span>
                        </td>
                        <td class="px-4 py-3 text-right" @click.stop>
                            <button @click="deleteMedia({{ $item->id }}, '{{ addslashes($item->original_filename) }}')"
                                class="text-red-500 hover:text-red-700 text-xs font-medium transition-colors px-2 py-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center text-gray-400 dark:text-gray-dark-500">No media found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($media->hasPages())
    <div class="flex justify-center">
        {{ $media->links() }}
    </div>
    @endif

</div>

{{-- Detail Panel Modal --}}
<div x-data="mediaDetail()" id="media-detail-panel">

    {{-- Backdrop --}}
    <div x-show="open" x-transition:enter="transition duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="closePanel()"
         class="fixed inset-0 bg-black/50 z-40" style="display:none"></div>

    {{-- Slide-in Panel --}}
    <div x-show="open"
         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="fixed top-0 right-0 h-full w-full max-w-md bg-white dark:bg-dark-neutral-bg shadow-2xl z-50 flex flex-col overflow-hidden"
         style="display:none">

        {{-- Panel Header --}}
        <div class="flex items-center justify-between p-5 border-b border-[#E8EDF2] dark:border-[#313442]">
            <h3 class="font-bold text-gray-1100 dark:text-gray-dark-1100 text-base">Attachment Details</h3>
            <button @click="closePanel()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-[#313442] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Preview --}}
        <div class="p-5 border-b border-[#E8EDF2] dark:border-[#313442] bg-gray-50 dark:bg-[#1f2130] flex items-center justify-center min-h-[200px]">
            <template x-if="item && item.is_image">
                <img :src="item.url" :alt="item.alt_text" class="max-w-full max-h-56 object-contain rounded-lg">
            </template>
            <template x-if="item && item.is_video">
                <video :src="item.url" controls class="max-w-full max-h-56 rounded-lg"></video>
            </template>
            <template x-if="item && item.is_audio">
                <audio :src="item.url" controls class="w-full"></audio>
            </template>
            <template x-if="item && !item.is_image && !item.is_video && !item.is_audio">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto text-gray-300 dark:text-gray-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </template>
            <div x-show="!item" class="text-gray-400">Loading...</div>
        </div>

        {{-- Meta Info --}}
        <div x-show="item" class="p-5 border-b border-[#E8EDF2] dark:border-[#313442] space-y-1">
            <p class="text-xs text-gray-500 dark:text-gray-dark-500"><span class="font-medium text-gray-700 dark:text-gray-dark-1100">File:</span> <span x-text="item?.original_filename"></span></p>
            <p class="text-xs text-gray-500 dark:text-gray-dark-500"><span class="font-medium text-gray-700 dark:text-gray-dark-1100">Type:</span> <span x-text="item?.mime_type"></span></p>
            <p class="text-xs text-gray-500 dark:text-gray-dark-500"><span class="font-medium text-gray-700 dark:text-gray-dark-1100">Size:</span> <span x-text="item?.human_size"></span></p>
            <p class="text-xs text-gray-500 dark:text-gray-dark-500" x-show="item?.width"><span class="font-medium text-gray-700 dark:text-gray-dark-1100">Dimensions:</span> <span x-text="item?.width + ' × ' + item?.height + ' px'"></span></p>
            <p class="text-xs text-gray-500 dark:text-gray-dark-500"><span class="font-medium text-gray-700 dark:text-gray-dark-1100">Uploaded:</span> <span x-text="item?.created_at"></span></p>
        </div>

        {{-- Editable Fields --}}
        <div x-show="item" class="flex-1 overflow-y-auto p-5 space-y-4">
            <div>
                <p class="text-gray-1100 text-sm font-medium mb-[8px] dark:text-gray-dark-1100">Alt Text</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                    <input type="text" x-model="editForm.alt_text" placeholder="Describe this image for accessibility..."
                        class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-gray-400 dark:placeholder:text-gray-dark-500">
                </div>
            </div>
            <div>
                <p class="text-gray-1100 text-sm font-medium mb-[8px] dark:text-gray-dark-1100">Caption</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                    <input type="text" x-model="editForm.caption" placeholder="Optional caption..."
                        class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-gray-400 dark:placeholder:text-gray-dark-500">
                </div>
            </div>
            <div>
                <p class="text-gray-1100 text-sm font-medium mb-[8px] dark:text-gray-dark-1100">Description</p>
                <textarea x-model="editForm.description" placeholder="Optional description..." rows="3"
                    class="textarea w-full text-gray-800 dark:text-white resize-none rounded-lg bg-transparent border border-[#E8EDF2] dark:border-[#313442] p-3 text-sm focus:outline-none placeholder:text-gray-400 dark:placeholder:text-gray-dark-500"></textarea>
            </div>

            {{-- URL Copy --}}
            <div>
                <p class="text-gray-1100 text-sm font-medium mb-[8px] dark:text-gray-dark-1100">File URL</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] flex items-center pr-3">
                    <input type="text" :value="item?.url" readonly
                        class="input w-full bg-transparent text-xs text-gray-500 dark:text-gray-dark-500 h-fit min-h-fit py-3 focus:outline-none pl-[13px]">
                    <button @click="copyUrl()" title="Copy URL" class="text-color-brands hover:opacity-75 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Panel Footer Actions --}}
        <div x-show="item" class="p-5 border-t border-[#E8EDF2] dark:border-[#313442] flex gap-3">
            <button @click="saveChanges()" :disabled="saving"
                class="flex-1 btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg py-[10px] px-[16px] text-sm text-white disabled:opacity-60">
                <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
            </button>
            <button @click="deleteFromPanel()"
                class="btn normal-case h-fit min-h-fit border-4 border-red-100 dark:border-red-900 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 py-[10px] px-[16px] text-sm transition-all duration-200">
                Delete
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Alpine.js component for the media library grid/list/upload
function mediaLibrary() {
    return {
        viewMode: 'grid',
        dragOver: false,
        uploading: false,
        uploadProgress: 0,
        uploadStatus: '',

        init() {
            // Default view from localStorage
            this.viewMode = localStorage.getItem('cms_media_view') || 'grid';
            this.$watch('viewMode', v => localStorage.setItem('cms_media_view', v));
        },

        handleDrop(e) {
            this.dragOver = false;
            const files = e.dataTransfer.files;
            if (files.length) this.uploadFiles(files);
        },

        handleFileSelect(e) {
            const files = e.target.files;
            if (files.length) this.uploadFiles(files);
        },

        async uploadFiles(files) {
            this.uploading = true;
            this.uploadProgress = 0;
            this.uploadStatus = 'Uploading ' + files.length + ' file(s)...';

            const formData = new FormData();
            Array.from(files).forEach(f => formData.append('files[]', f));
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const xhr = new XMLHttpRequest();
                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        this.uploadProgress = Math.round((e.loaded / e.total) * 100);
                    }
                });

                await new Promise((resolve, reject) => {
                    xhr.onload = () => {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            this.uploadStatus = 'Upload complete!';
                            this.uploadProgress = 100;
                            setTimeout(() => {
                                window.location.reload();
                            }, 600);
                            resolve();
                        } else {
                            this.uploadStatus = 'Some uploads failed.';
                            console.error(data.errors);
                            setTimeout(() => { this.uploading = false; }, 2000);
                            reject();
                        }
                    };
                    xhr.onerror = reject;
                    xhr.open('POST', '{{ route('cms.media.upload') }}');
                    xhr.send(formData);
                });
            } catch (err) {
                this.uploadStatus = 'Upload failed.';
                setTimeout(() => { this.uploading = false; }, 2000);
            }
        },

        openDetail(id) {
            window.dispatchEvent(new CustomEvent('open-media-detail', { detail: { id } }));
        },

        async deleteMedia(id, filename) {
            if (!confirm('Delete "' + filename + '"? This cannot be undone.')) return;
            const res = await fetch('/admin/media/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) window.location.reload();
            else alert('Delete failed.');
        }
    };
}

// Alpine.js component for the detail panel
function mediaDetail() {
    return {
        open: false,
        item: null,
        editForm: { alt_text: '', caption: '', description: '' },
        saving: false,

        init() {
            window.addEventListener('open-media-detail', e => this.loadItem(e.detail.id));
        },

        async loadItem(id) {
            this.item = null;
            this.open = true;
            const res = await fetch('/admin/media/' + id, { headers: { 'Accept': 'application/json' } });
            this.item = await res.json();
            this.editForm = {
                alt_text: this.item.alt_text || '',
                caption: this.item.caption || '',
                description: this.item.description || '',
            };
        },

        closePanel() {
            this.open = false;
            this.item = null;
        },

        async saveChanges() {
            this.saving = true;
            const res = await fetch('/admin/media/' + this.item.id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.editForm)
            });
            const data = await res.json();
            this.saving = false;
            if (data.success) {
                this.item = data.media;
            }
        },

        async deleteFromPanel() {
            if (!confirm('Delete "' + this.item.original_filename + '"? This cannot be undone.')) return;
            const res = await fetch('/admin/media/' + this.item.id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) {
                this.closePanel();
                window.location.reload();
            }
        },

        copyUrl() {
            navigator.clipboard.writeText(this.item.url).then(() => {
                alert('URL copied to clipboard!');
            });
        }
    };
}
</script>
@endpush

@endsection
