{{-- Services Section Component --}}
@props(["services"])

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


