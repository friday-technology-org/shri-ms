@extends('cms-core::layouts.admin')

@section('title', 'Site Health & Maintenance - Shri-ms')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex justify-between flex-col gap-y-3 mb-[24px] md:flex-row">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Site Health & Diagnostics</h2>
            <div class="flex items-center text-xs gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Tools</span>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Site Health</span>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
        <span class="block sm:inline font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- System Health Checklist (Full Width) --}}
    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl p-6">
        <h3 class="text-gray-1100 text-lg font-bold mb-4 dark:text-white">System Diagnostics</h3>
        <div class="divide-y divide-[#E8EDF2] dark:divide-[#313442]">
            @foreach($checks as $key => $check)
            <div class="py-4 flex flex-col md:flex-row md:items-center justify-between gap-2">
                <div>
                    <h4 class="text-sm font-bold text-gray-1100 dark:text-white">{{ $check['label'] }}</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-dark-500 mt-1">{{ $check['message'] }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold text-gray-400 mr-2">{{ $check['value'] }}</span>
                    @if($check['status'] === 'good')
                        <span class="health-badge health-badge-good">Good</span>
                    @elseif($check['status'] === 'warning')
                        <span class="health-badge health-badge-warning">Warning</span>
                    @else
                        <span class="health-badge health-badge-bad">Critical</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Database Maintenance (Full Width) --}}
    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl p-6">
        <h3 class="text-gray-1100 text-lg font-bold mb-2 dark:text-white">Database Optimization & Maintenance</h3>
        <p class="text-xs text-gray-500 dark:text-gray-dark-500 mb-6">Run bulk cleanup tasks to keep the database optimized and clean up unnecessary records.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- Clean Transients --}}
            <div class="border border-[#E8EDF2] dark:border-[#313442] p-4 rounded-xl flex flex-col justify-between gap-4">
                <div>
                    <h4 class="text-sm font-bold text-gray-1100 dark:text-white">Expired Transients</h4>
                    <p class="text-xs text-gray-400 mt-1">Pending: {{ $expiredTransientsCount }} items</p>
                </div>
                <form action="{{ route('cms.tools.maintenance') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="clean_transients">
                    <button type="submit" class="w-full text-center px-4 py-2 bg-neutral dark:bg-dark-neutral-border text-gray-800 dark:text-white rounded-lg text-xs font-semibold hover:opacity-75">Clean Transients</button>
                </form>
            </div>

            {{-- Clean Revisions --}}
            <div class="border border-[#E8EDF2] dark:border-[#313442] p-4 rounded-xl flex flex-col justify-between gap-4">
                <div>
                    <h4 class="text-sm font-bold text-gray-1100 dark:text-white">Post Revisions</h4>
                    <p class="text-xs text-gray-400 mt-1">Total revision snapshots: {{ $revisionCount }}</p>
                </div>
                <form action="{{ route('cms.tools.maintenance') }}" method="POST" onsubmit="return confirm('Erase all post revision history?');">
                    @csrf
                    <input type="hidden" name="action" value="clean_revisions">
                    <button type="submit" class="w-full text-center px-4 py-2 bg-neutral dark:bg-dark-neutral-border text-gray-800 dark:text-white rounded-lg text-xs font-semibold hover:opacity-75">Clean Revisions</button>
                </form>
            </div>

            {{-- Clean Spam Comments --}}
            <div class="border border-[#E8EDF2] dark:border-[#313442] p-4 rounded-xl flex flex-col justify-between gap-4">
                <div>
                    <h4 class="text-sm font-bold text-gray-1100 dark:text-white">Spam Comments</h4>
                    <p class="text-xs text-gray-400 mt-1">Spam count: {{ $spamCount }}</p>
                </div>
                <form action="{{ route('cms.tools.maintenance') }}" method="POST" onsubmit="return confirm('Permanently delete all spam comments?');">
                    @csrf
                    <input type="hidden" name="action" value="clean_spam">
                    <button type="submit" class="w-full text-center px-4 py-2 bg-neutral dark:bg-dark-neutral-border text-gray-800 dark:text-white rounded-lg text-xs font-semibold hover:opacity-75">Empty Spam</button>
                </form>
            </div>

            {{-- Optimize Tables --}}
            <div class="border border-[#E8EDF2] dark:border-[#313442] p-4 rounded-xl flex flex-col justify-between gap-4">
                <div>
                    <h4 class="text-sm font-bold text-gray-1100 dark:text-white">Optimize Tables</h4>
                    <p class="text-xs text-gray-400 mt-1">Re-index tables to reclaim storage space.</p>
                </div>
                <form action="{{ route('cms.tools.maintenance') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="optimize_tables">
                    <button type="submit" class="w-full text-center px-4 py-2 bg-neutral dark:bg-dark-neutral-border text-gray-800 dark:text-white rounded-lg text-xs font-semibold hover:opacity-75">Optimize DB</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
