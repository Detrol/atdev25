{{-- About/Journey Section Component --}}
@props(['profile', 'workImageMedia' => []])

<!-- Om Mig Section -->
<x-animated-section
    id="om-mig"
    theme="purple-blue"
    next-theme="blue-pink"
    pattern="geometric-lines"
>
    {{-- Wave transition from Hero section --}}
    <x-wave-divider color="purple-blue" position="top" />

    <div class="max-w-6xl mx-auto px-6 py-20">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Text -->
            <div class="space-y-6">
                <h2 class="about-title text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 dark:from-purple-400 dark:via-blue-400 dark:to-pink-400 bg-clip-text text-transparent">
                    Min Resa
                </h2>

                <div class="space-y-4 text-gray-700 dark:text-gray-300 text-lg leading-relaxed">
                    <p class="about-paragraph">
                        Sedan jag började koda har teknologin varit min passion.
                        Genom åren har jag skapat många applikationer och sett webbutvecklingen utvecklas från enkla HTML-sidor till avancerade, AI-drivna system.
                    </p>

                    <p class="about-paragraph">
                        Jag började med HTML och ASP, gick sedan vidare till PHP, och från 2017 har Laravel varit mitt huvudramverk.
                        Idag arbetar jag med moderna verktyg som Alpine och Vue, och kombinerar webbutveckling med AI-teknologi för att skapa smarta lösningar.
                    </p>
                </div>
            </div>

            <!-- Foto -->
            <div>
                @if(!empty($workImageMedia))
                <div class="about-image">
                    <img srcset="{{ $workImageMedia['srcset'] }}"
                         sizes="(max-width: 768px) 100vw, 600px"
                         src="{{ $workImageMedia['src'] }}"
                         alt="Andreas Thun arbetar - utvecklare vid sitt skrivbord"
                         width="800"
                         height="600"
                         loading="lazy"
                         decoding="async"
                         class="rounded-2xl shadow-2xl border border-gray-200 dark:border-white/10 hover:scale-105 transition-transform duration-500">
                </div>
                @else
                <div class="about-image aspect-square bg-gradient-to-br from-purple-500/20 via-blue-500/20 to-pink-500/20 rounded-2xl border border-gray-200 dark:border-white/10 flex items-center justify-center backdrop-blur-sm">
                    <div class="text-center p-8">
                        <svg class="w-24 h-24 mx-auto mb-4 text-gray-300 dark:text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Ladda upp en arbetsbild via admin-panelen</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Wave separator to How I Work (uses next section's color) --}}
    <x-wave-divider color="blue-pink" position="bottom" />
</x-animated-section>
