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
     * Register media collections with unique filename generation.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
            ->usingFileName(function (string $fileName): string {
                // Generate unique filename with timestamp for cache busting
                $baseName = pathinfo($fileName, PATHINFO_FILENAME);
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                return $baseName . '-' . time() . '.' . $extension;
            });

        $this->addMediaCollection('work_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
            ->usingFileName(function (string $fileName): string {
                // Generate unique filename with timestamp for cache busting
                $baseName = pathinfo($fileName, PATHINFO_FILENAME);
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                return $baseName . '-' . time() . '.' . $extension;
            });
    }

    /**
     * Register media conversions.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        // Avatar conversions - Perfect squares for aspect ratio compliance
        $this->addMediaConversion('tiny')
            ->fit(Fit::Crop, 128, 128)
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('avatar');

        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 200, 200)
            ->sharpen(10)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('avatar');

        $this->addMediaConversion('small')
            ->fit(Fit::Crop, 256, 256)
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

    /**
     * Prepare media URLs with fallback to original if conversions don't exist.
     * Returns array with srcset, sizes, and individual conversion URLs.
     */
    public function prepareMediaUrls(string $collection): array
    {
        if (!$this->hasMedia($collection)) {
            return [];
        }

        $media = $this->getFirstMedia($collection);

        // Always use getUrl() which handles conversion existence automatically
        // Spatie will return conversion URL if it exists, otherwise original
        $urls = [
            'tiny' => $media->getUrl('tiny'),
            'small' => $media->getUrl('small'),
            'medium' => $media->getUrl('medium'),
            'optimized' => $media->getUrl('optimized'),
        ];

        // Build srcset attribute
        $urls['srcset'] = sprintf(
            '%s 128w, %s 256w, %s 512w, %s 800w',
            $urls['tiny'],
            $urls['small'],
            $urls['medium'],
            $urls['optimized']
        );

        // Default src (fallback) - use small for best balance
        $urls['src'] = $urls['small'];

        return $urls;
    }
}
