<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'name',
        'title',
        'bio',
        'avatar',
        'hero_image',
        'email',
        'phone',
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
