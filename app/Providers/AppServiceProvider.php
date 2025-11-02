<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define rate limiters
        RateLimiter::for('contact', function (Request $request) {
            return [
                Limit::perMinute(5)->by($request->ip()),
                Limit::perDay(20)->by($request->ip()),
            ];
        });

        // Authorize log-viewer for authenticated users
        Gate::define('viewLogViewer', function ($user = null) {
            return $user !== null;
        });

        // Share profile globally with all views
        view()->composer('*', function ($view) {
            $view->with('profile', \App\Models\Profile::current());
        });
    }
}
