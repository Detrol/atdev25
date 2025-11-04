{{-- FAQ Section Component --}}

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
                    <div class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14 space-y-4">
                        <p class="font-semibold text-gray-900 dark:text-white">Nej, jag bygger skräddarsydda lösningar med Laravel.</p>

                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2 text-sm">Varför inte WordPress?</h4>
                            <ul class="space-y-1.5 text-sm">
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <span><strong>Bättre prestanda</strong> - Snabbare laddningstider</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span><strong>Högre säkerhet</strong> - Färre sårbarheter</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                    </svg>
                                    <span><strong>Total flexibilitet</strong> - Exakt vad du behöver, inget annat</span>
                                </li>
                            </ul>
                        </div>

                        <p class="text-sm">
                            <strong>Behöver du redigera innehåll själv?</strong> Jag bygger ett intuitivt admin-gränssnitt specifikt för dina behov - ofta enklare och snabbare än WordPress.
                        </p>
                    </div>
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
                    <div class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14 space-y-4">
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2 text-sm">Mina primära verktyg:</h4>
                            <ul class="space-y-1.5 text-sm">
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span><strong>Laravel (PHP)</strong> - Backend-ramverk</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span><strong>Vue, Alpine & React</strong> - Frontend-ramverk</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span><strong>MySQL, PostgreSQL</strong> - Databaser</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span><strong>Tailwind CSS</strong> - Modern styling</span>
                                </li>
                            </ul>
                        </div>

                        <p class="text-sm bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-700/30">
                            <strong>AI-accelererad utveckling:</strong> Tack vare moderna AI-verktyg kan jag snabbt arbeta effektivt med nästan vilket programmeringsspråk som helst. Jag anpassar tech-stacken efter projektets behov och kan leverera högkvalitativ kod även i mindre vanliga språk.
                        </p>
                    </div>
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
                    <div class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14 space-y-4">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 border border-green-200 dark:border-green-700/30">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Snabbaste sättet: Använd AI-priskalkylatorn
                            </h4>
                            <p class="text-sm mb-3">
                                <a href="#" @click.prevent="document.querySelector('.price-calculator')?.scrollIntoView({ behavior: 'smooth' })" class="text-green-700 dark:text-green-300 hover:underline font-semibold">Priskalkylatorn</a> använder AI för att analysera dina behov och ge en realistisk estimering direkt - oftast inom några sekunder!
                            </p>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2 text-sm">För bästa resultat, beskriv:</h4>
                            <ul class="space-y-1.5 text-sm">
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span><strong>Vilka funktioner</strong> behöver webbplatsen? (inloggning, formulär, betalningar...)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
                                    </svg>
                                    <span><strong>Integrationer</strong> - Externa system som ska kopplas in</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span><strong>Användarroller</strong> - Behöver olika personer olika rättigheter?</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <span><strong>Design & komplexitet</strong> - Enkel/standard/avancerad design</span>
                                </li>
                            </ul>
                        </div>

                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            <strong>Tips:</strong> Ju mer detaljer, desto exaktare pris! Kalkylatorn är helt gratis att använda.
                        </p>
                    </div>
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
                    <div class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14 space-y-4">
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2 text-sm flex items-center gap-2">
                                <svg class="w-4 h-4 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                SEO-optimering ingår alltid:
                            </h4>
                            <ul class="space-y-1.5 text-sm ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-pink-600 dark:text-pink-400">•</span>
                                    <span>Korrekta metatagg (title, description, Open Graph)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-pink-600 dark:text-pink-400">•</span>
                                    <span>Semantisk HTML-struktur</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-pink-600 dark:text-pink-400">•</span>
                                    <span>Snabba laddningstider (Core Web Vitals)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-pink-600 dark:text-pink-400">•</span>
                                    <span>Mobilanpassad design</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2 text-sm flex items-center gap-2">
                                <svg class="w-4 h-4 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                GDPR-grundläggande:
                            </h4>
                            <ul class="space-y-1.5 text-sm ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-pink-600 dark:text-pink-400">•</span>
                                    <span>Cookie-consent banner</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-pink-600 dark:text-pink-400">•</span>
                                    <span>Integritetspolicy (Privacy Policy)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-pink-600 dark:text-pink-400">•</span>
                                    <span>Säker datahantering</span>
                                </li>
                            </ul>
                        </div>

                        <p class="text-xs bg-amber-50 dark:bg-amber-900/20 rounded-lg p-3 border border-amber-200 dark:border-amber-700/30">
                            <strong>OBS:</strong> För fullständig GDPR-compliance bör du alltid konsultera en jurist. Jag implementerar tekniska lösningar, men tar inget juridiskt ansvar.
                        </p>
                    </div>
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
                    <div class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14 space-y-4">
                        <p class="font-semibold text-gray-900 dark:text-white">Ja! Tack vare AI-verktyg kan jag hjälpa till med både design och logotyp.</p>

                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2 text-sm">Vad jag kan hjälpa till med:</h4>
                            <ul class="space-y-1.5 text-sm">
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-orange-600 dark:text-orange-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                    </svg>
                                    <span><strong>Logotypdesign</strong> med AI-assistans</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-orange-600 dark:text-orange-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                    </svg>
                                    <span><strong>Grafisk profil</strong> - Färgpalett och typsnitt</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-orange-600 dark:text-orange-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span><strong>Design-mockups</strong> innan utveckling</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-orange-600 dark:text-orange-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span><strong>Bildgenerering</strong> för hero-sektioner och innehåll</span>
                                </li>
                            </ul>
                        </div>

                        <p class="text-sm">
                            Vi arbetar kreativt tillsammans för att ta fram en visuell identitet som matchar din verksamhet. Processen är iterativ - vi justerar tills du är 100% nöjd.
                        </p>

                        <p class="text-xs text-gray-500 dark:text-gray-500">
                            <strong>Föredrar du en professionell designer?</strong> Jag kan rekommendera <a href="https://www.fiverr.com" target="_blank" rel="noopener" class="text-orange-600 dark:text-orange-400 hover:underline">Fiverr</a> där du hittar designers till bra priser.
                        </p>
                    </div>
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
                    <div class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14 space-y-4">
                        <p class="font-semibold text-gray-900 dark:text-white">Ja! Jag bygger ett skräddarsytt admin-gränssnitt specifikt för dina behov.</p>

                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2 text-sm">Vad du kan redigera själv:</h4>
                            <ul class="space-y-1.5 text-sm">
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span><strong>Texter</strong> - Rubriker, brödtext, beskrivningar</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span><strong>Bilder</strong> - Enkel upload med automatisk optimering</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span><strong>Innehåll</strong> - Produkter, tjänster, blogginlägg</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                    </svg>
                                    <span><strong>Inställningar</strong> - Allt du specifikt behöver ändra</span>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-3 border border-indigo-200 dark:border-indigo-700/30">
                            <p class="text-sm">
                                <strong>Fördelar:</strong> Inget krångel med WordPress-widgets eller komplicerade plugins. Gränssnittet är byggt exakt för vad DU behöver - enklare och snabbare att lära sig.
                            </p>
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-500">
                            <strong>Utbildning ingår:</strong> Vid leverans får du en genomgång av admin-gränssnittet så du känner dig trygg.
                        </p>
                    </div>
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
                    <div class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14 space-y-4">
                        <p class="font-semibold text-gray-900 dark:text-white">Ja! Jag erbjuder hosting på min kraftfulla VPS.</p>

                        <div class="bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-cyan-900/20 dark:to-blue-900/20 rounded-xl p-4 border border-cyan-200 dark:border-cyan-700/30">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                BONUS: Gratis livslång support
                            </h4>
                            <p class="text-sm">
                                Om du hyr hosting hos mig ingår framtida support <strong>alltid</strong> - du behöver aldrig betala extra för bugfixar eller teknisk hjälp!
                            </p>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2 text-sm">Vad ingår:</h4>
                            <ul class="space-y-1.5 text-sm">
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Förkonfigurerad VPS för optimal prestanda</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Säkerhetsuppdateringar och backups</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>SSL-certifikat (HTTPS)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Hjälp med domän och e-postkonfiguration</span>
                                </li>
                            </ul>
                        </div>

                        <p class="text-sm">
                            <strong>Egen hosting?</strong> Jag hjälper dig även sätta upp på DigitalOcean, AWS eller annan tjänst du föredrar.
                        </p>
                    </div>
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
                    <div class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14 space-y-6">
                        <!-- Småjobb -->
                        <div class="bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20 rounded-xl p-4 border border-teal-200 dark:border-teal-700/30">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                För småjobb (under 15 000 kr):
                            </h4>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Inget förskott krävs</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Betala först när du är 100% nöjd med leveransen</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Max 1 veckas leveranstid</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Perfekt för att testa hur vi jobbar tillsammans</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Större projekt -->
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">För större projekt (15 000+ kr):</h4>
                            <ul class="space-y-1.5 text-sm ml-2">
                                <li class="flex items-start gap-2">
                                    <span class="text-teal-600 dark:text-teal-400">•</span>
                                    <span>30-50% vid projektstart</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-teal-600 dark:text-teal-400">•</span>
                                    <span>30-40% vid godkänd demo/preview</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-teal-600 dark:text-teal-400">•</span>
                                    <span>Resterande vid slutleverans</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Alla projekt inkluderar -->
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">Alla projekt inkluderar:</h4>
                            <ul class="space-y-1.5 text-sm">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span>30-dagars bugfix-garanti</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span>Gratis livslång support om du hyr hosting hos mig</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>&lt;24h responstid</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Praktiskt -->
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm">
                                <strong>Praktiskt:</strong> Du faktureras via mitt egenanställningsföretag Frilans Finans, som tar 6% avgift för deras tjänster - detta läggs på totalkostnaden. Om du är ett företag utanför Sverige behöver jag även ditt momsnummer.
                            </p>
                        </div>
                    </div>
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
                    <div class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14 space-y-4">
                        <p class="font-semibold text-gray-900 dark:text-white">Inte så mycket! Vi börjar med en genomgång.</p>

                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2 text-sm">Bra att ha (men inte krav):</h4>
                            <ul class="space-y-1.5 text-sm">
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span><strong>Projektmål</strong> - Vad ska webbplatsen uppnå?</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span><strong>Målgrupp</strong> - Vilka ska använda siten?</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span><strong>Grafisk profil</strong> - Logotyp, färger, typsnitt (om finns)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    <span><strong>Inspiration</strong> - Exempel på webbplatser du gillar</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span><strong>Innehåll</strong> - Texter, bilder (vi kan skapa tillsammans)</span>
                                </li>
                            </ul>
                        </div>

                        <p class="text-sm bg-violet-50 dark:bg-violet-900/20 rounded-lg p-3 border border-violet-200 dark:border-violet-700/30">
                            <strong>Saknas något?</strong> Oroa dig inte - vi kan ta fram allt detta tillsammans under projektets gång!
                        </p>
                    </div>
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
                    <div class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14 space-y-4">
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 border border-green-200 dark:border-green-700/30">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                SUPPORT (ingår alltid):
                            </h4>
                            <ul class="space-y-1.5 text-sm">
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span><strong>30-dagars bugfix-garanti</strong> efter lansering</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span><strong>Gratis livslång support</strong> om du hyr hosting hos mig</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span><strong>&lt;24h responstid</strong> på alla frågor</span>
                                </li>
                            </ul>
                            <p class="text-xs mt-3 text-gray-700 dark:text-gray-300">
                                <em>Support = Fixa saker som inte fungerar (bugfixar, tekniska problem, e-postproblem etc.)</em>
                            </p>
                        </div>

                        <div class="bg-rose-50 dark:bg-rose-900/20 rounded-xl p-4 border border-rose-200 dark:border-rose-700/30">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                VIDAREUTVECKLING (separat offert):
                            </h4>
                            <ul class="space-y-1.5 text-sm">
                                <li class="flex items-start gap-2">
                                    <span class="text-rose-600 dark:text-rose-400">•</span>
                                    <span>Nya funktioner eller features</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-rose-600 dark:text-rose-400">•</span>
                                    <span>Designändringar eller omarbetning</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-rose-600 dark:text-rose-400">•</span>
                                    <span>Integration med nya tjänster</span>
                                </li>
                            </ul>
                            <p class="text-xs mt-3 text-gray-700 dark:text-gray-300">
                                <em>Vidareutveckling = Lägga till något nytt som inte fanns från början</em>
                            </p>
                        </div>

                        <p class="text-sm">
                            <strong>Flexibla underhållsavtal</strong> finns för löpande support och säkerhetsuppdateringar!
                        </p>
                    </div>
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
