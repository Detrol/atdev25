<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ValidRecaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip validation if reCAPTCHA is disabled
        if (!config('recaptcha.enabled')) {
            return;
        }

        // Missing token
        if (empty($value)) {
            $fail('Säkerhetsverifiering saknas.');
            return;
        }

        try {
            // Verify with Google
            $response = Http::asForm()->post(config('recaptcha.verify_url'), [
                'secret' => config('recaptcha.secret_key'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            // Check if verification was successful
            if (!$result['success']) {
                Log::warning('reCAPTCHA verification failed', [
                    'error_codes' => $result['error-codes'] ?? [],
                    'ip' => request()->ip(),
                ]);

                $fail('Säkerhetsverifiering misslyckades. Vänligen försök igen.');
                return;
            }

            // Check score (reCAPTCHA v3 returns a score between 0.0 and 1.0)
            $score = $result['score'] ?? 0;
            $threshold = config('recaptcha.threshold', 0.5);

            if ($score < $threshold) {
                Log::warning('reCAPTCHA score too low', [
                    'score' => $score,
                    'threshold' => $threshold,
                    'ip' => request()->ip(),
                ]);

                $fail('Säkerhetsverifiering misslyckades. Vänligen försök igen.');
                return;
            }

        } catch (\Exception $e) {
            // Log error but don't fail validation (graceful degradation)
            Log::error('reCAPTCHA verification error', [
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);

            // In production, you might want to fail here instead
            // For now, we allow the request to proceed if reCAPTCHA service is down
            if (app()->environment('production')) {
                $fail('Säkerhetsverifiering kunde inte genomföras. Vänligen försök igen senare.');
            }
        }
    }
}
