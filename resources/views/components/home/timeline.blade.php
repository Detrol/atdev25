{{-- Timeline & Stats Section Component --}}

<section id="expertis" class="relative py-24 bg-gradient-to-b from-white via-gray-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-hidden">
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
            showHtml: false,
            showPhp: false,
            showLaravel: false,
            showAi: false,
            mobileCurrentStep: -1
        }" x-intersect="visible = true" x-init="
            $watch('visible', value => {
                if (value) {
                    setTimeout(() => {
                        // Trigger smooth CSS animation
                        progress = 100;

                        // Show icons when progress bar reaches each year position (and keep them visible)
                        setTimeout(() => { showHtml = true; }, 625);
                        setTimeout(() => { showPhp = true; }, 1875);
                        setTimeout(() => { showLaravel = true; }, 3125);
                        setTimeout(() => { showAi = true; }, 4375);

                        // Mobile animation
                        if (window.innerWidth < 768) {
                            setTimeout(() => { mobileCurrentStep = 0; }, 0);
                            setTimeout(() => { mobileCurrentStep = 1; }, 1250);
                            setTimeout(() => { mobileCurrentStep = 2; }, 2500);
                            setTimeout(() => { mobileCurrentStep = 3; }, 3750);
                        }
                    }, 800);
                }
            });
        ">
            <div class="relative group overflow-hidden rounded-3xl p-8 md:p-12 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 shadow-2xl hover:shadow-purple-500/50 transition-all duration-500" x-show="visible" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-20">
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
                        <!-- Tech Icons - Desktop -->
                        <div class="hidden md:flex absolute -top-20 left-[12.5%] -translate-x-1/2 items-center justify-center" x-show="showHtml" x-transition:enter="transition-all ease-out duration-600" x-transition:enter-start="opacity-0 scale-50 translate-y-20" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-cloak>
                            <div class="bg-white/95 backdrop-blur-sm p-4 rounded-2xl shadow-2xl border-2 border-orange-500 animate-[sway_2s_ease-in-out_infinite]">
                                <img src="https://cdn.simpleicons.org/html5/E34F26" alt="HTML5" class="w-8 h-8 drop-shadow-lg">
                            </div>
                        </div>
                        <div class="hidden md:flex absolute -top-20 left-[37.5%] -translate-x-1/2 items-center justify-center" x-show="showPhp" x-transition:enter="transition-all ease-out duration-600" x-transition:enter-start="opacity-0 scale-50 translate-y-20" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-cloak>
                            <div class="bg-white/95 backdrop-blur-sm p-4 rounded-2xl shadow-2xl border-2 border-purple-600 animate-[sway_2s_ease-in-out_infinite]">
                                <img src="https://cdn.simpleicons.org/php/777BB4" alt="PHP" class="w-8 h-8 drop-shadow-lg">
                            </div>
                        </div>
                        <div class="hidden md:flex absolute -top-20 left-[62.5%] -translate-x-1/2 items-center justify-center" x-show="showLaravel" x-transition:enter="transition-all ease-out duration-600" x-transition:enter-start="opacity-0 scale-50 translate-y-20" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-cloak>
                            <div class="bg-white/95 backdrop-blur-sm p-4 rounded-2xl shadow-2xl border-2 border-red-600 animate-[sway_2s_ease-in-out_infinite]">
                                <img src="https://cdn.simpleicons.org/laravel/FF2D20" alt="Laravel" class="w-8 h-8 drop-shadow-lg">
                            </div>
                        </div>
                        <div class="hidden md:flex absolute -top-20 left-[87.5%] -translate-x-1/2 items-center justify-center" x-show="showAi" x-transition:enter="transition-all ease-out duration-600" x-transition:enter-start="opacity-0 scale-50 translate-y-20" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-cloak>
                            <div class="bg-white/95 backdrop-blur-sm p-4 rounded-2xl shadow-2xl border-2 border-amber-600 animate-[sway_2s_ease-in-out_infinite]">
                                <img src="https://cdn.simpleicons.org/anthropic/CC9B7A" alt="Anthropic" class="w-8 h-8 drop-shadow-lg">
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="hidden md:block h-2 bg-white/20 rounded-full overflow-hidden mb-4">
                            <div class="h-full bg-white/90 rounded-full transition-all duration-[5000ms] ease-linear" :style="`width: ${progress}%`"></div>
                        </div>

                        <!-- Milestones - Desktop -->
                        <div class="hidden md:grid grid-cols-4 gap-4">
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

                        <!-- Milestones - Mobile -->
                        <div class="md:hidden grid grid-cols-2 gap-4">
                            @foreach([
                                ['year' => 2004, 'tech' => 'HTML & ASP', 'step' => 0, 'color' => 'orange', 'icon' => 'html5/E34F26'],
                                ['year' => 2010, 'tech' => 'PHP & MySQL', 'step' => 1, 'color' => 'purple', 'icon' => 'php/777BB4'],
                                ['year' => 2017, 'tech' => 'Laravel', 'step' => 2, 'color' => 'red', 'icon' => 'laravel/FF2D20'],
                                ['year' => 2023, 'tech' => 'AI Integration', 'step' => 3, 'color' => 'amber', 'icon' => 'anthropic/CC9B7A']
                            ] as $milestone)
                            <div class="relative rounded-xl p-4 transition-all duration-400"
                                 :class="mobileCurrentStep >= {{ $milestone['step'] }} ? 'bg-white/30 ring-2 ring-{{ $milestone['color'] }}-400 shadow-lg shadow-{{ $milestone['color'] }}-500/50' : 'bg-white/10'"
                                 x-show="visible"
                                 x-transition:enter="transition ease-out duration-500 delay-{{ 300 + ($milestone['step'] * 200) }}"
                                 x-transition:enter-start="opacity-0 translate-y-4"
                                 x-transition:enter-end="opacity-100 translate-y-0">
                                <div class="text-2xl font-bold text-white mb-1 transition-all" :class="mobileCurrentStep >= {{ $milestone['step'] }} ? 'opacity-100 scale-110' : 'opacity-80 scale-100'">{{ $milestone['year'] }}</div>
                                <div class="text-white/80 text-sm transition-opacity" :class="mobileCurrentStep >= {{ $milestone['step'] }} ? 'opacity-100' : 'opacity-60'">{{ $milestone['tech'] }}</div>
                                <div class="absolute -top-3 -right-3" x-show="mobileCurrentStep >= {{ $milestone['step'] }}" x-transition:enter="transition-all ease-out duration-500" x-transition:enter-start="opacity-0 scale-0 rotate-45" x-transition:enter-end="opacity-100 scale-100 rotate-0" x-cloak>
                                    <div class="bg-white/95 backdrop-blur-sm p-2 rounded-xl shadow-2xl border-2 border-{{ $milestone['color'] }}-500 animate-[sway_1.8s_ease-in-out_infinite]">
                                        <img src="https://cdn.simpleicons.org/{{ $milestone['icon'] }}" alt="{{ $milestone['tech'] }}" class="w-10 h-10 drop-shadow-lg">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        @include('components.home.stats')
    </div>
</section>
