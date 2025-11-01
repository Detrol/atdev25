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
    public function __construct(public Project $project)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (! $this->project->live_url) {
            Log::warning('Project screenshot skipped: no live_url', ['project_id' => $this->project->id]);

            return;
        }

        try {
            $filename = 'screenshots/'.$this->project->slug.'-'.time().'.png';
            $path = storage_path('app/public/'.$filename);

            // Ensure directory exists
            $directory = dirname($path);
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Take screenshot
            Browsershot::url($this->project->live_url)
                ->windowSize(1920, 1080)
                ->setScreenshotType('png')
                ->timeout(60)
                ->waitUntilNetworkIdle()
                ->save($path);

            // Update project
            $this->project->update([
                'screenshot_path' => $filename,
                'screenshot_taken_at' => now(),
            ]);

            Log::info('Project screenshot taken', [
                'project_id' => $this->project->id,
                'path' => $filename,
            ]);
        } catch (CouldNotTakeBrowsershot $e) {
            Log::error('Failed to take screenshot', [
                'project_id' => $this->project->id,
                'url' => $this->project->live_url,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
