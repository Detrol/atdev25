@extends('layouts.app')

@section('content')
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-6 focus:py-3 focus:bg-purple-600 focus:text-white focus:rounded-lg focus:shadow-lg">Hoppa till huvudinnehåll</a>

<nav class="fixed left-0 right-0 z-50 px-4 transition-all duration-300" role="navigation" aria-label="Huvudnavigation" x-data="{ mobileMenuOpen: false, scrolled: false, lastScroll: 0, showNav: true }" x-init="window.addEventListener('scroll', () => { const currentScroll = window.scrollY; scrolled = currentScroll > 50; if (currentScroll <= 0) { showNav = true; } else if (currentScroll > lastScroll) { showNav = false; } else { showNav = true; } lastScroll = currentScroll; })" :style="showNav ? 'top: 0;' : 'top: -100px;'">
    <div class="max-w-5xl mx-auto mt-6 px-8 py-4 flex justify-between items-center transition-all duration-300" :class="(scrolled && showNav) ? 'bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg rounded-full shadow-lg' : 'bg-white/10 dark:bg-gray-900/10 backdrop-blur-sm rounded-full'">
        <a href="/" class="text-2xl font-bold transition-all hover:scale-105" :class="(scrolled && showNav) ? 'text-gray-900 dark:text-white' : 'text-white'">AT<span :class="(scrolled && showNav) ? 'text-purple-600' : 'text-white/90'">Dev</span></a>
        <div class="hidden md:flex items-center gap-6">
            <a href="/" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">Hem</a>
            <a href="#services" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">Tjänster</a>
            <a href="#projects" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">Projekt</a>
            <a href="#contact" class="transition-all font-medium whitespace-nowrap" :class="(scrolled && showNav) ? 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' : 'text-white/80 hover:text-white'">Kontakt</a>
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
            <a href="#services" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">Tjänster</a>
            <a href="#projects" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">Projekt</a>
            <a href="#contact" class="block py-2 text-gray-900 dark:text-white hover:text-purple-600 font-medium transition-colors">Kontakt</a>
            @auth
            <a href="/admin" class="block py-2 px-6 bg-purple-600 text-white rounded-full text-center hover:bg-purple-700 font-medium transition-colors">Admin</a>
            @endauth
        </div>
    </div>
</nav>

<section id="main-content" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 gradient-mesh"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/30 to-black/50"></div>
    <div class="relative z-10 max-w-5xl mx-auto px-6 py-32 text-center text-white">
        <div class="space-y-8" x-data="{ visible: false }" x-intersect="visible = true" x-init="setTimeout(() => visible = true, 100)">
            <div class="inline-flex items-center gap-3 px-6 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 pulse-glow" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-100" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span><span class="text-sm font-medium">Tillgänglig för nya projekt</span>
            </div>
            <h1 class="text-5xl md:text-7xl font-bold leading-tight" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">AI-Driven Utveckling<br><span class="text-white/90">Premium Kvalitet, Lågt Pris</span></h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-500" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">Med 20+ års erfarenhet kombinerar jag modern AI och automation för att leverera högkvalitativa webbapplikationer till en bråkdel av priset.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-700" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <a href="#projects" class="px-8 py-4 bg-white text-purple-600 rounded-full font-semibold hover:bg-white/90 transition-all hover:scale-105 shadow-2xl">Se Mina Projekt</a>
                <a href="#contact" class="px-8 py-4 bg-white/10 backdrop-blur-md text-white rounded-full font-semibold hover:bg-white/20 transition-all border border-white/20">Kontakta Mig</a>
            </div>
        </div>
    </div>
</section>

<section class="relative py-24 bg-white dark:bg-gray-900 overflow-hidden">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Expertis Möter Innovation</h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">20+ års erfarenhet kombinerat med cutting-edge AI för optimal leverans</p>
        </div>

        <div class="relative" x-data="{ years: 20, projects: 100, savings: 70, satisfaction: 98 }">
            <div class="grid grid-cols-12 gap-6 auto-rows-[200px]">
                <!-- Feature Card 1 -->
                <div class="col-span-12 md:col-span-7 row-span-2 group relative overflow-hidden rounded-3xl p-8 bg-gradient-to-br from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20 border border-purple-200 dark:border-purple-700/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-purple-500/30"
                     x-data="{ tiltY: 0 }"
                     @mousemove="const rect = $el.getBoundingClientRect(); tiltY = ((event.clientX - rect.left - rect.width/2) / rect.width) * -8"
                     @mouseleave="tiltY = 0"
                     :style="`transform: translateY(${tiltY !== 0 ? '-0.25rem' : '0'}) perspective(1000px) rotateY(${tiltY}deg)`">
                    <div class="relative z-10 h-full flex flex-col justify-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">20+ Års Erfarenhet</h3>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">Djup expertis inom webbutveckling, från grundläggande arkitektur till komplexa enterprise-lösningar.</p>
                    </div>
                </div>

                <!-- Stat Card 1 -->
                <div class="col-span-6 md:col-span-5 bg-white dark:bg-gray-800 rounded-3xl p-6 flex flex-col justify-center items-center text-center border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-purple-500 dark:hover:border-purple-500">
                    <div class="text-6xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-2" x-text="years + '+'"></div>
                    <div class="text-sm uppercase tracking-wider text-gray-600 dark:text-gray-400 font-semibold">Års Erfarenhet</div>
                </div>

                <!-- Stat Card 2 -->
                <div class="col-span-6 md:col-span-5 bg-white dark:bg-gray-800 rounded-3xl p-6 flex flex-col justify-center items-center text-center border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-blue-500 dark:hover:border-blue-500">
                    <div class="text-6xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2" x-text="projects + '+'"></div>
                    <div class="text-sm uppercase tracking-wider text-gray-600 dark:text-gray-400 font-semibold">Projekt</div>
                </div>

                <!-- Feature Card 2 -->
                <div class="col-span-12 md:col-span-7 row-span-2 group relative overflow-hidden rounded-3xl p-8 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border border-blue-200 dark:border-blue-700/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/30"
                     x-data="{ tiltY: 0 }"
                     @mousemove="const rect = $el.getBoundingClientRect(); tiltY = ((event.clientX - rect.left - rect.width/2) / rect.width) * -8"
                     @mouseleave="tiltY = 0"
                     :style="`transform: translateY(${tiltY !== 0 ? '-0.25rem' : '0'}) perspective(1000px) rotateY(${tiltY}deg)`">
                    <div class="relative z-10 h-full flex flex-col justify-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">AI & Automation</h3>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">Utnyttjar moderna AI-verktyg och automation för att maximera effektivitet och minimera kostnader.</p>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="col-span-6 md:col-span-5 bg-white dark:bg-gray-800 rounded-3xl p-6 flex flex-col justify-center items-center text-center border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-green-500 dark:hover:border-green-500">
                    <div class="text-6xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent mb-2" x-text="savings + '%'"></div>
                    <div class="text-sm uppercase tracking-wider text-gray-600 dark:text-gray-400 font-semibold">Kostnadsbesparing</div>
                </div>

                <!-- Stat Card 4 -->
                <div class="col-span-6 md:col-span-5 bg-white dark:bg-gray-800 rounded-3xl p-6 flex flex-col justify-center items-center text-center border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-pink-500 dark:hover:border-pink-500">
                    <div class="text-6xl font-bold bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent mb-2" x-text="satisfaction + '%'"></div>
                    <div class="text-sm uppercase tracking-wider text-gray-600 dark:text-gray-400 font-semibold">Nöjdhet</div>
                </div>

                <!-- Feature Card 3 -->
                <div class="col-span-12 md:col-span-12 group relative overflow-hidden rounded-3xl p-8 bg-gradient-to-br from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 border border-green-200 dark:border-green-700/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-green-500/30"
                     x-data="{ tiltY: 0 }"
                     @mousemove="const rect = $el.getBoundingClientRect(); tiltY = ((event.clientX - rect.left - rect.width/2) / rect.width) * -6"
                     @mouseleave="tiltY = 0"
                     :style="`transform: translateY(${tiltY !== 0 ? '-0.25rem' : '0'}) perspective(1000px) rotateY(${tiltY}deg)`">
                    <div class="relative z-10 h-full flex flex-col justify-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Kostnadseffektivt</h3>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">Premium kvalitet till en bråkdel av traditionella byråpriser tack vare smart resursanvändning.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('components.price-calculator')

<section id="services" class="relative py-24 bg-white dark:bg-gray-900 overflow-hidden">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Mina Tjänster</h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">Skräddarsydda lösningar för alla dina webbutvecklingsbehov</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($services as $service)
            <div class="group relative bg-white dark:bg-gray-800 rounded-3xl p-8 border-2 border-gray-200 dark:border-gray-700 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:border-purple-500 dark:hover:border-purple-500">
                <!-- Icon -->
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    @if($service->icon === 'code')
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                    @elseif($service->icon === 'puzzle-piece')
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
                    </svg>
                    @elseif($service->icon === 'wrench')
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    @else
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    @endif
                </div>

                <!-- Title -->
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ $service->title }}
                </h3>

                <!-- Description -->
                <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                    {{ $service->description }}
                </p>

                <!-- Features -->
                @if($service->features && count($service->features) > 0)
                <div class="space-y-3">
                    @foreach($service->features as $feature)
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Hover indicator -->
                <div class="absolute bottom-6 right-6 w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </div>
            </div>
            @endforeach
        </div>

        <!-- CTA -->
        <div class="mt-16 text-center">
            <p class="text-gray-600 dark:text-gray-400 mb-6 text-lg">
                Inte säker på vilken tjänst som passar dig bäst?
            </p>
            <a href="#contact" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-full font-semibold hover:shadow-2xl hover:shadow-purple-500/30 transition-all hover:scale-105">
                Kontakta Mig För Rådgivning
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<section id="projects" class="relative py-24 bg-gray-50 dark:bg-gray-800 overflow-hidden">
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Utvalda Projekt</h2>
            <p class="text-xl text-gray-600 dark:text-gray-400">En samling av mina senaste arbeten</p>
        </div>

        <div class="grid md:grid-cols-12 gap-6">
            @forelse($projects as $index => $project)
            <div class="group relative {{ $index === 0 ? 'md:col-span-12' : 'md:col-span-6' }} transition-all duration-300 hover:-translate-y-2 hover:scale-[1.02]">
                <div class="relative w-full {{ $index === 0 ? 'h-96' : 'h-80' }} rounded-3xl overflow-hidden">
                    <!-- Gradient border on hover -->
                    <div class="absolute inset-0 rounded-3xl bg-gradient-to-r from-purple-500 via-blue-500 to-pink-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <!-- Content wrapper -->
                    <div class="absolute inset-[2px] rounded-3xl h-full overflow-hidden bg-white dark:bg-gray-900">
                        @if($project->cover_image)
                        <img src="{{ asset('storage/' . $project->cover_image) }}"
                             alt="{{ $project->title }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-purple-400 via-blue-500 to-pink-500 flex items-center justify-center">
                            <svg class="w-24 h-24 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>

                        <div class="absolute inset-0 p-8 flex flex-col justify-end">
                            <h3 class="text-3xl font-bold text-white mb-3">
                                {{ $project->title }}
                            </h3>

                            <p class="text-white/90 mb-6 line-clamp-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                {{ $project->summary }}
                            </p>

                            @if($project->technologies)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach((is_array($project->technologies) ? $project->technologies : json_decode($project->technologies, true)) as $tech)
                                <span class="px-3 py-1 bg-white/20 border border-white/30 text-white text-sm rounded-full">
                                    {{ trim($tech) }}
                                </span>
                                @endforeach
                            </div>
                            @endif

                            <a href="/projects/{{ $project->slug }}"
                               class="absolute bottom-8 right-8 w-14 h-14 bg-white/20 border border-white/30 rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-white/30 hover:scale-110">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="md:col-span-12 text-center py-24">
                <div class="inline-block p-8 glass-morph rounded-3xl">
                    <svg class="w-24 h-24 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-xl font-semibold">Inga projekt att visa ännu</p>
                    <p class="text-gray-500 dark:text-gray-500 mt-2">Fantastiska projekt kommer snart!</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Tech Stack CTA -->
        <div class="mt-16 text-center" x-data="{ techStackOpen: false }">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500 via-blue-500 to-pink-500 rounded-3xl blur-xl opacity-30 animate-pulse"></div>
                <button @click="techStackOpen = true" class="relative px-10 py-5 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 text-white rounded-3xl font-bold text-lg shadow-2xl hover:shadow-purple-500/50 transition-all hover:scale-105 active:scale-95 flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Utforska Min Tech Stack
                </button>
            </div>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Interaktiv visualisering av teknologier jag använder</p>

            <!-- Tech Stack Modal -->
            <div x-show="techStackOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click.self="techStackOpen = false"
                 class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
                 x-cloak>
                <div @click.stop
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-90"
                     class="relative bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-7xl max-h-[90vh] overflow-hidden">

                    <!-- Header -->
                    <div class="sticky top-0 z-10 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 px-8 py-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Tech Stack Visualizer</h2>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">Interaktiv graf över teknologier och deras relationer</p>
                            </div>
                            <button @click="techStackOpen = false" class="p-3 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors">
                                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="overflow-y-auto max-h-[calc(90vh-120px)] p-8">
                        <!-- D3.js Visualization Container -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 mb-8">
                            <div id="tech-graph-modal" class="w-full" style="height: 500px;"></div>

                            <!-- Legend -->
                            <div class="mt-6 flex flex-wrap justify-center gap-6 text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Frontend</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-green-500"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Backend</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-purple-500"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Database</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-orange-500"></div>
                                    <span class="text-gray-700 dark:text-gray-300">DevOps</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full bg-gray-500"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Övrigt</span>
                                </div>
                            </div>
                        </div>

                        <!-- Technology Statistics -->
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Teknologistatistik</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="tech-stats-modal">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Website Audit CTA Section -->
<section class="relative py-24 bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-700 overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute w-96 h-96 bg-white/5 rounded-full blur-3xl -top-48 -left-48"></div>
        <div class="absolute w-96 h-96 bg-white/5 rounded-full blur-3xl -bottom-48 -right-48"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-6 text-center">
        <div class="inline-block p-3 bg-white/10 rounded-2xl mb-6">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
        </div>

        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
            Hur Presterar Din Webbplats?
        </h2>

        <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
            Få en gratis, AI-driven analys av din webbplats SEO, prestanda och användarupplevelse.
            Professionell rapport på 2-5 minuter.
        </p>

        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <div class="flex items-center gap-2 text-white/80">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>100% Gratis</span>
            </div>
            <div class="flex items-center gap-2 text-white/80">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>AI-Analyserad</span>
            </div>
            <div class="flex items-center gap-2 text-white/80">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>Konkreta Råd</span>
            </div>
        </div>

        <a href="{{ route('audits.create') }}"
           class="inline-flex items-center gap-3 px-10 py-5 bg-white text-purple-600 rounded-2xl font-bold text-lg shadow-2xl hover:shadow-white/25 transition-all hover:scale-105 active:scale-95">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Starta Gratis Website Audit
        </a>

        <p class="mt-6 text-white/70 text-sm">
            Ingen kreditkort krävs • Resultat på 2-5 minuter • Rapport via e-post
        </p>
    </div>
</section>

<section id="contact" class="relative py-24 bg-white dark:bg-gray-900 overflow-hidden">
    <div class="relative max-w-4xl mx-auto px-6">
        <div class="relative text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 bg-clip-text text-transparent">
                Låt Oss Skapa Något Fantastiskt
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400">Har du ett projekt i tankarna? Kontakta mig idag!</p>
        </div>

        @if(session('success'))
        <div class="mb-8 p-4 bg-green-50 dark:bg-green-900/20 rounded-2xl border-l-4 border-green-500" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center gap-3 text-green-600 dark:text-green-400">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-8 p-4 bg-red-50 dark:bg-red-900/20 rounded-2xl border-l-4 border-red-500" x-data="{ show: true }" x-show="show" x-transition>
            <div class="flex items-start gap-3 text-red-600 dark:text-red-400">
                <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="font-medium mb-2">Vänligen rätta följande fel:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="relative bg-white dark:bg-gray-800 rounded-3xl p-8 md:p-12 border-2 border-gray-200 dark:border-gray-700 shadow-2xl overflow-hidden" x-data="{ submitting: false }">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 via-blue-500/5 to-pink-500/5 -z-10"></div>
            <form method="POST" action="{{ route('contact.store') }}" @submit="submitting = true" class="space-y-8" x-data="{
                nameFocused: false,
                emailFocused: false,
                messageFocused: false,
                estimationId: null,
                estimation: null,
                init() {
                    // Listen for estimation data from price calculator
                    window.addEventListener('estimation-ready', (event) => {
                        this.estimationId = event.detail.id;
                        this.estimation = event.detail.data;
                        console.log('Estimation received:', this.estimation);
                    });
                }
            }">
                @csrf

                <!-- Hidden field for price estimation ID -->
                <input type="hidden" name="price_estimation_id" :value="estimationId">

                <!-- Name Field with Floating Label -->
                <div class="relative group">
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        maxlength="255"
                        @focus="nameFocused = true"
                        @blur="nameFocused = ($event.target.value !== '')"
                        x-init="nameFocused = ('{{ old('name') }}' !== '')"
                        class="peer w-full px-4 py-4 pt-6 bg-gray-50 dark:bg-gray-900 rounded-2xl text-gray-900 dark:text-white
                               border-2 border-gray-300 dark:border-gray-600
                               focus:border-purple-500 dark:focus:border-purple-400
                               focus:shadow-lg focus:shadow-purple-500/20
                               transition-all duration-300
                               placeholder-transparent
                               @error('name') border-red-500 dark:border-red-400 @enderror">
                    <label
                        for="name"
                        class="absolute left-4 transition-all duration-300 pointer-events-none
                               text-gray-600 dark:text-gray-400"
                        :class="nameFocused ? 'top-2 text-xs font-semibold text-purple-600 dark:text-purple-400' : 'top-4 text-base'">
                        Namn <span class="text-red-500">*</span>
                    </label>
                    @error('name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Email Field with Floating Label -->
                <div class="relative group">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        maxlength="255"
                        @focus="emailFocused = true"
                        @blur="emailFocused = ($event.target.value !== '')"
                        x-init="emailFocused = ('{{ old('email') }}' !== '')"
                        class="peer w-full px-4 py-4 pt-6 bg-gray-50 dark:bg-gray-900 rounded-2xl text-gray-900 dark:text-white
                               border-2 border-gray-300 dark:border-gray-600
                               focus:border-purple-500 dark:focus:border-purple-400
                               focus:shadow-lg focus:shadow-purple-500/20
                               transition-all duration-300
                               placeholder-transparent
                               @error('email') border-red-500 dark:border-red-400 @enderror">
                    <label
                        for="email"
                        class="absolute left-4 transition-all duration-300 pointer-events-none
                               text-gray-600 dark:text-gray-400"
                        :class="emailFocused ? 'top-2 text-xs font-semibold text-purple-600 dark:text-purple-400' : 'top-4 text-base'">
                        E-post <span class="text-red-500">*</span>
                    </label>
                    @error('email')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Price Estimation Summary (shown when coming from price calculator) -->
                <div x-show="estimation" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 dark:from-purple-900/20 dark:via-blue-900/20 dark:to-pink-900/20 rounded-2xl p-6 border-2 border-purple-200 dark:border-purple-700">
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Din Prisestimering</h3>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-3">
                            <p class="text-xs font-semibold text-purple-600 dark:text-purple-400 mb-1">Projekttyp</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="estimation?.project_type_label"></p>
                        </div>
                        <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-3">
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-1">Komplexitet</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white"><span x-text="estimation?.complexity"></span>/10</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-3">
                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Arbetstid (AI)</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="estimation?.hours_ai"></p>
                        </div>
                        <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-3">
                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Leverans</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="estimation?.delivery_weeks_ai"></p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl p-4">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-xs font-semibold opacity-90">AI-Driven Pris (inkl. moms)</p>
                            <span class="px-2 py-1 bg-white/20 rounded-full text-xs font-bold">-50%</span>
                        </div>
                        <p class="text-2xl font-bold" x-text="estimation?.price_ai_vat"></p>
                        <p class="text-xs opacity-75 mt-1">Din besparing: <span x-text="estimation?.savings_vat"></span></p>
                    </div>

                    <div class="mt-4 pt-4 border-t border-purple-200 dark:border-purple-700" x-show="estimation?.key_features?.length > 0">
                        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Identifierade Funktioner:</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="feature in estimation?.key_features || []" :key="feature">
                                <span class="px-2 py-1 bg-white/70 dark:bg-gray-800/70 rounded-lg text-xs text-gray-700 dark:text-gray-300" x-text="feature"></span>
                            </template>
                        </div>
                    </div>

                    <p class="mt-4 text-xs text-gray-600 dark:text-gray-400 text-center">
                        📊 Denna analys kommer att kopplas till ditt meddelande
                    </p>
                </div>

                <!-- Message Field with Floating Label -->
                <div class="relative group">
                    <textarea
                        id="message"
                        name="message"
                        required
                        minlength="10"
                        maxlength="5000"
                        rows="6"
                        @focus="messageFocused = true"
                        @blur="messageFocused = ($event.target.value !== '')"
                        x-init="messageFocused = ('{{ old('message') }}' !== '')"
                        class="peer w-full px-4 py-4 pt-6 bg-gray-50 dark:bg-gray-900 rounded-2xl text-gray-900 dark:text-white
                               border-2 border-gray-300 dark:border-gray-600
                               focus:border-purple-500 dark:focus:border-purple-400
                               focus:shadow-lg focus:shadow-purple-500/20
                               transition-all duration-300 resize-none
                               placeholder-transparent
                               @error('message') border-red-500 dark:border-red-400 @enderror">{{ old('message') }}</textarea>
                    <label
                        for="message"
                        class="absolute left-4 transition-all duration-300 pointer-events-none
                               text-gray-600 dark:text-gray-400"
                        :class="messageFocused ? 'top-2 text-xs font-semibold text-purple-600 dark:text-purple-400' : 'top-4 text-base'">
                        Meddelande <span class="text-red-500">*</span>
                    </label>
                    @error('message')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </p>
                    @else
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Vi svarar vanligtvis inom 24 timmar.</p>
                    @enderror
                </div>

                <!-- Honeypot -->
                <input type="text" name="website" value="" tabindex="-1" autocomplete="off" style="position: absolute; left: -9999px; width: 1px; height: 1px;">

                <!-- Submit Button -->
                <div class="relative">
                    <button
                        type="submit"
                        :disabled="submitting"
                        class="w-full px-8 py-5 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600
                               rounded-2xl font-bold text-lg text-white
                               transition-all duration-300
                               disabled:opacity-50 disabled:cursor-not-allowed
                               hover:shadow-2xl hover:shadow-purple-500/30 hover:-translate-y-0.5
                               active:translate-y-0
                               flex items-center justify-center gap-3">
                        <span x-show="!submitting" class="flex items-center gap-3">
                            Skicka Meddelande
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </span>
                        <span x-show="submitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Skickar...
                        </span>
                    </button>
                </div>

                <div class="pt-6 border-t border-purple-200 dark:border-purple-700">
                    <p class="text-center text-gray-700 dark:text-gray-300 mb-3">Eller kontakta mig direkt via e-post:</p>
                    <div class="flex justify-center">
                        <a href="mailto:andreas@atdev.se" class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-gray-800 rounded-full shadow-lg hover:shadow-xl transition-all hover:scale-105">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="font-semibold text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400 transition-colors">andreas@atdev.se</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<footer class="bg-gray-900 dark:bg-black text-white py-12">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <div class="text-2xl font-bold mb-2">AT<span class="text-purple-400">Dev</span></div>
                <p class="text-gray-400">AI-Driven Utveckling sedan {{ date('Y') - 20 }}</p>
            </div>
            <div class="flex gap-6">
                <a href="/" class="text-gray-400 hover:text-white transition-colors">Hem</a>
                <a href="#services" class="text-gray-400 hover:text-white transition-colors">Tjänster</a>
                <a href="#projects" class="text-gray-400 hover:text-white transition-colors">Projekt</a>
                <a href="#contact" class="text-gray-400 hover:text-white transition-colors">Kontakt</a>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} ATDev. Alla rättigheter förbehållna.</p>
        </div>
    </div>
</footer>

<!-- D3.js for Tech Stack Modal -->
<script src="https://d3js.org/d3.v7.min.js"></script>

<script>
// Tech Stack Modal Handler
document.addEventListener('alpine:initialized', () => {
    let techDataLoaded = false;
    let techData = null;

    // Listen for modal open
    document.addEventListener('click', async function(e) {
        if (e.target.closest('[\\@click="techStackOpen = true"]') || e.target.closest('button[x-on\\:click="techStackOpen = true"]')) {
            if (!techDataLoaded) {
                await loadTechStack();
            }
        }
    });

    async function loadTechStack() {
        try {
            const response = await fetch('/api/tech-stack');
            techData = await response.json();
            techDataLoaded = true;

            // Wait for modal to be visible
            setTimeout(() => {
                renderTechGraph();
                renderTechStats();
            }, 300);
        } catch (error) {
            console.error('Failed to load tech stack data:', error);
        }
    }

    function renderTechGraph() {
        const container = document.getElementById('tech-graph-modal');
        if (!container || !techData) return;

        // Clear previous graph
        container.innerHTML = '';

        const width = container.clientWidth;
        const height = 500;

        // Color mapping
        const colors = {
            frontend: '#3B82F6',
            backend: '#10B981',
            database: '#8B5CF6',
            devops: '#F59E0B',
            other: '#6B7280'
        };

        // Create SVG
        const svg = d3.select('#tech-graph-modal')
            .append('svg')
            .attr('width', width)
            .attr('height', height);

        // Tooltip
        const tooltip = d3.select('body')
            .append('div')
            .attr('class', 'absolute bg-gray-900 text-white px-3 py-2 rounded-lg text-sm shadow-lg pointer-events-none opacity-0 transition-opacity duration-200')
            .style('z-index', '9999');

        // Force simulation
        const simulation = d3.forceSimulation(techData.nodes)
            .force('link', d3.forceLink(techData.links)
                .id(d => d.id)
                .distance(d => 100 - (d.value * 10)))
            .force('charge', d3.forceManyBody().strength(-300))
            .force('center', d3.forceCenter(width / 2, height / 2))
            .force('collision', d3.forceCollide().radius(d => Math.sqrt(d.count) * 10 + 10));

        // Links
        const link = svg.append('g')
            .selectAll('line')
            .data(techData.links)
            .join('line')
            .attr('stroke', '#cbd5e1')
            .attr('stroke-opacity', d => 0.3 + (d.value * 0.1))
            .attr('stroke-width', d => Math.sqrt(d.value));

        // Nodes
        const node = svg.append('g')
            .selectAll('g')
            .data(techData.nodes)
            .join('g')
            .call(d3.drag()
                .on('start', dragstarted)
                .on('drag', dragged)
                .on('end', dragended));

        node.append('circle')
            .attr('r', d => Math.sqrt(d.count) * 10 + 5)
            .attr('fill', d => colors[d.group])
            .attr('stroke', '#fff')
            .attr('stroke-width', 2)
            .style('cursor', 'pointer')
            .on('mouseover', function(event, d) {
                d3.select(this)
                    .transition()
                    .duration(200)
                    .attr('r', Math.sqrt(d.count) * 10 + 8);

                tooltip
                    .style('opacity', 1)
                    .html(`<strong>${d.name}</strong><br/>${d.count} ${d.count === 1 ? 'projekt' : 'projekt'}`)
                    .style('left', (event.pageX + 10) + 'px')
                    .style('top', (event.pageY - 10) + 'px');
            })
            .on('mouseout', function(event, d) {
                d3.select(this)
                    .transition()
                    .duration(200)
                    .attr('r', Math.sqrt(d.count) * 10 + 5);

                tooltip.style('opacity', 0);
            });

        node.append('text')
            .text(d => d.name)
            .attr('x', 0)
            .attr('y', d => Math.sqrt(d.count) * 10 + 20)
            .attr('text-anchor', 'middle')
            .attr('font-size', '12px')
            .attr('font-weight', '600')
            .attr('fill', '#1f2937')
            .style('pointer-events', 'none');

        simulation.on('tick', () => {
            link
                .attr('x1', d => d.source.x)
                .attr('y1', d => d.source.y)
                .attr('x2', d => d.target.x)
                .attr('y2', d => d.target.y);

            node.attr('transform', d => `translate(${d.x},${d.y})`);
        });

        function dragstarted(event, d) {
            if (!event.active) simulation.alphaTarget(0.3).restart();
            d.fx = d.x;
            d.fy = d.y;
        }

        function dragged(event, d) {
            d.fx = event.x;
            d.fy = event.y;
        }

        function dragended(event, d) {
            if (!event.active) simulation.alphaTarget(0);
            d.fx = null;
            d.fy = null;
        }
    }

    function renderTechStats() {
        const container = document.getElementById('tech-stats-modal');
        if (!container || !techData) return;

        container.innerHTML = techData.technologies.map(tech => `
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">${tech.name}</h4>
                    <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 text-xs font-medium px-2.5 py-0.5 rounded">
                        ${tech.count} ${tech.count === 1 ? 'projekt' : 'projekt'}
                    </span>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <p class="font-medium mb-1">Används i:</p>
                    <ul class="list-disc list-inside space-y-1">
                        ${tech.projects.map(project => `<li class="text-gray-700 dark:text-gray-300">${project}</li>`).join('')}
                    </ul>
                </div>
            </div>
        `).join('');
    }
});
</script>
@endsection
