{{-- Projects Section Component --}}
@props(["projects"])

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
