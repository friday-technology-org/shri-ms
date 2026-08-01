@php
    $cloudTerms = \Cms\Core\Models\Term::orderBy('name')->take($widget->setting('count', 20))->get();
@endphp
<div class="cms-widget cms-widget-tag-cloud">
    @if($widget->title)<h4 class="cms-widget-title">{{ $widget->title }}</h4>@endif
    <div class="cms-tag-cloud-links">
        @foreach($cloudTerms as $term)
            <a href="{{ url($term->slug) }}" class="cms-tag-cloud-link">{{ $term->name }}</a>
        @endforeach
    </div>
</div>
