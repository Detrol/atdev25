<section id="price-calculator" class="relative py-24 bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-hidden">
    <div class="relative z-10 max-w-4xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-12" x-data="{ visible: false }" x-intersect="visible = true; if(window.GA4) GA4.trackCalculatorView()">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-full shadow-lg mb-4" x-show="visible" x-transition>
                <span class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></span>
                <span class="text-sm font-semibold text-purple-600 dark:text-purple-400">AI-Driven Prisestimering</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4" x-show="visible" x-transition>
                Smart Priskalkylator
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto" x-show="visible" x-transition>
                Beskriv ditt projekt – AI analyserar och räknar ut pris & leveranstid
            </p>
        </div>

        <!-- Calculator -->
        <div x-data="priceCalculator()" class="space-y-8">

            <!-- Input Section -->
            <div class="relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-2xl border border-gray-200 dark:border-gray-700">
                <div class="mb-6">
                    <label for="service-category" class="block text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        Välj tjänstekategori:
                    </label>
                    <select
                        id="service-category"
                        x-model="serviceCategory"
                        @change="updatePlaceholder(); if(window.GA4) GA4.trackCalculatorService(serviceCategory)"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 rounded-2xl border-2 border-gray-300 dark:border-gray-600 focus:border-purple-500 dark:focus:border-purple-400 focus:ring-4 focus:ring-purple-500/20 transition-all text-gray-900 dark:text-white mb-4"
                    >
                        <option value="">-- Välj en tjänst --</option>
                        <option value="web_development">Webbutveckling från Grunden</option>
                        <option value="mobile_app">Mobilapputveckling</option>
                        <option value="bug_fixes">Buggfix och Felsökning</option>
                        <option value="performance">Prestandaoptimering</option>
                        <option value="api_integration">API-utveckling och Integration</option>
                        <option value="security">Säkerhet och Compliance</option>
                        <option value="maintenance">Underhåll och Support</option>
                        <option value="modernization">Modernisering och Uppgradering</option>
                    </select>
                </div>

                <!-- Website URL (shown for relevant categories) -->
                <div x-show="shouldShowWebsiteField()" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mb-6">
                    <label for="website-url" class="block text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        <span x-text="websiteFieldLabel()"></span> <span class="text-gray-500 text-base font-normal">(Valfritt)</span>
                    </label>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3" x-text="websiteFieldDescription()"></p>
                    <input
                        type="text"
                        id="website-url"
                        x-model="websiteUrl"
                        @input="error = null"
                        placeholder="exempel.se eller https://exempel.se"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 rounded-2xl border-2 border-gray-300 dark:border-gray-600 focus:border-purple-500 dark:focus:border-purple-400 focus:ring-4 focus:ring-purple-500/20 transition-all text-gray-900 dark:text-white placeholder-gray-400"
                    />
                </div>

                <div class="mb-6">
                    <label for="project-description" class="block text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        Beskriv ditt projekt:
                    </label>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Berätta vad du vill bygga, vilka funktioner du behöver, och eventuella specifika krav. Ju mer detaljer, desto bättre estimering!
                    </p>
                    <textarea
                        id="project-description"
                        x-model="description"
                        @input="error = null"
                        @focus="if(window.GA4) GA4.trackCalculatorInput()"
                        rows="6"
                        :placeholder="placeholder"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 rounded-2xl border-2 border-gray-300 dark:border-gray-600 focus:border-purple-500 dark:focus:border-purple-400 focus:ring-4 focus:ring-purple-500/20 transition-all resize-none text-gray-900 dark:text-white placeholder-gray-400"
                        :class="{ 'border-red-500 dark:border-red-400': error }"
                    ></textarea>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-sm" :class="description.length < 20 ? 'text-gray-600 dark:text-gray-400' : (description.length > 1800 ? 'text-orange-600' : 'text-green-600')">
                            <span x-text="description.length"></span> / 2000 tecken
                            <span x-show="description.length < 20" class="text-gray-600 dark:text-gray-400">(minst 20 tecken krävs)</span>
                        </span>
                    </div>
                </div>

                <!-- Error Message -->
                <div x-show="error" x-transition class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-red-700 dark:text-red-300 text-sm" x-text="error"></p>
                    </div>
                </div>

                <!-- reCAPTCHA v3 Security Verification (Invisible) -->
                <x-recaptcha />

                <button
                    @click="estimate()"
                    :disabled="loading || !serviceCategory || description.length < 20 || description.length > 2000"
                    class="w-full py-5 px-8 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 text-white rounded-2xl font-bold text-lg shadow-2xl hover:shadow-purple-500/50 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center gap-3"
                >
                    <template x-if="!loading">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span>Analysera & Beräkna Pris</span>
                        </div>
                    </template>
                    <template x-if="loading">
                        <div class="flex items-center gap-3">
                            <svg class="animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-show="websiteUrl">AI analyserar webbplats & projekt...</span>
                            <span x-show="!websiteUrl">AI analyserar ditt projekt...</span>
                        </div>
                    </template>
                </button>
            </div>

            <!-- Results Section (shown after estimation) -->
            <div id="price-results" x-show="result" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">

                <!-- Project Analysis Card -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-2xl border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Projektanalys</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">AI-genererad estimering</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <!-- Project Type -->
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-2xl p-4">
                            <p class="text-sm font-semibold text-purple-600 dark:text-purple-400 mb-1">Projekttyp</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white" x-text="result?.project_type_label"></p>
                        </div>

                        <!-- Complexity -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-4">
                            <p class="text-sm font-semibold text-blue-600 dark:text-blue-400 mb-1">Komplexitet</p>
                            <div class="flex items-center gap-2">
                                <span class="text-2xl font-bold text-gray-900 dark:text-white" x-text="result?.complexity"></span>
                                <span class="text-gray-500 dark:text-gray-400">/10</span>
                                <div class="flex-1 ml-2">
                                    <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-green-500 via-yellow-500 to-red-500 rounded-full transition-all duration-500" :style="`width: ${(result?.complexity || 0) * 10}%`"></div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2" x-text="result?.complexity_label"></p>
                        </div>
                    </div>

                    <!-- Key Features -->
                    <div x-show="result?.key_features?.length > 0">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Identifierade Huvudfunktioner:</p>
                        <ul class="grid md:grid-cols-2 gap-2">
                            <template x-for="feature in result?.key_features || []" :key="feature">
                                <li class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span x-text="feature"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <!-- Recommended Solution Approach -->
                    <div x-show="result?.solution_approach" class="mt-6 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl p-6 border-2 border-green-200 dark:border-green-800">
                        <div class="flex items-center gap-3 mb-3">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            <p class="text-base font-bold text-green-800 dark:text-green-300">Rekommenderad Teknisk Lösning</p>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line" x-text="result?.solution_approach"></p>
                    </div>
                </div>

                <!-- Price Comparison -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Traditional Development -->
                    <div class="bg-gray-100 dark:bg-gray-700/50 rounded-3xl p-6 border-2 border-gray-300 dark:border-gray-600">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h4 class="font-bold text-gray-700 dark:text-gray-300">Traditionell Utveckling</h4>
                        </div>
                        <div class="space-y-3 text-gray-700 dark:text-gray-300">
                            <div class="flex justify-between items-center">
                                <span class="text-sm">Arbetstid:</span>
                                <span class="font-semibold" x-text="result?.hours_traditional"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm">Leverans:</span>
                                <span class="font-semibold" x-text="result?.delivery_weeks_traditional"></span>
                            </div>
                            <div class="pt-3 border-t border-gray-300 dark:border-gray-600">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm">Pris (exkl. moms):</span>
                                    <span class="font-bold text-lg" x-text="result?.price_traditional"></span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span>Inkl. moms (25%):</span>
                                    <span class="font-semibold" x-text="result?.price_traditional_vat"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- AI-Driven Development -->
                    <div class="relative bg-gradient-to-br from-purple-600 via-blue-600 to-pink-600 text-white rounded-3xl p-6 shadow-2xl border-2 border-purple-400">
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold" x-text="result ? ('-' + (result.savings_percent || 80) + '%') : ''"></span>
                        </div>
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <h4 class="font-bold">ATDev</h4>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-white/90">Arbetstid:</span>
                                <span class="font-semibold" x-text="result?.hours_ai"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-white/90">Leverans:</span>
                                <span class="font-semibold" x-text="result?.delivery_weeks_ai"></span>
                            </div>
                            <div class="pt-3 border-t border-white/30">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm text-white/90">Pris (exkl. moms):</span>
                                    <span class="font-bold text-xl" x-text="result?.price_ai"></span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-white/90">Inkl. moms (25%):</span>
                                    <span class="font-semibold" x-text="result?.price_ai_vat"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Savings Highlight -->
                        <div class="mt-4 pt-4 border-t border-white/30">
                            <p class="text-sm text-white/90 mb-2">Din besparing:</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-bold" x-text="result?.savings"></span>
                                <span class="text-white/80" x-text="result ? ('(' + (result.savings_percent || 80) + '%)') : ''"></span>
                            </div>
                            <p class="text-xs text-white/70 mt-1">Tack vare AI & automation</p>
                        </div>
                    </div>
                </div>

                <!-- AI Explanation (Expandable) -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-200 dark:border-blue-800" x-data="{ open: false }">
                    <button @click="open = !open; if(window.GA4) GA4.trackCalculatorAIExpand()" class="w-full px-6 py-4 flex items-center justify-between text-left">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold text-blue-800 dark:text-blue-300">Hur sparar AI upp till 80% av tiden?</span>
                        </div>
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="px-6 pb-4 text-sm text-gray-700 dark:text-gray-300 space-y-2">
                        <p class="font-medium mb-3">Med 20+ års erfarenhet och moderna AI-verktyg levererar jag samma kvalitet på en bråkdel av tiden:</p>
                        <ul class="space-y-2 pl-5">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span><strong>GitHub Copilot & Claude Code</strong> – AI skriver 40-60% av koden med min expertis som guide</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span><strong>Automation</strong> – Testing, deployment och dokumentation automatiseras</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span><strong>Erfarenhet</strong> – 20+ år betyder jag vet exakt vad som ska byggas, inga omvägar</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span><strong>Modern stack</strong> – Beprövade ramverk (Laravel, Vue.js) med bästa praxis inbyggt</span>
                            </li>
                        </ul>
                        <p class="mt-4 text-xs text-gray-600 dark:text-gray-400">
                            <strong>Research-backat:</strong> McKinsey, GitHub och Google rapporterar liknande produktivitetsökningar (30-60%) med AI-verktyg.
                        </p>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="text-center pt-4">
                    <button @click="bookConsultation()" class="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-2xl font-bold text-lg shadow-2xl hover:shadow-purple-500/50 transition-all hover:scale-105 active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Boka Gratis Konsultation
                    </button>
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        Vi går igenom din exakta situation och skapar en detaljerad projektplan
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
