<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add standard security headers
        foreach (config('seo.security_headers', []) as $header => $value) {
            $response->headers->set($header, $value);
        }

        // Add Content Security Policy if enabled
        if (config('seo.csp.enabled', true)) {
            $csp = $this->buildCSP();
            $headerName = config('seo.csp.report_only', false)
                ? 'Content-Security-Policy-Report-Only'
                : 'Content-Security-Policy';

            $response->headers->set($headerName, $csp);
        }

        return $response;
    }

    /**
     * Build Content Security Policy string from config
     */
    private function buildCSP(): string
    {
        $directives = config('seo.csp.directives', []);
        $policy = [];

        foreach ($directives as $directive => $sources) {
            $policy[] = $directive . ' ' . implode(' ', $sources);
        }

        return implode('; ', $policy);
    }
}
