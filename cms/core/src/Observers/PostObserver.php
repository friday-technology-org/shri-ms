<?php

namespace Cms\Core\Observers;

use Cms\Core\Models\Post;
use Cms\Core\Models\PostRevision;
use Cms\Core\Models\SlugRedirect;

class PostObserver
{
    public function updating(Post $post)
    {
        // 1. Create a revision snapshot before updating
        if ($post->isDirty(['title', 'content', 'excerpt'])) {
            PostRevision::create([
                'post_id' => $post->id,
                'author_id' => auth()->id(),
                'title' => $post->getOriginal('title'),
                'content' => $post->getOriginal('content'),
                'excerpt' => $post->getOriginal('excerpt'),
            ]);
        }

        // 2. Track slug changes for 301 redirects
        if ($post->isDirty('slug') && !empty($post->getOriginal('slug'))) {
            // Save the old slug
            SlugRedirect::firstOrCreate([
                'old_slug' => $post->getOriginal('slug'),
            ], [
                'post_id' => $post->id,
            ]);
        }
    }

    public function saved(Post $post)
    {
        \Cms\Core\Http\Middleware\PageCache::clear();
    }

    public function deleted(Post $post)
    {
        \Cms\Core\Http\Middleware\PageCache::clear();
    }
}
