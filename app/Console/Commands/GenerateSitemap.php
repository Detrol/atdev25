<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate
                           {--force : Force regeneration even if cache exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate or regenerate the XML sitemap and clear cache';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Generating sitemap...');

        if ($this->option('force')) {
            $this->info('Force flag detected, clearing existing cache...');
            Cache::forget('sitemap');
        }

        try {
            // Clear sitemap cache
            Cache::forget('sitemap');
            $this->info('✓ Sitemap cache cleared');

            // Trigger sitemap generation by making a request to the sitemap endpoint
            $response = Http::get(route('sitemap'));

            if ($response->successful()) {
                $this->info('✓ Sitemap generated successfully');
                $this->info('✓ Cached for 24 hours');
                $this->newLine();
                $this->comment('Sitemap available at: ' . route('sitemap'));

                return Command::SUCCESS;
            }

            $this->error('Failed to generate sitemap. HTTP status: ' . $response->status());
            return Command::FAILURE;

        } catch (\Exception $e) {
            $this->error('Error generating sitemap: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
