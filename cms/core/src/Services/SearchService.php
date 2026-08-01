<?php

namespace Cms\Core\Services;

use Cms\Core\Models\Post;
use Illuminate\Database\Eloquent\Builder;

class SearchService
{
    /**
     * Search posts, pages, and CPTs based on a query string.
     */
    public function search(string $term, array $postTypes = ['post', 'page'], int $perPage = 10)
    {
        // Allow third party plugins to override search results via filter hook
        $customResults = apply_filters('cms_custom_search_results', null, $term, $postTypes, $perPage);
        if ($customResults !== null) {
            return $customResults;
        }

        $query = Post::where('status', 'published')
            ->whereIn('post_type', $postTypes);

        $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('content', 'like', "%{$term}%")
              ->orWhere('excerpt', 'like', "%{$term}%")
              ->orWhereHas('meta', function (Builder $metaQuery) use ($term) {
                  $metaQuery->where('meta_value', 'like', "%{$term}%");
              });
        });

        // Simple relevance sorting: posts matching title get higher priority
        $query->orderByRaw("CASE WHEN title LIKE ? THEN 1 WHEN content LIKE ? THEN 2 ELSE 3 END ASC", ["%{$term}%", "%{$term}%"]);

        return $query->paginate($perPage);
    }
}
