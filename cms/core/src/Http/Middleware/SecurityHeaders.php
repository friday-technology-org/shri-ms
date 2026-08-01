<?php

namespace Cms\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Inject security hardening headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Don't inject headers on binary or file downloads
        if ($response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
            return $response;
        }

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        
        // Sensible CSP policy allowing inline styles/scripts for custom theme customizations
        $response->headers->set('Content-Security-Policy', "default-src 'self' * data: 'unsafe-inline' 'unsafe-eval';");

        return $response;
    }
}
