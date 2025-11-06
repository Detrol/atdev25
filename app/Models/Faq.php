<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'tags',
        'sort_order',
        'active',
        'show_in_ai_chat',
        'show_in_price_calculator',
        'show_in_public_faq',
    ];

    protected $casts = [
        'tags' => 'array',
        'active' => 'boolean',
        'show_in_ai_chat' => 'boolean',
        'show_in_price_calculator' => 'boolean',
        'show_in_public_faq' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeForAiChat($query)
    {
        return $query->active()->where('show_in_ai_chat', true);
    }

    public function scopeForPriceCalculator($query)
    {
        return $query->active()->where('show_in_price_calculator', true);
    }

    public function scopeForPublicFaq($query)
    {
        return $query->active()->where('show_in_public_faq', true);
    }

    public function scopeWithTag($query, string $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }
}
