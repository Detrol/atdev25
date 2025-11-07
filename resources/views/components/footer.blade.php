{{-- Footer Component --}}
<footer role="contentinfo" aria-label="Sidfot" class="bg-gray-900 dark:bg-gray-950 text-gray-300 py-12 mt-auto border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Kolumn 1: Om --}}
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">ATDev</h3>
                <p class="text-sm text-gray-400 leading-relaxed mb-4">
                    AI-driven webbutveckling med 20+ års erfarenhet.
                    Premiumkvalitet till en bråkdel av priset.
                </p>

                {{-- Social Links --}}
                @if($profile && ($profile->github || $profile->linkedin || $profile->twitter))
                <div class="flex items-center gap-3 mt-4">
                    @if($profile->github)
                    <a href="{{ $profile->github }}" target="_blank" rel="noopener noreferrer"
                       onclick="if(window.GA4) GA4.trackExternalLink('{{ $profile->github }}', 'social')"
                       class="w-10 h-10 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center transition-colors group"
                       aria-label="GitHub">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                    @endif

                    @if($profile->linkedin)
                    <a href="{{ $profile->linkedin }}" target="_blank" rel="noopener noreferrer"
                       onclick="if(window.GA4) GA4.trackExternalLink('{{ $profile->linkedin }}', 'social')"
                       class="w-10 h-10 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center transition-colors group"
                       aria-label="LinkedIn">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    @endif

                    @if($profile->twitter)
                    <a href="{{ $profile->twitter }}" target="_blank" rel="noopener noreferrer"
                       onclick="if(window.GA4) GA4.trackExternalLink('{{ $profile->twitter }}', 'social')"
                       class="w-10 h-10 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center transition-colors group"
                       aria-label="Twitter/X">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    @endif
                </div>
                @endif
            </div>

            {{-- Kolumn 2: Snabblänkar --}}
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Snabblänkar</h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="/#projects" onclick="if(window.GA4) GA4.trackFooterLink('projects')" class="text-gray-400 hover:text-blue-400 transition-colors">
                            Projekt
                        </a>
                    </li>
                    <li>
                        <a href="/#services" onclick="if(window.GA4) GA4.trackFooterLink('services')" class="text-gray-400 hover:text-blue-400 transition-colors">
                            Tjänster
                        </a>
                    </li>
                    <li>
                        <a href="/#contact" onclick="if(window.GA4) GA4.trackFooterLink('contact')" class="text-gray-400 hover:text-blue-400 transition-colors">
                            Kontakt
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gdpr.showcase') }}" onclick="if(window.GA4) GA4.trackFooterLink('gdpr-showcase')" class="text-gray-400 hover:text-blue-400 transition-colors">
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
                        <a href="{{ route('gdpr.privacy') }}" onclick="if(window.GA4) GA4.trackFooterLink('privacy-policy')" class="text-gray-400 hover:text-blue-400 transition-colors">
                            Integritetspolicy
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gdpr.cookies') }}" onclick="if(window.GA4) GA4.trackFooterLink('cookie-policy')" class="text-gray-400 hover:text-blue-400 transition-colors">
                            Cookie-policy
                        </a>
                    </li>
                    <li>
                        <button @click="window.dispatchEvent(new CustomEvent('open-cookie-banner')); if(window.GA4) GA4.trackFooterLink('cookie-settings')"
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
