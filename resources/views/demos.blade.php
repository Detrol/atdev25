@extends('layouts.app')

@section('content')
@include('partials.navigation', ['currentPage' => 'demos'])

<!-- Hero Section -->
<section id="main-content" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 gradient-mesh"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/30 to-black/50"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 py-32 text-center text-white">
        <div class="space-y-8" x-data="{ visible: false }" x-intersect="visible = true" x-init="setTimeout(() => visible = true, 100)">
            <div class="inline-flex items-center gap-3 px-6 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 pulse-glow" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-100" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                </svg>
                <span class="text-sm font-medium">Fully Interactive</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-bold leading-tight" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Interactive Demos<br>
                <span class="text-white/90">Upplev Möjligheterna</span>
            </h1>

            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-500" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Prova våra interaktiva showcase-funktioner och se vad som är möjligt för din verksamhet. Inga registreringar, bara ren innovation.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-700" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <a href="#demos" class="px-8 py-4 bg-white text-purple-600 rounded-full font-semibold hover:bg-white/90 transition-all hover:scale-105 shadow-2xl inline-flex items-center gap-2">
                    <span>Börja Utforska</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <a href="/#contact" class="px-8 py-4 bg-white/10 backdrop-blur-md text-white rounded-full font-semibold hover:bg-white/20 transition-all border border-white/20">
                    Kontakta Mig
                </a>
            </div>

            <!-- Scroll indicator -->
            <div class="mt-16 animate-bounce" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <svg class="w-6 h-6 mx-auto text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- 3D/AR Product Viewer Demo Section -->
@if(isset($demos['product_viewer']) && $demos['product_viewer']['enabled'])
<x-demos.product-viewer :demo="$demos['product_viewer']" />
@endif

<!-- Before/After Image Slider Demo Section -->
@if(isset($demos['before_after_slider']) && $demos['before_after_slider']['enabled'])
<x-demos.before-after-slider :demo="$demos['before_after_slider']" />
@endif

<!-- Smart Menu with AI Allergen Analysis -->
@if(isset($demos['smart_menu']) && $demos['smart_menu']['enabled'])
<x-demos.smart-menu :demo="$demos['smart_menu']" />
@endif

<!-- Google Reviews Showcase -->
@if(isset($demos['google_reviews']) && $demos['google_reviews']['enabled'])
<x-demos.google-reviews :demo="$demos['google_reviews']" />
@endif

<!-- Inspiration & CTA Section -->
<section class="py-32 bg-gradient-to-br from-pink-50 via-purple-50 to-indigo-50 dark:from-gray-900 dark:via-pink-900/20 dark:to-purple-900/20 border-b-4 border-pink-200/50 dark:border-pink-500/20">
    <div class="max-w-4xl mx-auto px-6">
        <div class="glass-morph p-12 rounded-3xl text-center space-y-8"
             x-data="{ visible: false }"
             x-intersect="visible = true"
             x-transition:enter="transition ease-out duration-700"
             x-transition:enter-start="opacity-0 translate-y-8"
             x-transition:enter-end="opacity-100 translate-y-0">

            <!-- Icon -->
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-purple-500 to-blue-500 rounded-2xl shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>

            <!-- Heading -->
            <div class="space-y-4">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white">
                    Bara Några Exempel
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-purple-600 to-blue-600 mx-auto rounded-full"></div>
            </div>

            <!-- Description -->
            <p class="text-xl text-gray-700 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
                Dessa interaktiva demos är bara <span class="font-semibold text-purple-600 dark:text-purple-400">några exempel</span> på vad jag kan hjälpa till med.
                Bara <span class="font-semibold text-blue-600 dark:text-blue-400">fantasin sätter gränserna</span> för just er verksamhet.
            </p>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-3 gap-6 pt-8">
                <div class="p-6 bg-white/50 dark:bg-gray-800/50 rounded-xl backdrop-blur-sm border border-purple-200/50 dark:border-purple-500/20">
                    <div class="text-3xl mb-3">💡</div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Dina Idéer</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Har du en vision? Låt oss förverkliga den tillsammans</p>
                </div>
                <div class="p-6 bg-white/50 dark:bg-gray-800/50 rounded-xl backdrop-blur-sm border border-blue-200/50 dark:border-blue-500/20">
                    <div class="text-3xl mb-3">🎨</div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Kreativa Lösningar</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Unika funktioner anpassade efter era behov</p>
                </div>
                <div class="p-6 bg-white/50 dark:bg-gray-800/50 rounded-xl backdrop-blur-sm border border-pink-200/50 dark:border-pink-500/20">
                    <div class="text-3xl mb-3">🚀</div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Innovativa Förbättringar</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Ta er verksamhet till nästa nivå</p>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-8">
                <a href="/#contact" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-xl font-semibold text-lg transition-all hover:scale-105 shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                    <span>Dela Dina Idéer</span>
                </a>
                <a href="/#services" class="inline-flex items-center gap-3 px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl font-semibold text-lg transition-all hover:scale-105 shadow-lg border-2 border-purple-200 dark:border-purple-500/30 hover:border-purple-400 dark:hover:border-purple-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span>Se Alla Tjänster</span>
                </a>
            </div>

            <!-- Bottom note -->
            <p class="text-sm text-gray-500 dark:text-gray-400 pt-4">
                Tveka inte att höra av dig – inga idéer är för stora eller för små! 💬
            </p>
        </div>
    </div>
</section>

@push('scripts')
@vite('resources/js/demos/product-viewer.js')
@vite('resources/js/demos/before-after-slider.js')
@vite('resources/js/demos/smart-menu.js')
@vite('resources/js/demos/google-reviews.js')
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
@endpush

@endsection
