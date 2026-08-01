<div class="cms-widget cms-widget-custom-html">
    @if($widget->title)<h4 class="cms-widget-title">{{ $widget->title }}</h4>@endif
    <div class="cms-widget-html-content">
        {!! $widget->setting('content') !!}
    </div>
</div>
