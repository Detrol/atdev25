<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a specific project.
     *
     * Data contract:
     * - project: Project (all fields including description, gallery, live_url, repo_url, screenshot_path)
     */
    public function show(Project $project)
    {
        // Only show published projects to public
        abort_if($project->status !== \App\Enums\ProjectStatus::PUBLISHED, 404);

        return view('projects.show', compact('project'));
    }
}
