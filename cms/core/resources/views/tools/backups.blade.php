@extends('cms-core::layouts.admin')

@section('title', 'Backups - LaraCMS')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex justify-between flex-col gap-y-3 mb-[24px] md:flex-row">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Backups</h2>
            <div class="flex items-center text-xs gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Tools</span>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Backups</span>
            </div>
        </div>
        <div class="flex items-center gap-[15px]">
            <form action="{{ route('cms.tools.backups.store') }}" method="POST">
                @csrf
                <button type="submit" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-sm py-[10px] px-[24px] rounded-xl font-semibold shadow-md">Create Backup Now</button>
            </form>
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

    {{-- Backups list table (Full Width) --}}
    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl p-6 overflow-x-scroll scrollbar-hide xl:overflow-x-hidden">
        <table class="w-full border-separate border-spacing-y-[12px] min-w-[700px]">
            <thead>
                <tr class="text-left">
                    <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pl-5 pb-2">Backup File</th>
                    <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pb-2">File Size</th>
                    <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pb-2">Created</th>
                    <th class="text-right text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pr-5 pb-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $backup)
                <tr>
                    <td class="py-4 pl-5 border-y border-neutral border-l dark:border-dark-neutral-bg rounded-l-xl text-sm font-semibold text-gray-1100 dark:text-white">
                        {{ $backup['filename'] }}
                    </td>
                    <td class="py-4 border-y border-neutral dark:border-dark-neutral-bg text-sm text-gray-500 dark:text-gray-dark-500">
                        {{ number_format($backup['size'] / (1024 * 1024), 2) }} MB
                    </td>
                    <td class="py-4 border-y border-neutral dark:border-dark-neutral-bg text-sm text-gray-400">
                        {{ date('d M Y \a\t H:i', $backup['created_at']) }}
                    </td>
                    <td class="py-4 border-y border-neutral border-r dark:border-dark-neutral-bg rounded-r-xl text-right pr-5">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('cms.tools.backups.download', $backup['filename']) }}" class="text-color-brands text-xs font-bold hover:underline">Download ZIP</a>
                            
                            <form action="{{ route('cms.tools.backups.destroy', $backup['filename']) }}" method="POST" onsubmit="return confirm('Delete this backup archive?');">
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
                        No backup archives created yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
