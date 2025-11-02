<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CookieConsentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ConsentController extends Controller
{
    public function __construct(
        private CookieConsentService $consentService
    ) {}

    /**
     * Hämta nuvarande cookie consent preferences
     *
     * GET /api/consent
     */
    public function show(): JsonResponse
    {
        $preferences = $this->consentService->getPreferences();
        $hasChoice = $this->consentService->hasChoiceMade();

        return response()->json([
            'has_choice_made' => $hasChoice,
            'preferences' => $preferences,
        ]);
    }

    /**
     * Spara cookie consent preferences
     *
     * POST /api/consent
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'essential' => 'sometimes|boolean',
            'functional' => 'sometimes|boolean',
            'analytics' => 'sometimes|boolean',
            'marketing' => 'sometimes|boolean',
        ]);

        $preferences = [
            'functional' => $request->boolean('functional', false),
            'analytics' => $request->boolean('analytics', false),
            'marketing' => $request->boolean('marketing', false),
        ];

        $consent = $this->consentService->saveConsent($request, $preferences);

        // Hämta consent ID och sätt cookie explicit
        $consentId = $this->consentService->getConsentIdForCookie($request);
        $lifetime = $this->consentService->getCookieLifetime();

        return response()->json([
            'success' => true,
            'message' => 'Cookie preferences sparade',
            'preferences' => [
                'essential' => $consent->essential,
                'functional' => $consent->functional,
                'analytics' => $consent->analytics,
                'marketing' => $consent->marketing,
            ],
        ])->cookie(
            'cookie_consent_id',
            $consentId,
            $lifetime,
            '/',
            null,
            config('session.secure', false), // Använd samma secure-inställning som sessioner
            false,
            false,
            'lax' // SameSite=Lax för bättre kompatibilitet
        );
    }

    /**
     * Acceptera alla cookies
     *
     * POST /api/consent/accept-all
     */
    public function acceptAll(Request $request): JsonResponse
    {
        $consent = $this->consentService->acceptAll($request);

        // Hämta consent ID och sätt cookie explicit
        $consentId = $this->consentService->getConsentIdForCookie($request);
        $lifetime = $this->consentService->getCookieLifetime();

        return response()->json([
            'success' => true,
            'message' => 'Alla cookies accepterade',
            'preferences' => [
                'essential' => true,
                'functional' => true,
                'analytics' => true,
                'marketing' => true,
            ],
        ])->cookie(
            'cookie_consent_id',
            $consentId,
            $lifetime,
            '/',
            null,
            config('session.secure', false),
            false,
            false,
            'lax'
        );
    }

    /**
     * Avvisa alla cookies utom essential
     *
     * POST /api/consent/reject-all
     */
    public function rejectAll(Request $request): JsonResponse
    {
        $consent = $this->consentService->rejectAll($request);

        // Hämta consent ID och sätt cookie explicit
        $consentId = $this->consentService->getConsentIdForCookie($request);
        $lifetime = $this->consentService->getCookieLifetime();

        return response()->json([
            'success' => true,
            'message' => 'Endast nödvändiga cookies accepterade',
            'preferences' => [
                'essential' => true,
                'functional' => false,
                'analytics' => false,
                'marketing' => false,
            ],
        ])->cookie(
            'cookie_consent_id',
            $consentId,
            $lifetime,
            '/',
            null,
            config('session.secure', false),
            false,
            false,
            'lax'
        );
    }

    /**
     * Kontrollera om specifik kategori är godkänd
     *
     * GET /api/consent/check/{category}
     */
    public function check(string $category): JsonResponse
    {
        $hasConsent = $this->consentService->hasConsent($category);

        return response()->json([
            'category' => $category,
            'has_consent' => $hasConsent,
        ]);
    }
}
