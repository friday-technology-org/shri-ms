@extends('cms-core::layouts.admin')

@section('title', 'Multisite Network - LaraCMS')

@section('content')
<div>
    <div class="flex justify-between flex-col gap-y-3 mb-[24px] md:flex-row">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Multisite Network Control</h2>
            <div class="flex items-center text-xs gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Multisite Network</span>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Add Tenant Site --}}
        <div class="bg-white dark:bg-dark-neutral-bg border border-neutral dark:border-dark-neutral-border p-6 rounded-2xl">
            <h3 class="text-lg font-bold text-gray-1100 dark:text-white border-b border-[#E8EDF2] dark:border-[#313442] pb-3 mb-4">Add Tenant Site</h3>
            <form action="{{ route('cms.network.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Site Name</label>
                    <input type="text" name="name" placeholder="e.g. Sub Site" class="w-full border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Domain Mapping (optional)</label>
                    <input type="text" name="domain" placeholder="site1.example.com" class="w-full border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Subdirectory Path (optional)</label>
                    <input type="text" name="path" placeholder="site1" class="w-full border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white">
                </div>
                <button type="submit" class="w-full text-center text-sm font-semibold py-3 px-5 rounded-xl bg-color-brands text-white hover:opacity-90">Create Network Site</button>
            </form>
        </div>

        {{-- Sites list --}}
        <div class="lg:col-span-2 bg-white dark:bg-dark-neutral-bg border border-neutral dark:border-dark-neutral-border p-6 rounded-2xl">
            <h3 class="text-lg font-bold text-gray-1100 dark:text-white border-b border-[#E8EDF2] dark:border-[#313442] pb-3 mb-4">Sites in the Network</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#E8EDF2] dark:border-[#313442]">
                            <th class="pb-3 text-sm font-semibold text-gray-500">ID</th>
                            <th class="pb-3 text-sm font-semibold text-gray-500">Name</th>
                            <th class="pb-3 text-sm font-semibold text-gray-500">Mapping</th>
                            <th class="pb-3 text-sm font-semibold text-gray-500">Status</th>
                            <th class="pb-3 text-sm font-semibold text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sites as $site)
                        <tr class="border-b border-[#E8EDF2] dark:border-[#313442] last:border-0">
                            <td class="py-4 text-sm text-gray-500">#{{ $site->id }}</td>
                            <td class="py-4 text-sm font-semibold text-gray-1100 dark:text-white">{{ $site->name }}</td>
                            <td class="py-4 text-sm text-gray-500">
                                @if($site->domain)
                                    Domain: <code>{{ $site->domain }}</code>
                                @elseif($site->path)
                                    Subfolder: <code>/{{ $site->path }}</code>
                                @else
                                    Primary Site
                                @endif
                            </td>
                            <td class="py-4 text-sm">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $site->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $site->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="py-4 text-sm flex gap-2">
                                @if($site->id !== 1)
                                <form action="{{ route('cms.network.toggle', $site->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-blue-500 hover:text-blue-700 font-semibold text-xs">Toggle</button>
                                </form>
                                <form action="{{ route('cms.network.destroy', $site->id) }}" method="POST" onsubmit="return confirm('Delete this site from the network?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-xs">Delete</button>
                                </form>
                                @else
                                <span class="text-gray-400 text-xs">Primary</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
