@if($fieldGroups && $fieldGroups->count() > 0)
    <div class="mt-8">
        @foreach($fieldGroups as $group)
            <div class="border border-neutral rounded-lg bg-neutral-bg dark:border-dark-neutral-border pb-6 dark:bg-dark-neutral-bg mb-6">
                <div class="bg-neutral rounded-t-lg py-[15px] pl-[18px] mb-[27px] dark:bg-dark-neutral-border">
                    <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px]">{{ $group->title }}</p>
                </div>
                
                <div class="px-5 flex flex-col gap-6">
                    @foreach($group->fields as $field)
                        @php
                            $metaValue = isset($post) ? $post->getMeta($field->name, $field->default_value) : old('meta.'.$field->name, $field->default_value);
                        @endphp
                        
                        @include('cms-core::layouts.partials.custom-fields-renderer', [
                            'field' => $field,
                            'namePrefix' => 'meta',
                            'idPrefix' => 'meta',
                            'value' => $metaValue
                        ])
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    @endpush
@endif
