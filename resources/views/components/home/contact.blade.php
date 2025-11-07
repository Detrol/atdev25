{{-- Contact Section Component --}}

<section id="contact" class="relative py-24 bg-white dark:bg-gray-900 overflow-hidden" x-data="{ viewed: false }" x-intersect="viewed = true; if(window.GA4) GA4.trackContactView()">
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
        <div class="mb-8 p-4 bg-red-50 dark:bg-red-900/20 rounded-2xl border-l-4 border-red-500" x-data="{ show: true }" x-show="show" x-transition x-init="if(window.GA4) GA4.trackContactError(@js($errors->all()))">
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

        <!-- Småjobb Info Box -->
        <div class="mb-8 bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 dark:from-purple-900/20 dark:via-blue-900/20 dark:to-pink-900/20 rounded-2xl p-6 border-2 border-purple-200 dark:border-purple-700">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Nytt hos mig? Testa riskfritt!</h3>
                    <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                        För projekt under <strong>5 000 kr</strong> tar jag inget förskott.
                        Du betalar först när du är 100% nöjd med leveransen.
                        Perfekt för att se hur vi jobbar tillsammans!
                    </p>
                </div>
            </div>
        </div>

        <div class="relative bg-white dark:bg-gray-800 rounded-3xl p-8 md:p-12 border-2 border-gray-200 dark:border-gray-700 shadow-2xl overflow-hidden" x-data="{ submitting: false }">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 via-blue-500/5 to-pink-500/5 -z-10"></div>
            <form method="POST" action="{{ route('contact.store') }}" @submit="submitting = true; if (window.GA4) GA4.trackContactFormSubmit('contact')" class="space-y-8" x-data="{
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

                        // Track calculator-to-contact transition
                        if (window.GA4) {
                            window.GA4.trackContactFromCalculator(this.estimationId);
                        }
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
                        @focus="nameFocused = true; if(window.GA4) GA4.trackContactInput()"
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
                        @focus="emailFocused = true; if(window.GA4) GA4.trackContactInput()"
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
                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Arbetstid</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="estimation?.hours_ai"></p>
                        </div>
                        <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-3">
                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Leveranstid</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="estimation?.delivery_weeks_ai"></p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl p-4">
                        <div class="flex justify-between items-center mb-3">
                            <p class="text-xs font-semibold opacity-90">Estimerat Pris</p>
                            <span class="px-2 py-1 bg-white/20 rounded-full text-xs font-bold">-80%</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-baseline">
                                <span class="text-sm opacity-90">Exkl. moms:</span>
                                <span class="text-xl font-bold" x-text="estimation?.price_ai"></span>
                            </div>
                            <div class="flex justify-between items-baseline border-t border-white/20 pt-2">
                                <span class="text-sm opacity-90">Inkl. moms:</span>
                                <span class="text-2xl font-bold" x-text="estimation?.price_ai_vat"></span>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-white/30">
                            <div class="flex justify-between items-center">
                                <span class="text-xs opacity-90">Din besparing (80%):</span>
                                <span class="font-bold" x-text="estimation?.savings_vat"></span>
                            </div>
                        </div>
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
                        @focus="messageFocused = true; if(window.GA4) GA4.trackContactInput()"
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
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Vi svarar vanligtvis inom 24 timmar.</p>
                    @enderror
                </div>

                <!-- Honeypot -->
                <input type="text" name="website" value="" tabindex="-1" autocomplete="off" aria-hidden="true" style="position: absolute; left: -9999px; width: 1px; height: 1px;">

                <!-- Turnstile Security Verification (Invisible) -->
                <x-turnstile theme="light" />

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
                        <a href="mailto:andreas@atdev.me" onclick="if(window.GA4) GA4.trackEmailClick('andreas@atdev.me')" class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-gray-800 rounded-full shadow-lg hover:shadow-xl transition-all hover:scale-105">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="font-semibold text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400 transition-colors">andreas@atdev.me</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

