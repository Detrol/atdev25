@extends('layouts.app')

@section('title', 'Gratis Website Audit')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 dark:from-gray-900 dark:via-purple-900/20 dark:to-blue-900/20 py-20 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-block mb-6">
                <div class="w-20 h-20 bg-gradient-to-r from-purple-600 to-blue-600 rounded-3xl flex items-center justify-center shadow-2xl shadow-purple-500/30">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Gratis AI-Driven Website Audit
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Få en professionell analys av din webbplats SEO, prestanda och användarupplevelse – analyserad av AI.
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 md:p-12 border border-gray-200 dark:border-gray-700">
            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="font-semibold text-red-800 dark:text-red-200 mb-1">Det uppstod problem:</p>
                        <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('audits.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- URL Field -->
                <div>
                    <label for="url" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Webbplats URL *
                    </label>
                    <input type="url"
                           name="url"
                           id="url"
                           value="{{ old('url') }}"
                           placeholder="https://exempel.se"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Ange den fullständiga URL:en inklusive http:// eller https://</p>
                </div>

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Ditt Namn *
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           placeholder="För- och efternamn"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        E-postadress *
                    </label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email') }}"
                           placeholder="din@epost.se"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Vi skickar rapporten hit när den är klar (~2-5 minuter)</p>
                </div>

                <!-- Company Field (Optional) -->
                <div>
                    <label for="company" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Företag (valfritt)
                    </label>
                    <input type="text"
                           name="company"
                           id="company"
                           value="{{ old('company') }}"
                           placeholder="Ditt företagsnamn"
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                </div>

                <!-- Honeypot (Hidden) -->
                <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-purple-500/50 transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Starta Gratis Granskning
                </button>

                <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-4">
                    Granskningen tar cirka 2-5 minuter. Du får rapporten via e-post och kan också följa statusen online.
                </p>
            </form>
        </div>

        <!-- Features -->
        <div class="grid md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-lg rounded-2xl p-6 text-center border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">SEO-Analys</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Meta tags, rubriker, bildoptimering och mer</p>
            </div>

            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-lg rounded-2xl p-6 text-center border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Performance</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Laddningstid, resurser och optimeringspotential</p>
            </div>

            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-lg rounded-2xl p-6 text-center border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 bg-pink-100 dark:bg-pink-900/30 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Åtgärdsförslag</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Konkreta tips för förbättringar</p>
            </div>
        </div>
    </div>
</div>
@endsection
