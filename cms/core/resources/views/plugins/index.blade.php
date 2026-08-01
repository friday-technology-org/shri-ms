@extends('cms-core::layouts.admin')

@section('title', 'Plugins - Shri-ms')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex justify-between flex-col gap-y-3 mb-[24px] md:flex-row">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Plugins</h2>
            <div class="flex items-center text-xs gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Plugins</span>
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

    {{-- Premium Upload Form (Stacked Top, Full Width) --}}
    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl p-6">
        <div class="max-w-xl mx-auto text-center py-4">
            <h3 class="text-gray-1100 text-lg font-bold mb-2 dark:text-white">Upload & Install Plugin</h3>
            <p class="text-xs text-gray-500 dark:text-gray-dark-500 mb-6">If you have a plugin in a .zip format, you can install it by uploading it here.</p>
            
            <form action="{{ route('cms.plugins.upload') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center">
                @csrf
                <div class="w-full border-2 border-dashed border-[#E8EDF2] dark:border-[#313442] hover:border-color-brands dark:hover:border-color-brands rounded-xl p-8 mb-4 transition-colors cursor-pointer relative group">
                    <input type="file" name="plugin_zip" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="pluginZipFile">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-[#1f2130] flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-color-brands" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                        <div class="text-sm font-semibold text-gray-700 dark:text-gray-dark-1100" id="fileNameDisplay">Choose a file or drag it here</div>
                        <div class="text-[11px] text-gray-400 dark:text-gray-dark-500">ZIP archive only (max 10MB)</div>
                    </div>
                </div>
                
                <button type="submit" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-sm py-[10px] px-[24px] rounded-xl font-semibold shadow-md">Install Now</button>
            </form>
        </div>
    </div>

    {{-- Plugins List Table (Stacked Bottom, Full Width) --}}
    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-gray-1100 text-lg font-bold dark:text-white">Installed Plugins</h3>
            <span class="text-xs text-gray-500 dark:text-gray-dark-500 font-semibold">{{ count($plugins) }} total plugins</span>
        </div>

        <div class="overflow-x-scroll scrollbar-hide xl:overflow-x-hidden">
            <table class="w-full border-separate border-spacing-y-[12px]">
                <thead>
                    <tr class="text-left">
                        <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pl-5 pb-2 w-1/4">Plugin</th>
                        <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pb-2 w-2/5">Description</th>
                        <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pb-2">Status</th>
                        <th class="text-right text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pr-5 pb-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plugins as $slug => $plugin)
                    <tr>
                        <td class="py-5 pl-5 border-y border-neutral border-l dark:border-dark-neutral-bg rounded-l-xl text-sm">
                            <div class="font-bold text-gray-1100 dark:text-white text-[15px]">{{ $plugin['name'] ?? $slug }}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-dark-500 mt-1">Version {{ $plugin['version'] ?? '1.0.0' }} | By {{ $plugin['author'] ?? 'Unknown' }}</div>
                        </td>
                        <td class="py-5 border-y border-neutral dark:border-dark-neutral-bg text-sm text-gray-500 dark:text-gray-dark-500 pr-4">
                            {{ $plugin['description'] ?? 'No description provided.' }}
                        </td>
                        <td class="py-5 border-y border-neutral dark:border-dark-neutral-bg text-sm">
                            @if($plugin['is_active'])
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[11px] rounded-lg font-bold">Active</span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-500 text-[11px] rounded-lg font-bold dark:bg-dark-neutral-border dark:text-gray-dark-500">Inactive</span>
                            @endif
                        </td>
                        <td class="py-5 border-y border-neutral border-r dark:border-dark-neutral-bg rounded-r-xl text-right pr-5">
                            <div class="flex items-center justify-end gap-3">
                                @if($plugin['is_active'])
                                    <form action="{{ route('cms.plugins.deactivate', $slug) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 dark:text-yellow-500 text-xs font-bold hover:underline">Deactivate</button>
                                    </form>
                                @else
                                    <form action="{{ route('cms.plugins.activate', $slug) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-color-brands text-xs font-bold hover:underline">Activate</button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('cms.plugins.destroy', $slug) }}" method="POST" onsubmit="return confirm('Delete this plugin? This will permanently remove its files.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-xs font-bold hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500 dark:text-gray-dark-500 border border-neutral dark:border-dark-neutral-border rounded-xl">
                            No plugins installed yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('pluginZipFile').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('fileNameDisplay').textContent = file.name;
        }
    });
</script>
@endsection
