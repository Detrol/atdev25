{{-- Timeline & Stats Section Component --}}

<section id="expertis" class="relative py-24 bg-gradient-to-b from-white via-gray-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-hidden">
    <div class="relative z-10 max-w-6xl mx-auto px-6">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 bg-clip-text text-transparent mb-4" data-lazy="fade-in">
                Expertis & Innovation
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto" data-lazy="fade-in" data-delay="100">
                Över två decennier av webbutveckling kombinerat med modern teknologi
            </p>
        </div>

        <!-- Timeline Card (Featured) -->
        <div class="mb-8" data-lazy="fade-in" data-delay="200">
            <div class="relative group overflow-hidden rounded-3xl p-8 md:p-12 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 shadow-2xl hover:shadow-purple-500/50 transition-all duration-500">
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
                        <div class="timeline-icon hidden md:flex absolute -top-20 left-[12.5%] -translate-x-1/2 items-center justify-center">
                            <div class="bg-white/95 dark:bg-gray-800/90 backdrop-blur-sm p-4 rounded-2xl shadow-2xl border-2 border-orange-500 dark:border-orange-400 animate-[sway_2s_ease-in-out_infinite]">
                                <img src="{{ asset('images/icons/html5.svg') }}" alt="HTML5" width="32" height="32" class="w-8 h-8 drop-shadow-lg">
                            </div>
                        </div>
                        <div class="timeline-icon hidden md:flex absolute -top-20 left-[37.5%] -translate-x-1/2 items-center justify-center">
                            <div class="bg-white/95 dark:bg-gray-800/90 backdrop-blur-sm p-4 rounded-2xl shadow-2xl border-2 border-purple-600 dark:border-purple-400 animate-[sway_2s_ease-in-out_infinite]">
                                <img src="{{ asset('images/icons/php.svg') }}" alt="PHP" width="32" height="32" class="w-8 h-8 drop-shadow-lg">
                            </div>
                        </div>
                        <div class="timeline-icon hidden md:flex absolute -top-20 left-[62.5%] -translate-x-1/2 items-center justify-center">
                            <div class="bg-white/95 dark:bg-gray-800/90 backdrop-blur-sm p-4 rounded-2xl shadow-2xl border-2 border-red-600 dark:border-red-400 animate-[sway_2s_ease-in-out_infinite]">
                                <img src="{{ asset('images/icons/laravel.svg') }}" alt="Laravel" width="32" height="32" class="w-8 h-8 drop-shadow-lg">
                            </div>
                        </div>
                        <div class="timeline-icon hidden md:flex absolute -top-20 left-[87.5%] -translate-x-1/2 items-center justify-center">
                            <div class="bg-white/95 dark:bg-gray-800/90 backdrop-blur-sm p-4 rounded-2xl shadow-2xl border-2 border-amber-600 dark:border-amber-400 animate-[sway_2s_ease-in-out_infinite]">
                                <img src="{{ asset('images/icons/anthropic.svg') }}" alt="Anthropic" width="32" height="32" class="w-8 h-8 drop-shadow-lg">
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="hidden md:block h-2 bg-white/20 rounded-full overflow-hidden mb-4">
                            <div class="timeline-progress h-full bg-white/90 rounded-full w-0"></div>
                        </div>

                        <!-- Milestones - Desktop -->
                        <div class="hidden md:grid grid-cols-4 gap-4">
                            <div class="timeline-milestone text-center">
                                <div class="text-2xl font-bold text-white mb-1">2001</div>
                                <div class="text-white/80 text-sm">HTML & ASP</div>
                            </div>
                            <div class="timeline-milestone text-center">
                                <div class="text-2xl font-bold text-white mb-1">2004</div>
                                <div class="text-white/80 text-sm">PHP & MySQL</div>
                            </div>
                            <div class="timeline-milestone text-center">
                                <div class="text-2xl font-bold text-white mb-1">2014</div>
                                <div class="text-white/80 text-sm">Laravel</div>
                            </div>
                            <div class="timeline-milestone text-center">
                                <div class="text-2xl font-bold text-white mb-1">2023</div>
                                <div class="text-white/80 text-sm">AI Integration</div>
                            </div>
                        </div>

                        <!-- Milestones - Mobile -->
                        <div class="md:hidden grid grid-cols-2 gap-4">
                            @foreach([
                                ['year' => 2001, 'tech' => 'HTML & ASP', 'step' => 0, 'color' => 'orange', 'icon' => 'html5'],
                                ['year' => 2004, 'tech' => 'PHP & MySQL', 'step' => 1, 'color' => 'purple', 'icon' => 'php'],
                                ['year' => 2014, 'tech' => 'Laravel', 'step' => 2, 'color' => 'red', 'icon' => 'laravel'],
                                ['year' => 2023, 'tech' => 'AI Integration', 'step' => 3, 'color' => 'amber', 'icon' => 'anthropic']
                            ] as $milestone)
                            <div class="mobile-milestone relative rounded-xl p-4 transition-all duration-400 bg-white/10 dark:bg-white/5">
                                <div class="text-2xl font-bold text-white mb-1">{{ $milestone['year'] }}</div>
                                <div class="text-white/80 text-sm">{{ $milestone['tech'] }}</div>
                                <div class="mobile-milestone-icon absolute -top-3 -right-3">
                                    <div class="bg-white/95 dark:bg-gray-800/90 backdrop-blur-sm p-2 rounded-xl shadow-2xl border-2 border-{{ $milestone['color'] }}-500 dark:border-{{ $milestone['color'] }}-400 animate-[sway_1.8s_ease-in-out_infinite]">
                                        <img src="{{ asset('images/icons/' . $milestone['icon'] . '.svg') }}" alt="{{ $milestone['tech'] }}" width="40" height="40" class="w-10 h-10 drop-shadow-lg">
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
