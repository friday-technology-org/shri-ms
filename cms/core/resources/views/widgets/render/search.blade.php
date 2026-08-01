<div class="cms-widget cms-widget-search">
    @if($widget->title)<h4 class="cms-widget-title">{{ $widget->title }}</h4>@endif
    <form action="{{ url('/') }}" method="GET" class="cms-widget-search-form">
        <input type="text" name="s" placeholder="Search…" value="{{ request('s') }}">
        <button type="submit">Search</button>
    </form>
</div>
