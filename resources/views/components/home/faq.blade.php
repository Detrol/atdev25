{{-- FAQ Section Component - Database-driven --}}

<section id="faq" class="relative py-24 bg-gradient-to-b from-gray-50 via-white to-gray-50 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 overflow-hidden">
    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 bg-clip-text text-transparent mb-4" data-lazy="fade-in">
                Vanliga Frågor
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400" data-lazy="fade-in" data-delay="100">
                Svar på det du kanske undrar
            </p>
        </div>

        <!-- FAQ Accordion -->
        <div class="grid md:grid-cols-2 gap-4" x-data="{ openFaq: null }">
            @forelse($faqsWithStyling as $item)
                <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-2 transition-all duration-300 {{ $item['color']['hover'] }}"
                     :class="openFaq === {{ $item['number'] }} ? '{{ $item['color']['border'] }} shadow-2xl {{ $item['color']['shadow'] }}' : 'border-gray-200 dark:border-gray-700'"
                     data-lazy="slide-up"
                     data-delay="{{ $item['delay'] }}">

                    <button @click="openFaq = openFaq === {{ $item['number'] }} ? null : {{ $item['number'] }}"
                            class="w-full text-left p-6 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 {{ $item['color']['bg'] }} rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $item['icon'] !!}
                                </svg>
                            </div>
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item['faq']->question }}</span>
                        </div>
                        <svg class="w-6 h-6 text-gray-400 flex-shrink-0 transition-transform duration-300"
                             :class="{ 'rotate-180': openFaq === {{ $item['number'] }} }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="openFaq === {{ $item['number'] }}"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="px-6 pb-6"
                         x-cloak>
                        <div class="text-gray-600 dark:text-gray-400 leading-relaxed pl-14 prose prose-sm dark:prose-invert max-w-none">
                            {!! $item['faq']->answer !!}
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">Inga FAQs tillgängliga för tillfället.</p>
                </div>
            @endforelse
        </div>

        <!-- CTA -->
        <div class="mt-12 text-center p-8 bg-gradient-to-r from-purple-50 via-blue-50 to-pink-50 dark:from-purple-900/20 dark:via-blue-900/20 dark:to-pink-900/20 rounded-2xl border border-purple-200 dark:border-purple-700/30"
             data-lazy="fade-in"
             data-delay="600">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Fick du inte svar på din fråga?</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Kontakta mig så svarar jag inom 24 timmar!
            </p>
            <a href="#contact" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 text-white rounded-full font-semibold hover:shadow-2xl hover:shadow-purple-500/30 transition-all hover:scale-105">
                Ställ Din Fråga
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

@include('components.price-calculator')
