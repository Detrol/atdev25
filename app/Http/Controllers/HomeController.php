<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Services\StructuredDataService;

class HomeController extends Controller
{
    /**
     * Display the home page.
     *
     * Data contract:
     * - profile: Profile (name, title, bio, avatar, hero_image, email, phone, github, linkedin, twitter)
     * - services: Collection<Service> (id, slug, title, description, icon, features, sort_order)
     * - projects: Collection<Project> (id, slug, title, summary, cover_image, technologies, featured)
     * - structuredData: string (JSON-LD schema markup for SEO)
     */
    public function index(StructuredDataService $seo)
    {
        $profile = Profile::current();

        $services = Service::active()
            ->ordered()
            ->get();

        $projects = Project::published()
            ->featured()
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        // Generate structured data for SEO
        $schemas = [
            $seo->person($profile),
            $seo->organization(),
            $seo->website(),
        ];

        $structuredData = $seo->renderSchemas($schemas);

        // SEO meta tags
        $seoTitle = 'ATDev - AI-Driven Utveckling | 20+ Års Erfarenhet | Andreas Thun';
        $seoDescription = 'Utvecklare med 20+ års erfarenhet kombinerar AI och automation för att leverera högkvalitativa webbapplikationer. Specialist på Laravel, React, AI-integration och prompt engineering. Baserad i Stockholm, Sverige.';
        $seoKeywords = 'webbutveckling, AI-utveckling, Laravel-utvecklare, React-utvecklare, prompt engineering, AI-expert, Andreas Thun, ATDev, Stockholm, Sverige, fullstack-utvecklare, AI-automation';
        $seoImage = $profile?->hero_image ? asset('storage/' . $profile->hero_image) : asset('images/og-default.jpg');

        // Preload critical hero image for performance
        $preloadImage = $profile?->hasMedia('avatar')
            ? $profile->getFirstMediaUrl('avatar', 'optimized')
            : null;

        return view('home', compact(
            'profile',
            'services',
            'projects',
            'structuredData',
            'seoTitle',
            'seoDescription',
            'seoKeywords',
            'seoImage',
            'preloadImage'
        ));
    }
}
