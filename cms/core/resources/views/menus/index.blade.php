@extends('cms-core::layouts.admin')

@section('title', 'Menus - Shri-ms')

@section('content')
<div>
    <div class="flex flex-col gap-y-2 mb-[36px]">
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100">Menus</h2>
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <span class="capitalize text-color-brands">Appearance</span>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <span class="capitalize text-color-brands">Menus</span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="flex gap-5 flex-col">
        {{-- Create new menu --}}
        <div class="w-full border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] py-[25px] h-fit">
            <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px] mb-5">Create a new menu</p>
            <form action="{{ route('cms.menus.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Menu Name</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                        <input type="text" name="name" required placeholder="e.g. Main Menu"
                            class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-gray-400">
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Assign to Location</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                        <select name="location" class="select w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            <option value="" class="bg-white dark:bg-dark-neutral-bg">— None —</option>
                            @foreach($locations as $key => $label)
                                <option value="{{ $key }}" class="bg-white dark:bg-dark-neutral-bg">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn normal-case h-fit min-h-fit self-start border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-sm py-[9px] px-[16px]">Create Menu</button>
            </form>
        </div>

        {{-- Existing menus --}}
        <div class="w-full border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-neutral dark:bg-dark-neutral-border">
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-5 py-3">Menu</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-5 py-3">Location</th>
                        <th class="text-right text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E8EDF2] dark:divide-[#313442]">
                    @forelse($menus as $menu)
                    <tr>
                        <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-gray-dark-1100">{{ $menu->name }}</td>
                        <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-dark-500">{{ $menu->location ? ($locations[$menu->location] ?? $menu->location) : '—' }}</td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('cms.menus.edit', $menu) }}" class="text-color-brands text-xs font-medium hover:opacity-75">Edit</a>
                                <form action="{{ route('cms.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Delete this menu?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-xs font-medium hover:opacity-75">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-5 py-10 text-center text-gray-400 dark:text-gray-dark-500">No menus yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
