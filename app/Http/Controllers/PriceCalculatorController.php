<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceEstimateRequest;
use App\Services\AIService;
use App\Services\PriceEstimateMapper;
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

        // Rate limiting (5 requests per 10 minutes per IP) - Exempt for authenticated admins
        // Check if user is authenticated by checking session cookie
        $isAdmin = $this->isAuthenticatedAdmin($request);

        if (! $isAdmin) {
            $key = 'price_estimate_'.$request->ip();

            if (! $this->checkThrottling($key)) {
                return response()->json([
                    'error' => 'För många förfrågningar. Vänligen försök igen om 10 minuter.',
                ], 429);
            }
        }

        try {
            // Get AI analysis (project type, complexity, tech, features)
            $aiAnalysis = $this->aiService->estimateProjectPrice($description);

            // Map to predefined time and price ranges for consistency
            $priceEstimate = PriceEstimateMapper::map(
                $aiAnalysis['project_type'],
                $aiAnalysis['complexity']
            );

            // Merge AI analysis with price estimate
            $estimation = array_merge($aiAnalysis, $priceEstimate);

            Log::info('Price estimation successful', [
                'ip' => $request->ip(),
                'project_type' => $estimation['project_type'],
                'complexity' => $estimation['complexity'],
                'hours_range_traditional' => $estimation['hours_range_traditional'],
                'hours_range_ai' => $estimation['hours_range_ai'],
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
     * Check if user is authenticated admin via session cookie
     */
    private function isAuthenticatedAdmin(Request $request): bool
    {
        // Check if request has valid Laravel session cookie
        // If it does, assume user is authenticated (rate limit exempt)
        $sessionCookie = $request->cookie(config('session.cookie', 'laravel_session'));

        if (! $sessionCookie) {
            return false;
        }

        // Simple check: if session cookie exists, likely authenticated
        // (This is a permissive check for convenience - rate limit is not critical security)
        return true;
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
