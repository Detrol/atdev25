@extends('layouts.app')

@section('title', 'Cookie-policy - ATDev')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Cookie-policy</h1>
            <p class="text-gray-600 dark:text-gray-400">Senast uppdaterad: {{ now()->format('Y-m-d') }}</p>
        </div>

        {{-- Content --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 prose dark:prose-invert max-w-none">

            <h2>1. Vad är cookies?</h2>
            <p>
                Cookies är små textfiler som lagras på din dator eller mobila enhet när du besöker en webbplats.
                De hjälper webbplatsen att komma ihåg information om ditt besök, som dina preferenser och tidigare aktiviteter.
            </p>
            <p>
                Cookies kan vara "sessionsbaserade" (raderas när du stänger webbläsaren) eller "permanenta"
                (sparas tills de löper ut eller du raderar dem manuellt).
            </p>

            <h2>2. Varför använder vi cookies?</h2>
            <p>Vi använder cookies för att:</p>
            <ul>
                <li>Säkerställa att webbplatsen fungerar korrekt</li>
                <li>Komma ihåg dina preferenser (t.ex. mörkt läge)</li>
                <li>Förbättra användarupplevelsen</li>
                <li>Analysera hur besökare använder webbplatsen</li>
                <li>Skydda mot spam och säkerhetsincidenter</li>
            </ul>

            <h2>3. Vilka cookies använder vi?</h2>
            <p>Vi kategoriserar cookies i fyra huvudgrupper:</p>

            {{-- Nödvändiga Cookies --}}
            <h3 class="mt-8 text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                3.1 Nödvändiga cookies
                <span class="ml-2 text-xs px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full">
                    Alltid aktiva
                </span>
            </h3>
            <p>
                Dessa cookies är nödvändiga för att webbplatsen ska fungera och kan inte stängas av.
                De används vanligtvis bara som svar på åtgärder du gör, som att sätta säkerhetsinställningar
                eller fylla i formulär.
            </p>

            <div class="overflow-x-auto mt-4">
                <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Cookie-namn</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Syfte</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Giltighetstid</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2"><code>XSRF-TOKEN</code></td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">CSRF-skydd för formulär</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">2 timmar</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2"><code>laravel_session</code></td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Session-hantering</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">2 timmar</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Funktionella Cookies --}}
            <h3 class="mt-8 text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                <span class="inline-block w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                3.2 Funktionella cookies
                <span class="ml-2 text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full">
                    Kan stängas av
                </span>
            </h3>
            <p>
                Dessa cookies gör att webbplatsen kan komma ihåg val du gör (som ditt användarnamn,
                språk eller region) och ger förbättrade, mer personliga funktioner.
            </p>

            <div class="overflow-x-auto mt-4">
                <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Cookie-namn</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Syfte</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Giltighetstid</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2"><code>darkMode</code> (localStorage)</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Sparar din preferens för mörkt/ljust läge</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Permanent</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2"><code>chat_session_id</code> (localStorage)</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Sparar din AI-chat historik mellan besök</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Permanent</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                <em>OBS: Om du stänger av funktionella cookies kommer dina preferenser inte att sparas mellan besök.</em>
            </p>

            {{-- Analytics Cookies --}}
            <h3 class="mt-8 text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                <span class="inline-block w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                3.3 Analytics cookies
                <span class="ml-2 text-xs px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 rounded-full">
                    Kan stängas av
                </span>
            </h3>
            <p>
                Dessa cookies hjälper oss att förstå hur besökare interagerar med webbplatsen genom att
                samla in och rapportera information anonymt.
            </p>

            <div class="overflow-x-auto mt-4">
                <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Cookie-namn</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Leverantör</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Syfte</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Giltighetstid</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2"><code>_ga</code></td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Google Analytics</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Unik identifierare för besökare</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">2 år</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2"><code>_ga_*</code></td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Google Analytics</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Session-data och händelser</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">2 år</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p class="mt-4 text-sm bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4">
                <strong>Status:</strong> Vi använder för närvarande inte Google Analytics eller andra analytics-tjänster.
                Dessa cookies laddas endast om du godkänner analytics-kategorin och vi aktiverar tjänsten i framtiden.
            </p>

            {{-- Marketing Cookies --}}
            <h3 class="mt-8 text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                <span class="inline-block w-3 h-3 bg-purple-500 rounded-full mr-2"></span>
                3.4 Marketing cookies
                <span class="ml-2 text-xs px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 rounded-full">
                    Kan stängas av
                </span>
            </h3>
            <p>
                Dessa cookies används för att spåra besökare över webbplatser i syfte att visa annonser
                som är relevanta och engagerande för den enskilda användaren.
            </p>

            <div class="overflow-x-auto mt-4">
                <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Cookie-namn</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Leverantör</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Syfte</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Giltighetstid</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2"><code>_fbp</code></td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Facebook Pixel</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Spåra besökare för annonser</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">3 månader</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2"><code>li_fat_id</code></td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">LinkedIn Insight</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Spåra besökare för B2B-annonser</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">30 dagar</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p class="mt-4 text-sm bg-purple-50 dark:bg-purple-900/20 border-l-4 border-purple-500 p-4">
                <strong>Status:</strong> Vi använder för närvarande inte marketing pixels eller annonsspårning.
                Dessa cookies laddas endast om du godkänner marketing-kategorin och vi aktiverar tjänster i framtiden.
            </p>

            <h2>4. Tredjepartscookies</h2>
            <p>
                Vissa cookies på vår webbplats sätts av tredjepartstjänster. Vi delar inte personuppgifter
                med dessa tredjeparter utan ditt samtycke.
            </p>

            <h3>4.1 Anthropic (AI-chatbot)</h3>
            <p>
                Vår AI-chatbot använder Anthropic Claude API. Konversationer skickas till Anthropic för
                bearbetning men Anthropic sätter inte cookies på vår webbplats.
            </p>

            <h3>4.2 Mailgun (E-postleverans)</h3>
            <p>
                Vi använder Mailgun för att skicka och ta emot e-post. Mailgun sätter inte cookies
                på vår webbplats.
            </p>

            <h2>5. Hur hanterar jag cookies?</h2>

            <h3>5.1 Via vår cookie-banner</h3>
            <p>
                När du besöker vår webbplats för första gången visas en cookie-banner där du kan välja
                vilka kategorier av cookies du vill tillåta.
            </p>
            <p>
                <button onclick="window.dispatchEvent(new CustomEvent('open-cookie-banner'))"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Öppna cookie-inställningar
                </button>
            </p>

            <h3>5.2 Via din webbläsare</h3>
            <p>De flesta webbläsare låter dig hantera cookies genom inställningar:</p>
            <ul>
                <li>
                    <strong>Chrome:</strong>
                    <a href="https://support.google.com/chrome/answer/95647" target="_blank" class="text-blue-600 hover:text-blue-700">
                        Hantera cookies i Chrome
                    </a>
                </li>
                <li>
                    <strong>Firefox:</strong>
                    <a href="https://support.mozilla.org/sv/kb/kakor-information-webbplatser-lagrar-pa-din-dator" target="_blank" class="text-blue-600 hover:text-blue-700">
                        Hantera cookies i Firefox
                    </a>
                </li>
                <li>
                    <strong>Safari:</strong>
                    <a href="https://support.apple.com/sv-se/guide/safari/sfri11471/mac" target="_blank" class="text-blue-600 hover:text-blue-700">
                        Hantera cookies i Safari
                    </a>
                </li>
                <li>
                    <strong>Edge:</strong>
                    <a href="https://support.microsoft.com/sv-se/microsoft-edge/ta-bort-cookies-i-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" class="text-blue-600 hover:text-blue-700">
                        Hantera cookies i Edge
                    </a>
                </li>
            </ul>

            <p class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 text-sm">
                <strong>OBS:</strong> Om du blockerar nödvändiga cookies kan vissa delar av webbplatsen
                sluta fungera korrekt.
            </p>

            <h2>6. Länkar till tredjepartswebbplatser</h2>
            <p>
                Vår webbplats kan innehålla länkar till andra webbplatser. Vi ansvarar inte för
                cookie-hanteringen på dessa webbplatser. Vi rekommenderar att du läser deras cookie-policies.
            </p>

            <h2>7. Ändringar av denna cookie-policy</h2>
            <p>
                Vi kan uppdatera denna cookie-policy från tid till annan. Eventuella ändringar publiceras
                på denna sida med ett uppdaterat datum.
            </p>
            <p>
                <strong>Senaste uppdatering:</strong> {{ now()->format('Y-m-d') }}
            </p>

            <h2>8. Kontakta oss</h2>
            <p>
                Om du har frågor om vår användning av cookies, kontakta oss:
            </p>
            <p>
                <strong>E-post:</strong> <a href="mailto:andreas@atdev.me" class="text-blue-600 hover:text-blue-700">andreas@atdev.me</a><br>
                <strong>Frilansare:</strong> Andreas Thun (ATDev)<br>
                <strong>Fakturering:</strong> Via Frilans Finans
            </p>

            <h2>9. Mer information om cookies</h2>
            <p>
                För mer allmän information om cookies, besök:
            </p>
            <ul>
                <li>
                    <a href="https://www.allaboutcookies.org/" target="_blank" class="text-blue-600 hover:text-blue-700">
                        All About Cookies
                    </a>
                </li>
                <li>
                    <a href="https://www.imy.se/privatperson/dataskydd/introduktion-till-gdpr/vad-sager-lagen/" target="_blank" class="text-blue-600 hover:text-blue-700">
                        Integritetsskyddsmyndigheten (IMY)
                    </a>
                </li>
            </ul>
        </div>

        {{-- Cookie Settings --}}
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6 text-center border border-blue-200 dark:border-blue-800">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                Vill du ändra dina cookie-inställningar?
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Du kan när som helst uppdatera dina preferenser
            </p>
            <button onclick="window.dispatchEvent(new CustomEvent('open-cookie-banner'))"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                Hantera cookie-inställningar
            </button>
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
@endsection
