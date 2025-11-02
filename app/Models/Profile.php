<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'avatar',
        'hero_image',
        'github',
        'linkedin',
        'twitter',
    ];

    /**
     * Get the singleton profile instance.
     */
    public static function current(): ?self
    {
        return self::first();
    }
}
