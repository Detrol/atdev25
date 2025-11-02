{{-- Footer Component --}}
<footer class="bg-gray-900 dark:bg-gray-950 text-gray-300 py-12 mt-auto border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Kolumn 1: Om --}}
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">ATDev</h3>
                <p class="text-sm text-gray-400 leading-relaxed">
                    AI-driven webbutveckling med 20+ års erfarenhet.
                    Premiumkvalitet till en bråkdel av priset.
                </p>
            </div>

            {{-- Kolumn 2: Snabblänkar --}}
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Snabblänkar</h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="/#projects" class="text-gray-400 hover:text-blue-400 transition-colors">
                            Projekt
                        </a>
                    </li>
                    <li>
                        <a href="/#services" class="text-gray-400 hover:text-blue-400 transition-colors">
                            Tjänster
                        </a>
                    </li>
                    <li>
                        <a href="/#contact" class="text-gray-400 hover:text-blue-400 transition-colors">
                            Kontakt
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gdpr.showcase') }}" class="text-gray-400 hover:text-blue-400 transition-colors">
                            GDPR Showcase
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Kolumn 3: Juridiskt & Integritet --}}
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Juridiskt & Integritet</h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('gdpr.privacy') }}" class="text-gray-400 hover:text-blue-400 transition-colors">
                            Integritetspolicy
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gdpr.cookies') }}" class="text-gray-400 hover:text-blue-400 transition-colors">
                            Cookie-policy
                        </a>
                    </li>
                    <li>
                        <button @click="window.dispatchEvent(new CustomEvent('open-cookie-banner'))"
                                class="text-gray-400 hover:text-blue-400 transition-colors text-left">
                            Cookie-inställningar
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-8 pt-8 border-t border-gray-800 text-center text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} ATDev - Andreas Thun. Alla rättigheter förbehållna.</p>
        </div>
    </div>
</footer>
