<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\Project;
use Illuminate\Support\Collection;

class StructuredDataService
{
    /**
     * Generate Person schema for Andreas Thun
     */
    public function person(?Profile $profile = null): array
    {
        $profile = $profile ?? Profile::current();

        if (!$profile) {
            return [];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => 'Andreas Thun',
            'jobTitle' => $profile->title ?? 'AI-Driven Fullstack-utvecklare',
            'description' => $profile->bio ?? 'Utvecklare med 20+ års erfarenhet som kombinerar AI och automation för att leverera högkvalitativa webbapplikationer.',
            'url' => url('/'),
            'image' => $profile->avatar ? asset('storage/' . $profile->avatar) : null,
            'email' => $profile->email ?? 'andreas@atdev.me',
            'telephone' => $profile->phone,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Stockholm',
                'addressCountry' => 'SE',
            ],
            'sameAs' => array_filter([
                $profile->github_url,
                $profile->linkedin_url,
                $profile->twitter_url,
            ]),
            'knowsAbout' => [
                'Webbutveckling',
                'AI-integration',
                'Laravel',
                'React',
                'Prompt Engineering',
                'Full Stack Development',
                'AI Automation',
            ],
            'worksFor' => $this->organization(),
        ];
    }

    /**
     * Generate Organization schema for ATDev
     */
    public function organization(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'ATDev',
            'url' => url('/'),
            'logo' => asset('images/logo.png'),
            'description' => 'AI-driven webbutveckling med 20+ års erfarenhet. Specialist på Laravel, React och AI-integration.',
            'founder' => [
                '@type' => 'Person',
                'name' => 'Andreas Thun',
            ],
            'foundingDate' => '2004',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Stockholm',
                'addressCountry' => 'SE',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'email' => 'andreas@atdev.me',
                'contactType' => 'Customer Service',
                'availableLanguage' => 'Swedish',
            ],
            'areaServed' => 'SE',
            'slogan' => 'AI-Driven Utveckling för Moderna Företag',
        ];
    }

    /**
     * Generate WebSite schema
     */
    public function website(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'ATDev',
            'alternateName' => 'Andreas Thun Development',
            'url' => url('/'),
            'description' => 'Portfolio och tjänster för AI-driven webbutveckling med 20+ års erfarenhet.',
            'inLanguage' => 'sv',
            'author' => [
                '@type' => 'Person',
                'name' => 'Andreas Thun',
            ],
            'publisher' => $this->organization(),
        ];
    }

    /**
     * Generate CreativeWork schema for a project
     */
    public function project(Project $project): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => $project->title,
            'description' => $project->summary ?? $project->description,
            'url' => url('/projects/' . $project->slug),
            'inLanguage' => 'sv',
            'author' => [
                '@type' => 'Person',
                'name' => 'Andreas Thun',
            ],
            'creator' => [
                '@type' => 'Person',
                'name' => 'Andreas Thun',
            ],
            'dateCreated' => $project->created_at->toIso8601String(),
            'dateModified' => $project->updated_at->toIso8601String(),
            'keywords' => implode(', ', $project->technologies ?? []),
        ];

        // Add image if available
        if ($project->screenshot_path) {
            $schema['image'] = asset('storage/' . $project->screenshot_path);
        } elseif ($project->cover_image) {
            $schema['image'] = asset('storage/' . $project->cover_image);
        }

        // Add live URL if available
        if ($project->live_url) {
            $schema['url'] = $project->live_url;
            $schema['sameAs'] = url('/projects/' . $project->slug);
        }

        // Add repo URL if available
        if ($project->repo_url) {
            $schema['codeRepository'] = $project->repo_url;
        }

        return $schema;
    }

    /**
     * Generate BreadcrumbList schema
     */
    public function breadcrumbs(array $items): array
    {
        $listItems = [];

        foreach ($items as $position => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];
    }

    /**
     * Generate ItemList schema for project collection
     */
    public function projectList(Collection $projects): array
    {
        $listItems = [];

        foreach ($projects as $position => $project) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'item' => $this->project($project),
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Projekt',
            'description' => 'Portfolio av webbutvecklingsprojekt',
            'itemListElement' => $listItems,
        ];
    }

    /**
     * Generate multiple schemas as JSON-LD script tags
     */
    public function renderSchemas(array $schemas): string
    {
        $scripts = [];

        foreach ($schemas as $schema) {
            if (!empty($schema)) {
                $scripts[] = '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
            }
        }

        return implode("\n", $scripts);
    }
}
