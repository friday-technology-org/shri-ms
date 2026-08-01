@props(['post' => null])

@php
    $meta = app(\Cms\Core\Services\SeoHelper::class)->getMetaTags($post);
@endphp

<!-- Shri-ms Search Engine Optimization Engine -->
<title>{{ $meta['title'] }}</title>
<meta name="description" content="{{ $meta['description'] }}">
<meta name="keywords" content="{{ $meta['keywords'] ?? '' }}">
<meta name="robots" content="{{ $meta['robots'] }}">
<link rel="canonical" href="{{ $meta['url'] }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $meta['og_type'] }}">
<meta property="og:title" content="{{ $meta['title'] }}">
<meta property="og:description" content="{{ $meta['description'] }}">
<meta property="og:url" content="{{ $meta['url'] }}">
@if($meta['image'])
<meta property="og:image" content="{{ $meta['image'] }}">
@endif

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $meta['title'] }}">
<meta name="twitter:description" content="{{ $meta['description'] }}">
@if($meta['image'])
<meta name="twitter:image" content="{{ $meta['image'] }}">
@endif

<!-- GDPR Cookie Banner Integration -->
@if(cms_option('gdpr_cookie_consent_enabled', false))
<div id="cms-gdpr-banner" class="fixed bottom-5 left-5 right-5 md:left-auto md:max-w-md bg-white dark:bg-dark-neutral-bg shadow-2xl border border-neutral dark:border-dark-neutral-border rounded-2xl p-5 z-[99999] flex flex-col gap-3 transition-transform duration-300 translate-y-0">
    <div class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
        {!! cms_option('gdpr_cookie_consent_text', 'We use cookies to improve your experience on our site.') !!}
    </div>
    <div class="flex gap-2 self-end">
        <button onclick="acceptCmsGdpr()" class="px-4 py-2 bg-color-brands hover:opacity-90 text-white rounded-lg text-xs font-semibold shadow">Accept</button>
    </div>
</div>

<script>
    if (localStorage.getItem('cms_cookies_accepted') === 'true') {
        document.getElementById('cms-gdpr-banner').remove();
    }
    function acceptCmsGdpr() {
        localStorage.setItem('cms_cookies_accepted', 'true');
        document.getElementById('cms-gdpr-banner').remove();
    }
</script>
@endif
