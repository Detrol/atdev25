@extends('layouts.app')

@section('title', 'Integritetspolicy - ATDev')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Integritetspolicy</h1>
            <p class="text-gray-600 dark:text-gray-400">Senast uppdaterad: {{ now()->format('Y-m-d') }}</p>
        </div>

        {{-- Content --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 prose dark:prose-invert max-w-none">

            <h2>1. Introduktion</h2>
            <p>
                Välkommen till ATDev. Vi värnar om din integritet och är engagerade i att skydda dina personuppgifter.
                Denna integritetspolicy förklarar hur vi samlar in, använder, lagrar och skyddar dina personuppgifter
                när du besöker vår webbplats <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-700">{{ url('/') }}</a>.
            </p>

            <h3>Dataskyddsansvarig</h3>
            <p>
                <strong>Frilansare:</strong> Andreas Thun (ATDev)<br>
                <strong>E-post:</strong> <a href="mailto:andreas@atdev.me" class="text-blue-600 hover:text-blue-700">andreas@atdev.me</a><br>
                <strong>Fakturering:</strong> Via Frilans Finans<br>
            </p>

            <h2>2. Vilka personuppgifter samlar vi in?</h2>

            <h3>2.1 Information du ger oss frivilligt</h3>
            <p>När du kontaktar oss via kontaktformuläret samlar vi in:</p>
            <ul>
                <li><strong>Namn:</strong> För att kunna tillta la dig personligt</li>
                <li><strong>E-postadress:</strong> För att kunna svara på din förfrågan</li>
                <li><strong>Meddelande:</strong> Ditt meddelande eller din fråga</li>
                <li><strong>IP-adress:</strong> För säkerhetsändamål och spam-skydd</li>
                <li><strong>User agent:</strong> Information om din webbläsare för teknisk support</li>
            </ul>

            <h3>2.2 Automatiskt insamlad information</h3>
            <p>När du besöker vår webbplats samlas följande information in automatiskt:</p>
            <ul>
                <li><strong>IP-adress:</strong> För att säkerställa funktionalitet och säkerhet</li>
                <li><strong>Webbläsarinformation:</strong> Typ av webbläsare och operativsystem</li>
                <li><strong>Besöksinformation:</strong> Sidor du besöker och tid på sidan</li>
            </ul>

            <h3>2.3 AI Chatbot</h3>
            <p>När du använder vår AI-chatbot sparas:</p>
            <ul>
                <li><strong>Session ID:</strong> En unik identifierare för din chat-session</li>
                <li><strong>Konversationshistorik:</strong> Dina frågor och AI:s svar</li>
                <li><strong>Tidsstämplar:</strong> När konversationen ägde rum</li>
            </ul>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                <em>OBS: Chat-historiken sparas endast om du godkänt funktionella cookies. Annars används en temporär session-ID som inte sparas mellan besök.</em>
            </p>

            <h2>3. Rättslig grund för behandling</h2>
            <p>Vi behandlar dina personuppgifter baserat på följande rättsliga grunder enligt GDPR:</p>

            <h3>3.1 Samtycke (Artikel 6.1.a)</h3>
            <p>
                Vissa cookies kräver ditt uttryckliga samtycke innan de aktiveras:
            </p>
            <ul>
                <li><strong>Funktionella cookies:</strong> Sparar dina preferenser (mörkt läge, chat-historik)</li>
                <li><strong>Marknadsföringscookies:</strong> Används för riktad marknadsföring och annonsering</li>
            </ul>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                <em>Observera: Analytics-cookies aktiveras baserat på berättigat intresse (se 3.2), inte samtycke.
                Du kan när som helst återkalla samtycke eller inaktivera analytics genom att ändra dina cookie-inställningar.</em>
            </p>

            <h3>3.2 Berättigat intresse (Artikel 6.1.f)</h3>
            <p>
                Vi behandlar vissa personuppgifter baserat på berättigat intresse när vårt legitima intresse
                väger tyngre än ditt intresse av integritet, och behandlingen inte är oproportionerlig eller oväntad.
            </p>

            <h4 class="text-lg font-semibold mt-4">Analytics och webbplatsanalys</h4>
            <p>
                <strong>Analytics-cookies är aktiverade som standard</strong> baserat på berättigat intresse.
                Vi har ett legitimt affärsintresse av att förstå hur besökare använder webbplatsen för att:
            </p>
            <ul>
                <li><strong>Förbättra användarupplevelsen</strong> genom att identifiera populära funktioner och vanliga problem</li>
                <li><strong>Optimera innehåll</strong> baserat på faktisk användning och beteende</li>
                <li><strong>Teknisk underhåll</strong> genom att upptäcka buggar och prestandaproblem</li>
                <li><strong>Affärsutveckling</strong> för att mäta effektiviteten av portfolion och tjänsteerbjudanden</li>
            </ul>

            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 bg-blue-50 dark:bg-blue-900/20 p-3 rounded">
                <strong>💡 Viktigt:</strong> Du kan när som helst inaktivera analytics-cookies genom att ändra dina
                cookie-inställningar. Vi använder endast anonymiserad IP-adressering och sparar ingen personligt
                identifierbar information i analytics-systemet.
            </p>

            <h4 class="text-lg font-semibold mt-4">Övriga berättigade intressen</h4>
            <ul>
                <li><strong>Spam-skydd:</strong> Förhindra missbruk av kontaktformuläret genom IP-loggning</li>
                <li><strong>Säkerhet:</strong> Upptäcka och förhindra säkerhetsincidenter</li>
                <li><strong>Systemunderhåll:</strong> Felsökning och teknisk support</li>
            </ul>

            <h3>3.3 Fullgörande av avtal (Artikel 6.1.b)</h3>
            <p>
                När du kontaktar oss för en potentiell tjänst behandlar vi dina uppgifter för att
                kunna fullgöra ett eventuellt avtal.
            </p>

            <h2>4. Hur använder vi dina personuppgifter?</h2>
            <p>Vi använder dina personuppgifter för följande ändamål:</p>
            <ul>
                <li><strong>Kommunikation:</strong> Att svara på dina förfrågningar via kontaktformuläret</li>
                <li><strong>Teknisk support:</strong> Att tillhandahålla teknisk support genom AI-chatboten</li>
                <li><strong>Förbättring av tjänster:</strong> Att analysera hur användare interagerar med webbplatsen</li>
                <li><strong>Säkerhet:</strong> Att förhindra spam, missbruk och säkerhetsincidenter</li>
                <li><strong>Rättsliga krav:</strong> Att uppfylla rättsliga skyldigheter</li>
            </ul>

            <h2>5. Hur länge sparas dina uppgifter?</h2>
            <p>Vi sparar dina personuppgifter enligt följande tidsramar:</p>
            <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600 mt-4">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Datatyp</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Lagringstid</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Kontaktmeddelanden</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">2 år från senaste aktivitet</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Chat-historik</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">1 år från senaste konversation</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Cookie-samtycken</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">1 år, sedan ny begäran</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">IP-adresser (säkerhet)</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">90 dagar</td>
                    </tr>
                </tbody>
            </table>
            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                <em>Efter dessa perioder raderas eller anonymiseras uppgifterna automatiskt.</em>
            </p>

            <h2>6. Dina rättigheter enligt GDPR</h2>
            <p>Du har följande rättigheter gällande dina personuppgifter:</p>

            <h3>6.1 Rätt till tillgång (Artikel 15)</h3>
            <p>Du har rätt att få information om vilka personuppgifter vi behandlar om dig och begära en kopia av dessa uppgifter.</p>

            <h3>6.2 Rätt till rättelse (Artikel 16)</h3>
            <p>Du har rätt att begära att felaktiga eller ofullständiga personuppgifter rättas.</p>

            <h3>6.3 Rätt till radering - "Rätten att bli glömd" (Artikel 17)</h3>
            <p>Du har rätt att begära att dina personuppgifter raderas under vissa omständigheter.</p>
            <p>
                <a href="{{ route('gdpr.showcase') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                    Begär radering av dina uppgifter →
                </a>
            </p>

            <h3>6.4 Rätt till dataportabilitet (Artikel 20)</h3>
            <p>Du har rätt att få ut dina personuppgifter i ett strukturerat, maskinläsbart format.</p>
            <p>
                <a href="{{ route('gdpr.showcase') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                    Exportera dina uppgifter →
                </a>
            </p>

            <h3>6.5 Rätt att göra invändningar (Artikel 21)</h3>
            <p>Du har rätt att invända mot behandling av dina personuppgifter i vissa fall.</p>

            <h3>6.6 Rätt att återkalla samtycke</h3>
            <p>Du kan när som helst återkalla ditt samtycke till cookies och databehandling genom att ändra dina cookie-inställningar.</p>

            <h3>Hur utövar jag mina rättigheter?</h3>
            <p>
                Kontakta oss på <a href="mailto:andreas@atdev.me" class="text-blue-600 hover:text-blue-700">andreas@atdev.me</a> för att utöva dina rättigheter.
                Vi svarar på din begäran inom 30 dagar.
            </p>

            <h2>7. Cookies och tracking</h2>
            <p>
                Vi använder cookies för att förbättra din upplevelse på webbplatsen. Läs vår fullständiga
                <a href="{{ route('gdpr.cookies') }}" class="text-blue-600 hover:text-blue-700">Cookie-policy</a>
                för mer information om vilka cookies vi använder och hur du kan hantera dem.
            </p>

            <h2>8. Tredjepartsleverantörer</h2>
            <p>Vi delar dina personuppgifter med följande tredjepartsleverantörer:</p>

            <h3>8.1 Mailgun (E-posttjänst)</h3>
            <p>
                Vi använder Mailgun för att skicka och ta emot e-post. Mailgun behandlar e-postadresser och meddelanden enligt deras integritetspolicy.
            </p>
            <p>
                <a href="https://www.mailgun.com/privacy-policy" target="_blank" class="text-blue-600 hover:text-blue-700">
                    Mailgun Privacy Policy →
                </a>
            </p>

            <h3>8.2 Anthropic (AI-tjänst)</h3>
            <p>
                Vår AI-chatbot använder Anthropic Claude API. Konversationer skickas till Anthropic för bearbetning.
                Anthropic sparar inte konversationsdata längre än nödvändigt för att tillhandahålla tjänsten.
            </p>
            <p>
                <a href="https://www.anthropic.com/privacy" target="_blank" class="text-blue-600 hover:text-blue-700">
                    Anthropic Privacy Policy →
                </a>
            </p>

            <h2>9. Säkerhet</h2>
            <p>Vi vidtar följande säkerhetsåtgärder för att skydda dina personuppgifter:</p>
            <ul>
                <li><strong>HTTPS-kryptering:</strong> All kommunikation är krypterad med SSL/TLS</li>
                <li><strong>Databaskryptering:</strong> Känslig data lagras krypterat</li>
                <li><strong>Åtkomstkontroll:</strong> Begränsad åtkomst till personuppgifter</li>
                <li><strong>Säkerhetsuppdateringar:</strong> Regelbundna uppdateringar av system och mjukvara</li>
                <li><strong>Spam-skydd:</strong> Honeypot-fält och rate limiting för att förhindra missbruk</li>
            </ul>

            <h2>10. Överföring till tredje land</h2>
            <p>
                Vissa av våra tredjepartsleverantörer kan vara baserade utanför EU/EES.
                Vi säkerställer att sådana överföringar sker i enlighet med GDPR genom:
            </p>
            <ul>
                <li>EU:s standardavtalsklausuler (SCC)</li>
                <li>Adequacy decisions från EU-kommissionen</li>
                <li>Andra lämpliga skyddsåtgärder enligt GDPR Kapitel V</li>
            </ul>

            <h2>11. Ändringar av denna policy</h2>
            <p>
                Vi kan uppdatera denna integritetspolicy från tid till annan för att återspegla ändringar i vår
                databehandling eller juridiska krav. Vi rekommenderar att du regelbundet granskar denna sida.
            </p>
            <p>
                <strong>Senaste uppdatering:</strong> {{ now()->format('Y-m-d') }}
            </p>

            <h2>12. Kontakta oss</h2>
            <p>
                Om du har frågor om denna integritetspolicy eller hur vi behandlar dina personuppgifter,
                kontakta oss:
            </p>
            <p>
                <strong>E-post:</strong> <a href="mailto:andreas@atdev.me" class="text-blue-600 hover:text-blue-700">andreas@atdev.me</a><br>
                <strong>Frilansare:</strong> Andreas Thun (ATDev)<br>
                <strong>Fakturering:</strong> Via Frilans Finans
            </p>

            <h2>13. Klagomål till tillsynsmyndighet</h2>
            <p>
                Om du anser att vi behandlar dina personuppgifter i strid med GDPR har du rätt att lämna in ett klagomål
                till tillsynsmyndigheten:
            </p>
            <p>
                <strong>Integritetsskyddsmyndigheten (IMY)</strong><br>
                Box 8114<br>
                104 20 Stockholm<br>
                Telefon: 08-657 61 00<br>
                E-post: <a href="mailto:imy@imy.se" class="text-blue-600 hover:text-blue-700">imy@imy.se</a><br>
                Webb: <a href="https://www.imy.se" target="_blank" class="text-blue-600 hover:text-blue-700">www.imy.se</a>
            </p>
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
