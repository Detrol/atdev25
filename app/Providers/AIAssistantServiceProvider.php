<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AIAssistantServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/ai_instructions.php', 'ai-assistant'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/ai_instructions.php' => config_path('ai-assistant.php'),
        ], 'ai-assistant-config');

        $this->ensureSettingsFileExists();
    }

    protected function ensureSettingsFileExists(): void
    {
        $settingsPath = storage_path('app/ai-settings.json');

        if (! File::exists($settingsPath)) {
            if (! File::exists(storage_path('app'))) {
                File::makeDirectory(storage_path('app'), 0755, true);
            }

            File::put(
                $settingsPath,
                json_encode(config('ai-assistant'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );
        }
    }
}
