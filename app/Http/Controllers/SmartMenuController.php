<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;

class SmartMenuController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Analyze a dish description for allergens using AI.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function analyzeAllergens(Request $request): JsonResponse
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'dish_description' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Ogiltig matbeskrivning. Max 1000 tecken.',
                'errors' => $validator->errors()
            ], 422);
        }

        $dishDescription = $request->input('dish_description');
        $clientIp = $request->ip();

        // Rate limiting: 10 requests per minute per IP
        if (!$this->checkThrottling($clientIp)) {
            return response()->json([
                'success' => false,
                'error' => 'För många förfrågningar. Vänligen vänta en minut innan du försöker igen.'
            ], 429);
        }

        try {
            // Get allergen analysis from AI
            $allergenAnalysis = $this->aiService->analyzeMenuAllergens($dishDescription);

            Log::info('Smart Menu allergen analysis', [
                'client_ip' => $clientIp,
                'dish' => substr($dishDescription, 0, 100),
                'allergens_found' => $allergenAnalysis['allergens'] ?? [],
            ]);

            return response()->json([
                'success' => true,
                'analysis' => $allergenAnalysis,
            ]);
        } catch (\Throwable $e) {
            Log::error('Smart Menu analysis error', [
                'client_ip' => $clientIp,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Ett fel uppstod vid AI-analysen. Försök igen.'
            ], 500);
        }
    }

    /**
     * Check rate limiting for the client IP.
     *
     * @param  string  $clientIp
     * @return bool
     */
    private function checkThrottling(string $clientIp): bool
    {
        $key = 'smart-menu-analyze:' . $clientIp;

        return RateLimiter::attempt(
            $key,
            10, // max attempts
            function () {
                return null;
            },
            60 // decay in seconds
        );
    }
}
