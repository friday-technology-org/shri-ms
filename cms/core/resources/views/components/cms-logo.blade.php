@props(['type' => 'header'])
@php($src = cms_logo($type))
@if($src)
<img src="{{ $src }}" alt="{{ cms_option('site_title', 'Logo') }}" {{ $attributes }}>
@endif
