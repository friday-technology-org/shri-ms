<?php

namespace Cms\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $segments = $request->segments();
        $locales = ['en', 'fr', 'es', 'de', 'ne', 'ar']; // Supported locales

        if (!empty($segments) && in_array($segments[0], $locales)) {
            $locale = $segments[0];
            app()->setLocale($locale);

            // Strip the locale segment internally so router matches the default route pattern
            // E.g., /fr/about behaves like /about internally, but app locale is set to 'fr'
            // We can do this by rewriting the path info if needed, but in Laravel the fallback route
            // of FrontendController catchAll will catch '/fr/about' directly and strip it.
            // Let's store the locale segment in the request attributes so that catchAll knows about it.
            $request->attributes->set('cms_locale', $locale);
        } else {
            app()->setLocale(config('app.locale', 'en'));
        }

        return $next($request);
    }
}
