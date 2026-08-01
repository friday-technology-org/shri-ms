@extends('cms-core::layouts.admin')

@section('title', 'Taxonomies - Shri-ms')

@section('content')
<div>
    <div class="flex justify-between flex-col gap-y-3 mb-[36px] md:flex-row">
    <div class="flex flex-col items-stretch gap-y-3 gap-x-[41px] lg:items-center lg:flex-row">
        <div>
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Taxonomies</h2>
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">Settings</span><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">Taxonomies</span>
        </div>
        </div>
        <a href="{{ route('cms.taxonomies.create') }}" class="btn flex items-center w-fit normal-case bg-color-brands h-auto border-white rounded-2xl border-gray-100 gap-x-[10.5px] border-[4px] hover:border-[#B2A7FF] hover:bg-color-brands dark:border-black dark:hover:border-[#B2A7FF] p-[17.5px]"><img src="{{ asset('assets/images/icons/icon-add.svg') }}" alt="add icon"><span class="text-white font-semibold text-[14px] leading-[21px]">Add New Taxonomy</span></a>
    </div>
    </div>
    
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl mb-9 overflow-x-scroll scrollbar-hide pl-[29px] pr-[22px] pb-[26px] pt-[17px] xl:overflow-x-hidden">
    <table class="w-full border-separate border-spacing-y-[15px] min-w-[1000px]">
        <thead> 
        <tr> 
            <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500 pl-5 w-1/4">Name</th>
            <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500">Slug</th>
            <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500">Hierarchical</th>
            <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500">Post Types</th>
            <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500">Actions</th>
        </tr>
        </thead>
        <tbody> 
        @forelse($taxonomies as $tax)
        <tr>
            <td class="py-5 pl-5 border-y border-neutral border-l dark:border-dark-neutral-bg rounded-l-[7px]">
            <div class="flex flex-col gap-y-2">
                <h3 class="leading-4 font-medium text-gray-1100 text-[16px] dark:text-gray-dark-1100">{{ $tax->name }}</h3>
            </div>
            </td>
            <td class="border-y border-neutral dark:border-dark-neutral-bg">
                <span class="text-xs text-gray-500 dark:text-gray-dark-500">{{ $tax->slug }}</span>
            </td>
            <td class="border-y border-neutral dark:border-dark-neutral-bg">
                <span class="py-1 bg-neutral px-[10px] dark:bg-dark-neutral-border rounded-[5px] text-[#37404E] dark:text-[#b3b3b3] capitalize">{{ $tax->hierarchical ? 'Yes (Categories)' : 'No (Tags)' }}</span>
            </td>
            <td class="border-y border-neutral dark:border-dark-neutral-bg">
                @if(is_array($tax->post_types))
                    @foreach($tax->post_types as $pt)
                        <span class="py-1 bg-neutral px-[10px] dark:bg-dark-neutral-border rounded-[5px] text-xs text-[#37404E] dark:text-[#b3b3b3]">{{ $pt }}</span>
                    @endforeach
                @else
                    -
                @endif
            </td>
            <td class="border-y border-neutral border-r dark:border-dark-neutral-bg rounded-r-[7px]">
            <div class="flex gap-2">
                <a href="{{ route('cms.taxonomies.edit', $tax->id) }}" class="bg-neutral-bg mt-auto border-transparent rounded-lg transition-all duration-200 group dark:bg-dark-neutral-bg border-[4px] dark:hover:border-dark-neutral-border hover:border-neutral">
                    <div class="border-neutral flex items-center gap-x-2 border rounded-lg py-2 dark:border-dark-neutral-border px-[10px] group-hover:border-transparent"><img src="{{ asset('assets/images/icons/icon-edit-2.svg') }}" alt="edit icon"><span class="text-gray-1100 dark:text-gray-dark-1100 text-[12px] leading-[19px]">Edit</span></div>
                </a>
                <form action="{{ route('cms.taxonomies.destroy', $tax->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this taxonomy?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-neutral-bg mt-auto border-transparent rounded-lg transition-all duration-200 group dark:bg-dark-neutral-bg border-[4px] dark:hover:border-dark-neutral-border hover:border-neutral">
                        <div class="border-neutral flex items-center gap-x-2 border rounded-lg py-2 dark:border-dark-neutral-border px-[10px] group-hover:border-transparent"><span class="text-red text-[12px] leading-[19px]">Delete</span></div>
                    </button>
                </form>
            </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="py-5 text-center text-gray-500">No taxonomies found.</td>
        </tr>
        @endforelse
        </tbody>
    </table>
    
    <div class="mt-5">
        {{ $taxonomies->links() }}
    </div>
    </div>
</div>
@endsection
