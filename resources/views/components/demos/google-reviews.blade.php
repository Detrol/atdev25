<section id="google-reviews" class="relative min-h-screen bg-gradient-to-br from-amber-50 via-yellow-50 to-orange-50 dark:from-gray-900 dark:via-amber-900/20 dark:to-orange-900/20 overflow-hidden py-32 border-b-4 border-amber-200/50 dark:border-amber-500/20">
    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-1/2 -left-1/4 w-96 h-96 bg-amber-200/30 dark:bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/4 -right-1/4 w-96 h-96 bg-orange-200/30 dark:bg-orange-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-1/2 left-1/3 w-96 h-96 bg-yellow-200/20 dark:bg-yellow-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-16" x-data="{ visible: false }" x-intersect="visible = true">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 rounded-full text-sm font-medium mb-6"
                 x-show="visible"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span>Google Reviews Integration</span>
            </div>

            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4"
                x-show="visible"
                x-transition:enter="transition ease-out duration-500 delay-100"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0">
                Automatisk Recensionsvisare
            </h2>

            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"
               x-show="visible"
               x-transition:enter="transition ease-out duration-500 delay-200"
               x-transition:enter-start="opacity-0 -translate-y-4"
               x-transition:enter-end="opacity-100 translate-y-0">
                Visa dina Google-recensioner automatiskt på hemsidan. Öka trovärdigheten med sociala bevis från riktiga kunder.
            </p>
        </div>

        <!-- Demo Content -->
        <div x-data="googleReviewsDemo()" x-init="init()" class="space-y-8">
            <!-- Search Interface -->
            <div class="max-w-2xl mx-auto">
                <div class="glass-morph p-6 rounded-2xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 text-center">
                        Prova med ditt eget företag
                    </h3>

                    <div class="flex gap-3">
                        <input type="text"
                               x-model="searchQuery"
                               @keyup.enter="searchPlace()"
                               placeholder="Sök efter företagsnamn..."
                               class="flex-1 px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-amber-500 dark:focus:border-amber-400 focus:outline-none transition-colors">

                        <button @click="searchPlace()"
                                :disabled="loading || !searchQuery.trim()"
                                class="px-6 py-3 bg-amber-500 hover:bg-amber-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white rounded-xl font-semibold transition-colors flex items-center gap-2">
                            <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" x-cloak>
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="loading ? 'Söker...' : 'Sök'"></span>
                        </button>
                    </div>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 text-center">
                        Standard: <button @click="loadDefaultPlace()" class="text-amber-600 dark:text-amber-400 hover:underline font-medium">Puts i Karlstad</button>
                    </p>
                </div>
            </div>

            <!-- Error Message -->
            <div x-show="error" x-transition class="max-w-2xl mx-auto" x-cloak>
                <div class="bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 px-6 py-4 rounded-xl">
                    <p x-text="error"></p>
                </div>
            </div>

            <!-- Search Results -->
            <div x-show="searchResults.length > 0 && !currentPlace" x-transition class="max-w-4xl mx-auto grid md:grid-cols-2 gap-4" x-cloak>
                <template x-for="result in searchResults" :key="result.place_id">
                    <div @click="selectPlace(result.place_id)"
                         class="glass-morph p-5 rounded-xl cursor-pointer hover:shadow-xl hover:scale-[1.02] transition-all">
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2" x-text="result.name"></h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3" x-text="result.formatted_address"></p>
                        <div class="flex items-center gap-2">
                            <div class="flex text-yellow-400" x-html="renderStars(result.rating)"></div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="result.rating"></span>
                            <span class="text-sm text-gray-500 dark:text-gray-400" x-text="`(${result.user_ratings_total} recensioner)`"></span>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Reviews Display -->
            <div x-show="currentPlace && !loading" x-transition class="space-y-8" x-cloak>
                <!-- Place Header -->
                <div class="glass-morph p-8 rounded-2xl text-center">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2" x-text="currentPlace.name"></h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4" x-text="currentPlace.formatted_address"></p>

                    <div class="flex items-center justify-center gap-3 mb-4">
                        <div class="flex text-yellow-400 text-2xl" x-html="renderStars(currentPlace.rating)"></div>
                        <span class="text-3xl font-bold text-gray-900 dark:text-white" x-text="currentPlace.rating"></span>
                        <span class="text-gray-600 dark:text-gray-400">/  5.0</span>
                    </div>

                    <p class="text-gray-600 dark:text-gray-400">
                        Baserat på <span class="font-semibold" x-text="currentPlace.user_ratings_total"></span> recensioner från Google
                    </p>

                    <template x-if="cacheInfo">
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-3">
                            Data cachad: <span x-text="cacheInfo"></span>
                        </p>
                    </template>
                </div>

                <!-- Rating Filters -->
                <div class="flex flex-wrap justify-center gap-2">
                    <button @click="filterByRating(null)"
                            :class="ratingFilter === null ? 'bg-amber-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                        Alla
                    </button>
                    <template x-for="rating in [5, 4, 3, 2, 1]" :key="rating">
                        <button @click="filterByRating(rating)"
                                :class="ratingFilter === rating ? 'bg-amber-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'"
                                class="px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                            <span x-html="renderStars(rating)"></span>
                        </button>
                    </template>
                </div>

                <!-- Reviews Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="review in displayedReviews" :key="review.time">
                        <div class="glass-morph p-6 rounded-xl hover:shadow-lg transition-shadow">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white font-bold flex-shrink-0"
                                     x-text="getInitials(review.author_name)">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h5 class="font-semibold text-gray-900 dark:text-white truncate" x-text="review.author_name"></h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="review.relative_time_description"></p>
                                </div>
                            </div>

                            <div class="flex text-yellow-400 mb-3" x-html="renderStars(review.rating)"></div>

                            <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed" x-text="review.text"></p>
                        </div>
                    </template>
                </div>

                <!-- Pagination -->
                <div x-show="totalPages > 1" class="flex items-center justify-center gap-4" x-cloak>
                    <button @click="previousPage()"
                            :disabled="currentPage === 1"
                            class="px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        ← Föregående
                    </button>

                    <span class="text-gray-600 dark:text-gray-400 font-medium">
                        Sida <span x-text="currentPage"></span> av <span x-text="totalPages"></span>
                    </span>

                    <button @click="nextPage()"
                            :disabled="currentPage === totalPages"
                            class="px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Nästa →
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="loading && !currentPlace" class="flex flex-col items-center justify-center py-16" x-cloak>
                <div class="w-16 h-16 border-4 border-amber-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                <p class="text-gray-600 dark:text-gray-400 font-medium">Hämtar recensioner från Google...</p>
            </div>
        </div>

        <!-- Use Cases & CTA -->
        <div class="mt-20 glass-morph p-8 rounded-2xl">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Perfekt för</h3>

            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <div class="text-center">
                    <div class="w-14 h-14 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Restauranger & Caféer</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Visa kundomdömen direkt på hemsidan</p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Butiker & E-handel</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Bygg förtroende med riktiga recensioner</p>
                </div>

                <div class="text-center">
                    <div class="w-14 h-14 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Tjänsteföretag</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Automatiska uppdateringar dagligen</p>
                </div>
            </div>

            <div class="text-center">
                <a href="/#contact" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl font-semibold text-lg transition-all hover:scale-105 shadow-lg">
                    <span>Vill du ha detta på din hemsida?</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
