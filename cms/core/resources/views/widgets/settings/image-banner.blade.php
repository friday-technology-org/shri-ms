@php
    $existingMedia = !empty($settings['media_id']) ? \Cms\Core\Models\Media::find($settings['media_id']) : null;
@endphp
<div x-data="{
    imageId: {{ $existingMedia?->id ?? 'null' }},
    imageUrl: {{ $existingMedia ? "'" . addslashes($existingMedia->thumbnailUrl('medium')) . "'" : 'null' }},
    pickImage() { window.openMediaPicker((media) => { this.imageId = media.id; this.imageUrl = media.url; }); },
    clearImage() { this.imageId = null; this.imageUrl = null; }
}" class="flex flex-col gap-3">
    <div>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-1">Banner Image</p>
        <input type="hidden" name="settings[media_id]" x-model="imageId">
        <div class="flex items-center gap-3">
            <div x-show="imageUrl" class="relative flex-shrink-0">
                <div class="w-14 h-14 rounded-lg overflow-hidden border border-[#E8EDF2] dark:border-[#313442] bg-gray-100 dark:bg-[#1f2130]">
                    <img :src="imageUrl" alt="Banner" class="w-full h-full object-cover">
                </div>
            </div>
            <button type="button" @click="pickImage()" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-xs py-[6px] px-[12px]">
                <span x-text="imageId ? 'Change' : 'Select Image'"></span>
            </button>
        </div>
    </div>
    <div>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-1">Link URL (optional)</p>
        <input type="text" name="settings[link_url]" value="{{ $settings['link_url'] ?? '' }}" placeholder="https://example.com"
            class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] bg-transparent text-sm text-gray-800 dark:text-white py-2 px-3 w-full focus:outline-none">
    </div>
</div>
