<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Undanta cookie_consent_id från encryption så den kan läsas av JavaScript
        $middleware->encryptCookies(except: [
            'cookie_consent_id',
        ]);

        // Undanta Mailgun webhook från CSRF-skydd
        // Webhook körs på webhooks.atdev.me och använder signature verification istället
        $middleware->validateCsrfTokens(except: [
            'mailgun/inbound',
        ]);

        // Add security headers globally (CSP, X-Frame-Options, etc.)
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
