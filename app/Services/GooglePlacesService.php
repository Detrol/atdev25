<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class GooglePlacesService
{
    private string $apiKey;

    private string $apiBaseUrl = 'https://maps.googleapis.com/maps/api/place';

    private const CACHE_TTL = 86400; // 24 hours in seconds

    public function __construct()
    {
        $this->apiKey = config('services.google.places_api_key');

        if (empty($this->apiKey)) {
            throw new Exception('Google Places API key not configured');
        }
    }

    /**
     * Search for places by name or query
     *
     * @param  string  $query  Search query
     * @param  string  $language  Response language (default: Swedish)
     * @return array Search results with place_id, name, address, rating, etc.
     */
    public function searchPlaces(string $query, string $language = 'sv'): array
    {
        $cacheKey = $this->getCacheKey('search', $query, $language);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($query, $language, $cacheKey) {
            try {
                $response = Http::timeout(10)->get("{$this->apiBaseUrl}/textsearch/json", [
                    'query' => $query,
                    'language' => $language,
                    'region' => 'se', // Bias results towards Sweden
                    'key' => $this->apiKey,
                ]);

                if (! $response->successful()) {
                    throw new Exception('Google API request failed');
                }

                $data = $response->json();

                return $this->handleApiResponse($data, $cacheKey);
            } catch (Exception $e) {
                Log::error('Google Places search error', [
                    'query' => $query,
                    'error' => $e->getMessage(),
                ]);

                throw $e;
            }
        });
    }

    /**
     * Get detailed place information including reviews
     *
     * @param  string  $placeId  Google Place ID
     * @param  string  $language  Response language (default: Swedish)
     * @return array Place details with reviews
     */
    public function getPlaceDetails(string $placeId, string $language = 'sv'): array
    {
        $this->validatePlaceId($placeId);

        $cacheKey = $this->getCacheKey('details', $placeId, $language);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($placeId, $language, $cacheKey) {
            try {
                $response = Http::timeout(10)->get("{$this->apiBaseUrl}/details/json", [
                    'place_id' => $placeId,
                    'fields' => 'name,formatted_address,rating,reviews,user_ratings_total,opening_hours,website,formatted_phone_number,photos',
                    'language' => $language,
                    'key' => $this->apiKey,
                ]);

                if (! $response->successful()) {
                    throw new Exception('Google API request failed');
                }

                $data = $response->json();

                return $this->handleApiResponse($data, $cacheKey);
            } catch (Exception $e) {
                Log::error('Google Places details error', [
                    'place_id' => $placeId,
                    'error' => $e->getMessage(),
                ]);

                throw $e;
            }
        });
    }

    /**
     * Get reviews for a specific place
     *
     * @param  string  $placeId  Google Place ID
     * @param  string  $language  Response language (default: Swedish)
     * @return array Formatted reviews data
     */
    public function getPlaceReviews(string $placeId, string $language = 'sv'): array
    {
        $details = $this->getPlaceDetails($placeId, $language);

        return [
            'place' => [
                'place_id' => $placeId,
                'name' => $details['result']['name'] ?? '',
                'formatted_address' => $details['result']['formatted_address'] ?? '',
                'rating' => $details['result']['rating'] ?? 0,
                'user_ratings_total' => $details['result']['user_ratings_total'] ?? 0,
                'website' => $details['result']['website'] ?? null,
                'phone' => $details['result']['formatted_phone_number'] ?? null,
            ],
            'reviews' => $this->formatReviews($details['result']['reviews'] ?? []),
            'meta' => [
                'cached' => Cache::has($this->getCacheKey('details', $placeId, $language)),
                'cached_at' => now()->toDateTimeString(),
                'total_reviews' => count($details['result']['reviews'] ?? []),
            ],
        ];
    }

    /**
     * Clear cache for a specific place
     *
     * @param  string  $placeId  Google Place ID
     */
    public function clearPlaceCache(string $placeId): bool
    {
        $languages = ['sv', 'en'];
        $cleared = true;

        foreach ($languages as $language) {
            $cacheKey = $this->getCacheKey('details', $placeId, $language);
            if (! Cache::forget($cacheKey)) {
                $cleared = false;
            }
        }

        return $cleared;
    }

    /**
     * Handle Google API response and check status
     *
     * @param  array  $data  API response data
     * @param  string  $cacheKey  Cache key for fallback
     * @return array Processed response
     *
     * @throws Exception
     */
    private function handleApiResponse(array $data, string $cacheKey): array
    {
        $status = $data['status'] ?? 'UNKNOWN';

        switch ($status) {
            case 'OK':
                return $data;

            case 'ZERO_RESULTS':
                return ['results' => [], 'status' => 'OK'];

            case 'INVALID_REQUEST':
                throw new InvalidArgumentException('Ogiltig förfrågan till Google Places API');
            case 'OVER_QUERY_LIMIT':
                Log::warning('Google API quota exceeded');

                // Try to return cached data
                $cached = Cache::get($cacheKey);
                if ($cached) {
                    return $cached;
                }

                throw new Exception('API-kvot överskriden. Försök igen senare.');
            case 'REQUEST_DENIED':
                Log::error('Google API request denied - check API key configuration');
                throw new Exception('API-konfigurationsfel');
            case 'UNKNOWN_ERROR':
                throw new Exception('Oväntat fel från Google API. Försök igen.');
            default:
                throw new Exception("Okänd API-status: {$status}");
        }
    }

    /**
     * Validate Place ID format
     *
     * @throws InvalidArgumentException
     */
    private function validatePlaceId(string $placeId): void
    {
        // Google Place IDs typically start with "ChIJ" but can have other prefixes
        if (strlen($placeId) < 10 || ! preg_match('/^[A-Za-z0-9_-]+$/', $placeId)) {
            throw new InvalidArgumentException('Ogiltigt Place ID-format');
        }
    }

    /**
     * Generate cache key
     *
     * @param  string  $type  Cache type (search, details)
     * @param  string  $identifier  Query or Place ID
     * @param  string  $language  Language code
     */
    private function getCacheKey(string $type, string $identifier, string $language = 'sv'): string
    {
        $sanitized = str_replace([' ', ',', '.'], '_', strtolower($identifier));

        return "google_places_{$type}:{$sanitized}:{$language}";
    }

    /**
     * Format reviews data for frontend consumption
     *
     * @param  array  $reviews  Raw reviews from API
     * @return array Formatted reviews
     */
    private function formatReviews(array $reviews): array
    {
        return array_map(function ($review) {
            return [
                'author_name' => $review['author_name'] ?? 'Anonym',
                'author_photo' => $review['profile_photo_url'] ?? null,
                'rating' => $review['rating'] ?? 0,
                'relative_time_description' => $review['relative_time_description'] ?? '',
                'text' => $review['text'] ?? '',
                'time' => $review['time'] ?? time(),
            ];
        }, $reviews);
    }
}
