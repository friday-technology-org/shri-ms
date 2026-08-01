@extends('cms-core::layouts.admin')

@section('title', 'Themes - LaraCMS')

@section('content')
<div>
    <div class="flex flex-col gap-y-2 mb-[36px]">
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100">Themes</h2>
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <span class="capitalize text-color-brands">Appearance</span>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <span class="capitalize text-color-brands">Themes</span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Upload a theme (styled like plugin upload) --}}
    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl p-6">
        <div class="max-w-xl mx-auto text-center py-4">
            <h3 class="text-gray-1100 text-lg font-bold mb-2 dark:text-white">Upload & Install Theme</h3>
            <p class="text-xs text-gray-500 dark:text-gray-500 mb-6">If you have a theme in a .zip format, you can install it by uploading it here.</p>
            <form action="{{ route('cms.themes.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center">
                @csrf
                <div class="w-full border-2 border-dashed border-[#E8EDF2] dark:border-[#313442] hover:border-color-brands dark:hover:border-color-brands rounded-xl p-8 mb-4 transition-colors cursor-pointer relative group">
                    <input type="file" name="theme_zip" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="themeZipFile">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-[#1f2130] flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-color-brands" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <div class="text-sm font-semibold text-gray-700 dark:text-gray-dark-1100" id="themeFileNameDisplay">Choose a file or drag it here</div>
                        <div class="text-[11px] text-gray-400 dark:text-gray-500">ZIP archive only (max 10MB)</div>
                    </div>
                </div>
                <button type="submit" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-sm py-[10px] px-[24px] rounded-xl font-semibold shadow-md">Install Now</button>
            </form>
        </div>
    </div>

    {{-- Installed themes --}}
    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl p-6 mt-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-gray-1100 text-lg font-bold dark:text-white">Installed Themes</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($themes as $theme)
            <div class="border-2 bg-neutral-bg dark:bg-dark-neutral-bg rounded-2xl overflow-hidden {{ $theme->is_active ? 'border-color-brands' : 'border-neutral dark:border-dark-neutral-border' }}">
                <div class="w-full aspect-square bg-gray-100 dark:bg-[#1f2130] overflow-hidden">
                    @if($theme->screenshotUrl())
                        <img src="{{ $theme->screenshotUrl() }}" alt="{{ $theme->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300 dark:text-gray-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="font-semibold text-gray-1100 dark:text-gray-dark-1100 text-[15px]">{{ $theme->name }}</h3>
                        @if($theme->is_active)
                            <span class="text-[10px] uppercase font-bold text-color-brands">Active</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-dark-500 mb-4">v{{ $theme->version }} @if($theme->author) &middot; {{ $theme->author }} @endif</p>

                    <div class="flex flex-wrap gap-2">
                        @unless($theme->is_active)
                        <form action="{{ route('cms.themes.activate', $theme->slug) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-xs py-[6px] px-[12px] transition-all">Activate</button>
                        </form>
                        @endunless
                        <a href="{{ route('cms.themes.preview', $theme->slug) }}" target="_blank" class="btn normal-case h-fit min-h-fit border-4 border-[#E8EDF2] dark:border-[#313442] bg-neutral-bg dark:bg-dark-neutral-bg text-gray-600 dark:text-gray-dark-500 text-xs py-[6px] px-[12px] hover:border-color-brands transition-all">Preview</a>
                        @unless($theme->is_active)
                        <form action="{{ route('cms.themes.destroy', $theme->slug) }}" method="POST" onsubmit="return confirm('Delete this theme? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn normal-case h-fit min-h-fit border-4 border-red-100 dark:border-red-900 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-xs py-[6px] px-[12px] transition-all">Delete</button>
                        </form>
                        @endunless
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20 text-gray-400 dark:text-gray-dark-500">No themes installed.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
