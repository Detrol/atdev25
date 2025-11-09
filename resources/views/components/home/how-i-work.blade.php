{{-- How I Work Section Component --}}

<x-animated-section
    id="hur-jag-jobbar"
    theme="blue-pink"
    next-theme="green-teal"
    pattern="circles-dots"
    scroll-mode="layered-scroll"
>
    <div class="max-w-6xl mx-auto px-6 py-24">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="hiw-title text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent mb-4">
                Hur Jag Jobbar
            </h2>
            <p class="hiw-subtitle text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                En tydlig process från idé till lansering – tillsammans når vi målet
            </p>
        </div>

        <!-- Process Steps -->
        <div class="mb-16 relative" x-data="{
            expandedStep: null,
            toggleStep(step) {
                this.expandedStep = this.expandedStep === step ? null : step;
            }
        }">
            <!-- Progress Line (connecting steps) -->
            <div class="progress-line absolute left-[45px] md:left-[63px] top-16 bottom-16 w-1 bg-gradient-to-b from-blue-500 via-purple-500 to-green-500 rounded-full opacity-30 hidden md:block"></div>

            <div class="space-y-6 relative">
                <!-- Step 1: Discovery & Planning -->
                <div class="process-step group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-900/50 dark:to-gray-900/70 border border-blue-200 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/20">

                <div @click="toggleStep(1)" class="cursor-pointer p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-6">
                            <!-- Step Number -->
                            <div class="step-number w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-2xl font-bold text-white">1</span>
                            </div>

                            <!-- Step Title -->
                            <div>
                                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2">Discovery & Planering</h3>
                                <p class="text-gray-600 dark:text-gray-400">Vi kartlägger dina behov och skapar en tydlig roadmap</p>
                            </div>
                        </div>

                        <!-- Expand Icon -->
                        <div class="ml-4">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 transition-transform duration-300"
                                 :class="expandedStep === 1 ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Expanded Content -->
                <div x-show="expandedStep === 1"
                     x-collapse
                     class="px-8 pb-8">
                    <div class="grid md:grid-cols-3 gap-4 pt-4 border-t border-blue-200 dark:border-gray-700">
                        <!-- Substep 1 -->
                        <div class="substep p-4 bg-white/60 dark:bg-gray-900/40 backdrop-blur-sm rounded-xl">
                            <div class="substep-icon w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Klientintervju</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Vi går igenom dina behov, målgrupp och vision för projektet.</p>
                        </div>

                        <!-- Substep 2 -->
                        <div class="substep p-4 bg-white/60 dark:bg-gray-900/40 backdrop-blur-sm rounded-xl">
                            <div class="substep-icon w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Kravanalys</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Detaljerad specifikation av funktioner, teknisk approach och tidslinje.</p>
                        </div>

                        <!-- Substep 3 -->
                        <div class="substep p-4 bg-white/60 dark:bg-gray-900/40 backdrop-blur-sm rounded-xl">
                            <div class="substep-icon w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Prisättning</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Småjobb (&lt;5k) utan förskott. Större projekt får fast offert med tydligt scope och leveransdatum.</p>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Step 2: Development & Iteration -->
                <div class="process-step group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-gray-900/50 dark:to-gray-900/70 border border-purple-200 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl hover:shadow-purple-500/20">

                <div @click="toggleStep(2)" class="cursor-pointer p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-6">
                            <div class="step-number w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-2xl font-bold text-white">2</span>
                            </div>

                            <div>
                                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2">Utveckling & Iteration</h3>
                                <p class="text-gray-600 dark:text-gray-400">Agil utveckling med kontinuerlig feedback och AI-assistans</p>
                            </div>
                        </div>

                        <div class="ml-4">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 transition-transform duration-300"
                                 :class="expandedStep === 2 ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div x-show="expandedStep === 2"
                     x-collapse
                     class="px-8 pb-8">
                    <div class="grid md:grid-cols-4 gap-4 pt-4 border-t border-purple-200 dark:border-gray-700">
                        <div class="substep p-4 bg-white/60 dark:bg-gray-900/40 backdrop-blur-sm rounded-xl">
                            <div class="substep-icon w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Prototyp</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Wireframes och mockups för tidig validering.</p>
                        </div>

                        <div class="substep p-4 bg-white/60 dark:bg-gray-900/40 backdrop-blur-sm rounded-xl">
                            <div class="substep-icon w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Kodning</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Clean code med Laravel best practices.</p>
                        </div>

                        <div class="substep p-4 bg-white/60 dark:bg-gray-900/40 backdrop-blur-sm rounded-xl">
                            <div class="substep-icon w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">AI-Assistans</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">60% snabbare genom smart automation.</p>
                        </div>

                        <div class="substep p-4 bg-white/60 dark:bg-gray-900/40 backdrop-blur-sm rounded-xl">
                            <div class="substep-icon w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Feedback</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Veckovisa demos och kontinuerlig dialog.</p>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Step 3: Launch & Support -->
                <div class="process-step group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-50 to-green-100 dark:from-gray-900/50 dark:to-gray-900/70 border border-green-200 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl hover:shadow-green-500/20">

                <div @click="toggleStep(3)" class="cursor-pointer p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-6">
                            <div class="step-number w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-2xl font-bold text-white">3</span>
                            </div>

                            <div>
                                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2">Lansering & Support</h3>
                                <p class="text-gray-600 dark:text-gray-400">Smidig deploy och 30 dagars bugfix-garanti</p>
                            </div>
                        </div>

                        <div class="ml-4">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400 transition-transform duration-300"
                                 :class="expandedStep === 3 ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div x-show="expandedStep === 3"
                     x-collapse
                     class="px-8 pb-8">
                    <div class="grid md:grid-cols-4 gap-4 pt-4 border-t border-green-200 dark:border-gray-700">
                        <div class="substep p-4 bg-white/60 dark:bg-gray-900/40 backdrop-blur-sm rounded-xl">
                            <div class="substep-icon w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Testing</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Manuell och automatiserad QA innan release.</p>
                        </div>

                        <div class="substep p-4 bg-white/60 dark:bg-gray-900/40 backdrop-blur-sm rounded-xl">
                            <div class="substep-icon w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Deploy</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Smidig lansering till din hosting.</p>
                        </div>

                        <div class="substep p-4 bg-white/60 dark:bg-gray-900/40 backdrop-blur-sm rounded-xl">
                            <div class="substep-icon w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">30-Dagars Garanti</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Bugfixes ingår första månaden.</p>
                        </div>

                        <div class="substep p-4 bg-white/60 dark:bg-gray-900/40 backdrop-blur-sm rounded-xl">
                            <div class="substep-icon w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Underhåll</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Frivilliga support-avtal för långsiktighet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Values Grid -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-16 mt-12">Mina Värderingar</h2>
            <div class="values-grid grid md:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Value 1 -->
                <div class="value-card group p-6 bg-white/80 dark:bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Budgetvänlig</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">AI-driven effektivitet ger premiumkvalitet till en bråkdel av priset.</p>
                </div>

                <!-- Value 2 -->
                <div class="value-card group p-6 bg-white/80 dark:bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-400 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Målorienterad</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Deadlines hålls och leveranser sker i tid. När jag sätter ett mål får jag saker gjort.</p>
                </div>

                <!-- Value 3 -->
                <div class="value-card group p-6 bg-white/80 dark:bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-green-500 dark:hover:border-green-400 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Transparent</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Inga dolda kostnader eller överraskningar. Du vet alltid läget i projektet.</p>
                </div>

                <!-- Value 4 -->
                <div class="value-card group p-6 bg-white/80 dark:bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-pink-500 dark:hover:border-pink-400 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Problemlösare</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">20+ års erfarenhet av att hitta lösningar, inte ursäkter.</p>
                </div>

            </div>
        </div>

        <!-- Additional Services -->
        <div>
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-8">Tilläggstjänster</h2>
            <div class="services-grid grid md:grid-cols-3 gap-6">

                <!-- Service 1: Hosting -->
                <div class="service-card group p-8 bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900/70 dark:to-gray-900/50 rounded-2xl border border-blue-200 dark:border-gray-700 hover:shadow-2xl hover:shadow-blue-500/20 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Managed Hosting</h4>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Snabb och säker hosting på min optimerade plattform. Perfekt för Laravel-applikationer med allt förinstallerat.</p>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="service-checkmark w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            99.9% uptime garanti
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="service-checkmark w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Dagliga backups
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="service-checkmark w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            SSL-certifikat inkluderat
                        </li>
                    </ul>
                </div>

                <!-- Service 2: Email -->
                <div class="service-card group p-8 bg-gradient-to-br from-purple-50 to-pink-100 dark:from-gray-900/70 dark:to-gray-900/50 rounded-2xl border border-purple-200 dark:border-gray-700 hover:shadow-2xl hover:shadow-purple-500/20 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">E-post Setup</h4>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Professionell företags-email via Google Workspace. Jag hjälper dig sätta upp allt.</p>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="service-checkmark w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Google Workspace integration
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="service-checkmark w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Egna domän-adresser
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="service-checkmark w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Komplett konfiguration
                        </li>
                    </ul>
                </div>

                <!-- Service 3: Support -->
                <div class="service-card group p-8 bg-gradient-to-br from-green-50 to-teal-100 dark:from-gray-900/70 dark:to-gray-900/50 rounded-2xl border border-green-200 dark:border-gray-700 hover:shadow-2xl hover:shadow-green-500/20 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Support & Underhåll</h4>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Löpande support, säkerhetsuppdateringar och feature-utveckling efter lansering.</p>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="service-checkmark w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Månatligt underhåll
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="service-checkmark w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Säkerhetspatchar
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="service-checkmark w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Prioriterad support
                        </li>
                    </ul>
                </div>

            </div>
        </div>

    </div>
</x-animated-section>
