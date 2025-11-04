<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SEO Meta Tags
    |--------------------------------------------------------------------------
    |
    | These values are used as fallbacks when specific pages don't provide
    | their own meta tags through controllers or views.
    |
    */

    'default_title' => 'ATDev - AI-Driven Utveckling | 20+ Års Erfarenhet',

    'default_description' => 'Utvecklare med 20+ års erfarenhet kombinerar AI och automation för att leverera högkvalitativa webbapplikationer till en bråkdel av priset. Specialist på Laravel, React och AI-integration.',

    'default_keywords' => 'webbutveckling, AI-utveckling, Laravel, React, prompt engineering, AI-expert, Andreas Thun, ATDev, Stockholm, Sverige',

    'default_image' => '/images/og-default.jpg',

    /*
    |--------------------------------------------------------------------------
    | Site Information
    |--------------------------------------------------------------------------
    */

    'site_name' => 'ATDev',

    'author' => 'Andreas Thun',

    'locale' => 'sv_SE',

    'language' => 'sv',

    /*
    |--------------------------------------------------------------------------
    | Organization Schema Data
    |--------------------------------------------------------------------------
    */

    'organization' => [
        'name' => 'ATDev',
        'url' => 'https://atdev.me',
        'logo' => '/images/logo.png',
        'description' => 'AI-driven webbutveckling med 20+ års erfarenhet. Specialist på Laravel, React och AI-integration.',
        'founding_date' => '2004',
        'area_served' => 'SE',
        'slogan' => 'AI-Driven Utveckling för Moderna Företag',
    ],

    /*
    |--------------------------------------------------------------------------
    | Person Schema Data
    |--------------------------------------------------------------------------
    */

    'person' => [
        'name' => 'Andreas Thun',
        'job_title' => 'AI-Driven Fullstack-utvecklare',
        'description' => 'Utvecklare med 20+ års erfarenhet som kombinerar AI och automation för att leverera högkvalitativa webbapplikationer.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy (CSP)
    |--------------------------------------------------------------------------
    |
    | Define Content Security Policy directives for improved security.
    | These will be added as HTTP headers via SecurityHeaders middleware.
    |
    */

    'csp' => [
        'enabled' => env('CSP_ENABLED', true),

        'directives' => [
            'default-src' => ["'self'"],
            'script-src' => ["'self'", "'unsafe-inline'", "'unsafe-eval'", 'https://ajax.googleapis.com'],
            'style-src' => ["'self'", "'unsafe-inline'"],
            'img-src' => ["'self'", 'data:', 'https:', 'blob:'],
            'font-src' => ["'self'", 'data:'],
            'connect-src' => ["'self'", 'blob:'],
            'frame-ancestors' => ["'none'"],
            'base-uri' => ["'self'"],
            'form-action' => ["'self'"],
        ],

        'report_only' => env('CSP_REPORT_ONLY', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    */

    'security_headers' => [
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
        // X-Content-Type-Options hanteras automatiskt av Laravel 11
        // X-XSS-Protection är deprecated (CSP ger bättre skydd)
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource Hints
    |--------------------------------------------------------------------------
    |
    | External domains to prefetch/preconnect for faster resource loading.
    |
    */

    'resource_hints' => [
        'dns_prefetch' => [
            // No external CDNs used
        ],

        'preconnect' => [
            // No external CDNs used
        ],
    ],
];
