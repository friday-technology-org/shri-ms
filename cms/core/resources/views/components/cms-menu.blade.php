@props(['location'])
@php($menu = \Cms\Core\Models\Menu::where('location', $location)->first())
@if($menu)
<ul {{ $attributes->merge(['class' => 'cms-menu cms-menu-' . $location]) }}>
    @include('cms-components::cms-menu-items', ['items' => $menu->tree()])
</ul>
@endif
