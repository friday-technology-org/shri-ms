{{-- Recursive menu item list — supports unlimited nesting depth --}}
@foreach($items as $item)
    <li class="cms-menu-item{{ $item->children->isNotEmpty() ? ' has-children' : '' }}{{ $item->css_class ? ' ' . $item->css_class : '' }}">
        <a href="{{ $item->resolvedUrl() }}"
           @if($item->target_blank) target="_blank" @endif
           @if($item->rel_nofollow) rel="nofollow" @endif>{{ $item->label }}</a>
        @if($item->children->isNotEmpty())
            <ul class="cms-submenu">
                @include('cms-components::cms-menu-items', ['items' => $item->children])
            </ul>
        @endif
    </li>
@endforeach
