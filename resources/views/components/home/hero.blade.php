{{-- Hero Section Component --}}
@props(['profile'])

<section id="main-content" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 gradient-mesh"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/30 to-black/50"></div>
    <div class="relative z-10 max-w-5xl mx-auto px-6 py-32 text-center text-white">
        <div class="space-y-8" x-data="{ visible: false }" x-intersect="visible = true" x-init="setTimeout(() => visible = true, 100)">
            <div class="inline-flex items-center gap-3 px-6 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 pulse-glow" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-100" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span><span class="text-sm font-medium">Tillgänglig för nya projekt</span>
            </div>

            @if($profile && $profile->hasMedia('avatar'))
            <div x-show="visible" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <img src="{{ $profile->getFirstMediaUrl('avatar', 'optimized') }}"
                     alt="Andreas Thun"
                     class="w-32 h-32 rounded-full mx-auto mb-6 border-4 border-white/20 shadow-2xl">
            </div>
            @endif

            <h1 class="text-5xl md:text-7xl font-bold leading-tight" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Hej! Mitt namn är Andreas
            </h1>

            <p class="text-2xl md:text-3xl text-white/90 font-medium" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-400" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Passion för problemlösning och innovation
            </p>

            <p class="text-lg md:text-xl text-white/80 max-w-3xl mx-auto leading-relaxed" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-500" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                Jag är en erfaren webbutvecklare med över 20 års erfarenhet.
                Jag skapar moderna, säkra och effektiva webblösningar för privatpersoner, företag och organisationer.
            </p>

            <div class="inline-block px-6 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-600" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <span class="text-lg font-semibold">20+ års erfarenhet</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4" x-show="visible" x-transition:enter="transition ease-out duration-700 delay-700" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <a href="#projects" class="px-8 py-4 bg-white text-purple-600 rounded-full font-semibold hover:bg-white/90 transition-all hover:scale-105 shadow-2xl">Se Mina Projekt</a>
                <a href="#contact" class="px-8 py-4 bg-white/10 backdrop-blur-md text-white rounded-full font-semibold hover:bg-white/20 transition-all border border-white/20">Kontakta Mig</a>
            </div>
        </div>
    </div>
</section>
