{{-- Shared Navigation Component
     Usage: @include('partials.navigation', ['currentPage' => 'home|demos'])
--}}
@php
    $currentPage = $currentPage ?? 'home';
@endphp

<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-6 focus:py-3 focus:bg-purple-600 focus:text-white focus:rounded-lg focus:shadow-lg">Hoppa till huvudinnehåll</a>

<nav class="fixed left-0 right-0 z-50 px-4 transition-all duration-300" role="navigation" aria-label="Huvudnavigation" x-data="{ mobileMenuOpen: false, scrolled: false, lastScroll: 0, showNav: true }" x-init="window.addEventListener('scroll', () => { const currentScroll = window.scrollY; scrolled = currentScroll > 50; if (currentScroll <= 0) { showNav = true; } else if (currentScroll > lastScroll) { showNav = false; } else { showNav = true; } lastScroll = currentScroll; })" :style="showNav ? 'top: 0;' : 'top: -100px;'">
    <div class="max-w-5xl mx-auto mt-6 px-8 py-4 flex justify-between items-center transition-all duration-300" :class="(scrolled && showNav) ? 'bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg rounded-full shadow-lg' : 'bg-white/10 dark:bg-gray-900/10 backdrop-blur-sm rounded-full'">
        <a href="/" class="text-2xl font-bold transition-all hover:scale-105" :class="(scrolled && showNav) ? 'text-gray-900 dark:text-white' : 'text-white'">AT<span :class="(scrolled && showNav) ? 'text-purple-600' : 'text-white/90'">Dev</span></a>

        <div class="hidden md:flex items-center gap-6">
            <a href="/" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? '{{ $currentPage === 'home' ? 'text-purple-600 dark:text-purple-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}' : '{{ $currentPage === 'home' ? 'text-white font-semibold' : 'text-white/80 hover:text-white' }}'">Hem</a>
            <a href="/#services" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">Tjänster</a>
            <a href="/#projects" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">Projekt</a>
            <a href="/demos" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? '{{ $currentPage === 'demos' ? 'text-purple-600 dark:text-purple-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}' : '{{ $currentPage === 'demos' ? 'text-white font-semibold' : 'text-white/80 hover:text-white' }}'">Demos</a>
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
            <a href="/" class="block py-2 font-medium transition-colors {{ $currentPage === 'home' ? 'text-purple-600 dark:text-purple-400 font-semibold' : 'text-gray-900 dark:text-white hover:text-purple-600' }}">Hem</a>
            <a href="/#services" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">Tjänster</a>
            <a href="/#projects" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">Projekt</a>
            <a href="/demos" class="block py-2 font-medium transition-colors {{ $currentPage === 'demos' ? 'text-purple-600 dark:text-purple-400 font-semibold' : 'text-gray-900 dark:text-white hover:text-purple-600' }}">Demos</a>
            <a href="/#contact" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">Kontakt</a>

            @auth
            <a href="/admin" class="block py-2 px-6 bg-purple-600 text-white rounded-full text-center hover:bg-purple-700 font-medium transition-colors">Admin</a>
            @endauth
        </div>
    </div>
</nav>
