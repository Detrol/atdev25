<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Profile extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
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

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg']);

        $this->addMediaCollection('work_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg']);
    }

    /**
     * Register media conversions.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        // Avatar conversions - Multiple sizes for responsive images
        $this->addMediaConversion('tiny')
            ->width(128)
            ->height(128)
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('avatar');

        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('avatar');

        $this->addMediaConversion('small')
            ->width(256)
            ->height(256)
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('avatar');

        $this->addMediaConversion('medium')
            ->width(512)
            ->height(512)
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('avatar');

        $this->addMediaConversion('optimized')
            ->width(800)
            ->height(800)
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('avatar');

        // Work image conversions
        $this->addMediaConversion('optimized')
            ->width(1200)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('work_image');
    }

    /**
     * Get avatar URL (fallback to old column if exists).
     */
    public function getAvatarUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('avatar');
        return $media ? $media->getUrl('optimized') : null;
    }

    /**
     * Get work image URL (fallback to old hero_image column if exists).
     */
    public function getWorkImageUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('work_image');
        return $media ? $media->getUrl('optimized') : null;
    }
}
