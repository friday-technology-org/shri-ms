{{--
    Media Picker Modal
    ==================
    Usage: Include this partial anywhere in your view.
    To open from JS: window.openMediaPicker(callback)
      - callback receives the selected media object: { id, url, thumbnail_url, original_filename, alt_text }

    To trigger from Blade button:
      @include('cms-core::layouts.partials.media-picker-modal')
      <button onclick="window.openMediaPicker(function(m){ ... })">Select Image</button>
--}}
{{--
    The precompiled tailwind.min.css bundle used by the admin theme was built by
    scanning only the theme's original demo pages, so it doesn't include several
    utility classes this modal relies on (fixed/inset-0/z-[100], the grid, etc).
    Without them the panel rendered inline instead of as a fullscreen overlay.
    These scoped rules backfill exactly the missing ones so the modal actually
    pops up; they're namespaced under #cms-media-picker so nothing else is affected.
--}}
<style>
    #cms-media-picker.fixed { position: fixed; }
    #cms-media-picker.inset-0, #cms-media-picker .inset-0 { top: 0; right: 0; bottom: 0; left: 0; }
    #cms-media-picker.z-\[100\] { z-index: 1000; }
    #cms-media-picker .max-w-5xl { max-width: 64rem; }
    #cms-media-picker .max-w-md { max-width: 28rem; }
    #cms-media-picker .max-w-xs { max-width: 20rem; }
    #cms-media-picker .my-8 { margin-top: 2rem; margin-bottom: 2rem; }
    #cms-media-picker .mt-1 { margin-top: .25rem; }
    #cms-media-picker .mt-4 { margin-top: 1rem; }
    #cms-media-picker .py-10 { padding-top: 2.5rem; padding-bottom: 2.5rem; }
    #cms-media-picker .px-\[16px\] { padding-left: 16px; padding-right: 16px; }
    #cms-media-picker .py-\[8px\] { padding-top: 8px; padding-bottom: 8px; }
    #cms-media-picker .border-b-2 { border-bottom-width: 2px; }
    #cms-media-picker .overflow-y-auto { overflow-y: auto; }
    #cms-media-picker .transition-transform { transition-property: transform; }
    #cms-media-picker .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0,0,0,.25); }
    #cms-media-picker .bg-black\/60 { background-color: rgba(0,0,0,.6); }
    #cms-media-picker .bg-purple-50 { background-color: #faf5ff; }
    #cms-media-picker .text-gray-700 { color: #374151; }
    #cms-media-picker .text-gray-800 { color: #1f2937; }
    #cms-media-picker .disabled\:opacity-40:disabled { opacity: .4; }
    #cms-media-picker .hover\:border-color-brands:hover { border-color: var(--color-brands); }
    #cms-media-picker .group:hover .group-hover\:scale-105 { transform: scale(1.05); }
    #cms-media-picker .ring-2.ring-color-brands.ring-offset-2 { box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--color-brands); }
    .dark #cms-media-picker .dark\:bg-\[\#1e1b3a\] { background-color: #1e1b3a; }
    .dark #cms-media-picker .dark\:bg-\[\#1f2130\] { background-color: #1f2130; }
    .dark #cms-media-picker .ring-2.ring-color-brands.ring-offset-2 { box-shadow: 0 0 0 2px #1f2128, 0 0 0 4px var(--color-brands); }

    /* Grid: base + responsive column counts */
    #cms-media-picker .grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    #cms-media-picker .col-span-full { grid-column: 1 / -1; }
    #cms-media-picker .aspect-square { aspect-ratio: 1 / 1; }
    @media (min-width: 640px) {
        #cms-media-picker .sm\:grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
    @media (min-width: 768px) {
        #cms-media-picker .md\:grid-cols-5 { grid-template-columns: repeat(5, minmax(0, 1fr)); }
    }
    @media (min-width: 1024px) {
        #cms-media-picker .lg\:grid-cols-6 { grid-template-columns: repeat(6, minmax(0, 1fr)); }
    }
</style>
<div id="cms-media-picker"
     x-data="mediaPickerModal()"
     x-init="init()"
     style="display:none"
     class="fixed inset-0 z-[100] flex items-stretch">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60" @click="close()"></div>

    {{-- Modal --}}
    <div class="relative z-10 w-full max-w-5xl mx-auto my-8 flex flex-col bg-white dark:bg-dark-neutral-bg rounded-2xl shadow-2xl overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#E8EDF2] dark:border-[#313442]">
            <h3 class="font-bold text-gray-1100 dark:text-gray-dark-1100 text-lg">Select Media</h3>
            <button @click="close()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-[#313442] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Tabs: Upload | Library --}}
        <div class="flex border-b border-[#E8EDF2] dark:border-[#313442] px-6">
            <button @click="tab = 'upload'" :class="tab === 'upload' ? 'border-color-brands text-color-brands' : 'border-transparent text-gray-500 dark:text-gray-dark-500'" class="py-3 px-4 text-sm font-medium border-b-2 transition-colors">Upload Files</button>
            <button @click="tab = 'library'; loadLibrary()" :class="tab === 'library' ? 'border-color-brands text-color-brands' : 'border-transparent text-gray-500 dark:text-gray-dark-500'" class="py-3 px-4 text-sm font-medium border-b-2 transition-colors">Media Library</button>
        </div>

        {{-- Upload Tab --}}
        <div x-show="tab === 'upload'" class="flex-1 flex flex-col items-center justify-center p-10">
            <div class="w-full max-w-md">
                <div
                    @dragover.prevent="pickerDragOver = true"
                    @dragleave.prevent="pickerDragOver = false"
                    @drop.prevent="pickerUploadDrop($event)"
                    :class="pickerDragOver ? 'border-color-brands bg-purple-50 dark:bg-[#1e1b3a]' : 'border-[#E8EDF2] dark:border-[#313442]'"
                    class="border-2 border-dashed rounded-2xl p-10 text-center cursor-pointer transition-all"
                    @click="$refs.pickerFile.click()">
                    <input type="file" x-ref="pickerFile" multiple class="hidden" @change="pickerUpload($event)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-dark-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="font-medium text-gray-700 dark:text-gray-dark-1100 text-sm">Drop or click to upload</p>
                    <p class="text-xs text-gray-400 mt-1">Images, docs, video — up to 50 MB</p>
                </div>
                <div x-show="pickerUploading" class="mt-4">
                    <div class="flex justify-between mb-1">
                        <span class="text-xs text-gray-500" x-text="pickerStatus"></span>
                        <span class="text-xs text-color-brands font-bold" x-text="pickerProgress + '%'"></span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-[#313442] rounded-full h-2">
                        <div class="bg-color-brands h-2 rounded-full transition-all" :style="'width:' + pickerProgress + '%'"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Library Tab --}}
        <div x-show="tab === 'library'" class="flex-1 overflow-y-auto p-6">
            {{-- Search --}}
            <div class="mb-4">
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] flex items-center pr-3 max-w-xs">
                    <input type="text" x-model="search" @input.debounce.300ms="loadLibrary()" placeholder="Search files..."
                        class="input bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-2 focus:outline-none pl-[13px] placeholder:text-gray-400 w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            {{-- Grid --}}
            <div x-show="!loading" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
                <template x-for="item in items" :key="item.id">
                    <div
                        @click="selectItem(item)"
                        :class="selected?.id === item.id ? 'ring-2 ring-color-brands ring-offset-2' : ''"
                        class="group cursor-pointer rounded-xl overflow-hidden border border-[#E8EDF2] dark:border-[#313442] hover:border-color-brands transition-all aspect-square bg-gray-100 dark:bg-[#1f2130] relative">
                        <template x-if="item.is_image">
                            <img :src="item.thumbnail_url" :alt="item.alt_text" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </template>
                        <template x-if="!item.is_image">
                            <div class="w-full h-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </template>
                        {{-- Check mark --}}
                        <div x-show="selected?.id === item.id" class="absolute top-1 right-1 w-5 h-5 bg-color-brands rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </template>
                <div x-show="items.length === 0" class="col-span-full text-center py-10 text-gray-400 dark:text-gray-dark-500 text-sm">No files found.</div>
            </div>
            <div x-show="loading" class="text-center py-10 text-gray-400 text-sm">Loading...</div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between px-6 py-4 border-t border-[#E8EDF2] dark:border-[#313442]">
            <div x-show="selected" class="text-sm text-gray-600 dark:text-gray-dark-500 truncate max-w-xs">
                Selected: <span class="font-medium text-gray-800 dark:text-gray-dark-1100" x-text="selected?.original_filename"></span>
            </div>
            <div x-show="!selected" class="text-sm text-gray-400">No item selected</div>
            <div class="flex gap-3">
                <button @click="close()" class="btn normal-case h-fit min-h-fit border-4 border-[#E8EDF2] dark:border-[#313442] bg-neutral-bg dark:bg-dark-neutral-bg text-gray-500 dark:text-gray-dark-500 py-[8px] px-[16px] text-sm hover:border-gray-300 transition-all">Cancel</button>
                <button @click="confirm()" :disabled="!selected"
                    class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white py-[8px] px-[16px] text-sm transition-all disabled:opacity-40">
                    Select
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function mediaPickerModal() {
    return {
        tab: 'library',
        items: [],
        search: '',
        selected: null,
        loading: false,
        pickerDragOver: false,
        pickerUploading: false,
        pickerProgress: 0,
        pickerStatus: '',
        _callback: null,

        init() {
            // Expose globally
            window.openMediaPicker = (callback) => {
                this._callback = callback;
                this.selected = null;
                this.tab = 'library';
                this.loadLibrary();
                document.getElementById('cms-media-picker').style.display = 'flex';
            };
        },

        close() {
            document.getElementById('cms-media-picker').style.display = 'none';
            this.selected = null;
            this.items = [];
        },

        confirm() {
            if (!this.selected) return;
            if (this._callback) this._callback(this.selected);
            this.close();
        },

        selectItem(item) {
            this.selected = this.selected?.id === item.id ? null : item;
        },

        async loadLibrary() {
            this.loading = true;
            const url = new URL('/admin/media/items', window.location.origin);
            if (this.search) url.searchParams.set('search', this.search);

            try {
                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                this.items = data.items || [];
            } catch (e) {
                this.items = [];
            }

            this.loading = false;
        },

        async pickerUpload(e) {
            await this._doUpload(e.target.files);
        },

        async pickerUploadDrop(e) {
            this.pickerDragOver = false;
            await this._doUpload(e.dataTransfer.files);
        },

        async _doUpload(files) {
            this.pickerUploading = true;
            this.pickerProgress = 0;
            this.pickerStatus = 'Uploading...';

            const formData = new FormData();
            Array.from(files).forEach(f => formData.append('files[]', f));
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

            const xhr = new XMLHttpRequest();
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) this.pickerProgress = Math.round((e.loaded / e.total) * 100);
            });

            await new Promise((resolve) => {
                xhr.onload = () => {
                    const data = JSON.parse(xhr.responseText);
                    this.pickerUploading = false;
                    if (data.success && data.uploaded.length > 0) {
                        this.pickerStatus = 'Done!';
                        // Switch to library and show the newly uploaded file
                        this.tab = 'library';
                        this.items = [data.uploaded[0], ...this.items];
                        this.selectItem(data.uploaded[0]);
                    } else {
                        this.pickerStatus = 'Upload failed.';
                    }
                    resolve();
                };
                xhr.onerror = resolve;
                xhr.open('POST', '/admin/media/upload');
                xhr.send(formData);
            });
        }
    };
}
</script>
