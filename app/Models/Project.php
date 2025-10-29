<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'summary',
        'description',
        'cover_image',
        'gallery',
        'live_url',
        'repo_url',
        'technologies',
        'screenshot_path',
        'screenshot_taken_at',
        'status',
        'featured',
        'sort_order',
    ];

    protected $casts = [
        'gallery' => 'array',
        'technologies' => 'array',
        'screenshot_taken_at' => 'datetime',
        'status' => ProjectStatus::class,
        'featured' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });

        static::updating(function ($project) {
            if ($project->isDirty('title') && empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }

    /**
     * Scope a query to only include published projects.
     */
    public function scopePublished($query)
    {
        return $query->where('status', ProjectStatus::PUBLISHED);
    }

    /**
     * Scope a query to only include featured projects.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
