{{-- Smart Menu with AI Allergen Analysis --}}
<section id="smart-menu" class="relative min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 dark:from-gray-900 dark:via-green-900/20 dark:to-emerald-900/20 py-32 overflow-hidden border-b-4 border-green-200/50 dark:border-green-500/20">
    <!-- Background decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute w-96 h-96 bg-green-300/20 dark:bg-green-500/10 rounded-full blur-3xl -top-48 -left-48"></div>
        <div class="absolute w-96 h-96 bg-blue-300/20 dark:bg-blue-500/10 rounded-full blur-3xl top-1/2 -right-48"></div>
        <div class="absolute w-96 h-96 bg-emerald-300/20 dark:bg-emerald-500/10 rounded-full blur-3xl -bottom-48 left-1/2"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 z-10">
        <!-- Header -->
        <div class="text-center mb-16" x-data="{ visible: false }" x-intersect="visible = true">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500/10 to-blue-500/10 dark:from-green-500/20 dark:to-blue-500/20 rounded-full mb-6 backdrop-blur-sm border border-green-200 dark:border-green-800" x-show="visible" x-transition.opacity.duration.800ms>
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="text-sm font-semibold text-green-700 dark:text-green-300">AI-Driven Menyanalys</span>
            </div>

            <h2 class="text-5xl md:text-6xl font-bold mb-6 text-gray-900 dark:text-white" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Smart Meny med
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-600 via-blue-600 to-emerald-600">AI-allergener</span>
            </h2>

            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                AI identifierar allergener automatiskt från matbeskrivningar. Perfekt för restauranger, caféer och cateringföretag.
            </p>
        </div>

        <!-- Main Demo -->
        <div x-data="smartMenuData(@js($demo['sample_dishes']))" x-init="init()" class="mb-16">
            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Left: Dish Selector -->
                <div class="space-y-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Välj en rätt att analysera</h3>

                    <div class="space-y-3">
                        <template x-for="(dish, index) in dishes" :key="dish.id">
                            <button
                                @click="selectDish(index)"
                                class="w-full text-left glass-morph rounded-xl p-4 transition-all duration-300 hover:shadow-lg group"
                                :class="selectedDishIndex === index ? 'ring-4 ring-green-500 dark:ring-green-400 bg-green-50/50 dark:bg-green-900/20' : 'hover:bg-white/50 dark:hover:bg-gray-800/50'"
                            >
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="font-bold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors" x-text="dish.name"></h4>
                                            <span
                                                class="text-xs px-2 py-0.5 rounded-full"
                                                :class="getCategoryColor(dish.category)"
                                                x-text="dish.category"
                                            ></span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2" x-text="dish.description"></p>
                                    </div>

                                    <!-- Selected indicator -->
                                    <div x-show="selectedDishIndex === index" class="flex-shrink-0">
                                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Right: AI Analysis Panel -->
                <div class="glass-morph rounded-2xl p-8 h-fit sticky top-24">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">AI-Allergenanalys</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Claude AI analyserar ingredienser och identifierar allergener</p>
                    </div>

                    <!-- Selected dish display -->
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                        <div class="flex items-center gap-2 mb-2">
                            <h4 class="font-bold text-gray-900 dark:text-white" x-text="selectedDish.name"></h4>
                            <span
                                class="text-xs px-2 py-0.5 rounded-full"
                                :class="getCategoryColor(selectedDish.category)"
                                x-text="selectedDish.category"
                            ></span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="selectedDish.description"></p>
                    </div>

                    <!-- Analyze button -->
                    <button
                        @click="analyzeDish()"
                        :disabled="analyzing"
                        class="w-full py-4 px-6 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-semibold hover:shadow-xl hover:shadow-green-500/30 transition-all hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center gap-2 mb-6"
                    >
                        <template x-if="!analyzing">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </template>
                        <template x-if="analyzing">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="analyzing ? 'Analyserar...' : 'Analysera med AI'"></span>
                    </button>

                    <!-- Error message -->
                    <div x-show="error" x-transition class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-red-800 dark:text-red-300 mb-1">Fel vid analys</p>
                                <p class="text-sm text-red-700 dark:text-red-400" x-text="error"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Analysis results -->
                    <div x-show="hasAnalysis" x-transition class="space-y-6">
                        <!-- Allergens -->
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <span>Identifierade Allergener</span>
                                <span class="text-sm px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 rounded-full" x-text="allergenCount"></span>
                            </h4>

                            <template x-if="allergenCount === 0">
                                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800">
                                    <p class="text-sm text-green-800 dark:text-green-300 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Inga allergener identifierades!</span>
                                    </p>
                                </div>
                            </template>

                            <template x-if="allergenCount > 0">
                                <div class="space-y-2">
                                    <template x-for="allergen in analysisResult.allergens" :key="allergen.allergen">
                                        <div
                                            class="p-3 rounded-xl border transition-all hover:shadow-md"
                                            :class="getConfidenceColor(allergen.confidence)"
                                        >
                                            <div class="flex items-start gap-3">
                                                <span class="text-2xl" x-text="allergen.icon"></span>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <h5 class="font-bold" x-text="allergen.name"></h5>
                                                        <span class="text-xs px-2 py-0.5 bg-white/50 rounded-full" x-text="getConfidenceLabel(allergen.confidence)"></span>
                                                    </div>
                                                    <p class="text-sm opacity-90" x-text="allergen.reason"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Dietary info badges -->
                        <template x-if="getDietaryBadges().length > 0">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white mb-3">Dietinformation</h4>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="badge in getDietaryBadges()" :key="badge.label">
                                        <div
                                            class="px-3 py-1.5 rounded-full text-sm font-semibold flex items-center gap-1.5"
                                            :class="badge.color"
                                        >
                                            <span x-text="badge.icon"></span>
                                            <span x-text="badge.label"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Notes -->
                        <template x-if="analysisResult && analysisResult.notes">
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                                <p class="text-sm text-blue-800 dark:text-blue-300" x-text="analysisResult.notes"></p>
                            </div>
                        </template>
                    </div>

                    <!-- Placeholder when no analysis -->
                    <div x-show="!hasAnalysis && !analyzing && !error" x-transition class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Välj en rätt och klicka "Analysera med AI" för att se allergener</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Use Cases & CTA -->
        <div class="glass-morph p-8 rounded-2xl">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Perfekt för</h3>

            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <div class="text-center">
                    <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Restauranger & Caféer</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Visa allergener automatiskt för varje rätt</p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Cateringfirmor</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">AI-driven allergendetektion för events</p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Skolkök & Offentlig Sektor</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Håll koll på allergener för alla måltider</p>
                </div>
            </div>

            <div class="text-center">
                <a href="/#contact" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl font-semibold text-lg transition-all hover:scale-105 shadow-lg">
                    <span>Vill du ha detta på din hemsida?</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
