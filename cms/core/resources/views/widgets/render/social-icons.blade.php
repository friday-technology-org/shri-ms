@php
    $socialIconMap = [
        'facebook' => 'icon-facebook-2.svg',
        'twitter' => 'icon-twitter-2.svg',
        'instagram' => 'icon-instagram-2.svg',
        'linkedin' => 'icon-linkedin-2.svg',
        'youtube' => 'icon-video-camera-boxy.svg',
    ];
@endphp
<div class="cms-widget cms-widget-social-icons">
    @if($widget->title)<h4 class="cms-widget-title">{{ $widget->title }}</h4>@endif
    <div class="cms-social-icons-list">
        @foreach($socialIconMap as $platform => $icon)
            @php $link = $widget->setting($platform); @endphp
            @if($link)
                <a href="{{ $link }}" target="_blank" rel="noopener" title="{{ ucfirst($platform) }}">
                    <img src="{{ asset('assets/images/icons/' . $icon) }}" alt="{{ ucfirst($platform) }}">
                </a>
            @endif
        @endforeach
    </div>
</div>
