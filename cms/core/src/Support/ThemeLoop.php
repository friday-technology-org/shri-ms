<?php

namespace Cms\Core\Support;

use Illuminate\Support\Collection;
use Cms\Core\Models\Post;

class ThemeLoop
{
    protected array $posts = [];
    protected int $currentIndex = -1;
    protected ?Post $currentPost = null;

    /**
     * Initialize the loop with a collection or array of posts.
     */
    public function setup($posts): void
    {
        if ($posts instanceof Collection) {
            $this->posts = $posts->all();
        } elseif (is_array($posts)) {
            $this->posts = $posts;
        } else {
            $this->posts = [$posts];
        }
        
        $this->currentIndex = -1;
        $this->currentPost = null;
    }

    /**
     * Check if there are more posts in the loop.
     */
    public function havePosts(): bool
    {
        return ($this->currentIndex + 1) < count($this->posts);
    }

    /**
     * Advance the loop to the next post and set it as current.
     */
    public function thePost(): void
    {
        $this->currentIndex++;
        $this->currentPost = $this->posts[$this->currentIndex] ?? null;
    }

    /**
     * Get the current post in the loop.
     */
    public function current(): ?Post
    {
        return $this->currentPost;
    }

    /**
     * Reset the loop data.
     */
    public function reset(): void
    {
        $this->posts = [];
        $this->currentIndex = -1;
        $this->currentPost = null;
    }
}
