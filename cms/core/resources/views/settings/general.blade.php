@extends('cms-core::layouts.admin')

@section('title', 'Global Settings - Shri-ms')

@section('content')
<div>
    {{-- Page Header --}}
    <div class="flex justify-between flex-col gap-y-3 mb-[24px] md:flex-row">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Global Settings</h2>
            <div class="flex items-center text-xs gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Settings</span>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
        <span class="block sm:inline font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
        <span class="block sm:inline font-semibold">{{ session('error') }}</span>
    </div>
    @endif

    <div class="space-y-6">
        {{-- Horizontal Tabs Bar (Full Width) --}}
        <div class="w-full flex flex-row flex-wrap gap-2 bg-neutral-bg dark:bg-dark-neutral-bg border border-neutral dark:border-dark-neutral-border p-3 rounded-2xl">
            <button type="button" onclick="switchSettingTab('general')" id="tab-btn-general" class="setting-tab-btn text-center text-sm font-semibold py-3 px-5 rounded-xl bg-neutral dark:bg-dark-neutral-border text-color-brands">General & Identity</button>
            <button type="button" onclick="switchSettingTab('reading')" id="tab-btn-reading" class="setting-tab-btn text-center text-sm font-semibold py-3 px-5 rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-[#1e202c]">Reading & Homepage</button>
            <button type="button" onclick="switchSettingTab('discussion')" id="tab-btn-discussion" class="setting-tab-btn text-center text-sm font-semibold py-3 px-5 rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-[#1e202c]">Discussion Settings</button>
            <button type="button" onclick="switchSettingTab('robots')" id="tab-btn-robots" class="setting-tab-btn text-center text-sm font-semibold py-3 px-5 rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-[#1e202c]">robots.txt Editor</button>
            <button type="button" onclick="switchSettingTab('gdpr')" id="tab-btn-gdpr" class="setting-tab-btn text-center text-sm font-semibold py-3 px-5 rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-[#1e202c]">Privacy & GDPR Tools</button>
        </div>

        {{-- Tab Contents (Full Width) --}}
        <div class="w-full bg-neutral-bg dark:bg-dark-neutral-bg border border-neutral dark:border-dark-neutral-border p-6 rounded-2xl">
            <form action="{{ route('cms.settings.update') }}" method="POST">
                @csrf

                {{-- Tab: General --}}
                <div id="tab-content-general" class="setting-tab-content space-y-6">
                    <h3 class="text-lg font-bold text-gray-1100 dark:text-white border-b border-[#E8EDF2] dark:border-[#313442] pb-3 mb-4">General Settings</h3>
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Site Title</label>
                        <input type="text" name="site_title" value="{{ cms_option('site_title') }}" class="w-full max-w-lg border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Site Tagline</label>
                        <input type="text" name="site_tagline" value="{{ cms_option('site_tagline') }}" class="w-full max-w-lg border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Timezone</label>
                        <select name="site_timezone" class="w-full max-w-lg border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white dark:bg-dark-neutral-bg">
                            @foreach(timezone_identifiers_list() as $tz)
                                <option value="{{ $tz }}" {{ cms_option('site_timezone', 'UTC') === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">My Admin Language (Per-User Locale)</label>
                        <select name="user_locale" class="w-full max-w-lg border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white dark:bg-dark-neutral-bg">
                            @foreach(['en' => 'English', 'fr' => 'French (Français)', 'es' => 'Spanish (Español)', 'de' => 'German (Deutsch)', 'ar' => 'Arabic (العربية) - RTL'] as $code => $label)
                                <option value="{{ $code }}" {{ (auth()->user()->locale ?? 'en') === $code ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">SEO Fallback Share Image (URL)</label>
                        <input type="url" name="seo_fallback_image" value="{{ cms_option('seo_fallback_image') }}" placeholder="https://example.com/fallback.png" class="w-full max-w-lg border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white">
                    </div>
                </div>

                {{-- Tab: Reading --}}
                <div id="tab-content-reading" class="setting-tab-content space-y-6 hidden">
                    <h3 class="text-lg font-bold text-gray-1100 dark:text-white border-b border-[#E8EDF2] dark:border-[#313442] pb-3 mb-4">Reading & Homepage</h3>
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Homepage Displays</label>
                        <div class="flex items-center gap-4 mt-2">
                            <label class="inline-flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <input type="radio" name="show_on_front" value="posts" class="mr-2" {{ cms_option('show_on_front', 'posts') === 'posts' ? 'checked' : '' }} onchange="toggleHomepageDropdowns()"> Your latest posts
                            </label>
                            <label class="inline-flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <input type="radio" name="show_on_front" value="page" class="mr-2" {{ cms_option('show_on_front') === 'page' ? 'checked' : '' }} onchange="toggleHomepageDropdowns()"> A static page
                            </label>
                        </div>
                    </div>
                    <div id="homepage-selection" class="{{ cms_option('show_on_front') === 'page' ? '' : 'hidden' }}">
                        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Homepage Page</label>
                        <select name="page_on_front" class="w-full max-w-lg border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white dark:bg-dark-neutral-bg">
                            <option value="">— Select a Page —</option>
                            @foreach(\Cms\Core\Models\Post::where('post_type', 'page')->where('status', 'published')->get() as $p)
                                <option value="{{ $p->id }}" {{ cms_option('page_on_front') == $p->id ? 'selected' : '' }}>{{ $p->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tab: Discussion --}}
                <div id="tab-content-discussion" class="setting-tab-content space-y-6 hidden">
                    <h3 class="text-lg font-bold text-gray-1100 dark:text-white border-b border-[#E8EDF2] dark:border-[#313442] pb-3 mb-4">Discussion Settings</h3>
                    <div class="flex flex-col gap-4">
                        <label class="inline-flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <input type="checkbox" name="comments_enabled" value="1" class="mr-2" {{ cms_option('comments_enabled', true) ? 'checked' : '' }}> Allow people to submit comments on new posts
                        </label>
                        <label class="inline-flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <input type="checkbox" name="comments_require_approval" value="1" class="mr-2" {{ cms_option('comments_require_approval', true) ? 'checked' : '' }}> Comment must be manually approved
                        </label>
                        <label class="inline-flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <input type="checkbox" name="comments_notify_author" value="1" class="mr-2" {{ cms_option('comments_notify_author', true) ? 'checked' : '' }}> Email post authors when a new comment is posted
                        </label>
                    </div>
                </div>

                {{-- Tab: Robots.txt --}}
                <div id="tab-content-robots" class="setting-tab-content space-y-6 hidden">
                    <h3 class="text-lg font-bold text-gray-1100 dark:text-white border-b border-[#E8EDF2] dark:border-[#313442] pb-3 mb-4">robots.txt Editor</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-dark-500 mb-4">You can edit the plain-text configuration contents of `/robots.txt` below.</p>
                    <div>
                        <textarea name="robots_txt_content" rows="10" class="w-full font-mono text-sm border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-gray-1100 dark:text-white">{{ cms_option('robots_txt_content') }}</textarea>
                    </div>
                </div>

                {{-- Tab: GDPR & Privacy --}}
                <div id="tab-content-gdpr" class="setting-tab-content space-y-6 hidden">
                    <h3 class="text-lg font-bold text-gray-1100 dark:text-white border-b border-[#E8EDF2] dark:border-[#313442] pb-3 mb-4">Privacy & GDPR Settings</h3>
                    
                    {{-- Cookie Banner Options --}}
                    <div class="space-y-4">
                        <label class="inline-flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <input type="checkbox" name="gdpr_cookie_consent_enabled" value="1" class="mr-2" {{ cms_option('gdpr_cookie_consent_enabled', false) ? 'checked' : '' }}> Enable Cookie Consent Banner
                        </label>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Cookie Banner Message</label>
                            <textarea name="gdpr_cookie_consent_text" rows="3" class="w-full border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white">{{ cms_option('gdpr_cookie_consent_text', 'We use cookies to improve your experience on our site.') }}</textarea>
                        </div>
                    </div>

                    {{-- Privacy Policy Page --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Privacy Policy Page</label>
                        <select name="privacy_policy_page_id" class="w-full max-w-lg border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white dark:bg-dark-neutral-bg">
                            <option value="">— Select a Page —</option>
                            @foreach(\Cms\Core\Models\Post::where('post_type', 'page')->get() as $p)
                                <option value="{{ $p->id }}" {{ cms_option('privacy_policy_page_id') == $p->id ? 'selected' : '' }}>{{ $p->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="hidden" id="tab-submit-stub"></button>
                </div>

                {{-- Action Panel Footer --}}
                <div class="mt-8 pt-4 border-t border-[#E8EDF2] dark:border-[#313442] flex justify-end">
                    <button type="submit" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-sm py-[10px] px-[24px] rounded-xl font-semibold shadow-md">Save Settings</button>
                </div>
            </form>

            {{-- Tab Contents Outside Primary Form (For tools that post to separate endpoints) --}}
            <div id="tab-content-gdpr-tools" class="setting-tab-content space-y-6 hidden mt-8 border-t border-neutral dark:border-dark-neutral-border pt-6">
                <h3 class="text-lg font-bold text-gray-1100 dark:text-white mb-2">GDPR Privacy Tools</h3>
                
                {{-- Data Export --}}
                <div class="border border-[#E8EDF2] dark:border-[#313442] p-4 rounded-xl">
                    <h4 class="text-sm font-bold text-gray-800 dark:text-white mb-2">Export Personal Data</h4>
                    <p class="text-xs text-gray-500 mb-4">Export all comments and posts linked to a user email address in JSON format.</p>
                    <form action="{{ route('cms.settings.export') }}" method="POST" class="flex gap-2 max-w-lg">
                        @csrf
                        <input type="email" name="gdpr_email" required placeholder="user@example.com" class="w-full border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-lg p-2 text-xs text-gray-1100 dark:text-white">
                        <button type="submit" class="px-4 py-2 bg-neutral dark:bg-dark-neutral-border text-gray-800 dark:text-white border border-[#E8EDF2] dark:border-[#313442] hover:opacity-75 rounded-lg text-xs font-semibold whitespace-nowrap">Export Data</button>
                    </form>
                </div>

                {{-- Data Erasure --}}
                <div class="border border-red-200 dark:border-red-900 p-4 rounded-xl mt-4">
                    <h4 class="text-sm font-bold text-red-700 dark:text-red-400 mb-2">Erase Personal Data (Right to be Forgotten)</h4>
                    <p class="text-xs text-gray-500 mb-4">Anonymizes or erases all stored personal data, including IP address, name, and comment entries linked to the email address.</p>
                    <form action="{{ route('cms.settings.erase') }}" method="POST" class="flex gap-2 max-w-lg" onsubmit="return confirm('WARNING: This will anonymize all comments and user details for this email address permanently. Proceed?');">
                        @csrf
                        <input type="email" name="gdpr_email" required placeholder="user@example.com" class="w-full border border-red-200 dark:border-red-900 bg-transparent rounded-lg p-2 text-xs text-gray-1100 dark:text-white">
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold whitespace-nowrap">Erase Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchSettingTab(tab) {
        // Toggle tabs active styles
        document.querySelectorAll('.setting-tab-btn').forEach(btn => {
            btn.classList.remove('bg-neutral', 'dark:bg-dark-neutral-border', 'text-color-brands');
            btn.classList.add('text-gray-500', 'hover:bg-gray-50', 'dark:hover:bg-[#1e202c]');
        });

        const activeBtn = document.getElementById('tab-btn-' + tab);
        activeBtn.classList.add('bg-neutral', 'dark:bg-dark-neutral-border', 'text-color-brands');
        activeBtn.classList.remove('text-gray-500', 'hover:bg-gray-50', 'dark:hover:bg-[#1e202c]');

        // Toggle contents visibility
        document.querySelectorAll('.setting-tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        document.getElementById('tab-content-' + tab).classList.remove('hidden');

        // Special handling for GDPR tools section
        if (tab === 'gdpr') {
            document.getElementById('tab-content-gdpr-tools').classList.remove('hidden');
        } else {
            document.getElementById('tab-content-gdpr-tools').classList.add('hidden');
        }
    }

    function toggleHomepageDropdowns() {
        const val = document.querySelector('input[name="show_on_front"]:checked').value;
        const selector = document.getElementById('homepage-selection');
        if (val === 'page') {
            selector.classList.remove('hidden');
        } else {
            selector.classList.add('hidden');
        }
    }
</script>
@endsection
