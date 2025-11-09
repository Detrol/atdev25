<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google reCAPTCHA v3 Configuration
    |--------------------------------------------------------------------------
    |
    | Enable/disable reCAPTCHA and configure site/secret keys.
    | Get your keys from: https://www.google.com/recaptcha/admin
    |
    */

    'enabled' => env('RECAPTCHA_ENABLED', true),

    'site_key' => env('RECAPTCHA_SITE_KEY'),

    'secret_key' => env('RECAPTCHA_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Minimum Score Threshold
    |--------------------------------------------------------------------------
    |
    | reCAPTCHA v3 returns a score (1.0 is very likely a good interaction,
    | 0.0 is very likely a bot). Recommended threshold: 0.5
    |
    */

    'threshold' => env('RECAPTCHA_THRESHOLD', 0.5),

    /*
    |--------------------------------------------------------------------------
    | Verify URL
    |--------------------------------------------------------------------------
    |
    | Google's reCAPTCHA verification endpoint
    |
    */

    'verify_url' => 'https://www.google.com/recaptcha/api/siteverify',
];
