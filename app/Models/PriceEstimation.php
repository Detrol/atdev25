<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceEstimation extends Model
{
    protected $fillable = [
        'description',
        'service_category',
        'project_type',
        'complexity',
        'project_type_label',
        'complexity_label',
        'key_features',
        'hours_traditional_min',
        'hours_traditional_max',
        'hours_ai_min',
        'hours_ai_max',
        'hours_traditional',
        'hours_ai',
        'price_traditional_min',
        'price_traditional_max',
        'price_ai_min',
        'price_ai_max',
        'price_traditional',
        'price_ai',
        'price_traditional_vat_min',
        'price_traditional_vat_max',
        'price_ai_vat_min',
        'price_ai_vat_max',
        'price_traditional_vat',
        'price_ai_vat',
        'savings_min',
        'savings_max',
        'savings',
        'savings_vat_min',
        'savings_vat_max',
        'savings_vat',
        'savings_percent',
        'delivery_weeks_traditional',
        'delivery_weeks_ai',
        'contact_message_id',
        'ip_address',
        'session_id',
    ];

    protected $casts = [
        'key_features' => 'array',
        'complexity' => 'integer',
        'hours_traditional_min' => 'integer',
        'hours_traditional_max' => 'integer',
        'hours_ai_min' => 'integer',
        'hours_ai_max' => 'integer',
        'price_traditional_min' => 'integer',
        'price_traditional_max' => 'integer',
        'price_ai_min' => 'integer',
        'price_ai_max' => 'integer',
        'price_traditional_vat_min' => 'integer',
        'price_traditional_vat_max' => 'integer',
        'price_ai_vat_min' => 'integer',
        'price_ai_vat_max' => 'integer',
        'savings_min' => 'integer',
        'savings_max' => 'integer',
        'savings_vat_min' => 'integer',
        'savings_vat_max' => 'integer',
        'savings_percent' => 'integer',
    ];

    /**
     * Get the contact message associated with this price estimation
     */
    public function contactMessage(): BelongsTo
    {
        return $this->belongsTo(ContactMessage::class);
    }
}
