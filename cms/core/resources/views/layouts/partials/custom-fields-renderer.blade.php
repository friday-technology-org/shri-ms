{{-- 
    $field : The field definition array/object 
    $namePrefix : e.g. "meta[group_name]"
    $idPrefix : e.g. "meta_group_name"
    $value : The value of this field 
--}}

@php
    $type = is_object($field) ? $field->type : ($field['type'] ?? 'text');
    $label = is_object($field) ? $field->label : ($field['label'] ?? '');
    $name = is_object($field) ? $field->name : ($field['name'] ?? '');
    $instructions = is_object($field) ? $field->instructions : ($field['instructions'] ?? '');
    $required = is_object($field) ? $field->required : ($field['required'] ?? false);
    $subFields = is_object($field) ? ($field->settings['sub_fields'] ?? []) : ($field['settings']['sub_fields'] ?? []);
    
    $currentNamePrefix = isset($namePrefix) && $namePrefix ? "{$namePrefix}[{$name}]" : "meta[{$name}]";
    $currentIdPrefix = isset($idPrefix) && $idPrefix ? "{$idPrefix}_{$name}" : "meta_{$name}";
@endphp
<div class="field-wrapper border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden shadow-sm bg-white dark:bg-[#161824]">
    <div class="field-header px-5 py-3 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-[#1b1e2b]">
        <p class="text-gray-800 text-sm leading-5 font-semibold capitalize dark:text-gray-200 m-0">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </p>

        @if($instructions)
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-0">{{ $instructions }}</p>
        @endif
    </div>
    
    <div class="field-body p-5">

    @if($type === 'group')
        <div class="pl-4 border-l-2 border-neutral dark:border-dark-neutral-border space-y-6 my-4">
            @foreach($subFields as $subField)
                @php
                    $subFieldName = $subField['name'] ?? '';
                    $subFieldValue = is_array($value) ? ($value[$subFieldName] ?? '') : '';
                @endphp
                @include('cms-core::layouts.partials.custom-fields-renderer', [
                    'field' => $subField,
                    'namePrefix' => $currentNamePrefix,
                    'idPrefix' => $currentIdPrefix,
                    'value' => $subFieldValue
                ])
            @endforeach
        </div>

    @elseif($type === 'repeater')
        @php
            $rows = is_array($value) ? $value : [];
            // Ensure rows are arrays
            foreach($rows as $k => $r) { if(!is_array($r)) { $rows[$k] = []; } }
        @endphp
        
        <div class="repeater-container border border-neutral rounded-lg p-4 bg-gray-50 dark:bg-[#1f2130] dark:border-dark-neutral-border"
             x-data="{
                initRepeater() {
                    this.$nextTick(() => {
                        if (typeof Sortable !== 'undefined' && this.$refs.sortableContainer) {
                            new Sortable(this.$refs.sortableContainer, {
                                handle: '.repeater-drag-handle',
                                animation: 150,
                                onEnd: (evt) => {
                                    this.updateNames(this.$refs.sortableContainer);
                                }
                            });
                        }
                    });
                },
                addRow() {
                    const tpl = this.$refs.rowTemplate.innerHTML;
                    const index = Date.now() + Math.floor(Math.random() * 1000); // unique enough
                    const newRowHtml = tpl.replace(/__INDEX__/g, index);
                    
                    const div = document.createElement('div');
                    div.innerHTML = newRowHtml;
                    const rowEl = div.firstElementChild;
                    
                    this.$refs.sortableContainer.appendChild(rowEl);
                    this.updateNames(this.$refs.sortableContainer);
                },
                updateNames(container) {
                    const rows = container.children;
                    const baseName = '{{ $currentNamePrefix }}';
                    const baseId = '{{ $currentIdPrefix }}';
                    
                    Array.from(rows).forEach((row, rowIndex) => {
                        const rowLabel = row.querySelector('.row-label-index');
                        if (rowLabel) rowLabel.innerText = rowIndex + 1;
                        
                        const nameRegex = new RegExp('^' + baseName.replace(/[\[\]]/g, '\\$&') + '\\[([a-zA-Z0-9_]+)\\]');
                        const idRegex = new RegExp('^' + baseId.replace(/[\[\]]/g, '\\$&') + '_([a-zA-Z0-9_]+)');
                        
                        const elements = row.querySelectorAll('[name], [id]');
                        elements.forEach(el => {
                            if (el.hasAttribute('name')) {
                                el.name = el.name.replace(nameRegex, baseName + '[' + rowIndex + ']');
                            }
                            if (el.hasAttribute('id')) {
                                el.id = el.id.replace(idRegex, baseId + '_' + rowIndex);
                            }
                        });
                    });
                }
             }"
             x-init="initRepeater()">
            
            <div class="repeater-rows space-y-4" x-ref="sortableContainer">
                @foreach($rows as $index => $rowValue)
                    <div class="repeater-row relative bg-white dark:bg-dark-neutral-bg p-5 border border-[#E8EDF2] dark:border-[#313442] rounded-lg shadow-sm group">
                        <div class="flex justify-between items-center mb-4 pb-2 border-b border-[#E8EDF2] dark:border-[#313442]">
                            <div class="flex items-center gap-3">
                                <div class="cursor-move repeater-drag-handle text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded transition-colors" title="Drag to reorder">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Row <span class="row-label-index">{{ $index + 1 }}</span></span>
                            </div>
                            <button type="button" @click="$event.target.closest('.repeater-row').remove(); updateNames($refs.sortableContainer)" class="flex items-center gap-1 text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 dark:text-red-400 dark:bg-red-900/20 dark:hover:bg-red-900/40 dark:hover:text-red-300 rounded px-3 py-1.5 transition-colors text-xs font-bold whitespace-nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                Remove Row
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-y-4">
                            @foreach($subFields as $subField)
                                @php
                                    $subFieldName = $subField['name'];
                                    $subFieldValue = is_array($rowValue) ? ($rowValue[$subFieldName] ?? '') : '';
                                @endphp
                                @include('cms-core::layouts.partials.custom-fields-renderer', [
                                    'field' => $subField,
                                    'namePrefix' => "{$currentNamePrefix}[{$index}]",
                                    'idPrefix' => "{$currentIdPrefix}_{$index}",
                                    'value' => $subFieldValue
                                ])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
            <template x-ref="rowTemplate">
                <div class="repeater-row relative bg-white dark:bg-dark-neutral-bg p-5 border border-[#E8EDF2] dark:border-[#313442] rounded-lg shadow-sm group">
                    <div class="flex justify-between items-center mb-4 pb-2 border-b border-[#E8EDF2] dark:border-[#313442]">
                        <div class="flex items-center gap-3">
                            <div class="cursor-move repeater-drag-handle text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded transition-colors" title="Drag to reorder">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Row <span class="row-label-index"></span></span>
                        </div>
                        <button type="button" @click="$event.target.closest('.repeater-row').remove(); updateNames($refs.sortableContainer)" class="flex items-center gap-1 text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 dark:text-red-400 dark:bg-red-900/20 dark:hover:bg-red-900/40 dark:hover:text-red-300 rounded px-3 py-1.5 transition-colors text-xs font-bold whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Remove Row
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-y-4">
                        @foreach($subFields as $subField)
                            @include('cms-core::layouts.partials.custom-fields-renderer', [
                                'field' => $subField,
                                'namePrefix' => "{$currentNamePrefix}[__INDEX__]",
                                'idPrefix' => "{$currentIdPrefix}___INDEX__",
                                'value' => ''
                            ])
                        @endforeach
                    </div>
                </div>
            </template>
            <button type="button" @click="addRow()" class="mt-4 btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] text-white py-[6px] px-[16px] rounded flex items-center gap-2 text-sm whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Add Row
            </button>
        </div>

    @elseif($type === 'textarea')
        <textarea name="{{ $currentNamePrefix }}" id="{{ $currentIdPrefix }}" class="textarea w-full text-gray-800 dark:text-white resize-y rounded-lg bg-transparent border border-[#E8EDF2] dark:border-[#313442] p-4 min-h-[100px] focus:outline-none placeholder:text-inherit" {{ $required ? 'required' : '' }}>{{ $value }}</textarea>
    
    @elseif($type === 'wysiwyg')
        @include('cms-core::layouts.partials.wysiwyg-editor', [
            'name' => $currentNamePrefix,
            'fieldId' => $currentIdPrefix,
            'value' => $value,
            'height' => '250px'
        ])
    
    @elseif($type === 'number')
        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
            <input name="{{ $currentNamePrefix }}" id="{{ $currentIdPrefix }}" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-inherit" type="number" value="{{ $value }}" {{ $required ? 'required' : '' }}>
        </div>
    
    @elseif($type === 'url')
        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
            <input name="{{ $currentNamePrefix }}" id="{{ $currentIdPrefix }}" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-inherit" type="url" value="{{ $value }}" {{ $required ? 'required' : '' }}>
        </div>

    @elseif($type === 'image' || $type === 'file')
        @php
            $existingMedia = null;
            if ($value && is_numeric($value)) {
                $existingMedia = \Cms\Core\Models\Media::find((int) $value);
            }
            $existingMediaId   = $existingMedia ? $existingMedia->id : 'null';
            $existingMediaUrl  = $existingMedia ? "'" . addslashes($existingMedia->thumbnailUrl('medium')) . "'" : 'null';
        @endphp

        <div
            x-data="{
                imageId:   {{ $existingMediaId }},
                imageUrl:  {{ $existingMediaUrl }},
                pickImage() {
                    window.openMediaPicker((media) => {
                        this.imageId   = media.id;
                        this.imageUrl  = media.medium_url || media.url;
                    });
                },
                clearImage() {
                    this.imageId   = null;
                    this.imageUrl  = null;
                }
            }"
            class="flex items-center gap-4"
        >
            {{-- Hidden input stores the media ID --}}
            <input type="hidden" name="{{ $currentNamePrefix }}" id="{{ $currentIdPrefix }}" x-model="imageId">

            {{-- Compact thumbnail preview (only when an image is set) --}}
            <div x-show="imageUrl" class="relative flex-shrink-0">
                <div class="w-20 h-20 rounded-xl overflow-hidden border border-[#E8EDF2] dark:border-[#313442] bg-gray-100 dark:bg-[#1f2130]">
                    <img :src="imageUrl" alt="Selected" class="w-full h-full object-cover">
                </div>
                {{-- Small ✕ remove button --}}
                <button
                    type="button"
                    @click="clearImage()"
                    class="absolute -top-1.5 -right-1.5 w-5 h-5 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-full shadow transition-colors"
                    title="Remove media"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Label + button column --}}
            <div class="flex flex-col gap-1.5 min-w-0">
                <p x-show="!imageId" class="text-xs text-gray-400 dark:text-gray-dark-500">No media selected</p>

                <button
                    type="button"
                    @click="pickImage()"
                    class="self-start btn normal-case h-fit min-h-fit transition-all duration-200 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-xs py-[6px] px-[12px]"
                >
                    <span class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span x-text="imageId ? 'Change {{ ucfirst($type) }}' : 'Select {{ ucfirst($type) }}'"></span>
                    </span>
                </button>
            </div>
        </div>
    
    @else
        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
            <input name="{{ $currentNamePrefix }}" id="{{ $currentIdPrefix }}" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-inherit" type="text" value="{{ $value }}" {{ $required ? 'required' : '' }}>
        </div>
    @endif
    </div>
</div>
