{{-- About/Journey Section Component --}}
@props(['profile', 'workImageMedia' => []])

<!-- Om Mig Section -->
<section id="om-mig" class="py-20 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900" data-lazy>
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Text -->
            <div class="space-y-6">
                <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-400 via-blue-400 to-pink-400 bg-clip-text text-transparent" data-lazy="fade-in">
                    Min Resa
                </h2>

                <div class="space-y-4 text-gray-300 text-lg leading-relaxed" data-lazy="fade-in" data-delay="100">
                    <p>
                        Sedan jag började koda har teknologin varit min passion.
                        Genom åren har jag skapat många applikationer och sett webbutvecklingen utvecklas från enkla HTML-sidor till avancerade, AI-drivna system.
                    </p>

                    <p>
                        Jag började med HTML och ASP, gick sedan vidare till PHP, och från 2017 har Laravel varit mitt huvudramverk.
                        Idag arbetar jag med moderna verktyg som Alpine och Vue, och kombinerar webbutveckling med AI-teknologi för att skapa smarta lösningar.
                    </p>

                    <div class="relative pl-6 border-l-4 border-purple-500/50 py-2">
                        <p class="text-xl font-semibold text-purple-300 italic">
                            "Om jag hittar ett problem kommer jag alltid att hitta ett sätt att lösa det."
                        </p>
                    </div>
                </div>
            </div>

            <!-- Foto -->
            <div>
                @if(!empty($workImageMedia))
                <div data-lazy="fade-in" data-delay="200">
                    <img srcset="{{ $workImageMedia['srcset'] }}"
                         sizes="(max-width: 768px) 100vw, 600px"
                         src="{{ $workImageMedia['src'] }}"
                         alt="Andreas Thun arbetar - utvecklare vid sitt skrivbord"
                         width="800"
                         height="600"
                         loading="lazy"
                         decoding="async"
                         class="rounded-2xl shadow-2xl border border-white/10 hover:scale-105 transition-transform duration-500">
                </div>
                @else
                <div class="aspect-square bg-gradient-to-br from-purple-500/20 via-blue-500/20 to-pink-500/20 rounded-2xl border border-white/10 flex items-center justify-center backdrop-blur-sm" data-lazy="fade-in" data-delay="200">
                    <div class="text-center p-8">
                        <svg class="w-24 h-24 mx-auto mb-4 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-400 text-sm">Ladda upp en arbetsbild via admin-panelen</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
