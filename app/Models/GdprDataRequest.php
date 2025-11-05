<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GdprDataRequest extends Model
{
    protected $fillable = [
        'email',
        'type',
        'token',
        'status',
        'expires_at',
        'processed_at',
        'data',
        'ip_address',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /**
     * Generera ny GDPR-request med token
     */
    public static function createRequest(string $email, string $type, ?string $ipAddress = null): self
    {
        return self::create([
            'email' => $email,
            'type' => $type,
            'token' => Str::random(64),
            'status' => 'pending',
            'expires_at' => now()->addHours(24), // Token giltig i 24h
            'ip_address' => $ipAddress,
        ]);
    }

    /**
     * Hitta request med token
     */
    public static function findByToken(string $token): ?self
    {
        return self::where('token', $token)
            ->where('expires_at', '>', now())
            ->where('status', 'pending')
            ->first();
    }

    /**
     * Markera som processad
     */
    public function markAsProcessed(?string $data = null): void
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now(),
            'data' => $data,
        ]);
    }

    /**
     * Markera som misslyckad
     */
    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }

    /**
     * Scope för pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending')
            ->where('expires_at', '>', now());
    }

    /**
     * Scope för export requests
     */
    public function scopeExports($query)
    {
        return $query->where('type', 'export');
    }

    /**
     * Scope för delete requests
     */
    public function scopeDeletes($query)
    {
        return $query->where('type', 'delete');
    }

    /**
     * Check om token är expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check om request är processad
     */
    public function isProcessed(): bool
    {
        return $this->status === 'completed';
    }
}
