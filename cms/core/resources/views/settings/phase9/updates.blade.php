@extends('cms-core::layouts.admin')

@section('title', 'LaraCMS Core Updates')

@section('content')
<div>
    <div class="flex justify-between flex-col gap-y-3 mb-[24px] md:flex-row">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">One-Click Core Updates</h2>
            <div class="flex items-center text-xs gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Updates</span>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
        <span class="block sm:inline font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
        <span class="block sm:inline font-semibold">{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white dark:bg-dark-neutral-bg border border-neutral dark:border-dark-neutral-border p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-gray-1100 dark:text-white border-b border-[#E8EDF2] dark:border-[#313442] pb-3 mb-4">LaraCMS Update Status</h3>
        
        <div class="space-y-6">
            <div class="flex items-center gap-4 p-4 bg-purple-50 dark:bg-[#1e1b3a] border border-[#d2cbff] dark:border-[#3a3560] rounded-xl">
                <div class="w-12 h-12 rounded-full bg-color-brands flex flex-shrink-0 items-center justify-center text-white font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-1100 dark:text-white mb-2 flex items-center">Current Version: <span class="bg-color-brands text-white px-2 py-1 rounded-md text-xs font-bold ml-2">{{ $info['current_version'] }}</span></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">Latest Available: <span class="bg-orange text-white px-2 py-1 rounded-md text-xs font-bold ml-2">{{ $info['latest_version'] }}</span></p>
                </div>
            </div>

            @if($info['has_update'])
            <div class="p-4 bg-blue-50 dark:bg-[#1e202c] border border-blue-200 dark:border-blue-900 rounded-xl">
                <h4 class="text-sm font-bold text-blue-800 dark:text-blue-400 mb-1">Release Notes:</h4>
                <p class="text-xs text-blue-950 dark:text-blue-200">{{ $info['release_notes'] }}</p>
            </div>

            <form action="{{ route('cms.updates.run') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Or Upload Update ZIP (For Testing)</label>
                    <div class="w-full border-2 border-dashed border-[#E8EDF2] dark:border-[#313442] hover:border-color-brands dark:hover:border-color-brands rounded-xl p-8 transition-colors cursor-pointer relative group">
                        <input type="file" name="update_zip" accept=".zip" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="updateZipFile" onchange="document.getElementById('updateFileNameDisplay').innerText = this.files[0] ? this.files[0].name : 'Choose a file or drag it here';">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-[#1f2130] flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-color-brands" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <div class="text-sm font-semibold text-gray-700 dark:text-gray-dark-1100" id="updateFileNameDisplay">Choose a file or drag it here</div>
                            <div class="text-[11px] text-gray-400 dark:text-gray-500">ZIP archive only. Leave empty to download directly from GitHub.</div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="text-sm font-semibold py-3 px-6 rounded-xl bg-color-brands text-white hover:opacity-90">
                    Update Now (One-Click)
                </button>
            </form>
            @else
            <p class="text-sm text-green-600 font-semibold">Your LaraCMS core engine is fully up to date!</p>
            @endif
        </div>
    </div>
</div>
@endsection
