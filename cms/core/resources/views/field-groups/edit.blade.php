@extends('cms-core::layouts.admin')

@section('title', 'Edit Field Group - Shri-ms')

@section('content')
<form action="{{ route('cms.field-groups.update', $fieldGroup->id) }}" method="POST" id="field-group-form">
    @csrf
    @method('PUT')
    <div>
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Edit Field Group</h2>
        <div class="flex justify-between flex-col gap-y-2 sm:flex-row mb-[54px]">
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">Custom Fields</span><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">Edit</span>
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
        
            <div class="mb-8">
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Field Group Title</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                <input name="title" class="input w-full bg-transparent text-sm leading-4 text-gray-800 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-white placeholder:text-inherit" type="text" placeholder="e.g., Homepage Settings" value="{{ old('title', $fieldGroup->title) }}" required>
                </div>
            </div>

            @php
                $ruleParam = $fieldGroup->location_rules[0]['param'] ?? 'post_type';
                $ruleValue = $fieldGroup->location_rules[0]['value'] ?? 'post';
            @endphp

            <!-- Location Rules -->
            <div class="mb-12 border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden shadow-sm bg-white dark:bg-[#161824]" x-data="{ param: '{{ $ruleParam }}' }">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-[#1b1e2b]">
                    <h3 class="text-gray-900 text-base font-semibold dark:text-gray-100 m-0">Location Rules</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 mb-0">Show this field group if</p>
                </div>
                <div class="p-6">
                
                <div class="flex flex-wrap items-center gap-4 md:gap-6">
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full flex-1 min-w-[200px]">
                        <select name="location_rules[0][param]" x-model="param" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            <option value="post_type" class="bg-white dark:bg-dark-neutral-bg">Post Type</option>
                            <option value="page" class="bg-white dark:bg-dark-neutral-bg">Page</option>
                            <option value="page_template" class="bg-white dark:bg-dark-neutral-bg">Page Template</option>
                            <option value="taxonomy" class="bg-white dark:bg-dark-neutral-bg">Taxonomy</option>
                        </select>
                    </div>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full flex-1 min-w-[200px]">
                        <select name="location_rules[0][operator]" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            <option value="==" class="bg-white dark:bg-dark-neutral-bg">is equal to</option>
                            <option value="!=" class="bg-white dark:bg-dark-neutral-bg">is not equal to</option>
                        </select>
                    </div>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full flex-1 min-w-[200px]">
                        <select x-show="param === 'post_type'" :name="param === 'post_type' ? 'location_rules[0][value]' : ''" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            @foreach($locationOptions['post_types'] as $pt)
                                <option value="{{ $pt->name }}" {{ ($ruleParam === 'post_type' && $ruleValue == $pt->name) ? 'selected' : '' }} class="bg-white dark:bg-dark-neutral-bg">{{ $pt->singular_label }}</option>
                            @endforeach
                        </select>
                        <select x-cloak x-show="param === 'page'" :name="param === 'page' ? 'location_rules[0][value]' : ''" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            @foreach($locationOptions['pages'] as $page)
                                <option value="{{ $page->id }}" {{ ($ruleParam === 'page' && $ruleValue == $page->id) ? 'selected' : '' }} class="bg-white dark:bg-dark-neutral-bg">{{ $page->title }}</option>
                            @endforeach
                        </select>
                        <select x-cloak x-show="param === 'page_template'" :name="param === 'page_template' ? 'location_rules[0][value]' : ''" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            @foreach($locationOptions['templates'] as $file => $name)
                                <option value="{{ $file }}" {{ ($ruleParam === 'page_template' && $ruleValue == $file) ? 'selected' : '' }} class="bg-white dark:bg-dark-neutral-bg">{{ $name }}</option>
                            @endforeach
                        </select>
                        <select x-cloak x-show="param === 'taxonomy'" :name="param === 'taxonomy' ? 'location_rules[0][value]' : ''" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            @foreach($locationOptions['taxonomies'] as $tax)
                                <option value="{{ $tax->name }}" {{ ($ruleParam === 'taxonomy' && $ruleValue == $tax->name) ? 'selected' : '' }} class="bg-white dark:bg-dark-neutral-bg">{{ $tax->singular_label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                </div> <!-- Closing .p-6 -->
            </div>

            <!-- Fields Builder -->
            <div class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-gray-1100 text-lg font-bold dark:text-gray-dark-1100">Fields</h3>
                    <button type="button" id="add-field-btn" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] text-white py-[6px] px-[16px]">
                        + Add Field
                    </button>
                </div>

                <div id="fields-container" class="flex flex-col gap-6">
                    <!-- Fields will be dynamically added here -->
                </div>
            </div>

            <div class="w-full bg-neutral h-[1px] dark:bg-dark-neutral-border mb-6"></div>
            
            <div class="flex items-center gap-4">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $fieldGroup->is_active ? 'checked' : '' }} class="checkbox checkbox-primary rounded border border-neutral dark:border-dark-neutral-border w-[18px] h-[18px]">
                <label for="is_active" class="text-sm font-medium text-gray-800 dark:text-gray-dark-500">Active (Show this field group)</label>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="submit" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg font-medium dark:border-dark-neutral-bg py-[10px] px-[24px] text-sm text-white">Update Field Group</button>
                <a href="{{ route('cms.field-groups.index') }}" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 border-neutral-bg bg-gray-200 font-medium text-gray-500 dark:border-dark-neutral-bg py-[10px] px-[24px] dark:bg-gray-dark-200 text-sm dark:text-gray-dark-500 hover:bg-gray-200 dark:hover:bg-gray-dark-200 hover:border-gray-300 dark:hover:border-gray-dark-300">Cancel</a>
            </div>
        </div>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('field-group-form');
    const container = document.getElementById('fields-container');
    const addBtn = document.getElementById('add-field-btn');

    const existingFields = @json($fieldGroup->fields);

    function createFieldRow(data = {}, isSubField = false) {
        const row = document.createElement('div');
        row.className = `field-row-generic bg-white dark:bg-[#161824] border border-[#E8EDF2] dark:border-gray-800 rounded-lg shadow-sm relative overflow-hidden ${isSubField ? 'mt-4' : 'mb-6'}`;
        
        const dragHandleClass = isSubField ? 'sub-drag-handle' : 'drag-handle';
        const titleText = isSubField ? 'Sub Field' : 'Field Configuration';

        row.innerHTML = `
            <div class="field-content">
                <div class="flex justify-between items-center py-3 px-5 border-b border-[#E8EDF2] dark:border-gray-800 bg-gray-50 dark:bg-[#1b1e2b]">
                    <div class="flex items-center gap-3">
                        <div class="cursor-move ${dragHandleClass} text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-200 dark:hover:bg-gray-800 rounded transition-colors" title="Drag to reorder">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-800 dark:text-gray-200">${titleText}</span>
                    </div>
                    <button type="button" class="remove-field-btn flex items-center gap-1 text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 dark:text-red-400 dark:bg-red-900/20 dark:hover:bg-red-900/40 dark:hover:text-red-300 rounded px-3 py-1.5 transition-colors text-xs font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Remove
                    </button>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <p class="text-gray-1100 text-xs font-bold capitalize mb-[5px] dark:text-gray-dark-1100">Label</p>
                        <input type="text" data-name="label" class="field-label input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-2 focus:outline-none pl-[13px] border rounded border-[#E8EDF2] dark:border-[#313442] placeholder:text-inherit" value="${data.label || ''}" required placeholder="Field Label">
                    </div>
                    <div>
                        <p class="text-gray-1100 text-xs font-bold capitalize mb-[5px] dark:text-gray-dark-1100">Name (slug)</p>
                        <input type="text" data-name="name" class="field-name input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-2 focus:outline-none pl-[13px] border rounded border-[#E8EDF2] dark:border-[#313442] placeholder:text-inherit" value="${data.name || ''}" required placeholder="field_name" data-modified="${data.name ? 'true' : ''}">
                    </div>
                    <div>
                        <p class="text-gray-1100 text-xs font-bold capitalize mb-[5px] dark:text-gray-dark-1100">Type</p>
                        <select data-name="type" class="field-type-select select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-2 focus:outline-none px-[13px] border rounded border-[#E8EDF2] dark:border-[#313442]">
                            <option value="text" ${data.type === 'text' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Text</option>
                            <option value="textarea" ${data.type === 'textarea' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Textarea</option>
                            <option value="wysiwyg" ${data.type === 'wysiwyg' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">WYSIWYG Editor</option>
                            <option value="image" ${data.type === 'image' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Image</option>
                            <option value="number" ${data.type === 'number' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Number</option>
                            <option value="group" ${data.type === 'group' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Group</option>
                            <option value="repeater" ${data.type === 'repeater' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Repeater</option>
                            <option value="url" ${data.type === 'url' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">URL</option>
                            <option value="file" ${data.type === 'file' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">File</option>
                        </select>
                    </div>
                </div>
                ${!isSubField ? `
                <div class="mb-4">
                    <p class="text-gray-1100 text-xs font-bold capitalize mb-[5px] dark:text-gray-dark-1100">Instructions (optional)</p>
                    <input type="text" data-name="instructions" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-2 focus:outline-none pl-[13px] border rounded border-[#E8EDF2] dark:border-[#313442] placeholder:text-inherit" value="${data.instructions || ''}" placeholder="Instructions for authors">
                </div>
                ` : ''}
                
                <div class="sub-fields-container mt-4 p-4 bg-gray-50 dark:bg-[#252734] border border-[#E8EDF2] dark:border-[#313442] rounded-lg ${(data.type === 'repeater' || data.type === 'group') ? 'block' : 'hidden'}">
                    <p class="sub-fields-title text-gray-1100 text-sm font-bold capitalize mb-[15px] dark:text-gray-dark-1100">${data.type === 'repeater' ? 'Repeater Sub-Fields' : 'Group Sub-Fields'}</p>
                    <div class="sub-fields-list mb-4 pl-4 border-l-2 border-[#E8EDF2] dark:border-[#313442] min-h-[10px]"></div>
                    <button type="button" class="add-sub-field-btn btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] text-white py-[6px] px-[16px] rounded flex items-center gap-2 text-sm whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Add Sub-Field
                    </button>
                </div>
                </div> <!-- Closing .p-5 -->
            </div>
        `;

        const labelInput = row.querySelector('.field-label');
        const nameInput = row.querySelector('.field-name');
        const typeSelect = row.querySelector('.field-type-select');
        const subFieldsContainer = row.querySelector('.sub-fields-container');
        const subFieldsList = row.querySelector('.sub-fields-list');
        const addSubFieldBtn = row.querySelector('.add-sub-field-btn');
        
        labelInput.addEventListener('input', function() {
            if (!nameInput.dataset.modified) {
                let slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/(^_|_$)/g, '');
                nameInput.value = slug;
            }
        });
        
        nameInput.addEventListener('input', function() {
            this.dataset.modified = true;
        });

        typeSelect.addEventListener('change', function() {
            if (this.value === 'repeater' || this.value === 'group') {
                subFieldsContainer.classList.remove('hidden');
                subFieldsContainer.classList.add('block');
                const titleEl = subFieldsContainer.querySelector('.sub-fields-title');
                if (titleEl) titleEl.innerText = this.value === 'repeater' ? 'Repeater Sub-Fields' : 'Group Sub-Fields';
                if (subFieldsList.children.length === 0) {
                    subFieldsList.appendChild(createFieldRow({}, true));
                }
                
                if (!subFieldsList.sortableInstance && typeof Sortable !== 'undefined') {
                    subFieldsList.sortableInstance = new Sortable(subFieldsList, {
                        handle: '.sub-drag-handle',
                        animation: 150
                    });
                }
            } else {
                subFieldsContainer.classList.add('hidden');
                subFieldsContainer.classList.remove('block');
            }
        });

        row.querySelector('.remove-field-btn').addEventListener('click', function() {
            row.remove();
        });

        addSubFieldBtn.addEventListener('click', function() {
            subFieldsList.appendChild(createFieldRow({}, true));
        });

        // Load existing sub-fields if any
        if ((data.type === 'repeater' || data.type === 'group') && data.settings && data.settings.sub_fields) {
            data.settings.sub_fields.forEach(subData => {
                subFieldsList.appendChild(createFieldRow(subData, true));
            });
            if (!subFieldsList.sortableInstance && typeof Sortable !== 'undefined') {
                subFieldsList.sortableInstance = new Sortable(subFieldsList, {
                    handle: '.sub-drag-handle',
                    animation: 150
                });
            }
        }

        return row;
    }

    addBtn.addEventListener('click', (e) => {
        e.preventDefault();
        container.appendChild(createFieldRow());
    });
    
    // Load existing fields
    if (existingFields.length > 0) {
        existingFields.forEach(field => container.appendChild(createFieldRow(field)));
    } else {
        container.appendChild(createFieldRow());
    }

    // Initialize top-level sortable
    if (typeof Sortable !== 'undefined') {
        new Sortable(container, {
            handle: '.drag-handle',
            animation: 150
        });
    }

    function updateFieldNames(containerEl, prefix) {
        const fieldRows = containerEl.children;
        Array.from(fieldRows).forEach((row, i) => {
            if (!row.classList.contains('field-row-generic')) return;
            
            const currentPrefix = `${prefix}[${i}]`;
            
            // Get inputs that belong strictly to this field, not its children
            const inputs = Array.from(row.querySelectorAll('[data-name]')).filter(el => el.closest('.field-row-generic') === row);
            inputs.forEach(input => {
                const propName = input.getAttribute('data-name');
                input.name = `${currentPrefix}[${propName}]`;
            });
            
            // Update sub-fields container if exists
            const subList = Array.from(row.querySelectorAll('.sub-fields-list')).find(el => el.closest('.field-row-generic') === row);
            if (subList) {
                updateFieldNames(subList, `${currentPrefix}[settings][sub_fields]`);
            }
        });
    }

    // On form submit, update field indexes so they match DOM order
    form.addEventListener('submit', function(e) {
        updateFieldNames(container, 'fields');
    });
});
</script>
@endsection
