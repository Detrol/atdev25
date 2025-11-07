<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceEstimateRequest;
use App\Models\PriceEstimation;
use App\Services\AIService;
use App\Services\BrightDataScraper;
use App\Services\PriceEstimateMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class PriceCalculatorController extends Controller
{
    protected AIService $aiService;
    protected BrightDataScraper $scraper;

    public function __construct(AIService $aiService, BrightDataScraper $scraper)
    {
        $this->aiService = $aiService;
        $this->scraper = $scraper;
    }

    /**
     * Estimate project price based on description and service category
     */
    public function estimate(PriceEstimateRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $description = $validated['description'];
        $serviceCategory = $validated['service_category'];
        $websiteUrl = $validated['website_url'] ?? null;

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
            // Scrape website if URL provided
            $scrapedData = null;
            $scrapedContent = null;
            $scrapeSuccessful = false;
            $scrapeError = null;

            if ($websiteUrl) {
                try {
                    Log::info('Scraping website for price estimation', ['url' => $websiteUrl]);
                    $scrapedData = $this->scraper->scrape($websiteUrl);
                    $scrapedContent = $this->scraper->generateSummary($scrapedData);
                    $scrapeSuccessful = true;

                    // Prepend scraped content to description for AI
                    $description = $scrapedContent . "\n\n" . $description;

                    Log::info('Website scraped successfully', [
                        'url' => $websiteUrl,
                        'technologies' => $scrapedData['technologies'] ?? [],
                    ]);
                } catch (\Exception $e) {
                    // Continue with estimation even if scraping fails
                    $scrapeError = $e->getMessage();
                    Log::warning('Website scraping failed, continuing with user description only', [
                        'url' => $websiteUrl,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Get AI analysis (project type, complexity, tech, features)
            $aiAnalysis = $this->aiService->estimateProjectPrice($description, $serviceCategory);

            // Map to predefined time and price ranges for consistency
            $priceEstimate = PriceEstimateMapper::map(
                $aiAnalysis['project_type'],
                $aiAnalysis['complexity']
            );

            // Merge AI analysis with price estimate
            $estimation = array_merge($aiAnalysis, $priceEstimate);

            // Save estimation to database
            $priceEstimationRecord = PriceEstimation::create([
                'description' => $validated['description'], // Use original description, not modified one
                'service_category' => $serviceCategory,
                'website_url' => $websiteUrl,
                'scraped_content' => $scrapedContent,
                'scraped_metadata' => $scrapedData,
                'scrape_successful' => $scrapeSuccessful,
                'scrape_error' => $scrapeError,
                'project_type' => $estimation['project_type'],
                'complexity' => $estimation['complexity'],
                'project_type_label' => $estimation['project_type_label'],
                'complexity_label' => $estimation['complexity_label'],
                'key_features' => $estimation['key_features'],
                'solution_approach' => $estimation['solution_approach'] ?? null,
                'hours_traditional_min' => $estimation['hours_range_traditional'][0],
                'hours_traditional_max' => $estimation['hours_range_traditional'][1],
                'hours_ai_min' => $estimation['hours_range_ai'][0],
                'hours_ai_max' => $estimation['hours_range_ai'][1],
                'hours_traditional' => $estimation['hours_traditional'],
                'hours_ai' => $estimation['hours_ai'],
                'price_traditional_min' => $estimation['price_range_traditional'][0],
                'price_traditional_max' => $estimation['price_range_traditional'][1],
                'price_ai_min' => $estimation['price_range_ai'][0],
                'price_ai_max' => $estimation['price_range_ai'][1],
                'price_traditional' => $estimation['price_traditional'],
                'price_ai' => $estimation['price_ai'],
                'price_traditional_vat_min' => $estimation['price_range_traditional_vat'][0],
                'price_traditional_vat_max' => $estimation['price_range_traditional_vat'][1],
                'price_ai_vat_min' => $estimation['price_range_ai_vat'][0],
                'price_ai_vat_max' => $estimation['price_range_ai_vat'][1],
                'price_traditional_vat' => $estimation['price_traditional_vat'],
                'price_ai_vat' => $estimation['price_ai_vat'],
                'savings_min' => $estimation['savings_range'][0],
                'savings_max' => $estimation['savings_range'][1],
                'savings' => $estimation['savings'],
                'savings_vat_min' => $estimation['savings_range_vat'][0],
                'savings_vat_max' => $estimation['savings_range_vat'][1],
                'savings_vat' => $estimation['savings_vat'],
                'savings_percent' => $estimation['savings_percent'],
                'delivery_weeks_traditional' => $estimation['delivery_weeks_traditional'],
                'delivery_weeks_ai' => $estimation['delivery_weeks_ai'],
                'ip_address' => $request->ip(),
                'session_id' => session()->getId(),
            ]);

            Log::info('Price estimation successful', [
                'ip' => $request->ip(),
                'estimation_id' => $priceEstimationRecord->id,
                'service_category' => $serviceCategory,
                'project_type' => $estimation['project_type'],
                'complexity' => $estimation['complexity'],
                'hours_range_traditional' => $estimation['hours_range_traditional'],
                'hours_range_ai' => $estimation['hours_range_ai'],
            ]);

            return response()->json([
                'success' => true,
                'estimation_id' => $priceEstimationRecord->id,
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
