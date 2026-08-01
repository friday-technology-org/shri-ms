<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Post;
use Cms\Core\Models\Term;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class SeoSitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap.
     */
    public function sitemap(): Response
    {
        $posts = Post::where('status', 'published')->orderBy('updated_at', 'desc')->get();
        $terms = Term::all();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        $xml .= '<url>';
        $xml .= '<loc>' . url('/') . '</loc>';
        $xml .= '<lastmod>' . (count($posts) > 0 ? $posts->first()->updated_at->toAtomString() : now()->toAtomString()) . '</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '</url>';

        // Posts, Pages, CPTs
        foreach ($posts as $post) {
            $xml .= '<url>';
            $xml .= '<loc>' . $post->permalink . '</loc>';
            $xml .= '<lastmod>' . $post->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>' . ($post->post_type === 'page' ? 'weekly' : 'monthly') . '</changefreq>';
            $xml .= '<priority>' . ($post->post_type === 'page' ? '0.8' : '0.6') . '</priority>';
            $xml .= '</url>';
        }

        // Terms/Categories/Tags
        foreach ($terms as $term) {
            $xml .= '<url>';
            $xml .= '<loc>' . url('category/' . $term->slug) . '</loc>';
            $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.4</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Output plain-text robots.txt file.
     */
    public function robots(): Response
    {
        $defaultRobots = "User-agent: *\nDisallow: /admin/\nDisallow: /install/\n\nSitemap: " . url('/sitemap.xml');
        $robotsContent = cms_option('robots_txt_content', $defaultRobots);

        return response($robotsContent, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
