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

<!-- Demos Section - Placeholder for Future Features -->
<section id="demos" class="relative min-h-screen flex items-center justify-center bg-white dark:bg-gray-900 overflow-hidden">
    <div class="max-w-5xl mx-auto px-6 py-24 text-center">
        <div class="space-y-12" x-data="{ visible: false }" x-intersect="visible = true">
            <!-- Coming Soon Icon -->
            <div class="flex justify-center" x-show="visible" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <div class="relative">
                    <div class="w-32 h-32 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center shadow-2xl shadow-purple-500/30 float-gentle">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-lg pulse-glow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Heading -->
            <div x-show="visible" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    Demos Kommer Snart
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Vi förbereder imponerande interaktiva demos som kommer att visa upp möjligheterna för din verksamhet.
                </p>
            </div>

            <!-- Feature Grid -->
            <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-400" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <!-- Feature 1 -->
                <div class="glass-morph p-6 rounded-2xl text-left group hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Fully Functional</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Prova alla funktioner live, inte bara screenshots</p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-morph p-6 rounded-2xl text-left group hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">No Signup Required</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Börja utforska direkt utan registrering</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-morph p-6 rounded-2xl text-left group hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Real Solutions</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Verkliga funktioner redo att använda i ditt projekt</p>
                </div>
            </div>

            <!-- CTA -->
            <div class="pt-8" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-600" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Intresserad av att se vad som är möjligt för din verksamhet?
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/#contact" class="px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-full font-semibold hover:shadow-xl hover:shadow-purple-500/30 transition-all hover:scale-105 inline-flex items-center justify-center gap-2">
                        <span>Kontakta Mig</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="/" class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-full font-semibold hover:shadow-lg transition-all border border-gray-200 dark:border-gray-700 inline-flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Tillbaka till Hem</span>
                    </a>
                </div>
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

@endsection
