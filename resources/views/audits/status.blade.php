@extends('layouts.app')

@section('title', 'Website Audit - ' . $audit->url)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4">
    <div class="max-w-5xl mx-auto">
        <!-- Status Header -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Website Audit</h1>
                    <a href="{{ $audit->url }}" target="_blank" class="text-purple-600 dark:text-purple-400 hover:underline flex items-center gap-2">
                        {{ $audit->url }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>

                <div class="text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold
                        @if($audit->status === 'completed') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                        @elseif($audit->status === 'processing') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                        @elseif($audit->status === 'pending') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300
                        @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                        @endif">
                        @if($audit->status === 'completed')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        @elseif($audit->status === 'processing')
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        @endif
                        {{ $audit->status_label }}
                    </div>
                </div>
            </div>

            @if($audit->isCompleted())
            <!-- Score Cards -->
            <div class="grid md:grid-cols-3 gap-6 mt-8">
                <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20 rounded-2xl border border-purple-200 dark:border-purple-800">
                    <div class="text-5xl font-bold text-purple-600 dark:text-purple-400 mb-2">{{ $audit->seo_score }}</div>
                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">SEO</div>
                </div>

                <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-2xl border border-blue-200 dark:border-blue-800">
                    <div class="text-5xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ $audit->performance_score }}</div>
                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">Performance</div>
                </div>

                <div class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl border border-green-200 dark:border-green-800">
                    <div class="text-5xl font-bold text-green-600 dark:text-green-400 mb-2">{{ $audit->overall_score }}</div>
                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">Övergripande</div>
                </div>
            </div>

            @if($audit->screenshot_path)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Screenshot</h3>
                <img src="{{ asset('storage/' . $audit->screenshot_path) }}"
                     alt="Screenshot av {{ $audit->url }}"
                     class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-lg">
            </div>
            @endif
            @endif

            @if($audit->isPending() || $audit->isProcessing())
            <div class="mt-8 text-center py-12">
                <div class="inline-block p-4 bg-blue-50 dark:bg-blue-900/30 rounded-full mb-4">
                    <svg class="w-12 h-12 text-blue-600 dark:text-blue-400 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Din granskning bearbetas...</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Detta tar normalt 2-5 minuter. Sidan uppdateras automatiskt.</p>
                <button onclick="location.reload()" class="px-6 py-3 bg-purple-600 text-white rounded-xl font-semibold hover:bg-purple-700 transition-colors">
                    Uppdatera Status
                </button>
            </div>

            <script>
                // Auto-refresh every 10 seconds if not completed
                @if(!$audit->isCompleted())
                setTimeout(() => location.reload(), 10000);
                @endif
            </script>
            @endif

            @if($audit->status === 'failed')
            <div class="mt-8 p-6 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 rounded-lg">
                <h3 class="text-lg font-semibold text-red-900 dark:text-red-200 mb-2">Granskningen misslyckades</h3>
                <p class="text-red-700 dark:text-red-300">Det uppstod ett problem när vi försökte granska webbplatsen. Detta kan bero på att sidan inte är tillgänglig, blockerar automatisk åtkomst, eller har tekniska problem.</p>
                <a href="{{ route('audits.create') }}" class="inline-block mt-4 px-6 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors">
                    Försök med en annan URL
                </a>
            </div>
            @endif
        </div>

        <!-- AI Report -->
        @if($audit->isCompleted() && $audit->ai_report)
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 border border-gray-200 dark:border-gray-700 prose prose-lg dark:prose-invert max-w-none">
            {!! \Illuminate\Support\Str::markdown($audit->ai_report) !!}
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('audits.create') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-full font-semibold hover:shadow-2xl hover:shadow-purple-500/30 transition-all hover:scale-105">
                Granska En Annan Webbplats
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
