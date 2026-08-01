@php
    $recentPosts = \Cms\Core\Models\Post::where('status', 'published')
        ->latest('published_at')
        ->take($widget->setting('count', 5))
        ->get();
@endphp
<div class="cms-widget cms-widget-recent-posts">
    @if($widget->title)<h4 class="cms-widget-title">{{ $widget->title }}</h4>@endif
    <ul>
        @foreach($recentPosts as $recentPost)
            <li><a href="{{ url($recentPost->slug) }}">{{ $recentPost->title }}</a></li>
        @endforeach
    </ul>
</div>
