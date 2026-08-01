<?php

namespace Cms\Core\Http\Middleware;

use Closure;
use Cms\Core\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateCmsApi
{
    public function handle(Request $request, Closure $next)
    {
        $tokenString = $request->bearerToken();

        if (!$tokenString) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Token is stored in plain or hashed. Hash matches standard security.
        // Let's look up both to be safe and support both formats
        $token = ApiToken::where('token', hash('sha256', $tokenString))
            ->orWhere('token', $tokenString)
            ->first();

        if (!$token || ($token->expires_at && $token->expires_at->isPast())) {
            return response()->json(['message' => 'Invalid or expired API token.'], 401);
        }

        // Update last used at
        $token->update(['last_used_at' => now()]);

        // Login user for duration of request
        Auth::login($token->user);

        return $next($request);
    }
}
