<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceEstimateRequest;
use App\Services\AIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class PriceCalculatorController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Estimate project price based on description
     */
    public function estimate(PriceEstimateRequest $request): JsonResponse
    {
        $description = $request->validated()['description'];

        // Rate limiting (5 requests per 10 minutes per IP)
        $key = 'price_estimate_'.$request->ip();

        if (! $this->checkThrottling($key)) {
            return response()->json([
                'error' => 'För många förfrågningar. Vänligen försök igen om 10 minuter.',
            ], 429);
        }

        try {
            // Get AI estimation
            $estimation = $this->aiService->estimateProjectPrice($description);

            // Calculate prices (700 kr/h)
            $hourlyRate = 700;
            $estimation['price_traditional'] = $estimation['estimated_hours_traditional'] * $hourlyRate;
            $estimation['price_ai'] = $estimation['estimated_hours_ai'] * $hourlyRate;
            $estimation['savings'] = $estimation['price_traditional'] - $estimation['price_ai'];
            $estimation['savings_percent'] = 50; // Always 50% due to AI efficiency

            // With VAT (25%)
            $estimation['price_traditional_vat'] = round($estimation['price_traditional'] * 1.25);
            $estimation['price_ai_vat'] = round($estimation['price_ai'] * 1.25);
            $estimation['savings_vat'] = $estimation['price_traditional_vat'] - $estimation['price_ai_vat'];

            Log::info('Price estimation successful', [
                'ip' => $request->ip(),
                'project_type' => $estimation['project_type'],
                'complexity' => $estimation['complexity'],
                'hours_traditional' => $estimation['estimated_hours_traditional'],
                'hours_ai' => $estimation['estimated_hours_ai'],
            ]);

            return response()->json([
                'success' => true,
                'estimation' => $estimation,
            ]);
        } catch (\Throwable $e) {
            Log::error('Price estimation failed', [
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
                'description_length' => strlen($description),
            ]);

            return response()->json([
                'error' => 'Kunde inte estimera projektet. Vänligen försök igen eller kontakta oss direkt.',
            ], 500);
        }
    }

    /**
     * Check rate limiting
     */
    private function checkThrottling(string $key): bool
    {
        return RateLimiter::attempt(
            $key,
            $maxAttempts = 5, // 5 requests
            function () {
                // Executed if rate limit not exceeded
            },
            $decaySeconds = 600 // Per 10 minutes
        );
    }
}
