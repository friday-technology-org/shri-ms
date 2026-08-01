@props(['area'])
@php($widgetArea = \Cms\Core\Models\WidgetArea::where('key', $area)->with(['widgets' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])->first())
@if($widgetArea && $widgetArea->widgets->isNotEmpty())
<div {{ $attributes->merge(['class' => 'cms-widget-area cms-widget-area-' . $area]) }}>
    @foreach($widgetArea->widgets as $widget)
        @if(view()->exists('theme::widgets.render.' . str_replace('_', '-', $widget->type)))
            @include('theme::widgets.render.' . str_replace('_', '-', $widget->type), ['widget' => $widget])
        @else
            @include('cms-core::widgets.render.' . str_replace('_', '-', $widget->type), ['widget' => $widget])
        @endif
    @endforeach
</div>
@endif
