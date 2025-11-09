{{-- Hero Section Component --}}
@props(['profile', 'avatarMedia' => []])

<section id="main-content" class="hero-container relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 gradient-mesh"></div>

    {{-- Parallax Background Elements (Organic Microbe Motion + Scroll Parallax) --}}
    <div class="hero-background absolute inset-0 overflow-hidden pointer-events-none">
        {{-- Large organic blobs - nested for combined animations --}}
        <div class="parallax-wrapper absolute top-1/4 left-10 w-[450px] h-[450px]">
            <div class="parallax-blob w-full h-full opacity-50 animate-microbe-wander"
                 style="background: radial-gradient(circle, rgba(168, 85, 247, 0.5) 0%, rgba(168, 85, 247, 0.2) 50%, transparent 75%);"></div>
        </div>

        <div class="parallax-wrapper absolute top-1/2 right-10 w-[400px] h-[400px]">
            <div class="parallax-blob w-full h-full opacity-45 animate-microbe-2"
                 style="background: radial-gradient(circle, rgba(59, 130, 246, 0.45) 0%, rgba(59, 130, 246, 0.2) 50%, transparent 75%); animation-delay: 3s;"></div>
        </div>

        <div class="parallax-wrapper absolute bottom-1/4 left-1/3 w-[350px] h-[350px]">
            <div class="parallax-blob w-full h-full opacity-40 animate-microbe-drift"
                 style="background: radial-gradient(circle, rgba(236, 72, 153, 0.4) 0%, rgba(236, 72, 153, 0.15) 50%, transparent 75%); animation-delay: 7s;"></div>
        </div>

        {{-- Floating geometric shapes with organic movement --}}
        <div class="parallax-wrapper absolute top-1/4 right-1/4 w-32 h-32">
            <div class="parallax-shape w-full h-full border-2 border-white/15 bg-gradient-to-br from-white/10 to-transparent rounded-2xl animate-microbe-1" style="animation-delay: 2s;"></div>
        </div>

        <div class="parallax-wrapper absolute bottom-1/3 left-1/4 w-28 h-28">
            <div class="parallax-shape w-full h-full border-2 border-purple-400/20 bg-gradient-to-br from-purple-500/15 to-transparent rounded-full animate-microbe-3" style="animation-delay: 5s;"></div>
        </div>

        <div class="parallax-wrapper absolute top-2/3 right-1/3 w-24 h-24">
            <div class="parallax-shape w-full h-full border-2 border-blue-400/20 bg-gradient-to-br from-blue-500/15 to-transparent rounded-lg animate-microbe-pulse" style="animation-delay: 1s;"></div>
        </div>
    </div>

    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/30 to-black/50"></div>
    <div class="relative z-10 max-w-5xl mx-auto px-6 py-32 text-center text-white" style="min-height: 600px;">
        <div class="space-y-8">
            <div class="flex flex-col gap-3 justify-center items-center">
                <div class="hero-badge inline-flex items-center gap-3 px-6 py-3 bg-white/10 dark:bg-white/5 backdrop-blur-md rounded-full border border-white/20 dark:border-white/10 pulse-glow">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span><span class="text-sm font-medium">Tillgänglig för nya projekt</span>
                </div>
                <div class="hero-badge inline-flex items-center gap-2 px-4 py-2 bg-purple-500/20 dark:bg-purple-500/10 backdrop-blur-md rounded-full border border-purple-400/30 dark:border-purple-400/20">
                    <svg class="w-4 h-4 text-purple-300 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-white">Småjobb utan förskott</span>
                </div>
            </div>

            @if(!empty($avatarMedia))
            <div class="hero-avatar">
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

            <h1 class="hero-title text-5xl md:text-7xl font-bold leading-tight">
                Hej! Mitt namn är Andreas
            </h1>

            <p class="hero-subtitle text-2xl md:text-3xl text-white/90 font-medium">
                Passion för problemlösning och innovation
            </p>

            <p class="text-lg md:text-xl text-white/80 max-w-3xl mx-auto leading-relaxed">
                Jag är en erfaren webbutvecklare med över 20 års erfarenhet.
                Jag skapar moderna, säkra och effektiva webblösningar för privatpersoner, företag och organisationer.
            </p>

            <div class="hero-experience inline-block px-6 py-3 bg-white/10 dark:bg-white/5 backdrop-blur-md rounded-full border border-white/20 dark:border-white/10">
                <span class="text-lg font-semibold">20+ års erfarenhet</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4">
                <a href="#projects"
                   onclick="if(window.GA4) GA4.trackHeroCTA('projects')"
                   class="hero-cta px-8 py-4 bg-white dark:bg-white/90 text-purple-600 rounded-full font-semibold hover:bg-white/90 dark:hover:bg-white transition-all hover:scale-105 shadow-2xl">Utforska Mina Projekt</a>
                <a href="#contact"
                   onclick="if(window.GA4) GA4.trackHeroCTA('contact')"
                   class="hero-cta px-8 py-4 bg-white/10 dark:bg-white/5 backdrop-blur-md text-white rounded-full font-semibold hover:bg-white/20 dark:hover:bg-white/10 transition-all border border-white/20 dark:border-white/10">Kontakta Mig</a>
                <a href="#price-calculator"
                   onclick="if(window.GA4) GA4.trackHeroCTA('calculator')"
                   class="hero-cta px-8 py-4 bg-gradient-to-r from-purple-500/20 to-blue-500/20 dark:from-purple-500/10 dark:to-blue-500/10 backdrop-blur-md text-white rounded-full font-semibold hover:from-purple-500/30 hover:to-blue-500/30 dark:hover:from-purple-500/20 dark:hover:to-blue-500/20 transition-all border border-purple-400/30 dark:border-purple-400/20 hover:scale-105">Beräkna Pris ⚡</a>
            </div>
        </div>
    </div>
</section>
