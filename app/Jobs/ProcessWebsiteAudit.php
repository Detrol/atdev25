<?php

namespace App\Jobs;

use App\Mail\WebsiteAuditCompleted;
use App\Models\WebsiteAudit;
use App\Services\AIService;
use App\Services\WebsiteDataCollector;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessWebsiteAudit implements ShouldQueue
{
    use Queueable;

    /**
     * Timeout in seconds
     */
    public $timeout = 180; // 3 minuter

    /**
     * Max attempts
     */
    public $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public WebsiteAudit $audit
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        WebsiteDataCollector $collector,
        AIService $aiService
    ): void {
        try {
            Log::info('Processing website audit', [
                'audit_id' => $this->audit->id,
                'url' => $this->audit->url,
            ]);

            // Mark as processing
            $this->audit->markAsProcessing();

            // Step 1: Collect website data
            $collectedData = $collector->collect($this->audit->url);

            // Save collected data and screenshot
            $this->audit->update([
                'collected_data' => $collectedData,
                'screenshot_path' => $collectedData['screenshot_path'] ?? null,
            ]);

            Log::info('Website data collected', [
                'audit_id' => $this->audit->id,
                'data_size' => strlen(json_encode($collectedData)),
            ]);

            // Step 2: Analyze with AI
            $analysis = $aiService->analyzeWebsite($collectedData);

            // Save AI report and scores
            $this->audit->update([
                'ai_report' => $analysis['ai_report'],
                'seo_score' => $analysis['seo_score'],
                'technical_score' => $analysis['technical_score'],
                'overall_score' => $analysis['overall_score'],
            ]);

            Log::info('AI analysis completed', [
                'audit_id' => $this->audit->id,
                'overall_score' => $analysis['overall_score'],
            ]);

            // Mark as completed
            $this->audit->markAsCompleted();

            // Send email notification
            try {
                Mail::to($this->audit->email)->send(new WebsiteAuditCompleted($this->audit));
                Log::info('Audit email sent', ['audit_id' => $this->audit->id]);
            } catch (Exception $e) {
                // Don't fail the job if email fails
                Log::error('Failed to send audit email', [
                    'audit_id' => $this->audit->id,
                    'error' => $e->getMessage(),
                ]);
            }

            Log::info('Website audit completed successfully', [
                'audit_id' => $this->audit->id,
            ]);
        } catch (Exception $e) {
            Log::error('Website audit processing failed', [
                'audit_id' => $this->audit->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Mark as failed
            $this->audit->markAsFailed();

            // Re-throw to mark job as failed
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(?\Throwable $exception): void
    {
        Log::error('ProcessWebsiteAudit job failed permanently', [
            'audit_id' => $this->audit->id,
            'error' => $exception?->getMessage(),
            'type' => $exception ? get_class($exception) : null,
        ]);

        $this->audit->markAsFailed();
    }
}
