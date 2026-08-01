@php
    $seoTitle = isset($model) ? $model->getMeta('seo_title') : old('meta.seo_title');
    $seoDescription = isset($model) ? $model->getMeta('seo_description') : old('meta.seo_description');
    $seoFocusKeyphrase = isset($model) ? $model->getMeta('seo_focus_keyphrase') : old('meta.seo_focus_keyphrase');
    $seoKeywords = isset($model) ? $model->getMeta('seo_keywords') : old('meta.seo_keywords');
    $seoRobots = isset($model) ? $model->getMeta('seo_robots') : old('meta.seo_robots', 'index, follow');
@endphp

<div class="mt-8 border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden shadow-sm bg-white dark:bg-[#161824]">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-[#1b1e2b]">
        <h3 class="text-gray-900 text-base font-semibold dark:text-gray-100 m-0">SEO Settings</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 mb-0">Optimize your content for search engines</p>
    </div>
    <div class="p-6">
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Focus Keyphrase</label>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                    <input name="meta[seo_focus_keyphrase]" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-inherit" type="text" placeholder="e.g. laravel cms" value="{{ $seoFocusKeyphrase }}" />
                </div>
                <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">The main keyword you want to rank for.</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">SEO Title</label>
                <div class="mb-2 flex flex-wrap gap-2" id="seo-title-vars">
                    <button type="button" class="btn normal-case h-fit min-h-fit py-1 px-2 text-xs bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-700 transition-colors" data-val="{title}">+ Page Title</button>
                    <button type="button" class="btn normal-case h-fit min-h-fit py-1 px-2 text-xs bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-700 transition-colors" data-val="{sep}">+ Separator</button>
                    <button type="button" class="btn normal-case h-fit min-h-fit py-1 px-2 text-xs bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-700 transition-colors" data-val="{sitename}">+ Site Name</button>
                    <button type="button" class="btn normal-case h-fit min-h-fit py-1 px-2 text-xs bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-700 transition-colors" data-val="{tagline}">+ Tagline</button>
                </div>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                    <input id="seo_title_input" name="meta[seo_title]" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-gray-400" type="text" placeholder="{title} {sep} {sitename}" value="{{ $seoTitle }}" />
                </div>
                <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">If left empty, defaults to <code>{title} {sep} {sitename}</code></p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Meta Description</label>
                <textarea name="meta[seo_description]" class="textarea w-full text-gray-800 dark:text-white resize-y rounded-lg bg-transparent border border-[#E8EDF2] dark:border-[#313442] p-4 min-h-[100px] focus:outline-none placeholder:text-inherit" placeholder="Write a compelling meta description...">{{ $seoDescription }}</textarea>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Keywords (comma separated)</label>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                    <input name="meta[seo_keywords]" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-inherit" type="text" placeholder="e.g. laravel, cms, seo" value="{{ $seoKeywords }}" />
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Robots</label>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full max-w-sm">
                    <select name="meta[seo_robots]" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                        <option value="index, follow" class="bg-white dark:bg-dark-neutral-bg" {{ $seoRobots == 'index, follow' ? 'selected' : '' }}>Index, Follow (Recommended)</option>
                        <option value="noindex, nofollow" class="bg-white dark:bg-dark-neutral-bg" {{ $seoRobots == 'noindex, nofollow' ? 'selected' : '' }}>Noindex, Nofollow</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const seoVarsContainer = document.getElementById('seo-title-vars');
        const seoTitleInput = document.getElementById('seo_title_input');

        if (seoVarsContainer && seoTitleInput) {
            seoVarsContainer.addEventListener('click', function(e) {
                if (e.target.tagName === 'BUTTON') {
                    const val = e.target.getAttribute('data-val');
                    if (val) {
                        const currentVal = seoTitleInput.value;
                        const cursorPos = seoTitleInput.selectionStart;
                        
                        // Insert at cursor or append
                        if (cursorPos !== undefined) {
                            const textBefore = currentVal.substring(0, cursorPos);
                            const textAfter  = currentVal.substring(cursorPos, currentVal.length);
                            seoTitleInput.value = textBefore + val + ' ' + textAfter;
                            // Set focus back to input
                            seoTitleInput.focus();
                            // Move cursor after the inserted value + space
                            const newCursorPos = cursorPos + val.length + 1;
                            seoTitleInput.setSelectionRange(newCursorPos, newCursorPos);
                        } else {
                            seoTitleInput.value += (currentVal ? ' ' : '') + val;
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
