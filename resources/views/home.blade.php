@extends('layouts.app')

@section('content')
@include('partials.navigation', ['currentPage' => 'home'])

<section id="main-content" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 gradient-mesh"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/30 to-black/50"></div>
    <div class="relative z-10 max-w-5xl mx-auto px-6 py-32 text-center text-white">
        <div class="space-y-8" x-data="{ visible: false }" x-intersect="visible = true" x-init="setTimeout(() => visible = true, 100)">
            <div class="inline-flex items-center gap-3 px-6 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 pulse-glow" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-100" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span><span class="text-sm font-medium">Tillgänglig för nya projekt</span>
            </div>

            @if($profile?->avatar)
            <div x-show="visible" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <img src="{{ Storage::url($profile->avatar) }}"
                     alt="Andreas Thun"
                     class="w-32 h-32 rounded-full mx-auto mb-6 border-4 border-white/20 shadow-2xl">
            </div>
            @endif

            <h1 class="text-5xl md:text-7xl font-bold leading-tight" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Hej! Mitt namn är Andreas
            </h1>

            <p class="text-2xl md:text-3xl text-white/90 font-medium" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-400" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Passion för problemlösning och innovation
            </p>

            <p class="text-lg md:text-xl text-white/80 max-w-3xl mx-auto leading-relaxed" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-500" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Jag är en erfaren webbutvecklare med över 20 års erfarenhet.
                Jag skapar moderna, säkra och effektiva webblösningar för privatpersoner, företag och organisationer.
            </p>

            <div class="inline-block px-6 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-600" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <span class="text-lg font-semibold">20+ års erfarenhet</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-700" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <a href="#projects" class="px-8 py-4 bg-white text-purple-600 rounded-full font-semibold hover:bg-white/90 transition-all hover:scale-105 shadow-2xl">Se Mina Projekt</a>
                <a href="#contact" class="px-8 py-4 bg-white/10 backdrop-blur-md text-white rounded-full font-semibold hover:bg-white/20 transition-all border border-white/20">Kontakta Mig</a>
            </div>
        </div>
    </div>
</section>

<!-- Om Mig Section -->
<section class="py-20 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Text -->
            <div class="space-y-6" x-data="{ visible: false }" x-intersect="visible = true">
                <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-400 via-blue-400 to-pink-400 bg-clip-text text-transparent" x-show="visible" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 -translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    Min Resa
                </h2>

                <div class="space-y-4 text-gray-300 text-lg leading-relaxed" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 -translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <p>
                        Sedan jag började koda har teknologin varit min passion.
                        Genom åren har jag skapat många applikationer och sett webbutvecklingen utvecklas från enkla HTML-sidor till avancerade, AI-drivna system.
                    </p>

                    <p>
                        Jag började med HTML och ASP, gick sedan vidare till PHP, och från 2017 har Laravel varit mitt huvudramverk.
                        Idag arbetar jag med moderna verktyg som Alpine och Vue, och kombinerar webbutveckling med AI-teknologi för att skapa smarta lösningar.
                    </p>

                    <div class="relative pl-6 border-l-4 border-purple-500/50 py-2">
                        <p class="text-xl font-semibold text-purple-300 italic">
                            "Om jag hittar ett problem kommer jag alltid att hitta ett sätt att lösa det."
                        </p>
                    </div>
                </div>
            </div>

            <!-- Foto -->
            <div x-data="{ visible: false }" x-intersect="visible = true">
                @if($profile?->hero_image)
                <div x-show="visible" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <img src="{{ Storage::url($profile->hero_image) }}"
                         alt="Andreas arbetar"
                         class="rounded-2xl shadow-2xl border border-white/10 hover:scale-105 transition-transform duration-500">
                </div>
                @else
                <div class="aspect-square bg-gradient-to-br from-purple-500/20 via-blue-500/20 to-pink-500/20 rounded-2xl border border-white/10 flex items-center justify-center backdrop-blur-sm" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="text-center p-8">
                        <svg class="w-24 h-24 mx-auto mb-4 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-400 text-sm">Ladda upp en arbetsbild via admin-panelen</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="relative py-24 bg-gradient-to-b from-white via-gray-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 opacity-30">
        <div class="absolute w-96 h-96 bg-purple-300 dark:bg-purple-600 rounded-full blur-3xl -top-48 -left-48 animate-pulse"></div>
        <div class="absolute w-96 h-96 bg-blue-300 dark:bg-blue-600 rounded-full blur-3xl -bottom-48 -right-48 animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-6">
        <!-- Section Header -->
        <div class="text-center mb-16" x-data="{ visible: false }" x-intersect="visible = true">
            <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 bg-clip-text text-transparent mb-4" x-show="visible" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Expertis & Innovation
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Över två decennier av webbutveckling kombinerat med modern teknologi
            </p>
        </div>

        <!-- Timeline Card (Featured) -->
        <div class="mb-8" x-data="{
            visible: false,
            progress: 0,
            currentIcon: null
        }" x-intersect="visible = true" x-init="
            $watch('visible', value => {
                if (value) {
                    setTimeout(() => {
                        // Trigger smooth CSS animation
                        progress = 100;

                        // Show icons when progress bar reaches each year position
                        // HTML at 12.5% = 1.25s
                        setTimeout(() => {
                            currentIcon = 'html';
                            setTimeout(() => { currentIcon = null }, 1800);
                        }, 1250);

                        // PHP at 37.5% = 3.75s
                        setTimeout(() => {
                            currentIcon = 'php';
                            setTimeout(() => { currentIcon = null }, 1800);
                        }, 3750);

                        // Laravel at 62.5% = 6.25s
                        setTimeout(() => {
                            currentIcon = 'laravel';
                            setTimeout(() => { currentIcon = null }, 1800);
                        }, 6250);

                        // AI at 87.5% = 8.75s
                        setTimeout(() => {
                            currentIcon = 'ai';
                            setTimeout(() => { currentIcon = null }, 1800);
                        }, 8750);
                    }, 800);
                }
            });
        ">
            <div class="relative group overflow-hidden rounded-3xl p-8 md:p-12 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 shadow-2xl hover:shadow-purple-500/50 transition-all duration-500" x-show="visible" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <!-- Glassmorphism Overlay -->
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-3xl md:text-4xl font-bold text-white mb-2">20+ Års Utvecklingsresa</h3>
                            <p class="text-white/90 text-lg">Från ASP till AI-Driven Laravel</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/30 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="relative">
                        <!-- Tech Icon Display (Positioned Above Each Year) -->

                        <!-- HTML Icon (12.5% - 2004) -->
                        <div class="absolute -top-20 left-[12.5%] -translate-x-1/2 flex items-center justify-center" x-show="currentIcon === 'html'" x-transition:enter="transition-all ease-out duration-600" x-transition:enter-start="opacity-0 scale-50 translate-y-20" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition-all ease-in duration-400" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-75" x-cloak>
                            <div class="bg-white/95 backdrop-blur-sm p-3 rounded-2xl shadow-2xl border-2 border-orange-500 animate-[sway_2s_ease-in-out_infinite]">
                                <img src="https://cdn.simpleicons.org/html5/E34F26" alt="HTML5" class="w-12 h-12 drop-shadow-lg">
                            </div>
                        </div>

                        <!-- PHP Icon (37.5% - 2010) -->
                        <div class="absolute -top-20 left-[37.5%] -translate-x-1/2 flex items-center justify-center" x-show="currentIcon === 'php'" x-transition:enter="transition-all ease-out duration-600" x-transition:enter-start="opacity-0 scale-50 translate-y-20" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition-all ease-in duration-400" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-75" x-cloak>
                            <div class="bg-white/95 backdrop-blur-sm p-3 rounded-2xl shadow-2xl border-2 border-purple-600 animate-[sway_2s_ease-in-out_infinite]">
                                <img src="https://cdn.simpleicons.org/php/777BB4" alt="PHP" class="w-12 h-12 drop-shadow-lg">
                            </div>
                        </div>

                        <!-- Laravel Icon (62.5% - 2017) -->
                        <div class="absolute -top-20 left-[62.5%] -translate-x-1/2 flex items-center justify-center" x-show="currentIcon === 'laravel'" x-transition:enter="transition-all ease-out duration-600" x-transition:enter-start="opacity-0 scale-50 translate-y-20" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition-all ease-in duration-400" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-75" x-cloak>
                            <div class="bg-white/95 backdrop-blur-sm p-3 rounded-2xl shadow-2xl border-2 border-red-600 animate-[sway_2s_ease-in-out_infinite]">
                                <img src="https://cdn.simpleicons.org/laravel/FF2D20" alt="Laravel" class="w-12 h-12 drop-shadow-lg">
                            </div>
                        </div>

                        <!-- AI Icon (87.5% - 2023) -->
                        <div class="absolute -top-20 left-[87.5%] -translate-x-1/2 flex items-center justify-center" x-show="currentIcon === 'ai'" x-transition:enter="transition-all ease-out duration-600" x-transition:enter-start="opacity-0 scale-50 translate-y-20" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition-all ease-in duration-400" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-75" x-cloak>
                            <div class="bg-white/95 backdrop-blur-sm p-3 rounded-2xl shadow-2xl border-2 border-amber-600 animate-[sway_2s_ease-in-out_infinite]">
                                <img src="https://cdn.simpleicons.org/anthropic/CC9B7A" alt="Anthropic" class="w-12 h-12 drop-shadow-lg">
                            </div>
                        </div>

                        <!-- Progress Bar Background -->
                        <div class="h-2 bg-white/20 rounded-full overflow-hidden mb-4">
                            <div class="h-full bg-white/90 rounded-full transition-all duration-[10000ms] ease-linear" :style="`width: ${progress}%`"></div>
                        </div>

                        <!-- Milestones -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                <div class="text-2xl font-bold text-white mb-1">2004</div>
                                <div class="text-white/80 text-sm">HTML & ASP</div>
                            </div>
                            <div class="text-center" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                <div class="text-2xl font-bold text-white mb-1">2010</div>
                                <div class="text-white/80 text-sm">PHP & MySQL</div>
                            </div>
                            <div class="text-center" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                <div class="text-2xl font-bold text-white mb-1">2017</div>
                                <div class="text-white/80 text-sm">Laravel</div>
                            </div>
                            <div class="text-center" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-900" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                <div class="text-2xl font-bold text-white mb-1">2023</div>
                                <div class="text-white/80 text-sm">AI Integration</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-data="{ visible: false, counters: { laravel: 0, response: 0 } }" x-intersect="visible = true" x-init="$watch('visible', value => { if (value) { let interval = setInterval(() => { if (counters.laravel < 8) counters.laravel++; if (counters.response < 24) counters.response++; if (counters.laravel >= 8 && counters.response >= 24) clearInterval(interval); }, 50) } })">

            <!-- Laravel Expertise -->
            <div class="group relative overflow-hidden rounded-2xl p-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-gray-200 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-400 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-purple-500/20" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <div class="text-5xl font-bold bg-gradient-to-r from-purple-600 to-purple-800 dark:from-purple-400 dark:to-purple-600 bg-clip-text text-transparent mb-2" x-text="counters.laravel + '+'"></div>
                    <div class="text-sm uppercase tracking-wider text-gray-600 dark:text-gray-400 font-semibold mb-2">År med Laravel</div>
                    <p class="text-sm text-gray-500 dark:text-gray-500">Huvudramverk sedan 2017</p>
                </div>
            </div>

            <!-- Response Time -->
            <div class="group relative overflow-hidden rounded-2xl p-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/20" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse shadow-lg shadow-green-400/50"></div>
                    </div>
                    <div class="text-5xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-400 dark:to-blue-600 bg-clip-text text-transparent mb-2">
                        &lt; <span x-text="counters.response"></span>h
                    </div>
                    <div class="text-sm uppercase tracking-wider text-gray-600 dark:text-gray-400 font-semibold mb-2">Responstid</div>
                    <p class="text-sm text-gray-500 dark:text-gray-500">Snabb kommunikation garanterad</p>
                </div>
            </div>

            <!-- Specializations -->
            <div class="group relative overflow-hidden rounded-2xl p-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-gray-200 dark:border-gray-700 hover:border-pink-500 dark:hover:border-pink-400 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-pink-500/20" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="absolute inset-0 bg-gradient-to-br from-pink-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="text-5xl font-bold bg-gradient-to-r from-pink-600 to-pink-800 dark:from-pink-400 dark:to-pink-600 bg-clip-text text-transparent mb-2">3</div>
                    <div class="text-sm uppercase tracking-wider text-gray-600 dark:text-gray-400 font-semibold mb-2">Kärnområden</div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-1 bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300 rounded-lg text-xs font-medium">E-handel</span>
                        <span class="px-2 py-1 bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300 rounded-lg text-xs font-medium">CRM</span>
                        <span class="px-2 py-1 bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300 rounded-lg text-xs font-medium">Bokning</span>
                    </div>
                </div>
            </div>

            <!-- Tech Stack -->
            <div class="group relative overflow-hidden rounded-2xl p-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-gray-200 dark:border-gray-700 hover:border-green-500 dark:hover:border-green-400 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-green-500/20" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-400" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                    </div>
                    <div class="text-sm uppercase tracking-wider text-gray-600 dark:text-gray-400 font-semibold mb-3">Modern Stack</div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-sm font-semibold">Laravel</span>
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-sm font-semibold">Vue</span>
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-sm font-semibold">Alpine</span>
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-sm font-semibold">AI</span>
                    </div>
                </div>
            </div>

            <!-- Delivery Time -->
            <div class="group relative overflow-hidden rounded-2xl p-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-400 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-orange-500/20" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="text-5xl font-bold bg-gradient-to-r from-orange-600 to-orange-800 dark:from-orange-400 dark:to-orange-600 bg-clip-text text-transparent mb-2">2-6</div>
                    <div class="text-sm uppercase tracking-wider text-gray-600 dark:text-gray-400 font-semibold mb-2">Veckor Leverans</div>
                    <p class="text-sm text-gray-500 dark:text-gray-500">Typisk projekttid</p>
                </div>
            </div>

            <!-- AI & Automation -->
            <div class="group relative overflow-hidden rounded-2xl p-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-indigo-500/20" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-600" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="text-sm uppercase tracking-wider text-gray-600 dark:text-gray-400 font-semibold mb-3">AI-Assisterad</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Smart automation möter 20+ års expertis för kostnadseffektiva, enterprise-grade lösningar</p>
                </div>
            </div>

        </div>
    </div>
</section>


<section id="services" class="relative py-24 bg-white dark:bg-gray-900 overflow-hidden">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Mina Tjänster</h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">Skräddarsydda lösningar för alla dina webbutvecklingsbehov</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($services as $service)
            <div class="group relative bg-white dark:bg-gray-800 rounded-3xl p-8 border-2 border-gray-200 dark:border-gray-700 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:border-purple-500 dark:hover:border-purple-500">
                <!-- Icon -->
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    @if($service->icon === 'code')
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                    @elseif($service->icon === 'puzzle-piece')
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
                    </svg>
                    @elseif($service->icon === 'wrench')
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    @else
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    @endif
                </div>

                <!-- Title -->
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ $service->title }}
                </h3>

                <!-- Description -->
                <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                    {{ $service->description }}
                </p>

                <!-- Features -->
                @if($service->features && count($service->features) > 0)
                <div class="space-y-3">
                    @foreach($service->features as $feature)
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Hover indicator -->
                <div class="absolute bottom-6 right-6 w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </div>
            </div>
            @endforeach
        </div>

        <!-- CTA -->
        <div class="mt-16 text-center">
            <p class="text-gray-600 dark:text-gray-400 mb-6 text-lg">
                Inte säker på vilken tjänst som passar dig bäst?
            </p>
            <a href="#contact" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-full font-semibold hover:shadow-2xl hover:shadow-purple-500/30 transition-all hover:scale-105">
                Kontakta Mig För Rådgivning
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>


<section id="projects" class="relative py-24 bg-gray-50 dark:bg-gray-800 overflow-hidden">
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Utvalda Projekt</h2>
            <p class="text-xl text-gray-600 dark:text-gray-400">En samling av mina senaste arbeten</p>
        </div>

        <div class="grid md:grid-cols-12 gap-6">
            @forelse($projects as $index => $project)
            <div class="group relative {{ $index === 0 ? 'md:col-span-12' : 'md:col-span-6' }} transition-all duration-300 hover:-translate-y-2 hover:scale-[1.02]">
                <div class="relative w-full {{ $index === 0 ? 'h-96' : 'h-80' }} rounded-3xl overflow-hidden">
                    <!-- Gradient border on hover -->
                    <div class="absolute inset-0 rounded-3xl bg-gradient-to-r from-purple-500 via-blue-500 to-pink-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <!-- Content wrapper -->
                    <div class="absolute inset-[2px] rounded-3xl h-full overflow-hidden bg-white dark:bg-gray-900">
                        @if($project->cover_image)
                        <img src="{{ asset('storage/' . $project->cover_image) }}"
                             alt="{{ $project->title }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-purple-400 via-blue-500 to-pink-500 flex items-center justify-center">
                            <svg class="w-24 h-24 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>

                        <div class="absolute inset-0 p-8 flex flex-col justify-end">
                            <h3 class="text-3xl font-bold text-white mb-3">
                                {{ $project->title }}
                            </h3>

                            <p class="text-white/90 mb-6 line-clamp-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                {{ $project->summary }}
                            </p>

                            @if($project->technologies)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach((is_array($project->technologies) ? $project->technologies : json_decode($project->technologies, true)) as $tech)
                                <span class="px-3 py-1 bg-white/20 border border-white/30 text-white text-sm rounded-full">
                                    {{ trim($tech) }}
                                </span>
                                @endforeach
                            </div>
                            @endif

                            <a href="/projects/{{ $project->slug }}"
                               class="absolute bottom-8 right-8 w-14 h-14 bg-white/20 border border-white/30 rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-white/30 hover:scale-110">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="md:col-span-12 text-center py-24">
                <div class="inline-block p-8 glass-morph rounded-3xl">
                    <svg class="w-24 h-24 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-xl font-semibold">Inga projekt att visa ännu</p>
                    <p class="text-gray-500 dark:text-gray-500 mt-2">Fantastiska projekt kommer snart!</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Tech Stack CTA -->
        <div class="mt-16 text-center" x-data="{ techStackOpen: false }">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500 via-blue-500 to-pink-500 rounded-3xl blur-xl opacity-30 animate-pulse"></div>
                <button @click="techStackOpen = true" class="relative px-10 py-5 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 text-white rounded-3xl font-bold text-lg shadow-2xl hover:shadow-purple-500/50 transition-all hover:scale-105 active:scale-95 flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Utforska Min Tech Stack
                </button>
            </div>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Interaktiv visualisering av teknologier jag använder</p>

            <!-- Tech Stack Modal -->
            <div x-show="techStackOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click.self="techStackOpen = false"
                 class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
                 x-cloak>
                <div @click.stop
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-90"
                     class="relative bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-7xl max-h-[90vh] overflow-hidden">

                    <!-- Header -->
                    <div class="sticky top-0 z-10 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 px-8 py-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Tech Stack Visualizer</h2>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">Interaktiv graf över teknologier och deras relationer</p>
                            </div>
                            <button @click="techStackOpen = false" class="p-3 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors">
                                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="overflow-y-auto max-h-[calc(90vh-120px)] p-8">
                        <!-- D3.js Visualization Container -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 mb-8">
                            <div id="tech-graph-modal" class="w-full" style="height: 500px;"></div>

                            <!-- Legend -->
                            <div class="mt-6 flex flex-wrap justify-center gap-6 text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Frontend</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-green-500"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Backend</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-purple-500"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Database</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-orange-500"></div>
                                    <span class="text-gray-700 dark:text-gray-300">DevOps</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-gray-500"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Övrigt</span>
                                </div>
                            </div>
                        </div>

                        <!-- Technology Statistics -->
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Teknologistatistik</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="tech-stats-modal">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Interactive Demos CTA Section -->
<section class="relative py-24 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-700 overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute w-96 h-96 bg-white/5 rounded-full blur-3xl -top-48 -left-48 animate-pulse"></div>
        <div class="absolute w-96 h-96 bg-white/5 rounded-full blur-3xl -bottom-48 -right-48 animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute w-64 h-64 bg-white/10 rounded-full blur-2xl top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-6 text-center">
        <div class="inline-block p-3 bg-white/10 rounded-2xl mb-6 float-gentle">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
            </svg>
        </div>

        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
            Upplev Funktionerna Live
        </h2>

        <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto leading-relaxed">
            Prova våra interaktiva demos och se exakt vad som är möjligt för din verksamhet.
        </p>

        <!-- CTA Button -->
        <div class="relative inline-block">
            <div class="absolute inset-0 bg-white rounded-full blur-xl opacity-30 animate-pulse"></div>
            <a href="/demos" class="relative inline-flex items-center gap-3 px-10 py-5 bg-white text-purple-600 rounded-full font-bold text-lg shadow-2xl hover:shadow-white/50 transition-all hover:scale-105 active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Utforska Interactive Demos</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>

        <p class="mt-6 text-white/70 text-sm">
            Upptäck kraftfulla funktioner som kan revolutionera din närvaro online
        </p>
    </div>
</section>


<!-- FAQ Section -->
<section id="faq" class="relative py-24 bg-gradient-to-b from-gray-50 via-white to-gray-50 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute w-96 h-96 bg-purple-300 dark:bg-purple-600 rounded-full blur-3xl top-1/4 left-1/4 animate-pulse"></div>
        <div class="absolute w-96 h-96 bg-blue-300 dark:bg-blue-600 rounded-full blur-3xl bottom-1/4 right-1/4 animate-pulse" style="animation-delay: 1.5s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-16" x-data="{ visible: false }" x-intersect="visible = true">
            <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 bg-clip-text text-transparent mb-4" x-show="visible" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Vanliga Frågor
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Svar på det du kanske undrar
            </p>
        </div>

        <!-- FAQ Accordion -->
        <div class="grid md:grid-cols-2 gap-4" x-data="{ openFaq: null, visible: false }" x-intersect="visible = true">

            <!-- FAQ 1 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-2 transition-all duration-300" :class="openFaq === 1 ? 'border-purple-500 dark:border-purple-400 shadow-2xl shadow-purple-500/20' : 'border-gray-200 dark:border-gray-700 hover:border-purple-300 dark:hover:border-purple-600'" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full text-left p-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Använder du WordPress eller andra CMS?</span>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-6" x-cloak>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Nej, jag bygger skräddarsydda lösningar med Laravel istället för WordPress. Detta ger bättre prestanda, säkerhet och flexibilitet. Om du behöver kunna redigera innehåll själv bygger jag ett intuitivt admin-gränssnitt specifikt för dina behov - ofta enklare och snabbare än WordPress.
                    </p>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-2 transition-all duration-300" :class="openFaq === 2 ? 'border-blue-500 dark:border-blue-400 shadow-2xl shadow-blue-500/20' : 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600'" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-150" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full text-left p-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Vilka språk och ramverk kan du arbeta med?</span>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-6" x-cloak>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Tack vare moderna AI-verktyg kan jag idag arbeta effektivt med nästan vilket programmeringsspråk som helst. Mina primära verktyg är Laravel (PHP), Vue, Alpine och React, men jag anpassar tech-stacken efter projektets behov. AI-assistans gör att jag snabbt kan leverera högkvalitativ kod även i mindre vanliga språk.
                    </p>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-2 transition-all duration-300" :class="openFaq === 3 ? 'border-green-500 dark:border-green-400 shadow-2xl shadow-green-500/20' : 'border-gray-200 dark:border-gray-700 hover:border-green-300 dark:hover:border-green-600'" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full text-left p-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Hur får jag bästa prisuppskattning?</span>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-6" x-cloak>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Använd <a href="#" @click.prevent="document.querySelector('.price-calculator')?.scrollIntoView({ behavior: 'smooth' })" class="text-purple-600 dark:text-purple-400 hover:underline font-semibold">priskalkylatorn</a> på denna sida och beskriv ditt projekt så detaljerat som möjligt! Ju mer information du ger om funktioner, integrationer och komplexitet, desto mer exakt blir uppskattningen. Kalkylatorn använder AI för att analysera dina behov och ge en realistisk estimering direkt.
                    </p>
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-2 transition-all duration-300" :class="openFaq === 4 ? 'border-pink-500 dark:border-pink-400 shadow-2xl shadow-pink-500/20' : 'border-gray-200 dark:border-gray-700 hover:border-pink-300 dark:hover:border-pink-600'" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-250" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <button @click="openFaq = openFaq === 4 ? null : 4" class="w-full text-left p-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Kan du hjälpa mig med SEO och GDPR-anpassning?</span>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-6" x-cloak>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Jag hjälper dig med grunderna i både SEO och GDPR. Alla webbplatser jag bygger är SEO-optimerade från start med rätt metatagg, semantisk HTML och snabba laddningstider. För GDPR implementerar jag cookie-hantering och privacy policy. Dock bör du alltid konsultera ett advokatbyrå angående GDPR för att vara säker på att din webbplats drivs enligt juridiska villkor - detta är inget jag tar ansvar för.
                    </p>
                </div>
            </div>

            <!-- FAQ 5 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-2 transition-all duration-300" :class="openFaq === 5 ? 'border-orange-500 dark:border-orange-400 shadow-2xl shadow-orange-500/20' : 'border-gray-200 dark:border-gray-700 hover:border-orange-300 dark:hover:border-orange-600'" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <button @click="openFaq = openFaq === 5 ? null : 5" class="w-full text-left p-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Ingår design och logotyp?</span>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-6" x-cloak>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Ja! Tack vare AI-verktyg kan jag idag hjälpa till med både grafisk design och logotypskapande. Jag arbetar gärna kreativt tillsammans med dig för att ta fram en visuell identitet som matchar din verksamhet. Allt från mockups till färdiga designkoncept ingår i processen. Om du föredrar en professionell grafisk designer kan jag även rekommendera <a href="https://www.fiverr.com" target="_blank" rel="noopener" class="text-purple-600 dark:text-purple-400 hover:underline">Fiverr</a>, där du kan hitta designers till bra priser.
                    </p>
                </div>
            </div>

            <!-- FAQ 6 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-2 transition-all duration-300" :class="openFaq === 6 ? 'border-indigo-500 dark:border-indigo-400 shadow-2xl shadow-indigo-500/20' : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-600'" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-350" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <button @click="openFaq = openFaq === 6 ? null : 6" class="w-full text-left p-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Kan jag själv redigera innehållet efteråt?</span>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 6 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-6" x-cloak>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Ja! Jag bygger ett skräddarsytt admin-gränssnitt där du enkelt kan redigera texter, bilder och innehåll. Gränssnittet anpassas efter exakt vad DU behöver kunna ändra - inget krångel med WordPress-widgets eller plugin. Enkel utbildning ingår vid leverans.
                    </p>
                </div>
            </div>

            <!-- FAQ 7 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-2 transition-all duration-300" :class="openFaq === 7 ? 'border-cyan-500 dark:border-cyan-400 shadow-2xl shadow-cyan-500/20' : 'border-gray-200 dark:border-gray-700 hover:border-cyan-300 dark:hover:border-cyan-600'" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-400" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <button @click="openFaq = openFaq === 7 ? null : 7" class="w-full text-left p-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Hjälper du med hosting och domän?</span>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 7 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 7" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-6" x-cloak>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Ja! Jag hyr en kraftfull VPS som jag har förkonfigurerat för optimal prestanda och säkerhet. Här kan jag erbjuda både utrymme och support för ett fast paketpris varje månad. Bonus: Om du hyr hosting hos mig ingår framtida support alltid - du behöver aldrig betala extra för bugfixar eller hjälp. Alternativt hjälper jag dig sätta upp på andra tjänster som DigitalOcean eller AWS, plus domän, SSL-certifikat och e-postkonfiguration.
                    </p>
                </div>
            </div>

            <!-- FAQ 8 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-2 transition-all duration-300" :class="openFaq === 8 ? 'border-teal-500 dark:border-teal-400 shadow-2xl shadow-teal-500/20' : 'border-gray-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-600'" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-450" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <button @click="openFaq = openFaq === 8 ? null : 8" class="w-full text-left p-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Hur går betalningen till?</span>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 8 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-6" x-cloak>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Vanligtvis delar vi upp betalningen i två eller tre delar: 30-50% vid projektstart, 30-40% vid godkänd demo/preview, och resterande vid slutleverans. Exakta villkor diskuterar vi baserat på projektets storlek. Du faktureras via mitt egenanställningsföretag CoolCompany, som tar 6% avgift för deras tjänster - detta läggs på totalkostnaden. Om du är ett företag utanför Sverige behöver jag även ditt momsnummer. Betalning via Swish eller banköverföring.
                    </p>
                </div>
            </div>

            <!-- FAQ 9 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-2 transition-all duration-300" :class="openFaq === 9 ? 'border-violet-500 dark:border-violet-400 shadow-2xl shadow-violet-500/20' : 'border-gray-200 dark:border-gray-700 hover:border-violet-300 dark:hover:border-violet-600'" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <button @click="openFaq = openFaq === 9 ? null : 9" class="w-full text-left p-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Vad behöver jag förbereda innan vi startar?</span>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 9 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 9" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-6" x-cloak>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Inte så mycket! Vi börjar med en genomgång där du berättar om dina mål, målgrupp och önskade funktioner. Ha gärna tillgång till eventuella grafiska profiler, logotyper, exempelwebbplatser du gillar och textinnehåll. Men oroa dig inte - vi kan ta fram allt detta tillsammans om det saknas.
                    </p>
                </div>
            </div>

            <!-- FAQ 10 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-2 transition-all duration-300" :class="openFaq === 10 ? 'border-rose-500 dark:border-rose-400 shadow-2xl shadow-rose-500/20' : 'border-gray-200 dark:border-gray-700 hover:border-rose-300 dark:hover:border-rose-600'" x-show="visible" x-transition:enter="transition ease-out duration-500 delay-550" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <button @click="openFaq = openFaq === 10 ? null : 10" class="w-full text-left p-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Ingår framtida support och uppdateringar?</span>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 10 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openFaq === 10" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-6 pb-6" x-cloak>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Ja, mindre justeringar och bugfixar de första 30 dagarna efter lansering ingår alltid. För att förtydliga: <strong>Support</strong> innebär att fixa saker som inte fungerar (bugfixar, tekniska problem, e-postproblem etc.) - detta ingår om du hyr hosting hos mig. Att lägga till nya funktioner eller ytterligare kod räknas dock som vidareutveckling och kräver separat offert. För löpande support och säkerhetsuppdateringar erbjuder jag flexibla underhållsavtal. Jag svarar alltid inom 24 timmar!
                    </p>
                </div>
            </div>

        </div>

        <!-- CTA -->
        <div class="mt-12 text-center p-8 bg-gradient-to-r from-purple-50 via-blue-50 to-pink-50 dark:from-purple-900/20 dark:via-blue-900/20 dark:to-pink-900/20 rounded-2xl border border-purple-200 dark:border-purple-700/30" x-data="{ visible: false }" x-intersect="visible = true" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-600" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Fick du inte svar på din fråga?</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Kontakta mig så svarar jag inom 24 timmar!
            </p>
            <a href="#contact" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 text-white rounded-full font-semibold hover:shadow-2xl hover:shadow-purple-500/30 transition-all hover:scale-105">
                Ställ Din Fråga
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>


@include('components.price-calculator')


<!-- Website Audit CTA Section -->
<section class="relative py-24 bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-700 overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute w-96 h-96 bg-white/5 rounded-full blur-3xl -top-48 -left-48"></div>
        <div class="absolute w-96 h-96 bg-white/5 rounded-full blur-3xl -bottom-48 -right-48"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-6 text-center">
        <div class="inline-block p-3 bg-white/10 rounded-2xl mb-6">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
        </div>

        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
            Hur Presterar Din Webbplats?
        </h2>

        <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
            Få en gratis, AI-driven analys av din webbplats SEO, prestanda och användarupplevelse.
            Professionell rapport på 2-5 minuter.
        </p>

        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <div class="flex items-center gap-2 text-white/80">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>100% Gratis</span>
            </div>
            <div class="flex items-center gap-2 text-white/80">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>AI-Analyserad</span>
            </div>
            <div class="flex items-center gap-2 text-white/80">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>Konkreta Råd</span>
            </div>
        </div>

        <a href="{{ route('audits.create') }}"
           class="inline-flex items-center gap-3 px-10 py-5 bg-white text-purple-600 rounded-2xl font-bold text-lg shadow-2xl hover:shadow-white/25 transition-all hover:scale-105 active:scale-95">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Starta Gratis Website Audit
        </a>

        <p class="mt-6 text-white/70 text-sm">
            Ingen kreditkort krävs • Resultat på 2-5 minuter • Rapport via e-post
        </p>
    </div>
</section>


<section id="contact" class="relative py-24 bg-white dark:bg-gray-900 overflow-hidden">
    <div class="relative max-w-4xl mx-auto px-6">
        <div class="relative text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 bg-clip-text text-transparent">
                Låt Oss Skapa Något Fantastiskt
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400">Har du ett projekt i tankarna? Kontakta mig idag!</p>
        </div>

        @if(session('success'))
        <div class="mb-8 p-4 bg-green-50 dark:bg-green-900/20 rounded-2xl border-l-4 border-green-500" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center gap-3 text-green-600 dark:text-green-400">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-8 p-4 bg-red-50 dark:bg-red-900/20 rounded-2xl border-l-4 border-red-500" x-data="{ show: true }" x-show="show" x-transition>
            <div class="flex items-start gap-3 text-red-600 dark:text-red-400">
                <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="font-medium mb-2">Vänligen rätta följande fel:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="relative bg-white dark:bg-gray-800 rounded-3xl p-8 md:p-12 border-2 border-gray-200 dark:border-gray-700 shadow-2xl overflow-hidden" x-data="{ submitting: false }">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 via-blue-500/5 to-pink-500/5 -z-10"></div>
            <form method="POST" action="{{ route('contact.store') }}" @submit="submitting = true" class="space-y-8" x-data="{
                nameFocused: false,
                emailFocused: false,
                messageFocused: false,
                estimationId: null,
                estimation: null,
                init() {
                    // Listen for estimation data from price calculator
                    window.addEventListener('estimation-ready', (event) => {
                        this.estimationId = event.detail.id;
                        this.estimation = event.detail.data;
                        console.log('Estimation received:', this.estimation);
                    });
                }
            }">
                @csrf

                <!-- Hidden field for price estimation ID -->
                <input type="hidden" name="price_estimation_id" :value="estimationId">

                <!-- Name Field with Floating Label -->
                <div class="relative group">
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        maxlength="255"
                        @focus="nameFocused = true"
                        @blur="nameFocused = ($event.target.value !== '')"
                        x-init="nameFocused = ('{{ old('name') }}' !== '')"
                        class="peer w-full px-4 py-4 pt-6 bg-gray-50 dark:bg-gray-900 rounded-2xl text-gray-900 dark:text-white
                               border-2 border-gray-300 dark:border-gray-600
                               focus:border-purple-500 dark:focus:border-purple-400
                               focus:shadow-lg focus:shadow-purple-500/20
                               transition-all duration-300
                               placeholder-transparent
                               @error('name') border-red-500 dark:border-red-400 @enderror">
                    <label
                        for="name"
                        class="absolute left-4 transition-all duration-300 pointer-events-none
                               text-gray-600 dark:text-gray-400"
                        :class="nameFocused ? 'top-2 text-xs font-semibold text-purple-600 dark:text-purple-400' : 'top-4 text-base'">
                        Namn <span class="text-red-500">*</span>
                    </label>
                    @error('name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Email Field with Floating Label -->
                <div class="relative group">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        maxlength="255"
                        @focus="emailFocused = true"
                        @blur="emailFocused = ($event.target.value !== '')"
                        x-init="emailFocused = ('{{ old('email') }}' !== '')"
                        class="peer w-full px-4 py-4 pt-6 bg-gray-50 dark:bg-gray-900 rounded-2xl text-gray-900 dark:text-white
                               border-2 border-gray-300 dark:border-gray-600
                               focus:border-purple-500 dark:focus:border-purple-400
                               focus:shadow-lg focus:shadow-purple-500/20
                               transition-all duration-300
                               placeholder-transparent
                               @error('email') border-red-500 dark:border-red-400 @enderror">
                    <label
                        for="email"
                        class="absolute left-4 transition-all duration-300 pointer-events-none
                               text-gray-600 dark:text-gray-400"
                        :class="emailFocused ? 'top-2 text-xs font-semibold text-purple-600 dark:text-purple-400' : 'top-4 text-base'">
                        E-post <span class="text-red-500">*</span>
                    </label>
                    @error('email')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Price Estimation Summary (shown when coming from price calculator) -->
                <div x-show="estimation" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 dark:from-purple-900/20 dark:via-blue-900/20 dark:to-pink-900/20 rounded-2xl p-6 border-2 border-purple-200 dark:border-purple-700">
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Din Prisestimering</h3>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-3">
                            <p class="text-xs font-semibold text-purple-600 dark:text-purple-400 mb-1">Projekttyp</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="estimation?.project_type_label"></p>
                        </div>
                        <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-3">
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-1">Komplexitet</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white"><span x-text="estimation?.complexity"></span>/10</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-3">
                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Arbetstid</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="estimation?.hours_ai"></p>
                        </div>
                        <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-3">
                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Leveranstid</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="estimation?.delivery_weeks_ai"></p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl p-4">
                        <div class="flex justify-between items-center mb-3">
                            <p class="text-xs font-semibold opacity-90">Estimerat Pris</p>
                            <span class="px-2 py-1 bg-white/20 rounded-full text-xs font-bold">-80%</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-baseline">
                                <span class="text-sm opacity-90">Exkl. moms:</span>
                                <span class="text-xl font-bold" x-text="estimation?.price_ai"></span>
                            </div>
                            <div class="flex justify-between items-baseline border-t border-white/20 pt-2">
                                <span class="text-sm opacity-90">Inkl. moms:</span>
                                <span class="text-2xl font-bold" x-text="estimation?.price_ai_vat"></span>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-white/30">
                            <div class="flex justify-between items-center">
                                <span class="text-xs opacity-90">Din besparing (80%):</span>
                                <span class="font-bold" x-text="estimation?.savings_vat"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-purple-200 dark:border-purple-700" x-show="estimation?.key_features?.length > 0">
                        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Identifierade Funktioner:</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="feature in estimation?.key_features || []" :key="feature">
                                <span class="px-2 py-1 bg-white/70 dark:bg-gray-800/70 rounded-lg text-xs text-gray-700 dark:text-gray-300" x-text="feature"></span>
                            </template>
                        </div>
                    </div>

                    <p class="mt-4 text-xs text-gray-600 dark:text-gray-400 text-center">
                        📊 Denna analys kommer att kopplas till ditt meddelande
                    </p>
                </div>

                <!-- Message Field with Floating Label -->
                <div class="relative group">
                    <textarea
                        id="message"
                        name="message"
                        required
                        minlength="10"
                        maxlength="5000"
                        rows="6"
                        @focus="messageFocused = true"
                        @blur="messageFocused = ($event.target.value !== '')"
                        x-init="messageFocused = ('{{ old('message') }}' !== '')"
                        class="peer w-full px-4 py-4 pt-6 bg-gray-50 dark:bg-gray-900 rounded-2xl text-gray-900 dark:text-white
                               border-2 border-gray-300 dark:border-gray-600
                               focus:border-purple-500 dark:focus:border-purple-400
                               focus:shadow-lg focus:shadow-purple-500/20
                               transition-all duration-300 resize-none
                               placeholder-transparent
                               @error('message') border-red-500 dark:border-red-400 @enderror">{{ old('message') }}</textarea>
                    <label
                        for="message"
                        class="absolute left-4 transition-all duration-300 pointer-events-none
                               text-gray-600 dark:text-gray-400"
                        :class="messageFocused ? 'top-2 text-xs font-semibold text-purple-600 dark:text-purple-400' : 'top-4 text-base'">
                        Meddelande <span class="text-red-500">*</span>
                    </label>
                    @error('message')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </p>
                    @else
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Vi svarar vanligtvis inom 24 timmar.</p>
                    @enderror
                </div>

                <!-- Honeypot -->
                <input type="text" name="website" value="" tabindex="-1" autocomplete="off" style="position: absolute; left: -9999px; width: 1px; height: 1px;">

                <!-- Submit Button -->
                <div class="relative">
                    <button
                        type="submit"
                        :disabled="submitting"
                        class="w-full px-8 py-5 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600
                               rounded-2xl font-bold text-lg text-white
                               transition-all duration-300
                               disabled:opacity-50 disabled:cursor-not-allowed
                               hover:shadow-2xl hover:shadow-purple-500/30 hover:-translate-y-0.5
                               active:translate-y-0
                               flex items-center justify-center gap-3">
                        <span x-show="!submitting" class="flex items-center gap-3">
                            Skicka Meddelande
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </span>
                        <span x-show="submitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Skickar...
                        </span>
                    </button>
                </div>

                <div class="pt-6 border-t border-purple-200 dark:border-purple-700">
                    <p class="text-center text-gray-700 dark:text-gray-300 mb-3">Eller kontakta mig direkt via e-post:</p>
                    <div class="flex justify-center">
                        <a href="mailto:andreas@atdev.me" class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-gray-800 rounded-full shadow-lg hover:shadow-xl transition-all hover:scale-105">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="font-semibold text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400 transition-colors">andreas@atdev.me</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- D3.js for Tech Stack Modal -->
<script src="https://d3js.org/d3.v7.min.js"></script>

<script>
// Tech Stack Modal Handler
document.addEventListener('alpine:initialized', () => {
    let techDataLoaded = false;
    let techData = null;

    // Listen for modal open
    document.addEventListener('click', async function(e) {
        if (e.target.closest('[\\@click="techStackOpen = true"]') || e.target.closest('button[x-on\\:click="techStackOpen = true"]')) {
            if (!techDataLoaded) {
                await loadTechStack();
            }
        }
    });

    async function loadTechStack() {
        try {
            const response = await fetch('/api/tech-stack');
            techData = await response.json();
            techDataLoaded = true;

            // Wait for modal to be visible
            setTimeout(() => {
                renderTechGraph();
                renderTechStats();
            }, 300);
        } catch (error) {
            console.error('Failed to load tech stack data:', error);
        }
    }

    function renderTechGraph() {
        const container = document.getElementById('tech-graph-modal');
        if (!container || !techData) return;

        // Clear previous graph
        container.innerHTML = '';

        const width = container.clientWidth;
        const height = 500;

        // Color mapping
        const colors = {
            frontend: '#3B82F6',
            backend: '#10B981',
            database: '#8B5CF6',
            devops: '#F59E0B',
            other: '#6B7280'
        };

        // Create SVG
        const svg = d3.select('#tech-graph-modal')
            .append('svg')
            .attr('width', width)
            .attr('height', height);

        // Tooltip
        const tooltip = d3.select('body')
            .append('div')
            .attr('class', 'absolute bg-gray-900 text-white px-3 py-2 rounded-lg text-sm shadow-lg pointer-events-none opacity-0 transition-opacity duration-200')
            .style('z-index', '9999');

        // Force simulation
        const simulation = d3.forceSimulation(techData.nodes)
            .force('link', d3.forceLink(techData.links)
                .id(d => d.id)
                .distance(d => 100 - (d.value * 10)))
            .force('charge', d3.forceManyBody().strength(-300))
            .force('center', d3.forceCenter(width / 2, height / 2))
            .force('collision', d3.forceCollide().radius(d => Math.sqrt(d.count) * 10 + 10));

        // Links
        const link = svg.append('g')
            .selectAll('line')
            .data(techData.links)
            .join('line')
            .attr('stroke', '#cbd5e1')
            .attr('stroke-opacity', d => 0.3 + (d.value * 0.1))
            .attr('stroke-width', d => Math.sqrt(d.value));

        // Nodes
        const node = svg.append('g')
            .selectAll('g')
            .data(techData.nodes)
            .join('g')
            .call(d3.drag()
                .on('start', dragstarted)
                .on('drag', dragged)
                .on('end', dragended));

        node.append('circle')
            .attr('r', d => Math.sqrt(d.count) * 10 + 5)
            .attr('fill', d => colors[d.group])
            .attr('stroke', '#fff')
            .attr('stroke-width', 2)
            .style('cursor', 'pointer')
            .on('mouseover', function(event, d) {
                d3.select(this)
                    .transition()
                    .duration(200)
                    .attr('r', Math.sqrt(d.count) * 10 + 8);

                tooltip
                    .style('opacity', 1)
                    .html(`<strong>${d.name}</strong><br/>${d.count} ${d.count === 1 ? 'projekt' : 'projekt'}`)
                    .style('left', (event.pageX + 10) + 'px')
                    .style('top', (event.pageY - 10) + 'px');
            })
            .on('mouseout', function(event, d) {
                d3.select(this)
                    .transition()
                    .duration(200)
                    .attr('r', Math.sqrt(d.count) * 10 + 5);

                tooltip.style('opacity', 0);
            });

        node.append('text')
            .text(d => d.name)
            .attr('x', 0)
            .attr('y', d => Math.sqrt(d.count) * 10 + 20)
            .attr('text-anchor', 'middle')
            .attr('font-size', '12px')
            .attr('font-weight', '600')
            .attr('fill', '#1f2937')
            .style('pointer-events', 'none');

        simulation.on('tick', () => {
            link
                .attr('x1', d => d.source.x)
                .attr('y1', d => d.source.y)
                .attr('x2', d => d.target.x)
                .attr('y2', d => d.target.y);

            node.attr('transform', d => `translate(${d.x},${d.y})`);
        });

        function dragstarted(event, d) {
            if (!event.active) simulation.alphaTarget(0.3).restart();
            d.fx = d.x;
            d.fy = d.y;
        }

        function dragged(event, d) {
            d.fx = event.x;
            d.fy = event.y;
        }

        function dragended(event, d) {
            if (!event.active) simulation.alphaTarget(0);
            d.fx = null;
            d.fy = null;
        }
    }

    function renderTechStats() {
        const container = document.getElementById('tech-stats-modal');
        if (!container || !techData) return;

        container.innerHTML = techData.technologies.map(tech => `
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">${tech.name}</h4>
                    <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 text-xs font-medium px-2.5 py-0.5 rounded">
                        ${tech.count} ${tech.count === 1 ? 'projekt' : 'projekt'}
                    </span>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <p class="font-medium mb-1">Används i:</p>
                    <ul class="list-disc list-inside space-y-1">
                        ${tech.projects.map(project => `<li class="text-gray-700 dark:text-gray-300">${project}</li>`).join('')}
                    </ul>
                </div>
            </div>
        `).join('');
    }
});
</script>
@endsection
