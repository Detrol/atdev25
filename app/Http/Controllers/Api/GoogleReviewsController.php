<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GooglePlacesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Exception;

class GoogleReviewsController extends Controller
{
    public function __construct(
        private GooglePlacesService $googlePlacesService
    ) {}

    /**
     * Get default place reviews (Puts i Karlstad)
     *
     * @return JsonResponse
     */
    public function default(): JsonResponse
    {
        try {
            $defaultPlaceId = config('services.google.default_place_id');

            if (empty($defaultPlaceId)) {
                return $this->errorResponse('Default Place ID ej konfigurerat', 'CONFIG_ERROR', 500);
            }

            $data = $this->googlePlacesService->getPlaceReviews($defaultPlaceId);

            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Search for places by name or query
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => 'required|string|max:200|min:2',
        ], [
            'query.required' => 'Sökfråga krävs',
            'query.min' => 'Sökfråga måste vara minst 2 tecken',
            'query.max' => 'Sökfråga får vara max 200 tecken',
        ]);

        // Check rate limit
        $key = 'google-search:' . $request->ip();
        if (!$this->checkRateLimit($key, 20)) {
            return $this->errorResponse(
                'För många sökningar. Försök igen om en minut.',
                'RATE_LIMIT_EXCEEDED',
                429
            );
        }

        try {
            $query = strip_tags($validated['query']);
            $results = $this->googlePlacesService->searchPlaces($query);

            // Format search results
            $formattedResults = array_map(function ($place) {
                return [
                    'place_id' => $place['place_id'] ?? '',
                    'name' => $place['name'] ?? '',
                    'formatted_address' => $place['formatted_address'] ?? '',
                    'rating' => $place['rating'] ?? 0,
                    'user_ratings_total' => $place['user_ratings_total'] ?? 0,
                ];
            }, $results['results'] ?? []);

            return $this->successResponse([
                'results' => $formattedResults,
                'count' => count($formattedResults),
            ]);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get place details with reviews by Place ID
     *
     * @param string $placeId
     * @return JsonResponse
     */
    public function show(string $placeId): JsonResponse
    {
        // Check rate limit
        $key = 'google-show:' . request()->ip();
        if (!$this->checkRateLimit($key, 30)) {
            return $this->errorResponse(
                'För många förfrågningar. Försök igen om en minut.',
                'RATE_LIMIT_EXCEEDED',
                429
            );
        }

        try {
            $data = $this->googlePlacesService->getPlaceReviews($placeId);

            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Check rate limit for a given key
     *
     * @param string $key
     * @param int $maxAttempts
     * @return bool
     */
    private function checkRateLimit(string $key, int $maxAttempts): bool
    {
        return RateLimiter::attempt(
            $key,
            $maxAttempts,
            function () {
                return true;
            },
            60 // decay seconds (1 minute)
        );
    }

    /**
     * Success response format
     *
     * @param mixed $data
     * @return JsonResponse
     */
    private function successResponse($data): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Error response format
     *
     * @param string $message
     * @param string $code
     * @param int $statusCode
     * @return JsonResponse
     */
    private function errorResponse(string $message, string $code, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $message,
            'code' => $code,
        ], $statusCode);
    }

    /**
     * Handle exceptions and return appropriate error response
     *
     * @param Exception $e
     * @return JsonResponse
     */
    private function handleException(Exception $e): JsonResponse
    {
        $message = $e->getMessage();
        $code = 'API_ERROR';
        $statusCode = 500;

        // Customize based on exception type
        if (str_contains($message, 'Ogiltig') || str_contains($message, 'Invalid')) {
            $code = 'INVALID_REQUEST';
            $statusCode = 400;
        } elseif (str_contains($message, 'inte hittades') || str_contains($message, 'not found')) {
            $code = 'PLACE_NOT_FOUND';
            $statusCode = 404;
        } elseif (str_contains($message, 'kvot') || str_contains($message, 'quota')) {
            $code = 'QUOTA_EXCEEDED';
            $statusCode = 503;
        }

        return $this->errorResponse($message, $code, $statusCode);
    }
}
