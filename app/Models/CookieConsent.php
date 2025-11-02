<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CookieConsent extends Model
{
    protected $fillable = [
        'session_id',
        'essential',
        'functional',
        'analytics',
        'marketing',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'essential' => 'boolean',
        'functional' => 'boolean',
        'analytics' => 'boolean',
        'marketing' => 'boolean',
    ];

    /**
     * Hämta consent för specifik session
     */
    public static function forSession(string $sessionId): ?self
    {
        return self::where('session_id', $sessionId)->first();
    }

    /**
     * Kontrollera om användaren har gett consent för specifik kategori
     */
    public function hasConsent(string $category): bool
    {
        return match($category) {
            'essential' => $this->essential,
            'functional' => $this->functional,
            'analytics' => $this->analytics,
            'marketing' => $this->marketing,
            default => false,
        };
    }

    /**
     * Hämta alla kategorier som användaren godkänt
     */
    public function getApprovedCategories(): array
    {
        $approved = [];

        if ($this->essential) $approved[] = 'essential';
        if ($this->functional) $approved[] = 'functional';
        if ($this->analytics) $approved[] = 'analytics';
        if ($this->marketing) $approved[] = 'marketing';

        return $approved;
    }

    /**
     * Scope för att hitta consents som godkänt analytics
     */
    public function scopeWithAnalytics($query)
    {
        return $query->where('analytics', true);
    }

    /**
     * Scope för att hitta consents som godkänt marketing
     */
    public function scopeWithMarketing($query)
    {
        return $query->where('marketing', true);
    }
}
