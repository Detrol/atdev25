<?php

use App\Services\TurnstileService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

uses(Tests\TestCase::class);

beforeEach(function () {
    Config::set('turnstile.verify_url', 'https://challenges.cloudflare.com/turnstile/v0/siteverify');
    Config::set('turnstile.timeout', 5);
});

it('returns true when turnstile is disabled', function () {
    Config::set('turnstile.enabled', false);

    $service = new TurnstileService;
    $result = $service->verify('fake-token', '127.0.0.1');

    expect($result)->toBeTrue();
});

it('returns false when secret key is not configured', function () {
    Config::set('turnstile.enabled', true);
    Config::set('turnstile.secret_key', '');

    $service = new TurnstileService;
    $result = $service->verify('fake-token', '127.0.0.1');

    expect($result)->toBeFalse();
});

it('returns true when cloudflare verification succeeds', function () {
    Config::set('turnstile.enabled', true);
    Config::set('turnstile.secret_key', 'test-secret-key');

    Http::fake([
        'challenges.cloudflare.com/*' => Http::response([
            'success' => true,
            'challenge_ts' => '2025-01-01T00:00:00Z',
        ], 200),
    ]);

    $service = new TurnstileService;
    $result = $service->verify('valid-token', '127.0.0.1');

    expect($result)->toBeTrue();
});

it('returns false when cloudflare verification fails', function () {
    Config::set('turnstile.enabled', true);
    Config::set('turnstile.secret_key', 'test-secret-key');

    Http::fake([
        'challenges.cloudflare.com/*' => Http::response([
            'success' => false,
            'error-codes' => ['invalid-input-response'],
        ], 200),
    ]);

    $service = new TurnstileService;
    $result = $service->verify('invalid-token', '127.0.0.1');

    expect($result)->toBeFalse();
});

it('returns true as fallback when cloudflare api times out', function () {
    Config::set('turnstile.enabled', true);
    Config::set('turnstile.secret_key', 'test-secret-key');

    Http::fake([
        'challenges.cloudflare.com/*' => function () {
            throw new \Illuminate\Http\Client\ConnectionException('Connection timeout');
        },
    ]);

    $service = new TurnstileService;
    $result = $service->verify('any-token', '127.0.0.1');

    // Should return true to not block legitimate users during Cloudflare outage
    expect($result)->toBeTrue();
});

it('sends correct data to cloudflare api', function () {
    Config::set('turnstile.enabled', true);
    Config::set('turnstile.secret_key', 'test-secret-key');

    Http::fake();

    $service = new TurnstileService;
    $service->verify('test-token', '192.168.1.1');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://challenges.cloudflare.com/turnstile/v0/siteverify'
            && $request['secret'] === 'test-secret-key'
            && $request['response'] === 'test-token'
            && $request['remoteip'] === '192.168.1.1';
    });
});
