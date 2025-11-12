<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
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
     * Spatie automatically uses UUID-based filenames which provides uniqueness.
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
        // Avatar conversions - Perfect squares for aspect ratio compliance
        // Matched to actual display sizes: 128px CSS (256px @2x DPR)
        $this->addMediaConversion('tiny')
            ->fit(Fit::Crop, 128, 128)
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('avatar');

        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 256, 256) // For 128px @2x DPR
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('avatar');

        $this->addMediaConversion('small')
            ->fit(Fit::Crop, 420, 420) // For ~210px @2x DPR (actual display size)
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('avatar');

        $this->addMediaConversion('medium')
            ->fit(Fit::Crop, 512, 512)
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('avatar');

        $this->addMediaConversion('optimized')
            ->fit(Fit::Crop, 800, 800)
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('avatar');

        // Work image conversions - responsive sizes for about section
        // Mobile: 100vw (~375px @2x = 750px), Desktop: 640px @2x = 1280px
        $this->addMediaConversion('small')
            ->width(640) // For mobile @1x
            ->quality(85)
            ->format('webp')
            ->performOnCollections('work_image');

        $this->addMediaConversion('medium')
            ->width(1280) // For desktop @2x DPR (640px CSS)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('work_image');

        $this->addMediaConversion('optimized')
            ->width(1600) // For large screens
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

    /**
     * Prepare media URLs with fallback to original if conversions don't exist.
     * Returns array with srcset, sizes, and individual conversion URLs.
     * Adds cache-busting query parameter based on media update time.
     */
    public function prepareMediaUrls(string $collection): array
    {
        if (! $this->hasMedia($collection)) {
            return [];
        }

        $media = $this->getFirstMedia($collection);

        // Cache busting: use media updated_at timestamp
        $version = $media->updated_at->timestamp;

        // Always use getUrl() which handles conversion existence automatically
        // Spatie will return conversion URL if it exists, otherwise original
        $urls = [
            'tiny' => $media->getUrl('tiny').'?v='.$version,
            'thumb' => $media->getUrl('thumb').'?v='.$version,
            'small' => $media->getUrl('small').'?v='.$version,
            'medium' => $media->getUrl('medium').'?v='.$version,
            'optimized' => $media->getUrl('optimized').'?v='.$version,
        ];

        // Build srcset attribute based on collection
        if ($collection === 'avatar') {
            // Avatar srcset: 128w, 256w, 420w, 512w, 800w
            $urls['srcset'] = sprintf(
                '%s 128w, %s 256w, %s 420w, %s 512w, %s 800w',
                $urls['tiny'],
                $urls['thumb'],
                $urls['small'],
                $urls['medium'],
                $urls['optimized']
            );
            $urls['src'] = $urls['tiny']; // Fallback to smallest
        } else {
            // Work image srcset: 640w, 1280w, 1600w
            $urls['srcset'] = sprintf(
                '%s 640w, %s 1280w, %s 1600w',
                $urls['small'],
                $urls['medium'],
                $urls['optimized']
            );
            $urls['src'] = $urls['small']; // Fallback to mobile size
        }

        return $urls;
    }
}
