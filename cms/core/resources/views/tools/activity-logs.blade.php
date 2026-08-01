@extends('cms-core::layouts.admin')

@section('title', 'Activity Audit Trail - Shri-ms')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex justify-between flex-col gap-y-3 mb-[24px] md:flex-row">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Activity Audit Trail</h2>
            <div class="flex items-center text-xs gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Tools</span>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Activity Logs</span>
            </div>
        </div>
    </div>

    {{-- Activity logs list table (Full Width) --}}
    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl p-6 overflow-x-scroll scrollbar-hide xl:overflow-x-hidden">
        <table class="w-full border-separate border-spacing-y-[12px] min-w-[800px]">
            <thead>
                <tr class="text-left">
                    <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pl-5 pb-2">User</th>
                    <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pb-2">Event</th>
                    <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pb-2 w-2/5">Description</th>
                    <th class="text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pb-2">IP Address</th>
                    <th class="text-right text-xs font-semibold text-gray-500 dark:text-gray-dark-500 pr-5 pb-2">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="py-4 pl-5 border-y border-neutral border-l dark:border-dark-neutral-bg rounded-l-xl text-sm font-semibold text-gray-1100 dark:text-white">
                        {{ $log->user ? $log->user->name : 'System / Guest' }}
                    </td>
                    <td class="py-4 border-y border-neutral dark:border-dark-neutral-bg text-xs">
                        <span class="px-2 py-0.5 bg-gray-100 dark:bg-dark-neutral-border text-gray-700 dark:text-gray-300 rounded font-mono">{{ $log->event }}</span>
                    </td>
                    <td class="py-4 border-y border-neutral dark:border-dark-neutral-bg text-sm text-gray-600 dark:text-gray-dark-400">
                        {{ $log->description }}
                    </td>
                    <td class="py-4 border-y border-neutral dark:border-dark-neutral-bg text-xs text-gray-400">
                        {{ $log->ip_address }}
                    </td>
                    <td class="py-4 border-y border-neutral border-r dark:border-dark-neutral-bg rounded-r-xl text-right pr-5 text-xs text-gray-400">
                        {{ $log->created_at->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500 dark:text-gray-dark-500 border border-neutral dark:border-dark-neutral-border rounded-xl">
                        No activity logs recorded yet.
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
