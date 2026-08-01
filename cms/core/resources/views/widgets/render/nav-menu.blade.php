@php
    $navMenu = $widget->setting('menu_id') ? \Cms\Core\Models\Menu::find($widget->setting('menu_id')) : null;
@endphp
@if($navMenu)
<div class="cms-widget cms-widget-nav-menu">
    @if($widget->title)<h4 class="cms-widget-title">{{ $widget->title }}</h4>@endif
    <ul class="cms-widget-nav-menu-list">
        @foreach($navMenu->tree() as $navItem)
            <li>
                <a href="{{ $navItem->resolvedUrl() }}" @if($navItem->target_blank) target="_blank" @endif @if($navItem->rel_nofollow) rel="nofollow" @endif class="{{ $navItem->css_class }}">{{ $navItem->label }}</a>
                @if($navItem->children->isNotEmpty())
                <ul>
                    @foreach($navItem->children as $child)
                        <li><a href="{{ $child->resolvedUrl() }}">{{ $child->label }}</a></li>
                    @endforeach
                </ul>
                @endif
            </li>
        @endforeach
    </ul>
</div>
@endif
