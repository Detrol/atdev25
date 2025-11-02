@extends('layouts.app')

@section('title', 'GDPR Showcase - ATDev')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white">GDPR Showcase</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Demonstration av GDPR-compliance funktioner
                    </p>
                </div>
            </div>

            <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 rounded">
                <p class="text-sm text-yellow-800 dark:text-yellow-400">
                    <strong>OBS:</strong> Detta är en showcase/demonstration. Funktionerna visar hur GDPR right-to-be-forgotten
                    och data portability skulle fungera i en riktig applikation med användarregistrering.
                </p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            {{-- Data Export Demo --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8" x-data="dataExportDemo()">
                <div class="flex items-center space-x-3 mb-6">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Data Export (Right to Portability)</h2>
                </div>

                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Begär en kopia av alla dina personuppgifter i maskinläsbart format (JSON).
                    Detta demonstrerar GDPR Artikel 20 - Rätten till dataportabilitet.
                </p>

                <form @submit.prevent="requestExport()" class="space-y-4">
                    <div>
                        <label for="export-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Din e-postadress
                        </label>
                        <input type="email"
                               id="export-email"
                               x-model="email"
                               required
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white"
                               placeholder="din@email.com">
                    </div>

                    <button type="submit"
                            :disabled="loading"
                            :class="loading ? 'opacity-50 cursor-not-allowed' : ''"
                            class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <svg x-show="loading" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Begär data export</span>
                    </button>
                </form>

                {{-- Result --}}
                <div x-show="result" x-transition class="mt-6">
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <h3 class="font-semibold text-green-900 dark:text-green-400 mb-2">✓ Export genererad!</h3>
                        <p class="text-sm text-green-800 dark:text-green-400 mb-3" x-text="message"></p>

                        <div x-show="summary" class="text-sm text-green-900 dark:text-green-300 mb-3">
                            <strong>Data som hittades:</strong>
                            <ul class="list-disc list-inside mt-1">
                                <li x-show="summary?.summary?.contact_messages > 0" x-text="`Kontaktmeddelanden: ${summary?.summary?.contact_messages}`"></li>
                                <li x-show="summary?.summary?.chat_sessions > 0" x-text="`Chat-sessioner: ${summary?.summary?.chat_sessions}`"></li>
                            </ul>
                        </div>

                        <button @click="showExportData()" class="text-sm text-green-700 dark:text-green-400 hover:underline">
                            → Visa exporterad data (JSON)
                        </button>
                    </div>
                </div>

                {{-- JSON Preview Modal --}}
                <div x-show="showJson"
                     x-transition
                     @click.away="showJson = false"
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-black opacity-50"></div>
                        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Exporterad data (JSON)</h3>
                                <button @click="showJson = false" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <pre class="bg-gray-100 dark:bg-gray-900 p-4 rounded overflow-auto max-h-96 text-xs" x-text="JSON.stringify(exportData, null, 2)"></pre>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Deletion Demo --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8" x-data="dataDeletionDemo()">
                <div class="flex items-center space-x-3 mb-6">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Data Deletion (Right to Erasure)</h2>
                </div>

                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Begär radering av alla dina personuppgifter. Detta demonstrerar GDPR Artikel 17 -
                    Rätten att bli glömd ("Right to be Forgotten").
                </p>

                <form @submit.prevent="requestDeletion()" class="space-y-4">
                    <div>
                        <label for="delete-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Din e-postadress
                        </label>
                        <input type="email"
                               id="delete-email"
                               x-model="email"
                               required
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white"
                               placeholder="din@email.com">
                    </div>

                    <button type="submit"
                            :disabled="loading"
                            :class="loading ? 'opacity-50 cursor-not-allowed' : ''"
                            class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <svg x-show="loading" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Begär radering</span>
                    </button>
                </form>

                {{-- Result --}}
                <div x-show="result" x-transition class="mt-6">
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <h3 class="font-semibold text-red-900 dark:text-red-400 mb-2">✓ Deletion request skapad!</h3>
                        <p class="text-sm text-red-800 dark:text-red-400 mb-3" x-text="message"></p>

                        <div x-show="summary" class="text-sm text-red-900 dark:text-red-300 mb-3">
                            <strong>Data som skulle raderas:</strong>
                            <ul class="list-disc list-inside mt-1">
                                <li x-show="summary?.data_found?.contact_messages > 0" x-text="`Kontaktmeddelanden: ${summary?.data_found?.contact_messages}`"></li>
                                <li x-show="summary?.data_found?.chat_sessions > 0" x-text="`Chat-sessioner: ${summary?.data_found?.chat_sessions}`"></li>
                            </ul>
                        </div>

                        <button @click="showEmailPreview()" class="text-sm text-red-700 dark:text-red-400 hover:underline">
                            → Visa bekräftelsemail (mockad)
                        </button>
                    </div>
                </div>

                {{-- Email Preview Modal --}}
                <div x-show="showEmail"
                     x-transition
                     @click.away="showEmail = false"
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-black opacity-50"></div>
                        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bekräftelsemail (Preview)</h3>
                                <button @click="showEmail = false" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-900 p-4 rounded">
                                <div class="text-sm mb-2">
                                    <strong>Till:</strong> <span x-text="emailPreview?.to"></span><br>
                                    <strong>Från:</strong> <span x-text="emailPreview?.from"></span><br>
                                    <strong>Ämne:</strong> <span x-text="emailPreview?.subject"></span>
                                </div>
                                <hr class="my-3 border-gray-300 dark:border-gray-600">
                                <pre class="whitespace-pre-wrap text-sm" x-text="emailPreview?.body"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Documentation Section --}}
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Om GDPR och dessa funktioner</h2>

            <div class="prose dark:prose-invert max-w-none">
                <p>
                    GDPR (General Data Protection Regulation) är EU:s dataskyddsförordning som skyddar individers
                    personuppgifter. Denna showcase demonstrerar två viktiga GDPR-rättigheter:
                </p>

                <h3>Artikel 20 - Rätten till dataportabilitet</h3>
                <p>
                    Du har rätt att få ut dina personuppgifter i ett strukturerat, maskinläsbart format.
                    Detta gör det möjligt att flytta dina data mellan olika tjänster.
                </p>

                <h3>Artikel 17 - Rätten att bli glömd</h3>
                <p>
                    Du har rätt att få dina personuppgifter raderade under vissa omständigheter. Detta kallas
                    även "Right to be Forgotten" och är en grundläggande del av GDPR.
                </p>

                <h3>Teknisk implementation</h3>
                <p>I en riktig applikation med användarregistrering skulle detta fungera så här:</p>
                <ol>
                    <li>Användaren begär data export/deletion via email</li>
                    <li>Systemet skapar en verifikations-token (giltig i 24 timmar)</li>
                    <li>Användaren får ett bekräftelsemail med en länk</li>
                    <li>När användaren klickar på länken processas requesten</li>
                    <li>Data exporteras som JSON eller raderas/anonymiseras</li>
                </ol>

                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded">
                    <p class="text-sm">
                        <strong>För mer information:</strong> Läs vår fullständiga
                        <a href="{{ route('gdpr.privacy') }}" class="text-blue-600 hover:text-blue-700">Integritetspolicy</a>
                        och <a href="{{ route('gdpr.cookies') }}" class="text-blue-600 hover:text-blue-700">Cookie-policy</a>.
                    </p>
                </div>
            </div>
        </div>

        {{-- Back to home --}}
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}"
               class="inline-flex items-center text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                ← Tillbaka till startsidan
            </a>
        </div>
    </div>
</div>

<script>
function dataExportDemo() {
    return {
        email: '',
        loading: false,
        result: false,
        message: '',
        summary: null,
        exportData: null,
        showJson: false,

        async requestExport() {
            this.loading = true;
            this.result = false;

            try {
                const response = await fetch('/gdpr/export-demo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email: this.email })
                });

                const data = await response.json();

                if (data.success) {
                    this.result = true;
                    this.message = data.message;
                    this.summary = data.summary;
                    this.exportData = data.data;
                }
            } catch (error) {
                alert('Ett fel uppstod: ' + error.message);
            } finally {
                this.loading = false;
            }
        },

        showExportData() {
            this.showJson = true;
        }
    };
}

function dataDeletionDemo() {
    return {
        email: '',
        loading: false,
        result: false,
        message: '',
        summary: null,
        emailPreview: null,
        showEmail: false,

        async requestDeletion() {
            this.loading = true;
            this.result = false;

            try {
                const response = await fetch('/gdpr/delete-demo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email: this.email })
                });

                const data = await response.json();

                if (data.success) {
                    this.result = true;
                    this.message = data.message;
                    this.summary = data.summary;
                    this.emailPreview = data.email_preview;
                }
            } catch (error) {
                alert('Ett fel uppstod: ' + error.message);
            } finally {
                this.loading = false;
            }
        },

        showEmailPreview() {
            this.showEmail = true;
        }
    };
}
</script>
@endsection
