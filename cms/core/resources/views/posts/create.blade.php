@extends('cms-core::layouts.admin')

@section('title', 'Add New Post - Shri-ms')

@section('content')
<form action="{{ route('cms.posts.store') }}" method="POST">
    @csrf
    <div>
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Add new post</h2>
        <div class="flex justify-between flex-col gap-y-2 sm:flex-row mb-[54px]">
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">Posts</span><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">Add new post</span>
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
        <div class="flex justify-between gap-x-5 flex-col xl:flex-row">
            
            <!-- Left Column: Content -->
            <div class="xl:w-[70%]">
            <div class="mb-12">
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Post title</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                    <input name="title" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-4 focus:outline-none pl-[13px] placeholder:text-inherit" type="text" placeholder="Add title" value="{{ old('title') }}" required>
                </div>
            </div>
            
            <div class="mb-12">
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Content</p>
                @include('cms-core::layouts.partials.wysiwyg-editor', ['name' => 'content', 'fieldId' => 'content-editor', 'value' => old('content')])
            </div>
            
            <div class="mb-12"> 
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Excerpt</p>
                <textarea name="excerpt" class="textarea w-full text-gray-800 dark:text-white resize-none rounded-lg bg-transparent border border-[#E8EDF2] dark:border-[#313442] p-4 min-h-[131px] focus:outline-none placeholder:text-inherit" placeholder="The excerpt">{{ old('excerpt') }}</textarea>
            </div>
            
            <!-- Custom Fields -->
            @include('cms-core::layouts.partials.custom-fields')

            <!-- SEO Fields -->
            @include('cms-core::layouts.partials.seo-fields')
            </div>

            <!-- Right Column: Sidebar settings -->
            <div class="flex-1 flex flex-col gap-y-[15px] mt-[25px]">
            <div class="border border-neutral rounded-lg bg-neutral-bg dark:border-dark-neutral-border pb-[31px] dark:bg-dark-neutral-bg">
                <div class="bg-neutral rounded-t-lg py-[15px] pl-[18px] mb-[27px] dark:bg-dark-neutral-border">
                <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[14px]">Publish</p>
                </div>
                
                <div class="px-5 mb-5">
                    <p class="text-gray-1100 text-sm font-semibold mb-2 dark:text-gray-dark-1100">Status</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full">
                        <select name="status" id="status" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            <option value="draft" class="bg-white dark:bg-dark-neutral-bg" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" class="bg-white dark:bg-dark-neutral-bg" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                </div>

                <div class="px-[18px] mb-[25px]">
                <div class="w-full bg-neutral h-[1px] dark:bg-dark-neutral-border"></div>
                </div>
                
                <div class="flex justify-between px-[18px]">
                <a href="{{ route('cms.posts.index') }}" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 border-neutral-bg bg-gray-200 font-medium text-gray-500 dark:border-dark-neutral-bg py-[7px] px-[14px] dark:bg-gray-dark-200 text-[12px] leading-[18px] dark:text-gray-dark-500 hover:bg-gray-200 dark:hover:bg-gray-dark-200 hover:border-gray-300 dark:hover:border-gray-dark-300">Cancel</a>
                <button type="submit" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg font-medium dark:border-dark-neutral-bg py-[7px] px-[14px] text-[12px] leading-[18px] text-white">Save Post</button>
                </div>
            </div>

            <!-- Taxonomies Checklists -->
            @include('cms-core::layouts.partials.taxonomies')

            <!-- Featured Image Meta Box -->
            <div class="border border-neutral rounded-lg bg-neutral-bg dark:border-dark-neutral-border dark:bg-dark-neutral-bg overflow-hidden" x-data="featuredImage(null)">
                <div class="bg-neutral rounded-t-lg py-[15px] pl-[18px] dark:bg-dark-neutral-border">
                    <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[14px]">Featured Image</p>
                </div>
                <div class="px-5 py-4">
                    <input type="hidden" name="featured_image_id" x-model="imageId">

                    <div class="flex items-center gap-4">
                        {{-- Small thumbnail --}}
                        <div x-show="imageUrl" class="relative flex-shrink-0">
                            <div class="w-16 h-16 rounded-lg overflow-hidden border border-[#E8EDF2] dark:border-[#313442] bg-gray-100 dark:bg-[#1f2130]">
                                <img :src="imageUrl" alt="Featured image" class="w-full h-full object-cover">
                            </div>
                            <button type="button" @click="clearImage()" title="Remove"
                                class="absolute -top-1.5 -right-1.5 w-5 h-5 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-full shadow transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <p x-show="!imageId" class="text-xs text-gray-400 dark:text-gray-dark-500">No image set</p>
                            <button type="button" @click="pickImage()"
                                class="btn normal-case h-fit min-h-fit transition-all duration-200 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-xs py-[6px] px-[12px]">
                                <span class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="imageId ? 'Change Image' : 'Set Featured Image'"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function featuredImage(initialId, initialUrl) {
    return {
        imageId: initialId || null,
        imageUrl: initialUrl || null,
        pickImage() {
            window.openMediaPicker((media) => {
                this.imageId = media.id;
                this.imageUrl = media.url;
            });
        },
        clearImage() {
            this.imageId = null;
            this.imageUrl = null;
        }
    };
}
</script>
@endpush

@endsection
