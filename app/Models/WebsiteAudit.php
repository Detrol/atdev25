<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WebsiteAudit extends Model
{
    protected $fillable = [
        'url',
        'name',
        'email',
        'company',
        'token',
        'status',
        'collected_data',
        'ground_truth_data',
        'ai_report',
        'seo_score',
        'technical_score',
        'overall_score',
        'validation_passed',
        'validation_errors',
        'screenshot_path',
        'completed_at',
    ];

    protected $casts = [
        'collected_data' => 'array',
        'ground_truth_data' => 'array',
        'validation_passed' => 'boolean',
        'validation_errors' => 'array',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method to generate token
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($audit) {
            if (empty($audit->token)) {
                $audit->token = Str::random(32);
            }
        });
    }

    /**
     * Scope for completed audits
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending audits
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for processing audits
     */
    public function scopeProcessing(Builder $query): Builder
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope for failed audits
     */
    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 'failed');
    }

    /**
     * Mark audit as processing
     */
    public function markAsProcessing(): void
    {
        $this->update(['status' => 'processing']);
    }

    /**
     * Mark audit as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark audit as failed
     */
    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }

    /**
     * Check if audit is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if audit is processing
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if audit is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get the status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'green',
            'processing' => 'blue',
            'pending' => 'yellow',
            'failed' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'Klar',
            'processing' => 'Bearbetas',
            'pending' => 'Väntar',
            'failed' => 'Misslyckades',
            default => 'Okänd',
        };
    }
}
