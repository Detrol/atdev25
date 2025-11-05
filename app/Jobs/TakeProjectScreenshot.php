<?php

namespace App\Jobs;

use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use Spatie\Browsershot\Exceptions\CouldNotTakeBrowsershot;

class TakeProjectScreenshot implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $projectId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Fetch project from database
        $project = Project::find($this->projectId);

        if (! $project) {
            Log::error('Screenshot failed: Project not found', ['project_id' => $this->projectId]);

            return;
        }

        if (! $project->live_url) {
            return;
        }

        try {
            // Generate optimized filename with webp format
            $filename = 'screenshots/'.$project->slug.'-'.time().'.webp';
            $path = storage_path('app/public/'.$filename);

            // Ensure directory exists
            $directory = dirname($path);
            if (! file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Take screenshot with optimization
            Browsershot::url($project->live_url)
                ->waitUntilNetworkIdle()
                ->windowSize(1920, 1080)
                ->setScreenshotType('webp', 85)  // WebP with 85% quality for optimization
                ->timeout(30)
                ->save($path);

            // Update project
            $project->update([
                'screenshot_path' => $filename,
                'screenshot_taken_at' => now(),
            ]);

            Log::info('Screenshot captured successfully', [
                'project_id' => $project->id,
                'filename' => $filename,
            ]);
        } catch (CouldNotTakeBrowsershot $e) {
            Log::error('Screenshot failed', [
                'project_id' => $project->id,
                'url' => $project->live_url,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
