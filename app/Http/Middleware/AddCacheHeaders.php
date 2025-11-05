<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddCacheHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Get the request path
        $path = $request->path();

        // Vite versioned assets (immutable, 1 year cache)
        if (preg_match('/^build\/assets\/.+-[a-zA-Z0-9]{8}\.(css|js)$/', $path)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');

            return $response;
        }

        // Image files (1 month cache)
        if (preg_match('/\.(webp|jpg|jpeg|png|gif|svg|ico)$/', $path)) {
            $response->headers->set('Cache-Control', 'public, max-age=2592000');

            return $response;
        }

        return $response;
    }
}
