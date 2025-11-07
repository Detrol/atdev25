<?php

namespace App\Services;

use App\Models\CookieConsent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CookieConsentService
{
    private const COOKIE_NAME = 'cookie_consent_id';

    private const COOKIE_LIFETIME = 60 * 24 * 365; // 1 år i minuter

    /**
     * Hämta eller skapa consent ID
     */
    private function getConsentId(Request $request): string
    {
        // Försök hämta från cookie först
        $consentId = $request->cookie(self::COOKIE_NAME);

        if (! $consentId) {
            // Skapa nytt unikt ID
            $consentId = Str::random(64);
        }

        return $consentId;
    }

    /**
     * Spara cookie consent preferences
     */
    public function saveConsent(Request $request, array $preferences): CookieConsent
    {
        $consentId = $this->getConsentId($request);

        // Hitta eller skapa consent-record
        $consent = CookieConsent::where('session_id', $consentId)->first();

        if ($consent) {
            // Uppdatera befintlig consent
            $consent->update([
                'essential' => true, // Alltid sant
                'functional' => $preferences['functional'] ?? false,
                'analytics' => $preferences['analytics'] ?? false,
                'marketing' => $preferences['marketing'] ?? false,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } else {
            // Skapa ny consent
            $consent = CookieConsent::create([
                'session_id' => $consentId,
                'essential' => true, // Alltid sant
                'functional' => $preferences['functional'] ?? false,
                'analytics' => $preferences['analytics'] ?? false,
                'marketing' => $preferences['marketing'] ?? false,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $consent;
    }

    /**
     * Hämta consent ID för att sätta som cookie
     */
    public function getConsentIdForCookie(Request $request): string
    {
        return $this->getConsentId($request);
    }

    /**
     * Hämta cookie lifetime (i minuter)
     */
    public function getCookieLifetime(): int
    {
        return self::COOKIE_LIFETIME;
    }

    /**
     * Hämta consent för nuvarande session
     */
    public function getConsent(): ?CookieConsent
    {
        $consentId = request()->cookie(self::COOKIE_NAME);

        if (! $consentId) {
            return null;
        }

        return CookieConsent::where('session_id', $consentId)->first();
    }

    /**
     * Kontrollera om användaren har gett consent för specifik kategori
     */
    public function hasConsent(string $category): bool
    {
        $consent = $this->getConsent();

        if (! $consent) {
            // Ingen consent given = essential + analytics tillåtet (Berättigat Intresse, GDPR Art. 6.1.f)
            return $category === 'essential' || $category === 'analytics';
        }

        return $consent->hasConsent($category);
    }

    /**
     * Acceptera alla kategorier
     */
    public function acceptAll(Request $request): CookieConsent
    {
        return $this->saveConsent($request, [
            'functional' => true,
            'analytics' => true,
            'marketing' => true,
        ]);
    }

    /**
     * Avvisa alla utom essential
     */
    public function rejectAll(Request $request): CookieConsent
    {
        return $this->saveConsent($request, [
            'functional' => false,
            'analytics' => false,
            'marketing' => false,
        ]);
    }

    /**
     * Check om användaren har gjort ett val
     */
    public function hasChoiceMade(): bool
    {
        return $this->getConsent() !== null;
    }

    /**
     * Hämta consent preferences som array
     */
    public function getPreferences(): array
    {
        $consent = $this->getConsent();

        if (! $consent) {
            // Berättigat Intresse (GDPR Art. 6.1.f): Analytics är aktiverat som standard
            return [
                'essential' => true,
                'functional' => false,
                'analytics' => true,
                'marketing' => false,
            ];
        }

        return [
            'essential' => $consent->essential,
            'functional' => $consent->functional,
            'analytics' => $consent->analytics,
            'marketing' => $consent->marketing,
        ];
    }

    /**
     * Radera consent för nuvarande session
     */
    public function deleteConsent(): void
    {
        $consentId = request()->cookie(self::COOKIE_NAME);

        if ($consentId) {
            CookieConsent::where('session_id', $consentId)->delete();
        }

        // Cookien tas bort via response i controller om denna metod används
    }
}
