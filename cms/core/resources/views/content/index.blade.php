@extends('cms-core::layouts.admin')

@section('title', $cpt->plural_label . ' - LaraCMS')

@section('content')
<div>
    <div class="flex justify-between flex-col gap-y-3 mb-[36px] md:flex-row">
    <div class="flex flex-col items-stretch gap-y-3 gap-x-[41px] lg:items-center lg:flex-row">
        <div>
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">All {{ strtolower($cpt->plural_label) }}</h2>
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">{{ $cpt->plural_label }} listing</span>
        </div>
        </div>
        <a href="{{ route('cms.content.create', $cpt->name) }}" class="btn flex items-center w-fit normal-case bg-color-brands h-auto border-white rounded-2xl border-gray-100 gap-x-[10.5px] border-[4px] hover:border-[#B2A7FF] hover:bg-color-brands dark:border-black dark:hover:border-[#B2A7FF] p-[17.5px]"><img src="{{ asset('assets/images/icons/icon-add.svg') }}" alt="add icon"><span class="text-white font-semibold text-[14px] leading-[21px]">Add New {{ $cpt->singular_label }}</span></a>
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

    <form action="{{ route('cms.content.index', $cpt->name) }}" method="GET" class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl search-input-shadow flex items-center justify-between flex-col py-[18px] pl-[28px] pr-[19px] mb-[38px] gap-[10px] sm:flex-row">
    <div class="flex items-center w-full"><img src="{{ asset('assets/images/icons/icon-search-normal.svg') }}" alt="search icon">
        <input name="search" value="{{ $search ?? '' }}" class="input w-full bg-transparent outline-none h-5 text-gray-800 text-sm leading-4 focus:!outline-none placeholder:text-gray-400 dark:text-white dark:placeholder:text-gray-dark-400 pl-[11px]" type="text" placeholder="Search {{ strtolower($cpt->singular_label) }}">
    </div>
    <button type="submit" class="btn text-sm h-fit min-h-fit capitalize leading-4 border-0 px-6 bg-color-brands rounded-lg py-[11.5px] hover:bg-color-brands text-white">Search Article</button>
    </form>

    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl mb-9 overflow-x-scroll scrollbar-hide pl-[29px] pr-[22px] pb-[26px] pt-[17px] xl:overflow-x-hidden">
    <table class="w-full border-separate border-spacing-y-[15px] min-w-[1000px]">
        <thead> 
        <tr> 
            <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500 pl-5 w-2/5">Title</th>
            <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500">Author</th>
            <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500">Status</th>
            <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500">Published</th>
            <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500">Actions</th>
        </tr>
        </thead>
        <tbody> 
        @forelse($posts as $post)
        <tr>
            <td class="py-5 pl-5 border-y border-neutral border-l dark:border-dark-neutral-bg rounded-l-[7px]">
            <div class="flex flex-col gap-y-2">
                <h3 class="leading-4 font-medium text-gray-1100 text-[16px] dark:text-gray-dark-1100">{{ $post->title }}</h3>
                <span class="text-xs text-gray-400 dark:text-gray-dark-400">{{ $post->slug }}</span>
            </div>
            </td>
            <td class="border-y border-neutral dark:border-dark-neutral-bg">
            <div class="flex items-center gap-x-3">
                <img src="{{ $post->author?->avatar_url ?? asset('assets/images/cms-avt-1.png') }}" class="w-8 h-8 rounded-full" alt="avatar">
                <div class="flex flex-col gap-y-[7px]">
                <h4 class="leading-4 text-gray-1100 text-[14px] dark:text-gray-dark-1100">{{ $post->author->name ?? 'Unknown' }}</h4>
                </div>
            </div>
            </td>
            <td class="border-y border-neutral dark:border-dark-neutral-bg">
                <span class="py-1 bg-neutral px-[10px] dark:bg-dark-neutral-border rounded-[5px] text-[#37404E] dark:text-[#b3b3b3] capitalize">{{ $post->status }}</span>
            </td>
            <td class="border-y border-neutral dark:border-dark-neutral-bg"><span class="leading-4 text-gray-1100 text-[14px] dark:text-gray-dark-1100">{{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}</span></td>
            <td class="border-y border-neutral border-r dark:border-dark-neutral-bg rounded-r-[7px]">
            <div class="flex gap-2">
                <a href="{{ $post->permalink }}" target="_blank" rel="noopener" class="bg-neutral-bg mt-auto border-transparent rounded-lg transition-all duration-200 group dark:bg-dark-neutral-bg border-[4px] dark:hover:border-dark-neutral-border hover:border-neutral">
                    <div class="border-neutral flex items-center gap-x-2 border rounded-lg py-2 dark:border-dark-neutral-border px-[10px] group-hover:border-transparent"><img src="{{ asset('assets/images/icons/icon-eye.svg') }}" alt="view icon"><span class="text-gray-1100 dark:text-gray-dark-1100 text-[12px] leading-[19px]">View</span></div>
                </a>
                <a href="{{ route('cms.content.edit', [$cpt->name, $post->id]) }}" class="bg-neutral-bg mt-auto border-transparent rounded-lg transition-all duration-200 group dark:bg-dark-neutral-bg border-[4px] dark:hover:border-dark-neutral-border hover:border-neutral">
                    <div class="border-neutral flex items-center gap-x-2 border rounded-lg py-2 dark:border-dark-neutral-border px-[10px] group-hover:border-transparent"><img src="{{ asset('assets/images/icons/icon-edit-2.svg') }}" alt="edit icon"><span class="text-gray-1100 dark:text-gray-dark-1100 text-[12px] leading-[19px]">Edit</span></div>
                </a>
                <form action="{{ route('cms.content.destroy', [$cpt->name, $post->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to trash this?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-neutral-bg mt-auto border-transparent rounded-lg transition-all duration-200 group dark:bg-dark-neutral-bg border-[4px] dark:hover:border-dark-neutral-border hover:border-neutral">
                        <div class="border-neutral flex items-center gap-x-2 border rounded-lg py-2 dark:border-dark-neutral-border px-[10px] group-hover:border-transparent"><span class="text-red text-[12px] leading-[19px]">Trash</span></div>
                    </button>
                </form>
            </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="py-5 text-center text-gray-500">No {{ strtolower($cpt->plural_label) }} found.</td>
        </tr>
        @endforelse
        </tbody>
    </table>
    
    <div class="mt-5">
        {{ $posts->links() }}
    </div>
    </div>
</div>
@endsection
