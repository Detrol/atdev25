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
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Nej, jag bygger skräddarsydda lösningar med Laravel istället för WordPress. Detta ger bättre prestanda, säkerhet och flexibilitet. Om du behöver kunna redigera innehåll själv bygger jag ett intuitivt admin-gränssnitt specifikt för dina behov - ofta enklare och snabbare än WordPress.
                    </p>
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
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Tack vare moderna AI-verktyg kan jag idag arbeta effektivt med nästan vilket programmeringsspråk som helst. Mina primära verktyg är Laravel (PHP), Vue, Alpine och React, men jag anpassar tech-stacken efter projektets behov. AI-assistans gör att jag snabbt kan leverera högkvalitativ kod även i mindre vanliga språk.
                    </p>
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
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Använd <a href="#" @click.prevent="document.querySelector('.price-calculator')?.scrollIntoView({ behavior: 'smooth' })" class="text-purple-600 dark:text-purple-400 hover:underline font-semibold">priskalkylatorn</a> på denna sida och beskriv ditt projekt så detaljerat som möjligt! Ju mer information du ger om funktioner, integrationer och komplexitet, desto mer exakt blir uppskattningen. Kalkylatorn använder AI för att analysera dina behov och ge en realistisk estimering direkt.
                    </p>
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
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Jag hjälper dig med grunderna i både SEO och GDPR. Alla webbplatser jag bygger är SEO-optimerade från start med rätt metatagg, semantisk HTML och snabba laddningstider. För GDPR implementerar jag cookie-hantering och privacy policy. Dock bör du alltid konsultera ett advokatbyrå angående GDPR för att vara säker på att din webbplats drivs enligt juridiska villkor - detta är inget jag tar ansvar för.
                    </p>
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
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Ja! Tack vare AI-verktyg kan jag idag hjälpa till med både grafisk design och logotypskapande. Jag arbetar gärna kreativt tillsammans med dig för att ta fram en visuell identitet som matchar din verksamhet. Allt från mockups till färdiga designkoncept ingår i processen. Om du föredrar en professionell grafisk designer kan jag även rekommendera <a href="https://www.fiverr.com" target="_blank" rel="noopener" class="text-purple-600 dark:text-purple-400 hover:underline">Fiverr</a>, där du kan hitta designers till bra priser.
                    </p>
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
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Ja! Jag bygger ett skräddarsytt admin-gränssnitt där du enkelt kan redigera texter, bilder och innehåll. Gränssnittet anpassas efter exakt vad DU behöver kunna ändra - inget krångel med WordPress-widgets eller plugin. Enkel utbildning ingår vid leverans.
                    </p>
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
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Ja! Jag hyr en kraftfull VPS som jag har förkonfigurerat för optimal prestanda och säkerhet. Här kan jag erbjuda både utrymme och support för ett fast paketpris varje månad. Bonus: Om du hyr hosting hos mig ingår framtida support alltid - du behöver aldrig betala extra för bugfixar eller hjälp. Alternativt hjälper jag dig sätta upp på andra tjänster som DigitalOcean eller AWS, plus domän, SSL-certifikat och e-postkonfiguration.
                    </p>
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
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Vanligtvis delar vi upp betalningen i två eller tre delar: 30-50% vid projektstart, 30-40% vid godkänd demo/preview, och resterande vid slutleverans. Exakta villkor diskuterar vi baserat på projektets storlek. Du faktureras via mitt egenanställningsföretag CoolCompany, som tar 6% avgift för deras tjänster - detta läggs på totalkostnaden. Om du är ett företag utanför Sverige behöver jag även ditt momsnummer. Betalning via Swish eller banköverföring.
                    </p>
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
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Inte så mycket! Vi börjar med en genomgång där du berättar om dina mål, målgrupp och önskade funktioner. Ha gärna tillgång till eventuella grafiska profiler, logotyper, exempelwebbplatser du gillar och textinnehåll. Men oroa dig inte - vi kan ta fram allt detta tillsammans om det saknas.
                    </p>
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
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14">
                        Ja, mindre justeringar och bugfixar de första 30 dagarna efter lansering ingår alltid. För att förtydliga: <strong>Support</strong> innebär att fixa saker som inte fungerar (bugfixar, tekniska problem, e-postproblem etc.) - detta ingår om du hyr hosting hos mig. Att lägga till nya funktioner eller ytterligare kod räknas dock som vidareutveckling och kräver separat offert. För löpande support och säkerhetsuppdateringar erbjuder jag flexibla underhållsavtal. Jag svarar alltid inom 24 timmar!
                    </p>
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
