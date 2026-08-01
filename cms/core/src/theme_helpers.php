<?php

use Cms\Core\Models\Post;
use Cms\Core\Models\Option;

if (!function_exists('get_template_directory_uri')) {
    /**
     * Get the URI of the current theme's directory.
     */
    function get_template_directory_uri(): string
    {
        $themeSlug = Option::get('active_theme', 'default');
        return url('themes/' . $themeSlug);
    }
}

if (!function_exists('theme_asset')) {
    /**
     * Get the URL for an asset located in the active theme.
     * Example: theme_asset('assets/css/style.css')
     */
    function theme_asset(string $path): string
    {
        return get_template_directory_uri() . '/' . ltrim($path, '/');
    }
}

if (!function_exists('cms_loop')) {
    /**
     * Get the global ThemeLoop singleton instance.
     */
    function cms_loop(): \Cms\Core\Support\ThemeLoop
    {
        return app('cms.theme.loop');
    }
}

if (!function_exists('have_posts')) {
    /**
     * Check if there are any posts remaining in the current loop.
     */
    function have_posts(): bool
    {
        return cms_loop()->havePosts();
    }
}

if (!function_exists('the_post')) {
    /**
     * Advance the loop to the next post and set it as the global current post.
     */
    function the_post(): void
    {
        cms_loop()->thePost();
    }
}

if (!function_exists('wp_reset_postdata')) {
    /**
     * Reset the global post data.
     */
    function wp_reset_postdata(): void
    {
        cms_loop()->reset();
    }
}

if (!function_exists('get_post')) {
    /**
     * Get the current post object or a specific post by ID.
     */
    function get_post($post = null): ?Post
    {
        if ($post instanceof Post) {
            return $post;
        }
        
        if (is_numeric($post)) {
            return Post::find($post);
        }

        return cms_loop()->current();
    }
}

// -----------------------------------------------------------------------------
// POST DATA HELPERS
// -----------------------------------------------------------------------------

if (!function_exists('get_the_title')) {
    function get_the_title($post = null): string
    {
        $p = get_post($post);
        return $p ? $p->title : '';
    }
}

if (!function_exists('the_title')) {
    function the_title($post = null): void
    {
        echo htmlspecialchars(get_the_title($post));
    }
}

if (!function_exists('get_the_content')) {
    function get_the_content($post = null): string
    {
        $p = get_post($post);
        return $p ? $p->content : '';
    }
}

if (!function_exists('the_content')) {
    function the_content($post = null): void
    {
        echo get_the_content($post);
    }
}

if (!function_exists('get_the_excerpt')) {
    function get_the_excerpt($post = null): string
    {
        $p = get_post($post);
        if (!$p) return '';
        
        if (!empty($p->excerpt)) {
            return $p->excerpt;
        }
        
        // Auto-generate excerpt from content if empty
        $text = strip_tags($p->content);
        if (mb_strlen($text) > 150) {
            return mb_substr($text, 0, 150) . '...';
        }
        return $text;
    }
}

if (!function_exists('the_excerpt')) {
    function the_excerpt($post = null): void
    {
        echo htmlspecialchars(get_the_excerpt($post));
    }
}

if (!function_exists('get_the_permalink')) {
    function get_the_permalink($post = null): string
    {
        $p = get_post($post);
        if (!$p) return '';
        
        // Simplified permalink logic. In a real system, this would resolve taxonomy/post_type routes.
        return url('/' . $p->slug);
    }
}

if (!function_exists('the_permalink')) {
    function the_permalink($post = null): void
    {
        echo htmlspecialchars(get_the_permalink($post));
    }
}

if (!function_exists('the_date')) {
    function the_date(string $format = 'F j, Y', $post = null): void
    {
        $p = get_post($post);
        if ($p && $p->published_at) {
            echo htmlspecialchars($p->published_at->format($format));
        }
    }
}

if (!function_exists('the_author')) {
    function the_author($post = null): void
    {
        $p = get_post($post);
        if ($p && $p->author) {
            echo htmlspecialchars($p->author->name);
        }
    }
}

// -----------------------------------------------------------------------------
// POST THUMBNAIL (MEDIA) HELPERS
// -----------------------------------------------------------------------------

if (!function_exists('has_post_thumbnail')) {
    function has_post_thumbnail($post = null): bool
    {
        $p = get_post($post);
        return $p && $p->featured_image_id;
    }
}

if (!function_exists('get_the_post_thumbnail_url')) {
    function get_the_post_thumbnail_url($post = null): string
    {
        $p = get_post($post);
        if ($p && $p->featured_image_id) {
            $media = \Cms\Core\Models\Media::find($p->featured_image_id);
            return $media ? $media->url() : '';
        }
        return '';
    }
}

if (!function_exists('the_post_thumbnail_url')) {
    function the_post_thumbnail_url($post = null): void
    {
        echo htmlspecialchars(get_the_post_thumbnail_url($post));
    }
}

// -----------------------------------------------------------------------------
// MENU HELPER
// -----------------------------------------------------------------------------

if (!function_exists('get_menu')) {
    /**
     * Syntactic sugar for cms_nav_menu() matching WP's style, returning the rendered HTML.
     */
    function get_menu(string $location): string
    {
        return function_exists('cms_nav_menu') ? cms_nav_menu($location) : '';
    }
}

// NOTE: get_field() and the_field() are already implemented in helpers.php.
// If needed, they could be adjusted to respect the current loop post if $postId is null,
// but they already default to null and handle their own logic or we can override them if they don't check the loop.

// -----------------------------------------------------------------------------
// ARCHIVE AND COMMENT HELPERS
// -----------------------------------------------------------------------------

// ---------- Archive helpers ----------
if (!function_exists('is_singular')) {
    /**
     * Determine if the current query is for a single post of a given post type.
     */
    function is_singular($post_type = null): bool {
        $p = get_post();
        if (!$p) return false;
        if (is_null($post_type)) return true;
        return $p->post_type === $post_type;
    }
}

if (!function_exists('is_post_type_archive')) {
    /**
     * Determine if the request is for a post type archive.
     * Simple heuristic based on the first URL segment.
     */
    function is_post_type_archive($post_type = null): bool {
        $segments = request()->segments();
        if (empty($segments)) return false;
        $first = $segments[0];
        if ($post_type) {
            return $first === $post_type;
        }
        return true;
    }
}

if (!function_exists('get_post_type_archive_link')) {
    /**
     * Return the URL for a post type archive.
     */
    function get_post_type_archive_link(string $post_type): string {
        return url('/' . $post_type);
    }
}

if (!function_exists('the_archive_title')) {
    /**
     * Echo a generic archive title based on the request.
     */
    function the_archive_title(): void {
        $title = '';
        if (is_post_type_archive()) {
            $post_type = request()->segment(1);
            $title = ucwords(str_replace(['-','_'], ' ', $post_type)) . ' Archive';
        } elseif (request()->is('category/*')) {
            $slug = request()->segment(2);
            $title = 'Category: ' . ucwords(str_replace(['-','_'], ' ', $slug));
        } elseif (request()->is('tag/*')) {
            $slug = request()->segment(2);
            $title = 'Tag: ' . ucwords(str_replace(['-','_'], ' ', $slug));
        }
        echo esc_html($title);
    }
}

if (!function_exists('the_archive_description')) {
    /**
     * Placeholder for archive description – returns empty string for now.
     */
    function the_archive_description(): void {
        // Could be extended to pull term description from taxonomy models.
        echo '';
    }
}

// ---------- Comment helpers ----------
if (!function_exists('get_comments_number')) {
    /**
     * Return the number of comments for a post.
     */
    function get_comments_number($post = null): int {
        $p = get_post($post);
        if (!$p) return 0;
        return $p->comments()->count();
    }
}

if (!function_exists('the_comments')) {
    /**
     * Echo a simple list of comments for a post.
     */
    function the_comments($post = null): void {
        $p = get_post($post);
        if (!$p) return;
        $comments = $p->comments()->orderBy('created_at', 'desc')->get();
        foreach ($comments as $comment) {
            echo '<div class="comment">';
            echo '<p class="comment-author">' . esc_html($comment->author_name) . '</p>'; // assumes author_name column
            echo '<div class="comment-content">' . esc_html($comment->content) . '</div>';
            echo '</div>';
        }
    }
}

if (!function_exists('comment_form')) {
    /**
     * Output a simple comment submission form.
     * Assumes a route named `comments.store` exists.
     */
    function comment_form($post = null): void {
        $p = get_post($post);
        if (!$p) return;
        $action = url('/comments'); // fallback URL; replace with named route if available
        echo '<form method="POST" action="' . esc_url($action) . '">';
        echo '<input type="hidden" name="_token" value="' . csrf_token() . '">';
        echo '<input type="hidden" name="post_id" value="' . e($p->id) . '">';
        echo '<p><label>Name</label><input type="text" name="author_name" required></p>';
        echo '<p><label>Email</label><input type="email" name="author_email" required></p>';
        echo '<p><label>Comment</label><textarea name="content" required></textarea></p>';
        echo '<p><button type="submit">Submit</button></p>';
        echo '</form>';
    }
}

// End of additional archive and comment helpers

// -----------------------------------------------------------------------------
// ADDITIONAL WORDPRESS‑STYLE HELPERS
// -----------------------------------------------------------------------------

// ---------- Escaping helpers ----------
if (!function_exists('esc_html')) {
    function esc_html($value): string {
        return e($value);
    }
}
if (!function_exists('esc_url')) {
    function esc_url($value): string {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('esc_attr')) {
    function esc_attr($value): string {
        return esc_html($value);
    }
}

// ---------- Author helpers ----------
if (!function_exists('get_the_author')) {
    function get_the_author($post = null): string {
        $p = get_post($post);
        return $p && $p->author ? $p->author->name : '';
    }
}
if (!function_exists('the_author')) {
    function the_author($post = null): void {
        echo esc_html(get_the_author($post));
    }
}
if (!function_exists('get_the_author_avatar_url')) {
    function get_the_author_avatar_url($post = null): string {
        $p = get_post($post);
        if ($p && $p->author && $p->author->avatar) {
            return asset($p->author->avatar);
        }
        return '';
    }
}
if (!function_exists('the_author_avatar_url')) {
    function the_author_avatar_url($post = null): void {
        echo esc_url(get_the_author_avatar_url($post));
    }
}
if (!function_exists('the_author_posts_link')) {
    function the_author_posts_link($post = null): void {
        $p = get_post($post);
        if ($p && $p->author) {
            $url = url('/author/' . $p->author->slug);
            echo '<a href="' . esc_url($url) . '">' . esc_html($p->author->name) . '</a>';
        }
    }
}

// ---------- Taxonomy helpers ----------
if (!function_exists('get_the_category')) {
    function get_the_category($post = null): array {
        $p = get_post($post);
        if ($p && method_exists($p, 'categories')) {
            return $p->categories()->pluck('name')->all();
        }
        return [];
    }
}
if (!function_exists('the_category')) {
    function the_category(string $separator = ', ', $post = null): void {
        echo esc_html(implode($separator, get_the_category($post)));
    }
}
if (!function_exists('get_the_tags')) {
    function get_the_tags($post = null): array {
        $p = get_post($post);
        if ($p && method_exists($p, 'tags')) {
            return $p->tags()->pluck('name')->all();
        }
        return [];
    }
}
if (!function_exists('the_tags')) {
    function the_tags(string $before = '', string $separator = ', ', string $after = '', $post = null): void {
        $tags = get_the_tags($post);
        if (!empty($tags)) {
            echo $before . esc_html(implode($separator, $tags)) . $after;
        }
    }
}

global $cms_filters;
$cms_filters = [];

if (!function_exists('add_filter')) {
    function add_filter(string $tag, callable $function, int $priority = 10): void {
        global $cms_filters;
        $cms_filters[$tag][$priority][] = $function;
    }
}

// ---------- Content filter placeholder ----------
if (!function_exists('apply_filters')) {
    /**
     * Filter system - passes value through registered callbacks
     */
    function apply_filters(string $tag, $value, ...$args) {
        global $cms_filters;
        if (!isset($cms_filters[$tag])) return $value;

        ksort($cms_filters[$tag]);
        foreach ($cms_filters[$tag] as $priority => $functions) {
            foreach ($functions as $function) {
                $value = call_user_func($function, $value, ...$args);
            }
        }
        return $value;
    }
}

// Overwrite the_content and the_excerpt to use apply_filters
if (!function_exists('the_content')) {
    function the_content($post = null): void {
        echo apply_filters('the_content', get_the_content($post));
    }
}
if (!function_exists('the_excerpt')) {
    function the_excerpt($post = null): void {
        echo esc_html(apply_filters('the_excerpt', get_the_excerpt($post)));
    }
}

// ---------- Widget helper ----------
if (!function_exists('dynamic_sidebar')) {
    /**
     * Render a widget area using existing cms_widget_area helper.
     */
    function dynamic_sidebar(string $area): void {
        echo cms_widget_area($area);
    }
}

// ---------- Open Graph / SEO helpers ----------
if (!function_exists('og_title')) {
    function og_title($post = null): string { return get_the_title($post); }
}
if (!function_exists('og_description')) {
    function og_description($post = null): string { return get_the_excerpt($post); }
}
if (!function_exists('og_image')) {
    function og_image($post = null): string { return get_the_post_thumbnail_url($post); }
}
if (!function_exists('og_url')) {
    function og_url($post = null): string { return get_the_permalink($post); }
}
if (!function_exists('render_og_tags')) {
    function render_og_tags($post = null): void {
        $tags = [
            'og:title' => og_title($post),
            'og:description' => og_description($post),
            'og:image' => og_image($post),
            'og:url' => og_url($post),
            'og:type' => 'article',
        ];
        foreach ($tags as $property => $content) {
            if ($content) {
                echo '<meta property="' . esc_attr($property) . '" content="' . esc_attr($content) . '">';
            }
        }
    }
}

// ---------- Site helpers ----------
if (!function_exists('home_url')) {
    function home_url(string $path = ''): string { return url($path); }
}
if (!function_exists('get_bloginfo')) {
    function get_bloginfo(string $key = 'name'): string {
        return match ($key) {
            'name' => config('app.name'),
            'description' => cms_option('site_tagline', ''),
            default => ''
        };
    }
}

// ---------- Pagination helper ----------
if (!function_exists('the_posts_navigation')) {
    /**
     * Render simple previous/next navigation for a LengthAwarePaginator.
     */
    function the_posts_navigation($paginator = null): void {
        $p = $paginator ?? cms_loop()->current();
        if ($p && method_exists($p, 'links')) {
            echo $p->links();
        }
    }
}

// End of additional helpers

// -----------------------------------------------------------------------------
// SEO helper for pages
// -----------------------------------------------------------------------------
if (!function_exists('get_page_seo')) {
    /**
     * Retrieve SEO meta tags for a page by slug. Falls back to site defaults if not found.
     *
     * @param string $slug Page slug
     * @return array SEO meta array: title, description, keywords, robots, url, image, og_type
     */
    function get_page_seo(string $slug): array
    {
        $page = Post::where('slug', $slug)->where('post_type', 'page')->first();
        /** @var \Cms\Core\Services\SeoHelper $seo */
        $seo = app(\Cms\Core\Services\SeoHelper::class);
        return $seo->getMetaTags($page);
    }
}

if (!function_exists('cms_get_available_templates')) {
    /**
     * Scan the active theme for any Blade files containing a Template Name comment.
     * e.g., @php /* Template Name: Full Width * / @endphp
     */
    function cms_get_available_templates(): array
    {
        try {
            $activeThemeSlug = cms_option('active_theme', 'default');
            $path = base_path('cms-content/themes/' . $activeThemeSlug);
            if (!is_dir($path)) return [];

            $templates = [];
            $files = \Illuminate\Support\Facades\File::allFiles($path);
            
            foreach ($files as $file) {
                if ($file->getExtension() === 'php' && str_ends_with($file->getFilename(), '.blade.php')) {
                    $content = file_get_contents($file->getPathname());
                    if (preg_match('/Template Name:\s*(.*?)(\*\/|-->|\n)/i', $content, $matches)) {
                        $relativePath = $file->getRelativePathname();
                        // Remove .blade.php extension for the view name
                        $viewName = str_replace(['/', '.blade.php'], ['.', ''], $relativePath);
                        $templates[$viewName] = trim($matches[1]);
                    }
                }
            }
            return $templates;
        } catch (\Exception $e) {
            return [];
        }
    }
}

if (!function_exists('cms_add_admin_menu')) {
    /**
     * Register a custom admin dashboard page with an MVC controller.
     */
    function cms_add_admin_menu(string $title, string $slug, string $role, $controllerAction, string $icon = 'icon-setting-2.svg')
    {
        $menus = \Illuminate\Support\Facades\Config::get('cms.admin_menus', []);
        $menus[$slug] = [
            'title' => $title,
            'slug' => $slug,
            'role' => $role,
            'icon' => $icon,
            'controllerAction' => $controllerAction,
            'submenus' => []
        ];
        \Illuminate\Support\Facades\Config::set('cms.admin_menus', $menus);
        
        \Illuminate\Support\Facades\Route::middleware(['web', 'auth', 'role:'.$role])
            ->prefix('admin')
            ->match(['get', 'post', 'put', 'patch', 'delete'], $slug . '/{any?}', function() use ($controllerAction, $title) {
                $content = app()->call($controllerAction);
                if ($content instanceof \Symfony\Component\HttpFoundation\Response && !$content instanceof \Illuminate\Http\Response) {
                    return $content;
                }
                if ($content instanceof \Illuminate\Contracts\Support\Renderable) {
                    $content = $content->render();
                } elseif ($content instanceof \Illuminate\Http\Response) {
                    $content = $content->getContent();
                }
                return view('cms-core::layouts.theme-page', compact('content', 'title'));
            })
            ->where('any', '.*')
            ->name('cms.admin.theme.' . $slug);
    }
}

if (!function_exists('cms_add_admin_submenu')) {
    /**
     * Register a custom admin dashboard sub-page with an MVC controller.
     */
    function cms_add_admin_submenu(string $parentSlug, string $title, string $slug, string $role, $controllerAction)
    {
        $menus = \Illuminate\Support\Facades\Config::get('cms.admin_menus', []);
        if (isset($menus[$parentSlug])) {
            $menus[$parentSlug]['submenus'][] = [
                'title' => $title,
                'slug' => $slug,
                'role' => $role,
                'controllerAction' => $controllerAction,
            ];
            \Illuminate\Support\Facades\Config::set('cms.admin_menus', $menus);
        }
        
        \Illuminate\Support\Facades\Route::middleware(['web', 'auth', 'role:'.$role])
            ->prefix('admin')
            ->match(['get', 'post', 'put', 'patch', 'delete'], $slug . '/{any?}', function() use ($controllerAction, $title) {
                $content = app()->call($controllerAction);
                if ($content instanceof \Symfony\Component\HttpFoundation\Response && !$content instanceof \Illuminate\Http\Response) {
                    return $content;
                }
                if ($content instanceof \Illuminate\Contracts\Support\Renderable) {
                    $content = $content->render();
                } elseif ($content instanceof \Illuminate\Http\Response) {
                    $content = $content->getContent();
                }
                return view('cms-core::layouts.theme-page', compact('content', 'title'));
            })
            ->where('any', '.*')
            ->name('cms.admin.theme.' . $slug);
    }
}

if (!function_exists('cms_seo_tags')) {
    /**
     * Render the SEO tags as HTML for the given post.
     *
     * @param \Cms\Core\Models\Post|null $post
     * @return string
     */
    function cms_seo_tags(?\Cms\Core\Models\Post $post = null): string
    {
        /** @var \Cms\Core\Services\SeoHelper $seo */
        $seo = app(\Cms\Core\Services\SeoHelper::class);
        $tags = $seo->getMetaTags($post);
        
        $html = [];
        if (!empty($tags['title'])) {
            $html[] = '<title>' . e($tags['title']) . '</title>';
            $html[] = '<meta property="og:title" content="' . e($tags['title']) . '" />';
            $html[] = '<meta name="twitter:title" content="' . e($tags['title']) . '" />';
        }
        if (!empty($tags['description'])) {
            $html[] = '<meta name="description" content="' . e($tags['description']) . '" />';
            $html[] = '<meta property="og:description" content="' . e($tags['description']) . '" />';
            $html[] = '<meta name="twitter:description" content="' . e($tags['description']) . '" />';
        }
        if (!empty($tags['keywords'])) {
            $html[] = '<meta name="keywords" content="' . e($tags['keywords']) . '" />';
        }
        if (!empty($tags['robots'])) {
            $html[] = '<meta name="robots" content="' . e($tags['robots']) . '" />';
        }
        if (!empty($tags['url'])) {
            $html[] = '<meta property="og:url" content="' . e($tags['url']) . '" />';
        }
        if (!empty($tags['image'])) {
            $html[] = '<meta property="og:image" content="' . e(asset($tags['image'])) . '" />';
            $html[] = '<meta name="twitter:image" content="' . e(asset($tags['image'])) . '" />';
            $html[] = '<meta name="twitter:card" content="summary_large_image" />';
        }
        if (!empty($tags['og_type'])) {
            $html[] = '<meta property="og:type" content="' . e($tags['og_type']) . '" />';
        }

        return implode("\n    ", $html);
    }
}
