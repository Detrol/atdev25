{{-- Hero Section Component --}}
@props(['profile', 'avatarMedia' => []])

<section id="main-content" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 gradient-mesh"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/30 to-black/50"></div>
    <div class="relative z-10 max-w-5xl mx-auto px-6 py-32 text-center text-white" style="min-height: 600px;">
        <div class="space-y-8">
            <div class="flex flex-col gap-3 justify-center items-center fade-in">
                <div class="inline-flex items-center gap-3 px-6 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 pulse-glow">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span><span class="text-sm font-medium">Tillgänglig för nya projekt</span>
                </div>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-500/20 backdrop-blur-md rounded-full border border-purple-400/30">
                    <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-white">Småjobb utan förskott</span>
                </div>
            </div>

            @if(!empty($avatarMedia))
            <div class="fade-in-delay-100">
                <img
                    srcset="{{ $avatarMedia['srcset'] }}"
                    sizes="(max-width: 768px) 128px, 256px"
                    src="{{ $avatarMedia['src'] }}"
                    alt="Andreas Thun - AI-driven utvecklare med 20+ års erfarenhet"
                    width="128"
                    height="128"
                    fetchpriority="high"
                    loading="eager"
                    decoding="async"
                    class="w-32 h-32 rounded-full mx-auto mb-6 border-4 border-white/20 shadow-2xl">
            </div>
            @endif

            <h1 class="text-5xl md:text-7xl font-bold leading-tight fade-in-delay-200">
                Hej! Mitt namn är Andreas
            </h1>

            <p class="text-2xl md:text-3xl text-white/90 font-medium fade-in-delay-300">
                Passion för problemlösning och innovation
            </p>

            <p class="text-lg md:text-xl text-white/80 max-w-3xl mx-auto leading-relaxed fade-in-delay-400">
                Jag är en erfaren webbutvecklare med över 20 års erfarenhet.
                Jag skapar moderna, säkra och effektiva webblösningar för privatpersoner, företag och organisationer.
            </p>

            <div class="inline-block px-6 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 fade-in-delay-400">
                <span class="text-lg font-semibold">20+ års erfarenhet</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4 fade-in-delay-500">
                <a href="#projects" class="px-8 py-4 bg-white text-purple-600 rounded-full font-semibold hover:bg-white/90 transition-all hover:scale-105 shadow-2xl">Se Mina Projekt</a>
                <a href="#contact" class="px-8 py-4 bg-white/10 backdrop-blur-md text-white rounded-full font-semibold hover:bg-white/20 transition-all border border-white/20">Kontakta Mig</a>
                <a href="#price-calculator" class="px-8 py-4 bg-gradient-to-r from-purple-500/20 to-blue-500/20 backdrop-blur-md text-white rounded-full font-semibold hover:from-purple-500/30 hover:to-blue-500/30 transition-all border border-purple-400/30 hover:scale-105">Beräkna Pris ⚡</a>
            </div>
        </div>
    </div>
</section>
