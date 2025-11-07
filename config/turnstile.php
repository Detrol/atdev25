<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudflare Turnstile Configuration
    |--------------------------------------------------------------------------
    |
    | Turnstile is Cloudflare's CAPTCHA alternative for bot protection.
    | https://www.cloudflare.com/products/turnstile/
    |
    */

    'enabled' => env('TURNSTILE_ENABLED', false),

    'site_key' => env('TURNSTILE_SITE_KEY', ''),

    'secret_key' => env('TURNSTILE_SECRET_KEY', ''),

    'verify_url' => 'https://challenges.cloudflare.com/turnstile/v0/siteverify',

    /*
    |--------------------------------------------------------------------------
    | Timeout Settings
    |--------------------------------------------------------------------------
    |
    | Request timeout in seconds for Turnstile API verification.
    | If verification times out, fallback to allow the request.
    |
    */

    'timeout' => 5,

];
