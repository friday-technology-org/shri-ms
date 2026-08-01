<?php

namespace Cms\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isInstalled = is_cms_installed();
        $isInstallRoute = $request->is('install*');

        if (!$isInstalled && !$isInstallRoute) {
            return redirect()->route('install.step1');
        }

        if ($isInstalled && $isInstallRoute && !$request->is('install/finish')) {
            return redirect()->to('/');
        }

        return $next($request);
    }
}
