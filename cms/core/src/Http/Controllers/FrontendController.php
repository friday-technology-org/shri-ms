<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Post;
use Cms\Core\Models\PostType;
use Cms\Core\Models\SlugRedirect;
use Cms\Core\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FrontendController extends Controller
{
    public function catchAll(Request $request)
    {
        $path = $request->path();
        
        $locale = $request->attributes->get('cms_locale');
        if ($locale) {
            $path = preg_replace('/^' . preg_quote($locale, '/') . '\/?/', '', $path);
        }

        // Remove trailing slash if present
        $slug = trim($path, '/');

        // (URL suffix logic removed)

        if (empty($slug)) {
            return $this->renderHomepage();
        }

        // 1. Check if an active post/page/CPT entry exists with this slug
        $post = null;
        if (!str_contains($slug, '/')) {
            $postQuery = Post::where('slug', $slug)
                        ->whereIn('post_type', ['post', 'page'])
                        ->where('status', 'published');
            if ($locale) {
                $postQuery->where('lang', $locale);
            }
            $post = $postQuery->first();
        } else {
            $parts = explode('/', $slug);
            if (count($parts) === 2) {
                $postTypeSlug = $parts[0];
                $postSlug = $parts[1];
                $postQuery = Post::where('post_type', $postTypeSlug)
                            ->where('slug', $postSlug)
                            ->where('status', 'published');
                if ($locale) {
                    $postQuery->where('lang', $locale);
                }
                $post = $postQuery->first();
            }
        }

        if ($post) {
            if ($post->post_type === 'page' && cms_option('page_on_front') == $post->id) {
                return redirect('/', 301);
            }
            return $this->renderPost($post);
        }

        // 2. If not found, check the slug_redirects table for 301 redirects
        $lookupSlug = $slug;
        if (str_contains($slug, '/')) {
            $parts = explode('/', $slug);
            if (count($parts) === 2) {
                $lookupSlug = $parts[1];
            }
        }

        $redirect = SlugRedirect::where('old_slug', $lookupSlug)->first();
        if ($redirect && $redirect->post) {
            $p = $redirect->post;
            $targetUrl = ($p->post_type === 'post' || $p->post_type === 'page') ? $p->slug : $p->post_type . '/' . $p->slug;
            return redirect('/' . $targetUrl, 301);
        }

        // 3. Taxonomy term archive
        $term = Term::where('slug', $slug)->first();
        if ($term) {
            return $this->renderTermArchive($term);
        }

        // 4. Custom Post Type archive (post_types.has_archive)
        $postType = PostType::where('name', $slug)->where('has_archive', true)->first();
        if ($postType) {
            return $this->renderPostTypeArchive($postType);
        }

        // 5. Nothing matched — 404
        $this->log404($request);

        if (view()->exists('theme::404')) {
            return response()->view('theme::404', [], 404);
        }
        abort(404);
    }

    protected function log404(Request $request): void
    {
        try {
            $log = \Cms\Core\Models\Cms404Log::firstOrNew([
                'url' => $request->getRequestUri(),
            ]);
            $log->referrer = $request->header('referer');
            $log->ip_address = $request->ip();
            $log->user_agent = $request->userAgent();
            $log->count = $log->exists ? $log->count + 1 : 1;
            $log->save();
        } catch (\Throwable $e) {
            logger()->error('Failed to log 404: ' . $e->getMessage());
        }
    }

    protected function renderHomepage()
    {
        if (cms_option('show_on_front') === 'page') {
            $pageId = cms_option('page_on_front');
            if ($pageId) {
                $page = Post::where('id', $pageId)->where('status', 'published')->first();
                if ($page) {
                    return $this->renderPost($page);
                }
            }
        }

        if (!view()->exists('theme::index')) {
            return 'Welcome to LaraCMS (Homepage)';
        }

        $posts = Post::where('post_type', 'post')
                     ->where('status', 'published')
                     ->latest('published_at')
                     ->paginate(10);

        cms_loop()->setup($posts->items());

        return view('theme::index', compact('posts'));
    }

    /**
     * Resolve the most specific available template for a post/page/CPT entry:
     * page  -> theme::page-{slug} -> theme::page -> theme::single -> theme::index
     * other -> theme::single-{post_type} -> theme::single -> theme::index
     */
    protected function renderPost(Post $post)
    {
        cms_loop()->setup($post);
        cms_loop()->thePost();

        $candidates = $post->post_type === 'page'
            ? ['theme::page-' . $post->slug, 'theme::page', 'theme::single', 'theme::index']
            : ['theme::single-' . $post->post_type, 'theme::single', 'theme::index'];

        $customTemplate = $post->getMeta('_cms_page_template');
        if ($customTemplate) {
            array_unshift($candidates, 'theme::' . $customTemplate);
        }

        foreach ($candidates as $view) {
            if (view()->exists($view)) {
                return view($view, compact('post'));
            }
        }

        return 'Render post: ' . $post->title;
    }

    protected function renderTermArchive(Term $term)
    {
        $posts = $term->posts()->where('status', 'published')->latest('published_at')->paginate(10);
        cms_loop()->setup($posts->items());

        if (view()->exists('theme::archive')) {
            return view('theme::archive', ['term' => $term, 'posts' => $posts]);
        }

        return 'Archive: ' . $term->name;
    }

    protected function renderPostTypeArchive(PostType $postType)
    {
        $posts = Post::where('post_type', $postType->name)
                     ->where('status', 'published')
                     ->latest('published_at')
                     ->paginate(10);

        cms_loop()->setup($posts->items());

        if (view()->exists('theme::archive')) {
            return view('theme::archive', ['postType' => $postType, 'posts' => $posts]);
        }

        return 'Archive: ' . $postType->plural_label;
    }
}
