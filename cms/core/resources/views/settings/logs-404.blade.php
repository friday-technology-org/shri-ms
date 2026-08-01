@extends('cms-core::layouts.admin')

@section('title', '404 Monitor - Shri-ms')

@section('content')
<div>
    <div class="flex justify-between flex-col gap-y-3 mb-[24px] md:flex-row">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">404 Monitor</h2>
            <div class="flex items-center text-xs gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Settings</span>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">404 Monitor</span>
            </div>
        </div>
        <div class="flex items-center gap-[15px]">
            <form action="{{ route('cms.settings.logs404.destroy') }}" method="POST" onsubmit="return confirm('Clear all logged 404s? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn normal-case h-fit min-h-fit border-4 bg-red-500 hover:bg-red-600 border-neutral-bg dark:border-dark-neutral-bg text-white text-sm py-[10px] px-[24px] rounded-xl font-semibold shadow-md">Clear Logs</button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
        <span class="block sm:inline font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl p-6 overflow-x-scroll scrollbar-hide xl:overflow-x-hidden">
        <table class="w-full border-separate border-spacing-y-[12px] min-w-[800px]">
            <thead>
                <tr class="text-left">
                    <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pl-5 pb-2">Target URL</th>
                    <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pb-2">Referrer</th>
                    <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pb-2">Last IP</th>
                    <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pb-2">Hits</th>
                    <th class="text-right text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pr-5 pb-2">Last Hit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="py-4 pl-5 border-y border-neutral border-l dark:border-dark-neutral-bg rounded-l-xl text-sm font-semibold text-gray-1100 dark:text-white">
                        {{ $log->url }}
                    </td>
                    <td class="py-4 border-y border-neutral dark:border-dark-neutral-bg text-xs text-gray-400">
                        {{ $log->referrer ?: 'Direct / No Referrer' }}
                    </td>
                    <td class="py-4 border-y border-neutral dark:border-dark-neutral-bg text-xs text-gray-400">
                        {{ $log->ip_address }}
                    </td>
                    <td class="py-4 border-y border-neutral dark:border-dark-neutral-bg text-sm font-bold text-color-brands">
                        {{ $log->count }}
                    </td>
                    <td class="py-4 border-y border-neutral border-r dark:border-dark-neutral-bg rounded-r-xl text-right pr-5 text-xs text-gray-400">
                        {{ $log->updated_at->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500 dark:text-gray-dark-500 border border-neutral dark:border-dark-neutral-border rounded-xl">
                        No 404 entries recorded yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-5">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
