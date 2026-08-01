@extends('cms-core::layouts.admin')

@section('title', 'Add New CPT - LaraCMS')

@section('content')
<form action="{{ route('cms.post-types.store') }}" method="POST">
    @csrf
    <div>
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Add Post Type</h2>
        <div class="flex justify-between flex-col gap-y-2 sm:flex-row mb-[54px]">
            <div class="flex items-center text-xs gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">Post Types</span><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">Add Post Type</span>
            </div>
        </div>
        
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] pt-[25px] pb-[68px]">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Plural Label</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                        <input type="text" name="plural_label" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-4 focus:outline-none pl-[13px] placeholder:text-inherit" required placeholder="e.g. Portfolios" value="{{ old('plural_label') }}">
                    </div>
                </div>
                <div>
                    <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Singular Label</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                        <input type="text" name="singular_label" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-4 focus:outline-none pl-[13px] placeholder:text-inherit" required placeholder="e.g. Portfolio Item" value="{{ old('singular_label') }}">
                    </div>
                </div>
                <div>
                    <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Post Type Key (slug)</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                        <input type="text" name="name" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-4 focus:outline-none pl-[13px] placeholder:text-inherit" placeholder="Auto-generated if empty" value="{{ old('name') }}">
                    </div>
                </div>
                <div>
                    <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Sidebar Icon (SVG asset path)</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                        <input type="text" name="icon" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-4 focus:outline-none pl-[13px] placeholder:text-inherit" placeholder="e.g. icon-file.svg" value="{{ old('icon', 'icon-file.svg') }}">
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Description</p>
                <textarea name="description" class="textarea w-full text-gray-800 dark:text-white resize-none rounded-lg bg-transparent border border-[#E8EDF2] dark:border-[#313442] p-4 min-h-[120px] focus:outline-none placeholder:text-inherit" placeholder="Description">{{ old('description') }}</textarea>
            </div>

            <div class="mb-8 border border-neutral dark:border-dark-neutral-border rounded-xl p-[20px] bg-neutral-bg dark:bg-dark-neutral-bg">
                <h3 class="text-gray-1100 text-lg font-bold mb-4 dark:text-gray-dark-1100">Settings</h3>
                
                <div class="flex items-center gap-4 mb-4">
                    <input type="checkbox" name="is_hierarchical" id="is_hierarchical" value="1" class="checkbox checkbox-primary rounded border border-neutral dark:border-dark-neutral-border w-[18px] h-[18px]">
                    <label for="is_hierarchical" class="text-sm font-medium text-gray-800 dark:text-gray-dark-500">Hierarchical (Like Pages, supports parent/child)</label>
                </div>
                
                <div class="flex items-center gap-4 mb-6">
                    <input type="checkbox" name="has_archive" id="has_archive" value="1" checked class="checkbox checkbox-primary rounded border border-neutral dark:border-dark-neutral-border w-[18px] h-[18px]">
                    <label for="has_archive" class="text-sm font-medium text-gray-800 dark:text-gray-dark-500">Has Archive (Enable front-end list view)</label>
                </div>

                <h4 class="text-gray-1100 font-medium mb-3 dark:text-gray-dark-1100">Supports (Editor Features)</h4>
                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-dark-500"><input type="checkbox" name="supports[]" value="title" checked class="checkbox checkbox-primary rounded border border-neutral dark:border-dark-neutral-border w-[18px] h-[18px]"> Title</label>
                    <label class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-dark-500"><input type="checkbox" name="supports[]" value="editor" checked class="checkbox checkbox-primary rounded border border-neutral dark:border-dark-neutral-border w-[18px] h-[18px]"> Editor</label>
                    <label class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-dark-500"><input type="checkbox" name="supports[]" value="excerpt" checked class="checkbox checkbox-primary rounded border border-neutral dark:border-dark-neutral-border w-[18px] h-[18px]"> Excerpt</label>
                    <label class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-dark-500"><input type="checkbox" name="supports[]" value="thumbnail" checked class="checkbox checkbox-primary rounded border border-neutral dark:border-dark-neutral-border w-[18px] h-[18px]"> Thumbnail</label>
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="submit" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg font-medium dark:border-dark-neutral-bg py-[10px] px-[24px] text-white">Save CPT</button>
            </div>
        </div>
    </div>
</form>
@endsection
