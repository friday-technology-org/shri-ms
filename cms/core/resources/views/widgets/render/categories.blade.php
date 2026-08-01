@php
    $categoryTerms = \Cms\Core\Models\Term::orderBy('name')->take($widget->setting('count', 10))->get();
@endphp
<div class="cms-widget cms-widget-categories">
    @if($widget->title)<h4 class="cms-widget-title">{{ $widget->title }}</h4>@endif
    <ul>
        @foreach($categoryTerms as $term)
            <li><a href="{{ url($term->slug) }}">{{ $term->name }}</a></li>
        @endforeach
    </ul>
</div>
