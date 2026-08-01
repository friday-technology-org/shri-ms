@php
    $postType = isset($page) ? 'page' : 'post';
    $applicableTaxonomies = \Cms\Core\Models\Taxonomy::whereJsonContains('post_types', $postType)->with('terms')->get();
@endphp

@if($applicableTaxonomies->count() > 0)
    <div class="mt-8">
        @foreach($applicableTaxonomies as $taxonomy)
            <div class="border border-neutral rounded-lg bg-neutral-bg dark:border-dark-neutral-border pb-6 dark:bg-dark-neutral-bg mb-6">
                <div class="bg-neutral rounded-t-lg py-[15px] pl-[18px] mb-[4px] dark:bg-dark-neutral-border">
                    <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px]">{{ $taxonomy->name }}</p>
                </div>
                
                <div class="px-5 pt-4 max-h-64 overflow-y-auto">
                    @if($taxonomy->hierarchical)
                        <!-- Checkboxes for Hierarchical (like Categories) -->
                        <div class="flex flex-col gap-2">
                            @foreach($taxonomy->terms()->whereNull('parent_id')->get() as $term)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="terms[]" value="{{ $term->id }}" 
                                        {{ isset($post) && $post->terms->contains($term->id) ? 'checked' : '' }}
                                        {{ isset($page) && $page->terms->contains($term->id) ? 'checked' : '' }}
                                        class="w-4 h-4 text-color-brands dark:bg-[#1A1C23] dark:border-[#313442]"> 
                                    <span class="text-sm dark:text-gray-300">{{ $term->name }}</span>
                                </label>
                                @foreach($term->children ?? [] as $child)
                                    <label class="flex items-center gap-2 ml-4">
                                        <input type="checkbox" name="terms[]" value="{{ $child->id }}" 
                                            {{ isset($post) && $post->terms->contains($child->id) ? 'checked' : '' }}
                                            {{ isset($page) && $page->terms->contains($child->id) ? 'checked' : '' }}
                                            class="w-4 h-4 text-color-brands dark:bg-[#1A1C23] dark:border-[#313442]"> 
                                        <span class="text-sm dark:text-gray-300">{{ $child->name }}</span>
                                    </label>
                                @endforeach
                            @endforeach
                        </div>
                    @else
                        <!-- Simple Checkboxes for Flat (like Tags) -->
                        <div class="flex flex-col gap-2">
                            @foreach($taxonomy->terms as $term)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="terms[]" value="{{ $term->id }}" 
                                        {{ isset($post) && $post->terms->contains($term->id) ? 'checked' : '' }}
                                        {{ isset($page) && $page->terms->contains($term->id) ? 'checked' : '' }}
                                        class="w-4 h-4 text-color-brands dark:bg-[#1A1C23] dark:border-[#313442]"> 
                                    <span class="text-sm dark:text-gray-300">{{ $term->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
