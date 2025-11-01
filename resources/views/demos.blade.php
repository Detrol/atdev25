@extends('layouts.app')

@section('content')
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-6 focus:py-3 focus:bg-purple-600 focus:text-white focus:rounded-lg focus:shadow-lg">Hoppa till huvudinnehåll</a>

<nav class="fixed left-0 right-0 z-50 px-4 transition-all duration-300" role="navigation" aria-label="Huvudnavigation" x-data="{ mobileMenuOpen: false, scrolled: false, lastScroll: 0, showNav: true }" x-init="window.addEventListener('scroll', () => { const currentScroll = window.scrollY; scrolled = currentScroll > 50; if (currentScroll <= 0) { showNav = true; } else if (currentScroll > lastScroll) { showNav = false; } else { showNav = true; } lastScroll = currentScroll; })" :style="showNav ? 'top: 0;' : 'top: -100px;'">
    <div class="max-w-5xl mx-auto mt-6 px-8 py-4 flex justify-between items-center transition-all duration-300" :class="(scrolled && showNav) ? 'bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg rounded-full shadow-lg' : 'bg-white/10 dark:bg-gray-900/10 backdrop-blur-sm rounded-full'">
        <a href="/" class="text-2xl font-bold transition-all hover:scale-105" :class="(scrolled && showNav) ? 'text-gray-900 dark:text-white' : 'text-white'">AT<span :class="(scrolled && showNav) ? 'text-purple-600' : 'text-white/90'">Dev</span></a>
        <div class="hidden md:flex items-center gap-6">
            <a href="/" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">Hem</a>
            <a href="/#services" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">Tjänster</a>
            <a href="/#projects" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">Projekt</a>
            <a href="/demos" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-purple-600 dark:text-purple-400 font-semibold' : 'text-white font-semibold'">Demos</a>
            <a href="/#contact" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">Kontakt</a>
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); document.documentElement.classList.toggle('dark', darkMode)" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="document.documentElement.classList.toggle('dark', darkMode)" class="p-2 rounded-lg transition-all" :class="(scrolled && showNav) ? 'hover:bg-gray-100 dark:hover:bg-gray-800' : 'hover:bg-white/10'" aria-label="Toggla dark mode">
                <svg x-show="!darkMode" class="w-5 h-5" :class="(scrolled && showNav) ? 'text-gray-600' : 'text-white/80'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                <svg x-show="darkMode" class="w-5 h-5" :class="(scrolled && showNav) ? 'text-gray-600' : 'text-white/80'" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </button>
            @auth
            <a href="/admin" class="px-6 py-2 rounded-full transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'bg-purple-600 text-white hover:bg-purple-700' : 'bg-white/20 text-white hover:bg-white/30'">Admin</a>
            @endauth
        </div>
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg transition-all" :class="(scrolled && showNav) ? 'hover:bg-gray-100' : 'hover:bg-white/10'" aria-label="Toggle menu">
            <svg class="w-6 h-6" :class="(scrolled && showNav) ? 'text-gray-900 dark:text-white' : 'text-white'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path><path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" x-cloak></path></svg>
        </button>
    </div>
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="md:hidden mt-4 mx-4" x-cloak>
        <div class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg rounded-2xl shadow-2xl p-6 space-y-4">
            <a href="/" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">Hem</a>
            <a href="/#services" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">Tjänster</a>
            <a href="/#projects" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">Projekt</a>
            <a href="/demos" class="block py-2 text-purple-600 dark:text-purple-400 font-semibold transition-colors">Demos</a>
            <a href="/#contact" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">Kontakt</a>
            @auth
            <a href="/admin" class="block py-2 px-6 bg-purple-600 text-white rounded-full text-center hover:bg-purple-700 font-medium transition-colors">Admin</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="main-content" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 gradient-mesh"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/30 to-black/50"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 py-32 text-center text-white">
        <div class="space-y-8" x-data="{ visible: false }" x-intersect="visible = true" x-init="setTimeout(() => visible = true, 100)">
            <div class="inline-flex items-center gap-3 px-6 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 pulse-glow" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-100" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                </svg>
                <span class="text-sm font-medium">Fully Interactive</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-bold leading-tight" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Interactive Demos<br>
                <span class="text-white/90">Upplev Möjligheterna</span>
            </h1>

            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-500" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Prova våra interaktiva showcase-funktioner och se vad som är möjligt för din verksamhet. Inga registreringar, bara ren innovation.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-700" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <a href="#demos" class="px-8 py-4 bg-white text-purple-600 rounded-full font-semibold hover:bg-white/90 transition-all hover:scale-105 shadow-2xl inline-flex items-center gap-2">
                    <span>Börja Utforska</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <a href="/#contact" class="px-8 py-4 bg-white/10 backdrop-blur-md text-white rounded-full font-semibold hover:bg-white/20 transition-all border border-white/20">
                    Kontakta Mig
                </a>
            </div>

            <!-- Scroll indicator -->
            <div class="mt-16 animate-bounce" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <svg class="w-6 h-6 mx-auto text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- 3D/AR Product Viewer Demo Section -->
@if(isset($demos['product_viewer']) && $demos['product_viewer']['enabled'])
<section id="product-viewer" class="relative min-h-screen bg-gradient-to-br from-purple-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-hidden py-24">
    <!-- Background decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute w-96 h-96 bg-purple-300/20 dark:bg-purple-600/10 rounded-full blur-3xl -top-48 -left-48"></div>
        <div class="absolute w-96 h-96 bg-blue-300/20 dark:bg-blue-600/10 rounded-full blur-3xl -bottom-48 -right-48"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-16" x-data="{ visible: false }" x-intersect="visible = true">
            <div x-show="visible" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-full text-sm font-semibold mb-6 shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>3D/AR Showcase</span>
                </div>

                <h2 class="text-4xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                    3D Produktvisare med AR
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto leading-relaxed">
                    Se produkter i 3D direkt i webbläsaren eller använd AR för att placera dem i ditt eget rum via mobilen.
                </p>
            </div>
        </div>

        <!-- Main Viewer & Product Selection -->
        <div x-data="productViewerData()" x-init="init()" class="space-y-12">
            <!-- 3D Viewer Container -->
            <div class="grid lg:grid-cols-3 gap-8 items-start">
                <!-- Product Viewer (2/3) -->
                <div class="lg:col-span-2">
                    <div class="glass-morph rounded-3xl overflow-hidden shadow-2xl">
                        <!-- Viewer -->
                        <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900" style="aspect-ratio: 16/10;">
                            <!-- Model Viewer Component -->
                            <model-viewer
                                x-ref="modelViewer"
                                :src="selectedProduct.model"
                                :poster="selectedProduct.poster"
                                ar
                                ar-modes="webxr scene-viewer quick-look"
                                camera-controls
                                :auto-rotate="autoRotate"
                                shadow-intensity="1"
                                exposure="1"
                                environment-image="neutral"
                                :ar-scale="selectedProduct.arScale"
                                class="w-full h-full"
                                style="--poster-color: transparent;"
                            >
                                <!-- AR Button (appears on AR-capable devices) -->
                                <button slot="ar-button" class="absolute bottom-4 right-4 px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all hover:scale-105 inline-flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span>Visa i Ditt Rum</span>
                                </button>

                                <!-- Loading indicator -->
                                <div slot="poster" class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                                    <div class="text-center">
                                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-purple-600 border-t-transparent"></div>
                                        <p class="mt-4 text-gray-600 dark:text-gray-400">Laddar 3D-modell...</p>
                                    </div>
                                </div>
                            </model-viewer>

                            <!-- Loading overlay -->
                            <div x-show="modelLoading && !modelLoaded" class="absolute inset-0 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm flex items-center justify-center">
                                <div class="text-center">
                                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-purple-600 border-t-transparent"></div>
                                    <p class="mt-4 text-gray-700 dark:text-gray-300 font-medium">Laddar...</p>
                                </div>
                            </div>

                            <!-- Error state -->
                            <div x-show="modelError" class="absolute inset-0 bg-red-50 dark:bg-red-900/20 flex items-center justify-center p-6">
                                <div class="text-center">
                                    <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-red-700 dark:text-red-300 font-semibold mb-2">Kunde inte ladda 3D-modellen</p>
                                    <p class="text-sm text-red-600 dark:text-red-400">Detta är en demo. Ladda ner GLB-modeller enligt <code>public/models/README.md</code></p>
                                </div>
                            </div>
                        </div>

                        <!-- Controls -->
                        <div class="p-6 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <!-- Auto-rotate toggle -->
                                <button @click="toggleAutoRotate()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg transition-all" :class="autoRotate ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span class="text-sm font-medium" x-text="autoRotate ? 'Auto-rotera PÅ' : 'Auto-rotera AV'"></span>
                                </button>

                                <!-- Reset camera -->
                                <button @click="resetCamera()" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Återställ vy</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="mt-6 grid md:grid-cols-3 gap-4">
                        <div class="flex items-start gap-3 p-4 glass-morph rounded-xl">
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm">Rotera</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Dra med musen eller svep</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 glass-morph rounded-xl">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm">Zooma</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Scroll eller nyp</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 glass-morph rounded-xl">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm">AR (Mobil)</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Tryck "Visa i Ditt Rum"</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info & Selection (1/3) -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Current Product Info -->
                    <div class="glass-morph rounded-2xl p-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3" x-text="selectedProduct.name"></h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed" x-text="selectedProduct.description"></p>

                        <!-- Category & Dimensions -->
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center gap-2 text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Kategori:</span>
                                <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full font-medium" x-text="selectedProduct.category"></span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Dimensioner:</span>
                                <span class="text-gray-900 dark:text-white font-medium" x-text="selectedProduct.dimensions"></span>
                            </div>
                        </div>

                        <!-- Use Cases -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Perfekt för:</p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="useCase in selectedProduct.useCases" :key="useCase">
                                    <span class="text-xs px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full" x-text="useCase"></span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Product Gallery -->
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Välj Produkt</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <template x-for="(product, index) in products" :key="product.id">
                                <button
                                    @click="selectProduct(index)"
                                    class="group relative rounded-xl overflow-hidden transition-all duration-300 hover:shadow-lg"
                                    :class="selectedProductIndex === index ? 'ring-4 ring-purple-500 dark:ring-purple-400' : 'ring-1 ring-gray-200 dark:ring-gray-700'"
                                >
                                    <div class="aspect-square bg-gray-100 dark:bg-gray-800">
                                        <img :src="product.poster" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" onerror="this.parentElement.innerHTML='<div class=\'flex items-center justify-center h-full text-gray-400\'><svg class=\'w-12 h-12\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg></div>'">
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-3">
                                        <p class="text-white text-sm font-semibold" x-text="product.name"></p>
                                    </div>
                                    <div x-show="selectedProductIndex === index" class="absolute top-2 right-2 w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center">
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

            <!-- Bottom CTA -->
            <div class="text-center glass-morph rounded-2xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                    Vill du ha detta på din hemsida?
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-2xl mx-auto">
                    3D/AR-funktionalitet är perfekt för möbelbutiker, inredningsdesigners, gallerier och alla som säljer fysiska produkter online.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/#contact" class="px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-full font-semibold hover:shadow-xl hover:shadow-purple-500/30 transition-all hover:scale-105 inline-flex items-center justify-center gap-2">
                        <span>Få Ett Pris</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="#demos" class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-full font-semibold hover:shadow-lg transition-all border border-gray-200 dark:border-gray-700 inline-flex items-center justify-center gap-2">
                        <span>Se Fler Demos</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Additional Demos Coming Soon -->
<section id="demos" class="relative py-24 bg-white dark:bg-gray-900">
    <div class="max-w-5xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">Fler Demos Kommer Snart</h2>
        <p class="text-xl text-gray-600 dark:text-gray-400 mb-12">Vi fortsätter bygga imponerande showcase-features.</p>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="glass-morph p-6 rounded-2xl">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-4 mx-auto">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-2">AI Smart Meny</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Automatisk meny med allergener för restauranger</p>
            </div>

            <div class="glass-morph p-6 rounded-2xl">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4 mx-auto">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-2">Före/Efter Slider</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Visa transformationer interaktivt</p>
            </div>

            <div class="glass-morph p-6 rounded-2xl">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-4 mx-auto">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-2">360° Virtual Tour</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Utforska lokaler innan besök</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-5xl mx-auto px-6 text-center">
        <div class="mb-8">
            <a href="/" class="text-3xl font-bold">AT<span class="text-purple-400">Dev</span></a>
        </div>
        <div class="flex flex-wrap justify-center gap-6 mb-8">
            <a href="/" class="hover:text-purple-400 transition-colors">Hem</a>
            <a href="/#services" class="hover:text-purple-400 transition-colors">Tjänster</a>
            <a href="/#projects" class="hover:text-purple-400 transition-colors">Projekt</a>
            <a href="/demos" class="hover:text-purple-400 transition-colors">Demos</a>
            <a href="/#contact" class="hover:text-purple-400 transition-colors">Kontakt</a>
        </div>
        <div class="text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} ATDev. Alla rättigheter förbehållna.</p>
        </div>
    </div>
</footer>

@push('scripts')
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
@endpush

@endsection
