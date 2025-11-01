{{-- Before/After Image Slider Demo Section --}}
<section id="before-after-slider" class="relative min-h-screen bg-gradient-to-br from-blue-50 via-cyan-50 to-sky-50 dark:from-gray-800 dark:via-blue-900/20 dark:to-cyan-900/20 overflow-hidden py-32 border-b-4 border-blue-200/50 dark:border-blue-500/20">
    <!-- Background decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute w-96 h-96 bg-blue-300/20 dark:bg-blue-600/10 rounded-full blur-3xl -top-48 -right-48"></div>
        <div class="absolute w-96 h-96 bg-purple-300/20 dark:bg-purple-600/10 rounded-full blur-3xl -bottom-48 -left-48"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-16" x-data="{ visible: false }" x-intersect="visible = true">
            <div x-show="visible" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-full text-sm font-semibold mb-6 shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Interaktiv Jämförelse</span>
                </div>

                <h2 class="text-4xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                    Före/Efter Slider
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto leading-relaxed">
                    Visa transformationer interaktivt. Dra i slidern för att jämföra före- och efterbilder sida vid sida.
                </p>
            </div>
        </div>

        <!-- Main Slider Component -->
        <div x-data="beforeAfterSliderData(@js($demo['examples']))" x-init="init()" @destroy="destroy()" class="space-y-12">
            <!-- Slider Container -->
            <div class="grid lg:grid-cols-3 gap-8 items-start">
                <!-- Slider Viewer (2/3) -->
                <div class="lg:col-span-2">
                    <div class="glass-morph rounded-3xl overflow-hidden shadow-2xl">
                        <!-- Main Slider -->
                        <div
                            x-ref="sliderContainer"
                            class="relative select-none touch-none cursor-ew-resize"
                            style="aspect-ratio: 16/10;"
                            @mousedown="startDrag($event)"
                            @touchstart="startDrag($event)"
                            @keydown="handleKeyboard($event)"
                            tabindex="0"
                            role="slider"
                            aria-label="Före/efter jämförelse slider"
                            :aria-valuenow="Math.round(sliderPosition)"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        >
                            <!-- Before Image (full width background) -->
                            <div class="absolute inset-0 bg-gray-100 dark:bg-gray-800">
                                <img
                                    :src="selectedExample.beforeImage"
                                    :alt="'Före: ' + selectedExample.title"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                    onerror="this.parentElement.innerHTML='<div class=\'flex items-center justify-center h-full text-gray-400\'><svg class=\'w-16 h-16\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg></div>'"
                                >
                            </div>

                            <!-- After Image (clipped by slider position) -->
                            <div
                                class="absolute inset-0 overflow-hidden pointer-events-none"
                                :style="`clip-path: inset(0 ${100 - sliderPosition}% 0 0)`"
                            >
                                <img
                                    :src="selectedExample.afterImage"
                                    :alt="'Efter: ' + selectedExample.title"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                    onerror="this.parentElement.innerHTML='<div class=\'flex items-center justify-center h-full text-gray-400\'><svg class=\'w-16 h-16\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg></div>'"
                                >
                            </div>

                            <!-- Labels -->
                            <div class="absolute top-4 left-4 px-4 py-2 bg-black/30 backdrop-blur-md text-white rounded-full font-bold text-sm pointer-events-none">
                                FÖRE
                            </div>
                            <div class="absolute top-4 right-4 px-4 py-2 bg-black/30 backdrop-blur-md text-white rounded-full font-bold text-sm pointer-events-none">
                                EFTER
                            </div>

                            <!-- Slider Handle -->
                            <div
                                class="absolute inset-y-0 flex items-center justify-center pointer-events-none"
                                :style="`left: calc(${sliderPosition}% - 24px)`"
                                style="width: 48px; z-index: 10;"
                            >
                                <!-- Vertical line -->
                                <div class="absolute inset-y-0 left-1/2 transform -translate-x-1/2 w-1 bg-white shadow-2xl"></div>

                                <!-- Center button -->
                                <div
                                    class="relative w-12 h-12 bg-white/95 dark:bg-white/90 backdrop-blur-md rounded-full shadow-2xl flex items-center justify-center transition-transform"
                                    :class="isDragging ? 'scale-110' : 'hover:scale-110'"
                                >
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Controls -->
                        <div class="p-6 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <!-- Reset button -->
                                <button @click="resetPosition()" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Återställ till mitten</span>
                                </button>

                                <!-- Position indicator -->
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Position: <span class="font-semibold" x-text="Math.round(sliderPosition) + '%'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="mt-6 grid md:grid-cols-3 gap-4">
                        <div class="flex items-start gap-3 p-4 glass-morph rounded-xl">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm">Dra</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Klicka och dra slidern</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 glass-morph rounded-xl">
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm">Touch</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Svep på mobil/platta</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 glass-morph rounded-xl">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm">Tangentbord</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Använd piltangenterna</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Example Info & Selection (1/3) -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Current Example Info -->
                    <div class="glass-morph rounded-2xl p-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3" x-text="selectedExample.title"></h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed" x-text="selectedExample.description"></p>

                        <!-- Category -->
                        <div class="mb-6">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Kategori:</span>
                            <div class="mt-1">
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full font-medium text-sm" x-text="selectedExample.category"></span>
                            </div>
                        </div>

                        <!-- Use Cases -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Perfekt för:</p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="useCase in selectedExample.useCases" :key="useCase">
                                    <span class="text-xs px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full" x-text="useCase"></span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Example Gallery -->
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Välj Exempel</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <template x-for="(example, index) in examples" :key="example.id">
                                <button
                                    @click="selectExample(index)"
                                    class="group relative rounded-xl overflow-hidden transition-all duration-300 hover:shadow-lg"
                                    :class="selectedExampleIndex === index ? 'ring-4 ring-blue-500 dark:ring-blue-400' : 'ring-1 ring-gray-200 dark:ring-gray-700'"
                                >
                                    <!-- Split preview thumbnail -->
                                    <div class="aspect-video relative bg-gray-100 dark:bg-gray-800">
                                        <img :src="example.beforeImage" :alt="'Före: ' + example.title" class="absolute inset-0 w-1/2 h-full object-cover" loading="lazy" onerror="this.style.display='none'">
                                        <img :src="example.afterImage" :alt="'Efter: ' + example.title" class="absolute inset-0 left-1/2 w-1/2 h-full object-cover" loading="lazy" onerror="this.style.display='none'">

                                        <!-- Center divider -->
                                        <div class="absolute inset-y-0 left-1/2 w-0.5 bg-white/70 shadow"></div>
                                    </div>

                                    <!-- Title overlay -->
                                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 to-transparent p-2">
                                        <p class="text-white text-xs font-semibold truncate" x-text="example.title"></p>
                                    </div>

                                    <!-- Selected indicator -->
                                    <div x-show="selectedExampleIndex === index" class="absolute top-2 right-2 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Use Cases & CTA -->
            <div class="glass-morph p-8 rounded-2xl">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Perfekt för</h3>

                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Byggfirmor & Renovering</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Visa dramatiska förändringar före/efter projekt</p>
                    </div>

                    <div class="text-center">
                        <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Fotografer & Designers</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Demonstrera bildbehandling och retuschering</p>
                    </div>

                    <div class="text-center">
                        <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Skönhet & Frisörer</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Visa transformationer och resultat</p>
                    </div>
                </div>

                <div class="text-center">
                    <a href="/#contact" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl font-semibold text-lg transition-all hover:scale-105 shadow-lg">
                        <span>Vill du ha detta på din hemsida?</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
