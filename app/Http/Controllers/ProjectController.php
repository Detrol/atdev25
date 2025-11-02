<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\StructuredDataService;

class ProjectController extends Controller
{
    /**
     * Display a specific project.
     *
     * Data contract:
     * - project: Project (all fields including description, gallery, live_url, repo_url, screenshot_path)
     * - structuredData: string (JSON-LD schema markup for SEO)
     */
    public function show(Project $project, StructuredDataService $seo)
    {
        // Only show published projects to public
        abort_if($project->status !== \App\Enums\ProjectStatus::PUBLISHED, 404);

        // Generate structured data for SEO
        $schemas = [
            $seo->project($project),
            $seo->breadcrumbs([
                ['name' => 'Hem', 'url' => url('/')],
                ['name' => 'Projekt', 'url' => url('/')],
                ['name' => $project->title, 'url' => url('/projects/' . $project->slug)],
            ]),
        ];

        $structuredData = $seo->renderSchemas($schemas);

        // SEO meta tags
        $seoTitle = $project->title . ' - Portfolio | ATDev';
        $seoDescription = $project->summary ?? $project->description ?? 'Ett webbutvecklingsprojekt av Andreas Thun. Specialist på Laravel, React och AI-integration.';
        $seoKeywords = implode(', ', array_merge($project->technologies ?? [], ['webbutveckling', 'portfolio', 'ATDev', 'Andreas Thun']));
        $seoImage = $project->screenshot_path
            ? asset('storage/' . $project->screenshot_path)
            : ($project->cover_image ? asset('storage/' . $project->cover_image) : asset('images/og-default.jpg'));
        $seoType = 'article';

        return view('projects.show', compact(
            'project',
            'structuredData',
            'seoTitle',
            'seoDescription',
            'seoKeywords',
            'seoImage',
            'seoType'
        ));
    }
}
