{{-- Cookie Consent Banner - Slide-in från höger (matchar AI chatbot design) --}}
<div x-data="cookieConsent"
     x-show="showBanner"
     x-transition:enter="transform transition ease-out duration-300"
     x-transition:enter-start="translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transform transition ease-in duration-200"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="translate-x-full"
     class="fixed z-50 max-w-md w-full bottom-4 left-4 right-4 md:right-0 md:left-auto md:top-1/2 md:-translate-y-1/2 md:bottom-auto md:mr-6 md:mx-0"
     style="display: none;">

    {{-- Glassmorphism Container (matchar AI chatbot) --}}
    <div class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Cookie-inställningar</h3>
                </div>
                <button @click="closeBanner()"
                        class="text-white/80 hover:text-white transition-colors"
                        aria-label="Stäng cookie-inställningar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Content --}}
        <div class="px-6 py-5 max-h-[60vh] md:max-h-[70vh] overflow-y-auto">
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                Vi använder cookies för att förbättra din upplevelse. Du kan välja vilka kategorier av cookies du vill tillåta.
            </p>

            {{-- Showcase Notice --}}
            <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded">
                <p class="text-xs text-blue-900 dark:text-blue-300">
                    <strong>💡 Showcase:</strong> Denna GDPR-lösning kan jag skapa för din webbplats också!
                    Komplett cookie-hantering, integritetspolicies och GDPR-compliance.
                </p>
            </div>

            {{-- Cookie Categories --}}
            <div class="space-y-3">
                {{-- Essential Cookies (Always On) --}}
                <label class="flex items-start space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                    <input type="checkbox"
                           id="cookie-essential"
                           checked
                           disabled
                           aria-label="Nödvändiga cookies - Alltid aktiva"
                           class="mt-1 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Nödvändiga</span>
                            <span class="text-xs px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full">
                                Alltid aktiv
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            Krävs för att webbplatsen ska fungera (CSRF-skydd, sessioner)
                        </p>
                    </div>
                </label>

                {{-- Functional Cookies --}}
                <label class="flex items-start space-x-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <input type="checkbox"
                           id="cookie-functional"
                           x-model="preferences.functional"
                           class="mt-1 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <div class="flex-1">
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Funktionella</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            Sparar dina preferenser (mörkt läge, chat-historik)
                        </p>
                    </div>
                </label>

                {{-- Analytics Cookies --}}
                <label class="flex items-start space-x-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <input type="checkbox"
                           id="cookie-analytics"
                           x-model="preferences.analytics"
                           class="mt-1 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <div class="flex-1">
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Analys</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            Hjälper oss förstå hur du använder webbplatsen
                        </p>
                    </div>
                </label>

                {{-- Marketing Cookies --}}
                <label class="flex items-start space-x-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <input type="checkbox"
                           id="cookie-marketing"
                           x-model="preferences.marketing"
                           class="mt-1 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <div class="flex-1">
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Marknadsföring</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            Används för att visa relevanta annonser
                        </p>
                    </div>
                </label>
            </div>

            {{-- Links --}}
            <div class="mt-4 flex items-center justify-center space-x-4 text-xs text-gray-600 dark:text-gray-400">
                <a href="{{ route('gdpr.privacy') }}" class="hover:text-blue-600 dark:hover:text-blue-400 underline">
                    Integritetspolicy
                </a>
                <span>•</span>
                <a href="{{ route('gdpr.cookies') }}" class="hover:text-blue-600 dark:hover:text-blue-400 underline">
                    Cookie-policy
                </a>
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-2 gap-3">
                <button @click="saveChoices()"
                        class="px-4 py-3 md:py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Spara val
                </button>
                <button @click="acceptAll()"
                        class="px-4 py-3 md:py-2.5 bg-green-700 hover:bg-green-800 text-white text-sm font-medium rounded-lg transition-colors">
                    Acceptera alla
                </button>
            </div>
            <button @click="rejectAll()"
                    class="w-full mt-2 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors">
                Endast nödvändiga
            </button>
        </div>
    </div>
</div>
