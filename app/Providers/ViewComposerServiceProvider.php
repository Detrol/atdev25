<?php

namespace App\Providers;

use App\Models\Faq;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('components.home.faq', function ($view) {
            $faqs = Faq::forPublicFaq()
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->get();

            // Icon SVG paths for each FAQ
            $icons = [
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>', // settings
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>', // code
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>', // calculator
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>', // shield
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>', // paint brush
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>', // edit
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>', // server
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>', // wallet
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>', // clipboard
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>', // support/headphones
            ];

            $colorClasses = [
                'purple' => [
                    'border' => 'border-purple-500 dark:border-purple-400',
                    'hover' => 'hover:border-purple-300 dark:hover:border-purple-600',
                    'bg' => 'bg-gradient-to-br from-purple-500 to-purple-600',
                    'shadow' => 'shadow-purple-500/20',
                ],
                'blue' => [
                    'border' => 'border-blue-500 dark:border-blue-400',
                    'hover' => 'hover:border-blue-300 dark:hover:border-blue-600',
                    'bg' => 'bg-gradient-to-br from-blue-500 to-blue-600',
                    'shadow' => 'shadow-blue-500/20',
                ],
                'green' => [
                    'border' => 'border-green-500 dark:border-green-400',
                    'hover' => 'hover:border-green-300 dark:hover:border-green-600',
                    'bg' => 'bg-gradient-to-br from-green-500 to-green-600',
                    'shadow' => 'shadow-green-500/20',
                ],
                'pink' => [
                    'border' => 'border-pink-500 dark:border-pink-400',
                    'hover' => 'hover:border-pink-300 dark:hover:border-pink-600',
                    'bg' => 'bg-gradient-to-br from-pink-500 to-pink-600',
                    'shadow' => 'shadow-pink-500/20',
                ],
                'orange' => [
                    'border' => 'border-orange-500 dark:border-orange-400',
                    'hover' => 'hover:border-orange-300 dark:hover:border-orange-600',
                    'bg' => 'bg-gradient-to-br from-orange-500 to-orange-600',
                    'shadow' => 'shadow-orange-500/20',
                ],
                'indigo' => [
                    'border' => 'border-indigo-500 dark:border-indigo-400',
                    'hover' => 'hover:border-indigo-300 dark:hover:border-indigo-600',
                    'bg' => 'bg-gradient-to-br from-indigo-500 to-indigo-600',
                    'shadow' => 'shadow-indigo-500/20',
                ],
                'cyan' => [
                    'border' => 'border-cyan-500 dark:border-cyan-400',
                    'hover' => 'hover:border-cyan-300 dark:hover:border-cyan-600',
                    'bg' => 'bg-gradient-to-br from-cyan-500 to-cyan-600',
                    'shadow' => 'shadow-cyan-500/20',
                ],
                'teal' => [
                    'border' => 'border-teal-500 dark:border-teal-400',
                    'hover' => 'hover:border-teal-300 dark:hover:border-teal-600',
                    'bg' => 'bg-gradient-to-br from-teal-500 to-teal-600',
                    'shadow' => 'shadow-teal-500/20',
                ],
                'rose' => [
                    'border' => 'border-rose-500 dark:border-rose-400',
                    'hover' => 'hover:border-rose-300 dark:hover:border-rose-600',
                    'bg' => 'bg-gradient-to-br from-rose-500 to-rose-600',
                    'shadow' => 'shadow-rose-500/20',
                ],
                'violet' => [
                    'border' => 'border-violet-500 dark:border-violet-400',
                    'hover' => 'hover:border-violet-300 dark:hover:border-violet-600',
                    'bg' => 'bg-gradient-to-br from-violet-500 to-violet-600',
                    'shadow' => 'shadow-violet-500/20',
                ],
            ];

            // Prepare FAQ data with all styling info
            $colorKeys = array_keys($colorClasses);
            $faqsWithStyling = $faqs->map(function ($faq, $index) use ($colorKeys, $colorClasses, $icons) {
                $colorKey = $colorKeys[$index % count($colorKeys)];

                return [
                    'faq' => $faq,
                    'number' => $index + 1,
                    'color' => $colorClasses[$colorKey],
                    'icon' => $icons[$index % count($icons)],
                    'delay' => ($index % 6) * 50 + 100,
                ];
            });

            $view->with('faqsWithStyling', $faqsWithStyling);
        });
    }
}
