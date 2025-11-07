<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurnstileService
{
    /**
     * Verify Cloudflare Turnstile token.
     *
     * @param string $token The cf-turnstile-response token from the form
     * @param string $ip The user's IP address
     * @return bool True if verification passes or Turnstile is disabled
     */
    public function verify(string $token, string $ip): bool
    {
        // Skip verification in testing environment
        if (app()->environment('testing')) {
            return true;
        }

        // Skip verification if Turnstile is disabled (local development)
        if (! config('turnstile.enabled')) {
            Log::info('Turnstile disabled - skipping verification');

            return true;
        }

        // Validate configuration
        if (empty(config('turnstile.secret_key'))) {
            Log::error('Turnstile secret key not configured');

            return false;
        }

        try {
            $response = Http::timeout(config('turnstile.timeout'))
                ->asForm()
                ->post(config('turnstile.verify_url'), [
                    'secret' => config('turnstile.secret_key'),
                    'response' => $token,
                    'remoteip' => $ip,
                ]);

            $result = $response->json();

            if ($result['success'] ?? false) {
                Log::info('Turnstile verification success', [
                    'ip' => $ip,
                    'challenge_ts' => $result['challenge_ts'] ?? null,
                ]);

                return true;
            }

            Log::warning('Turnstile verification failed', [
                'ip' => $ip,
                'errors' => $result['error-codes'] ?? [],
            ]);

            return false;

        } catch (\Exception $e) {
            // Fallback: Allow request if Turnstile service is down
            // This ensures we don't block legitimate users during outages
            Log::error('Turnstile verification exception', [
                'error' => $e->getMessage(),
                'ip' => $ip,
            ]);

            // Return true to allow request (honeypot + rate limiting still protect)
            return true;
        }
    }
}
