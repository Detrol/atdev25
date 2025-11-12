<!DOCTYPE html>
<html lang="sv" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Darkmode is forced (space theme requirement) - no FOIT fix needed --}}

    <!-- SEO Meta Tags -->
    @isset($seoTitle, $seoDescription)
        <x-seo-meta
            :title="$seoTitle"
            :description="$seoDescription"
            :keywords="$seoKeywords ?? null"
            :og-image="$seoImage ?? null"
            :og-type="$seoType ?? 'website'"
            :preload-image="$preloadImage ?? null"
        />
    @else
        <x-seo-meta
            title="ATDev - AI-Driven Utveckling | 20+ Års Erfarenhet"
            description="AI-driven webbutveckling med 20+ års erfarenhet. Specialist på Laravel, Vue, Alpine.js och AI-integration. Högkvalitativa lösningar till konkurrenskraftiga priser."
            keywords="webbutveckling, AI-utveckling, Laravel, Vue.js, Alpine.js, prompt engineering, AI-expert, Andreas Thun, ATDev"
        />
    @endisset

    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- reCAPTCHA Site Key -->
    @if(config('recaptcha.enabled'))
    <meta name="recaptcha-site-key" content="{{ config('recaptcha.site_key') }}">
    @endif

    @vite(['resources/css/app.css', 'resources/css/chat-widget.css', 'resources/js/app.js'])

    <!-- Google Analytics 4 (GDPR-compliant) -->
    <x-google-analytics />

    <!-- Google reCAPTCHA v3 (loaded once globally) -->
    @if(config('recaptcha.enabled'))
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}" async defer></script>
    @endif

    <!-- Structured Data (JSON-LD) -->
    @isset($structuredData)
        {!! $structuredData !!}
    @endisset
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300 flex flex-col min-h-screen">
    <main role="main" aria-label="Huvudinnehåll" class="flex-grow">
        @yield('content')
    </main>

    <!-- Certifications & Badges -->
    {{-- <x-certifications-badges /> --}}

    <!-- Footer -->
    <x-footer />

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed bottom-4 right-4 z-50 max-w-md" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="glass dark:glass-dark-border rounded-2xl p-4 shadow-2xl border-l-4 border-green-500">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="font-medium text-gray-800 dark:text-gray-100">{{ session('success') }}</p>
                    <button @click="show = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 ml-auto">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed bottom-4 right-4 z-50 max-w-md" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="glass dark:glass-dark-border rounded-2xl p-4 shadow-2xl border-l-4 border-red-500">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <p class="font-medium text-gray-800 dark:text-gray-100">{{ session('error') }}</p>
                    <button @click="show = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 ml-auto">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Cookie Consent Banner -->
    <x-cookie-consent />

    <!-- AI Chat Widget -->
    <div x-data="chatWidget()" x-init="init()" class="chat-widget-container">
        <!-- Chat Button -->
        <button @click="toggleChat()" class="chat-button" aria-label="Öppna AI-assistent">
            <svg x-show="!isOpen" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
            <svg x-show="isOpen" x-cloak fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Chat Window -->
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="chat-window"
             x-cloak>

            <!-- Header -->
            <div class="chat-header">
                <div class="chat-header-title">
                    <div class="chat-header-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <div class="chat-header-text">
                        <h3>AI Demo-Assistent</h3>
                        <p>Skräddarsydd för företag</p>
                    </div>
                </div>
                <button @click="toggleChat()" class="chat-close" aria-label="Stäng chat">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Error Message -->
            <div x-show="error" x-transition class="chat-error" x-cloak>
                <span x-text="error"></span>
                <button @click="clearError()" class="chat-error-close">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <!-- Messages -->
            <div class="chat-messages" x-ref="messagesContainer">
                <!-- Welcome message - Always visible -->
                <div class="py-6 px-4 border-b border-gray-200/50 dark:border-gray-700/50 mb-4">
                    <!-- Welcome Header -->
                    <div class="text-center mb-4">
                        <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-purple-500 to-blue-500 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">👋 Detta är en demo-assistent</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                            Den visar hur AI kan skräddarsys för <span class="font-semibold text-purple-600 dark:text-purple-400">DITT företag</span>!<br>
                            Tränad på ATDevs data – samma teknologi kan använda <span class="font-semibold text-blue-600 dark:text-blue-400">ER specifika data</span>.
                        </p>
                    </div>

                    <!-- Example Questions -->
                    <div class="space-y-2">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Testa att fråga:</p>
                        <button @click="selectSuggestedQuestion('Vilka projekt har du byggt?')" class="suggested-question">
                            <span>💼</span>
                            <span>Vilka projekt har du byggt?</span>
                        </button>
                        <button @click="selectSuggestedQuestion('Hur kan AI integreras i min verksamhet?')" class="suggested-question">
                            <span>🤖</span>
                            <span>Hur kan AI integreras i min verksamhet?</span>
                        </button>
                        <button @click="selectSuggestedQuestion('Vad kostar en skräddarsydd AI-assistent?')" class="suggested-question">
                            <span>💰</span>
                            <span>Vad kostar en skräddarsydd AI-assistent?</span>
                        </button>
                    </div>
                </div>

                <!-- Message list -->
                <template x-for="(message, index) in messages" :key="index">
                    <div class="chat-message" :class="message.role">
                        <div class="chat-message-avatar">
                            <span x-show="message.role === 'user'">👤</span>
                            <span x-show="message.role === 'assistant'">🤖</span>
                        </div>
                        <div class="chat-message-content" x-html="message.content"></div>
                    </div>
                </template>

                <!-- Loading indicator -->
                <div x-show="isLoading" class="chat-loading" x-cloak>
                    <div class="chat-loading-avatar"></div>
                    <div class="chat-loading-dots">
                        <div class="chat-loading-dot"></div>
                        <div class="chat-loading-dot"></div>
                        <div class="chat-loading-dot"></div>
                    </div>
                </div>
            </div>

            <!-- Input -->
            <div class="chat-input">
                <div class="chat-input-wrapper">
                    <textarea
                        x-ref="messageInput"
                        x-model="inputMessage"
                        @keydown="handleKeydown($event)"
                        placeholder="Skriv ditt meddelande..."
                        class="chat-input-field"
                        rows="1"
                        :disabled="isLoading"
                    ></textarea>
                    <button
                        @click="sendMessage()"
                        :disabled="!inputMessage.trim() || isLoading"
                        class="chat-input-button"
                        aria-label="Skicka meddelande"
                    >
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Page-specific scripts -->
    @stack('scripts')

    <!-- Alpine.js is now bundled via Vite in app.js -->
</body>
</html>
