<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Jobs\TakeProjectScreenshot;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects.
     *
     * Data contract:
     * - projects: Collection<Project> (paginated)
     */
    public function index()
    {
        $projects = Project::orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     *
     * Data contract:
     * - statuses: array (ProjectStatus enum values)
     */
    public function create()
    {
        $statuses = ProjectStatus::cases();
        return view('admin.projects.create', compact('statuses'));
    }

    /**
     * Store a newly created project.
     */
    public function store(ProjectRequest $request)
    {
        $project = Project::create($request->validated());

        // Dispatch screenshot job if live_url provided
        if ($project->live_url) {
            TakeProjectScreenshot::dispatch($project);
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projekt skapat!');
    }

    /**
     * Show the form for editing a project.
     *
     * Data contract:
     * - project: Project
     * - statuses: array (ProjectStatus enum values)
     */
    public function edit(Project $project)
    {
        $statuses = ProjectStatus::cases();
        return view('admin.projects.edit', compact('project', 'statuses'));
    }

    /**
     * Update a project.
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        // Dispatch screenshot job if live_url changed
        if ($project->wasChanged('live_url') && $project->live_url) {
            TakeProjectScreenshot::dispatch($project);
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projekt uppdaterat!');
    }

    /**
     * Delete a project.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projekt raderat!');
    }

    /**
     * Manually trigger screenshot for a project.
     */
    public function screenshot(Project $project)
    {
        if (!$project->live_url) {
            return redirect()->back()
                ->with('error', 'Projektet saknar live URL!');
        }

        TakeProjectScreenshot::dispatch($project);

        return redirect()->back()
            ->with('success', 'Screenshot-jobb startat!');
    }
}
