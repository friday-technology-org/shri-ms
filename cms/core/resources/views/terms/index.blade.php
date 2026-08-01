@extends('cms-core::layouts.admin')

@section('title', $taxonomy->name . ' - LaraCMS')

@section('content')
<div>
    <div class="flex justify-between flex-col gap-y-3 mb-[36px] md:flex-row">
    <div class="flex flex-col items-stretch gap-y-3 gap-x-[41px] lg:items-center lg:flex-row">
        <div>
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">{{ $taxonomy->name }}</h2>
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">Settings</span><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">{{ $taxonomy->name }}</span>
        </div>
        </div>
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
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Add New Term Form (Left column) -->
        <div class="w-full">
            <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] py-[25px]">
                <h3 class="text-gray-1100 text-lg font-bold mb-6 dark:text-white">Add New {{ rtrim($taxonomy->name, 's') }}</h3>
                <form action="{{ route('cms.terms.store', $taxonomy->id) }}" method="POST">
                    @csrf
                    
                    <div class="flex flex-col gap-5 mb-5">
                        <div>
                            <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Name</p>
                            <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full">
                                <input type="text" name="name" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-inherit" required>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 dark:text-gray-dark-500">The name is how it appears on your site.</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Slug</p>
                            <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full">
                                <input type="text" name="slug" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-inherit">
                            </div>
                            <p class="text-xs text-gray-500 mt-2 dark:text-gray-dark-500">The "slug" is the URL-friendly version of the name.</p>
                        </div>

                        @if($taxonomy->hierarchical)
                        <div>
                            <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Parent {{ rtrim($taxonomy->name, 's') }}</p>
                            <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full">
                                <select name="parent_id" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                                    <option value="" class="bg-white dark:bg-dark-neutral-bg">None</option>
                                    @foreach($parentTerms as $pt)
                                        <option value="{{ $pt->id }}" class="bg-white dark:bg-dark-neutral-bg">{{ $pt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 dark:text-gray-dark-500">Assign a parent term to establish hierarchy.</p>
                        </div>
                        @else
                        <div>
                            <p class="text-gray-400 text-base leading-4 font-medium capitalize mb-[10px]">Hierarchy</p>
                            <div class="w-full py-3 text-xs text-gray-400">
                                This taxonomy does not support hierarchical relations.
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="mb-5">
                        <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Description</p>
                        <textarea name="description" class="textarea w-full text-gray-800 dark:text-white resize-none rounded-lg bg-transparent border border-[#E8EDF2] dark:border-[#313442] p-4 min-h-[100px] focus:outline-none placeholder:text-inherit"></textarea>
                        <p class="text-xs text-gray-500 mt-2 dark:text-gray-dark-500">The description is not prominent by default; however, some themes may show it.</p>
                    </div>

                    <button type="submit" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-sm py-[10px] px-[24px] rounded-xl font-semibold shadow-md">Add New {{ rtrim($taxonomy->name, 's') }}</button>
                </form>
            </div>
        </div>

        <!-- List Terms (Right column) -->
        <div class="w-full xl:col-span-2">
            <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl overflow-x-auto scrollbar-hide px-[25px] py-[25px]">
                <table class="w-full border-separate border-spacing-y-[15px] min-w-[600px]">
                    <thead> 
                    <tr> 
                        <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500 pl-5 w-2/5">Name</th>
                        <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500">Description</th>
                        <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500">Slug</th>
                        <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500">Actions</th>
                    </tr>
                    </thead>
                    <tbody> 
                    @forelse($terms as $term)
                    <tr>
                        <td class="py-5 pl-5 border-y border-neutral border-l dark:border-dark-neutral-bg rounded-l-[7px]">
                        <div class="flex flex-col gap-y-2">
                            <h3 class="leading-4 font-medium text-gray-1100 text-[16px] dark:text-gray-dark-1100">
                                @if($term->parent_id) — @endif {{ $term->name }}
                            </h3>
                        </div>
                        </td>
                        <td class="border-y border-neutral dark:border-dark-neutral-bg">
                            <span class="text-xs text-gray-500 dark:text-gray-dark-500">{{ Str::limit($term->description, 30) }}</span>
                        </td>
                        <td class="border-y border-neutral dark:border-dark-neutral-bg">
                            <span class="text-xs text-gray-500 dark:text-gray-dark-500">{{ $term->slug }}</span>
                        </td>
                        <td class="border-y border-neutral border-r dark:border-dark-neutral-bg rounded-r-[7px]">
                        <div class="flex gap-2">
                            <a href="{{ route('cms.terms.edit', [$taxonomy->id, $term->id]) }}" class="bg-neutral-bg mt-auto border-transparent rounded-lg transition-all duration-200 group dark:bg-dark-neutral-bg border-[4px] dark:hover:border-dark-neutral-border hover:border-neutral">
                                <div class="border-neutral flex items-center gap-x-2 border rounded-lg py-2 dark:border-dark-neutral-border px-[10px] group-hover:border-transparent"><img src="{{ asset('assets/images/icons/icon-edit-2.svg') }}" alt="edit icon"><span class="text-gray-1100 dark:text-gray-dark-1100 text-[12px] leading-[19px]">Edit</span></div>
                            </a>
                            <form action="{{ route('cms.terms.destroy', [$taxonomy->id, $term->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');">
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
                        <td colspan="4" class="py-5 text-center text-gray-500">No {{ strtolower($taxonomy->name) }} found.</td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
                
                <div class="mt-5">
                    {{ $terms->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
