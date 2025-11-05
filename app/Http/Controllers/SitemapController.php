<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Project;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate and return XML sitemap
     * Cached for 24 hours for performance
     */
    public function index(): Response
    {
        $sitemap = Cache::remember('sitemap', 60 * 60 * 24, function () {
            return $this->generateSitemap();
        });

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=86400', // 24 hours
        ]);
    }

    /**
     * Generate sitemap XML content
     */
    private function generateSitemap(): string
    {
        $urls = collect();

        // Homepage
        $urls->push([
            'loc' => url('/'),
            'lastmod' => $this->getHomeLastModified(),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ]);

        // Static pages
        $urls->push([
            'loc' => route('tech-stack'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.6',
        ]);

        $urls->push([
            'loc' => route('demos'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.6',
        ]);

        $urls->push([
            'loc' => route('gdpr.privacy'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.3',
        ]);

        $urls->push([
            'loc' => route('gdpr.cookies'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.3',
        ]);

        $urls->push([
            'loc' => route('audits.create'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.5',
        ]);

        // Individual published projects (use slug for URL)
        Project::published()
            ->orderBy('updated_at', 'desc')
            ->get()
            ->each(function ($project) use ($urls) {
                $urls->push([
                    'loc' => url('/projects/'.$project->slug),
                    'lastmod' => $project->updated_at->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => $project->featured ? '0.8' : '0.7',
                ]);
            });

        return view('sitemap', ['urls' => $urls])->render();
    }

    /**
     * Get last modified date for homepage
     * Based on profile update or latest project
     */
    private function getHomeLastModified(): string
    {
        $profileUpdated = Profile::current()?->updated_at;
        $latestProjectUpdated = Project::published()->latest('updated_at')->first()?->updated_at;

        $latest = collect([$profileUpdated, $latestProjectUpdated])
            ->filter()
            ->sortDesc()
            ->first();

        return $latest?->toAtomString() ?? now()->toAtomString();
    }
}
