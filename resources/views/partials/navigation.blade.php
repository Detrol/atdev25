{{-- Shared Navigation Component
     Usage: @include('partials.navigation', ['currentPage' => 'home|demos'])
--}}
@php
    $currentPage = $currentPage ?? 'home';
@endphp

<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-6 focus:py-3 focus:bg-purple-600 focus:text-white focus:rounded-lg focus:shadow-lg">Hoppa till huvudinnehåll</a>

<nav class="fixed left-0 right-0 z-[9999] px-4 transition-all duration-300" role="navigation" aria-label="Huvudnavigation" x-data="{ mobileMenuOpen: false, scrolled: false, lastScroll: 0, showNav: true, aboutDropdownOpen: false, mobileAboutOpen: false }" x-init="window.addEventListener('scroll', () => { const currentScroll = window.scrollY; scrolled = currentScroll > 50; if (currentScroll <= 0) { showNav = true; } else if (currentScroll > lastScroll) { showNav = false; } else { showNav = true; } lastScroll = currentScroll; })" :style="showNav ? 'top: 0;' : 'top: -100px;'">
    <div class="max-w-5xl mx-auto mt-6 px-8 py-4 flex justify-between items-center transition-all duration-300" :class="(scrolled && showNav) ? 'bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg rounded-full shadow-lg' : 'bg-white/10 dark:bg-gray-900/10 backdrop-blur-sm rounded-full'">
        <a href="/" class="text-2xl font-bold transition-all hover:scale-105" :class="(scrolled && showNav) ? 'text-gray-900 dark:text-white' : 'text-white'">AT<span :class="(scrolled && showNav) ? 'text-purple-600' : 'text-white/90'">Dev</span></a>

        <div class="hidden md:flex items-center gap-6">
            <a href="/" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? '{{ $currentPage === 'home' ? 'text-purple-600 dark:text-purple-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}' : '{{ $currentPage === 'home' ? 'text-white font-semibold' : 'text-white/80 hover:text-white' }}'">Hem</a>

            {{-- Om Dropdown --}}
            <div class="relative" @click.away="aboutDropdownOpen = false">
                <button @click="aboutDropdownOpen = !aboutDropdownOpen" class="transition-all font-medium whitespace-nowrap flex items-center gap-1" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">
                    Om
                    <svg class="w-4 h-4 transition-transform" :class="aboutDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="aboutDropdownOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute top-full left-0 mt-2 py-2 w-48 bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg rounded-2xl shadow-2xl border border-gray-200/20 dark:border-gray-700/20"
                     x-cloak>
                    <a href="/#om-mig" @click="aboutDropdownOpen = false; if(window.GA4) GA4.trackNavigation('om-mig')" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Om Mig</a>
                    <a href="/#hur-jag-jobbar" @click="aboutDropdownOpen = false; if(window.GA4) GA4.trackNavigation('hur-jag-jobbar')" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Hur Jag Jobbar</a>
                    <a href="/#expertis" @click="aboutDropdownOpen = false; if(window.GA4) GA4.trackNavigation('expertis')" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Expertis</a>
                </div>
            </div>

            <a href="/#services" onclick="if(window.GA4) GA4.trackNavigation('services')" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">Tjänster</a>
            <a href="/#projects" onclick="if(window.GA4) GA4.trackNavigation('projects')" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">Projekt</a>
            <a href="/#faq" onclick="if(window.GA4) GA4.trackNavigation('faq')" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">FAQ</a>
            <a href="/demos" onclick="if(window.GA4) GA4.trackNavigation('demos')" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? '{{ $currentPage === 'demos' ? 'text-purple-600 dark:text-purple-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}' : '{{ $currentPage === 'demos' ? 'text-white font-semibold' : 'text-white/80 hover:text-white' }}'">Demos</a>
            <a href="/#contact" onclick="if(window.GA4) GA4.trackNavigation('contact')" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">Kontakt</a>

            {{-- Darkmode Toggle --}}
            <x-darkmode-toggle size="md" />

            @auth
            <a href="/admin" class="px-6 py-2 rounded-full transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'bg-purple-600 text-white hover:bg-purple-700' : 'bg-white/20 text-white hover:bg-white/30'">Admin</a>
            @endauth
        </div>

        <button @click="mobileMenuOpen = !mobileMenuOpen; if(window.GA4) GA4.trackMobileMenu(mobileMenuOpen ? 'close' : 'open')" class="md:hidden p-2 rounded-lg transition-all" :class="(scrolled && showNav) ? 'hover:bg-gray-100' : 'hover:bg-white/10'" aria-label="Toggle menu">
            <svg class="w-6 h-6" :class="(scrolled && showNav) ? 'text-gray-900 dark:text-white' : 'text-white'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path><path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" x-cloak></path></svg>
        </button>
    </div>

    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="md:hidden mt-4 mx-4" x-cloak>
        <div class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg rounded-2xl shadow-2xl p-6 space-y-4">
            <a href="/" class="block py-2 font-medium transition-colors {{ $currentPage === 'home' ? 'text-purple-600 dark:text-purple-400 font-semibold' : 'text-gray-900 dark:text-white hover:text-purple-600' }}">Hem</a>

            {{-- Om Accordion --}}
            <div>
                <button @click="mobileAboutOpen = !mobileAboutOpen" class="w-full flex items-center justify-between py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">
                    <span>Om</span>
                    <svg class="w-5 h-5 transition-transform" :class="mobileAboutOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="mobileAboutOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="pl-4 space-y-2 mt-2" x-cloak>
                    <a href="/#om-mig" onclick="if(window.GA4) GA4.trackNavigation('om-mig')" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-purple-600 font-medium transition-colors">Om Mig</a>
                    <a href="/#hur-jag-jobbar" onclick="if(window.GA4) GA4.trackNavigation('hur-jag-jobbar')" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-purple-600 font-medium transition-colors">Hur Jag Jobbar</a>
                    <a href="/#expertis" onclick="if(window.GA4) GA4.trackNavigation('expertis')" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-purple-600 font-medium transition-colors">Expertis</a>
                </div>
            </div>

            <a href="/#services" onclick="if(window.GA4) GA4.trackNavigation('services')" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">Tjänster</a>
            <a href="/#projects" onclick="if(window.GA4) GA4.trackNavigation('projects')" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">Projekt</a>
            <a href="/#faq" onclick="if(window.GA4) GA4.trackNavigation('faq')" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">FAQ</a>
            <a href="/demos" onclick="if(window.GA4) GA4.trackNavigation('demos')" class="block py-2 font-medium transition-colors {{ $currentPage === 'demos' ? 'text-purple-600 dark:text-purple-400 font-semibold' : 'text-gray-900 dark:text-white hover:text-purple-600' }}">Demos</a>
            <a href="/#contact" onclick="if(window.GA4) GA4.trackNavigation('contact')" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">Kontakt</a>

            {{-- Darkmode Toggle Mobile --}}
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-darkmode-toggle size="md" :show-label="true" class="w-full justify-center" />
            </div>

            @auth
            <a href="/admin" class="block py-2 px-6 bg-purple-600 text-white rounded-full text-center hover:bg-purple-700 font-medium transition-colors">Admin</a>
            @endauth
        </div>
    </div>
</nav>
