<?php

namespace App\Rules;

use App\Services\TurnstileService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidTurnstile implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $service = app(TurnstileService::class);
        $ip = request()->ip();

        if (! $service->verify($value, $ip)) {
            $fail('Vänligen bekräfta att du inte är en robot.');
        }
    }
}
