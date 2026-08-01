@extends('cms-core::layouts.admin')

@section('title', 'API Tokens - Shri-ms')

@section('content')
<div>
    <div class="flex justify-between flex-col gap-y-3 mb-[24px] md:flex-row">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">API Token Settings</h2>
            <div class="flex items-center text-xs gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">API Tokens</span>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
        <span class="block sm:inline font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Generate Token --}}
        <div class="lg:col-span-1 bg-white dark:bg-dark-neutral-bg border border-neutral dark:border-dark-neutral-border p-6 rounded-2xl">
            <h3 class="text-lg font-bold text-gray-1100 dark:text-white border-b border-[#E8EDF2] dark:border-[#313442] pb-3 mb-4">Generate API Token</h3>
            <form action="{{ route('cms.api-tokens.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Token Name</label>
                    <input type="text" name="name" placeholder="e.g. Mobile App, Headless Frontend" class="w-full border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white" required>
                </div>
                <button type="submit" class="w-full text-center text-sm font-semibold py-3 px-5 rounded-xl bg-color-brands text-white hover:opacity-90">Generate Token</button>
            </form>
        </div>

        {{-- Active Tokens List --}}
        <div class="lg:col-span-2 bg-white dark:bg-dark-neutral-bg border border-neutral dark:border-dark-neutral-border p-6 rounded-2xl">
            <h3 class="text-lg font-bold text-gray-1100 dark:text-white border-b border-[#E8EDF2] dark:border-[#313442] pb-3 mb-4">Active API Tokens</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#E8EDF2] dark:border-[#313442]">
                            <th class="pb-3 text-sm font-semibold text-gray-500">Name</th>
                            <th class="pb-3 text-sm font-semibold text-gray-500">Last Used</th>
                            <th class="pb-3 text-sm font-semibold text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tokens ?? [] as $token)
                        <tr class="border-b border-[#E8EDF2] dark:border-[#313442] last:border-0">
                            <td class="py-4 text-sm font-semibold text-gray-1100 dark:text-white">{{ $token->name }}</td>
                            <td class="py-4 text-sm text-gray-500">{{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never' }}</td>
                            <td class="py-4 text-sm">
                                <form action="{{ route('cms.api-tokens.destroy', $token->id) }}" method="POST" onsubmit="return confirm('Revoke this token?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Revoke</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-sm text-gray-500">No active tokens found. Create one to query the headless REST/GraphQL APIs.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
