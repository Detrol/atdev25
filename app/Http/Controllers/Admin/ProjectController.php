<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Jobs\TakeProjectScreenshot;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

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
        Log::info('ProjectController: Creating new project', [
            'title' => $request->input('title'),
            'live_url' => $request->input('live_url'),
        ]);

        $project = Project::create($request->validated());

        Log::info('ProjectController: Project created', [
            'project_id' => $project->id,
            'project_slug' => $project->slug,
        ]);

        // Dispatch screenshot job if live_url provided
        if ($project->live_url) {
            Log::info('ProjectController: Auto-dispatching screenshot job for new project', [
                'project_id' => $project->id,
                'live_url' => $project->live_url,
            ]);

            TakeProjectScreenshot::dispatch($project->id);
        } else {
            Log::info('ProjectController: No screenshot job - no live_url provided', [
                'project_id' => $project->id,
            ]);
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
     * - technologiesString: string (comma-separated)
     */
    public function edit(Project $project)
    {
        $statuses = ProjectStatus::cases();

        // Convert technologies array to comma-separated string for the form
        $oldTechnologies = old('technologies', $project->technologies ?? []);
        $technologiesString = is_array($oldTechnologies)
            ? implode(', ', $oldTechnologies)
            : $oldTechnologies;

        return view('admin.projects.edit', compact('project', 'statuses', 'technologiesString'));
    }

    /**
     * Update a project.
     */
    public function update(ProjectRequest $request, Project $project)
    {
        Log::info('ProjectController: Updating project', [
            'project_id' => $project->id,
            'project_slug' => $project->slug,
            'old_live_url' => $project->live_url,
            'new_live_url' => $request->input('live_url'),
        ]);

        $project->update($request->validated());

        // Dispatch screenshot job if live_url changed
        if ($project->wasChanged('live_url') && $project->live_url) {
            Log::info('ProjectController: Auto-dispatching screenshot job - live_url changed', [
                'project_id' => $project->id,
                'old_url' => $project->getOriginal('live_url'),
                'new_url' => $project->live_url,
            ]);

            TakeProjectScreenshot::dispatch($project->id);
        } else {
            Log::info('ProjectController: No screenshot job - live_url unchanged or empty', [
                'project_id' => $project->id,
                'live_url_changed' => $project->wasChanged('live_url'),
                'has_live_url' => !empty($project->live_url),
            ]);
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
        Log::info('ProjectController: Screenshot requested', [
            'project_id' => $project->id,
            'project_slug' => $project->slug,
            'project_title' => $project->title,
            'live_url' => $project->live_url,
            'user_id' => auth()->id(),
        ]);

        if (! $project->live_url) {
            Log::warning('ProjectController: Screenshot rejected - no live URL', [
                'project_id' => $project->id,
            ]);

            return redirect()->back()
                ->with('error', 'Projektet saknar live URL!');
        }

        Log::info('ProjectController: Dispatching TakeProjectScreenshot job', [
            'project_id' => $project->id,
            'queue_connection' => config('queue.default'),
        ]);

        TakeProjectScreenshot::dispatch($project->id);

        Log::info('ProjectController: Screenshot job dispatched successfully', [
            'project_id' => $project->id,
        ]);

        return redirect()->back()
            ->with('success', 'Screenshot-jobb startat!');
    }
}
