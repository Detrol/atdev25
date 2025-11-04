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
        Log::info('TakeProjectScreenshot: Job started', [
            'project_id' => $this->projectId,
        ]);

        // Fetch project from database
        $project = Project::find($this->projectId);

        if (! $project) {
            Log::error('TakeProjectScreenshot: Project not found', [
                'project_id' => $this->projectId,
            ]);
            return;
        }

        Log::info('TakeProjectScreenshot: Project loaded', [
            'project_id' => $project->id,
            'project_slug' => $project->slug,
            'project_title' => $project->title,
        ]);

        if (! $project->live_url) {
            Log::warning('TakeProjectScreenshot: Skipped - no live_url', [
                'project_id' => $project->id,
            ]);

            return;
        }

        Log::info('TakeProjectScreenshot: Preparing screenshot', [
            'project_id' => $project->id,
            'live_url' => $project->live_url,
        ]);

        try {
            $filename = 'screenshots/'.$project->slug.'-'.time().'.png';
            $path = storage_path('app/public/'.$filename);

            Log::info('TakeProjectScreenshot: File paths prepared', [
                'project_id' => $project->id,
                'filename' => $filename,
                'full_path' => $path,
            ]);

            // Ensure directory exists
            $directory = dirname($path);
            if (! file_exists($directory)) {
                Log::info('TakeProjectScreenshot: Creating directory', [
                    'directory' => $directory,
                ]);
                mkdir($directory, 0755, true);
            }

            Log::info('TakeProjectScreenshot: Directory ready', [
                'directory' => $directory,
                'exists' => file_exists($directory),
                'is_dir' => is_dir($directory),
                'writable' => is_writable($directory),
            ]);

            // Take screenshot (matching WebsiteDataCollector implementation)
            Log::info('TakeProjectScreenshot: Starting Browsershot', [
                'project_id' => $project->id,
                'url' => $project->live_url,
            ]);

            Browsershot::url($project->live_url)
                ->waitUntilNetworkIdle()
                ->windowSize(1920, 1080)
                ->timeout(30)
                ->save($path);

            Log::info('TakeProjectScreenshot: Screenshot saved', [
                'project_id' => $project->id,
                'path' => $path,
                'file_exists' => file_exists($path),
                'file_size' => file_exists($path) ? filesize($path) : 0,
            ]);

            // Update project
            $project->update([
                'screenshot_path' => $filename,
                'screenshot_taken_at' => now(),
            ]);

            Log::info('TakeProjectScreenshot: SUCCESS - Screenshot taken and saved', [
                'project_id' => $project->id,
                'path' => $filename,
                'full_path' => $path,
                'file_size' => file_exists($path) ? filesize($path).' bytes' : 'unknown',
            ]);
        } catch (CouldNotTakeBrowsershot $e) {
            Log::error('TakeProjectScreenshot: FAILED - Browsershot exception', [
                'project_id' => $project->id,
                'url' => $project->live_url,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        } catch (\Exception $e) {
            Log::error('TakeProjectScreenshot: FAILED - Unexpected exception', [
                'project_id' => $project->id,
                'url' => $project->live_url,
                'error_type' => get_class($e),
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
