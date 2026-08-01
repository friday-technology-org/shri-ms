@extends('cms-core::layouts.admin')

@section('title', 'Edit Term - Shri-ms')

@section('content')
<form action="{{ route('cms.terms.update', [$taxonomy->id, $term->id]) }}" method="POST">
    @csrf
    @method('PUT')
    <div>
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Edit {{ rtrim($taxonomy->name, 's') }}</h2>
        <div class="flex justify-between flex-col gap-y-2 sm:flex-row mb-[54px]">
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">Settings</span><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">Edit {{ rtrim($taxonomy->name, 's') }}</span>
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
                    <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Name</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                        <input type="text" name="name" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-4 focus:outline-none pl-[13px] placeholder:text-inherit" required value="{{ old('name', $term->name) }}">
                    </div>
                </div>
                <div>
                    <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Slug</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                        <input type="text" name="slug" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-4 focus:outline-none pl-[13px] placeholder:text-inherit" required value="{{ old('slug', $term->slug) }}">
                    </div>
                </div>
            </div>

            @if($taxonomy->hierarchical)
            <div class="mb-8">
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Parent {{ rtrim($taxonomy->name, 's') }}</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full">
                    <select name="parent_id" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-4 focus:outline-none px-[13px]">
                        <option value="" class="bg-white dark:bg-dark-neutral-bg">None</option>
                        @foreach($parentTerms as $pt)
                            <option value="{{ $pt->id }}" {{ $term->parent_id == $pt->id ? 'selected' : '' }} class="bg-white dark:bg-dark-neutral-bg">{{ $pt->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            @if($fieldGroups && $fieldGroups->count() > 0)
                @include('cms-core::layouts.partials.custom-fields', ['post' => $term])
            @endif

            <div class="mb-8">
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Description</p>
                <textarea name="description" class="textarea w-full text-gray-800 dark:text-white resize-none rounded-lg bg-transparent border border-[#E8EDF2] dark:border-[#313442] p-4 min-h-[120px] focus:outline-none placeholder:text-inherit" rows="4">{{ old('description', $term->description) }}</textarea>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="submit" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] text-white py-[10px] px-[24px]">Update {{ rtrim($taxonomy->name, 's') }}</button>
            </div>
        </div>
    </div>
</form>
@endsection
