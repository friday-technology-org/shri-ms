@php
    $bannerMedia = $widget->setting('media_id') ? \Cms\Core\Models\Media::find($widget->setting('media_id')) : null;
    $bannerLink = $widget->setting('link_url');
@endphp
@if($bannerMedia)
<div class="cms-widget cms-widget-image-banner">
    @if($widget->title)<h4 class="cms-widget-title">{{ $widget->title }}</h4>@endif
    @if($bannerLink)<a href="{{ $bannerLink }}">@endif
        <img src="{{ $bannerMedia->url() }}" alt="{{ $widget->title ?? 'Banner' }}">
    @if($bannerLink)</a>@endif
</div>
@endif
