@php($icons = cms_favicon())
@if(!empty($icons['ico']))
<link rel="shortcut icon" href="{{ $icons['ico'] }}">
@endif
@if(!empty($icons['favicon_32']))
<link rel="icon" type="image/png" sizes="32x32" href="{{ $icons['favicon_32'] }}">
@endif
@if(!empty($icons['apple_touch']))
<link rel="apple-touch-icon" href="{{ $icons['apple_touch'] }}">
@endif
@if(!empty($icons['android_192']))
<link rel="icon" type="image/png" sizes="192x192" href="{{ $icons['android_192'] }}">
@endif
@if(!empty($icons['android_512']))
<link rel="icon" type="image/png" sizes="512x512" href="{{ $icons['android_512'] }}">
@endif
@if(!empty($icons['mask_icon']))
<link rel="mask-icon" href="{{ $icons['mask_icon'] }}" color="{{ cms_option('customizer_color_primary', '#7364DB') }}">
@endif
