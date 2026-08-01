<?php

namespace Cms\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class PageCache
{
    /**
     * Handle public page caching.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only cache GET requests that are not admin, auth, or installer routes
        if (!$request->isMethod('GET') || $request->is('admin*') || $request->is('install*') || auth()->check()) {
            return $next($request);
        }

        $cacheDir = storage_path('framework/cache/pages');
        if (!File::isDirectory($cacheDir)) {
            File::makeDirectory($cacheDir, 0755, true);
        }

        $cacheKey = md5($request->fullUrl());
        $cacheFile = $cacheDir . '/' . $cacheKey . '.html';

        // Check if cache file exists and is still valid (let's set a default 1-hour cache lifetime)
        $cacheLifetime = 3600; // 1 hour
        if (File::isFile($cacheFile) && (time() - File::lastModified($cacheFile) < $cacheLifetime)) {
            $content = File::get($cacheFile);
            return response($content)
                ->header('X-Shri-ms-Cache', 'HIT')
                ->header('Content-Type', 'text/html; charset=UTF-8');
        }

        $response = $next($request);

        // Only cache successful HTML responses
        if ($response->getStatusCode() === 200 && str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            File::put($cacheFile, $response->getContent());
            $response->headers->set('X-Shri-ms-Cache', 'MISS');
        }

        return $response;
    }

    /**
     * Helper method to clear all static page cache files.
     */
    public static function clear(): void
    {
        $cacheDir = storage_path('framework/cache/pages');
        if (File::isDirectory($cacheDir)) {
            File::cleanDirectory($cacheDir);
        }
    }
}
