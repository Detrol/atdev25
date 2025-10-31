# AI-Assistent

En förenklad AI-assistent integrerad i ATDev-portfolion för att visa upp teknisk kompetens och ge besökare möjlighet att ställa tekniska frågor.

## Översikt

AI-assistenten är en **showcase-feature** som:
- Demonstrerar integration med Anthropic's Claude API
- Ger allmän teknisk rådgivning om webbutveckling, arkitektur och bästa praxis
- Kan svara på frågor om projekt i portfolion
- Visar upp modern UI/UX med glassmorphism-design
- Sparar konversationshistorik mellan sessioner

## Arkitektur

### Backend (Laravel)

#### Förenklad AIService
**Plats**: `app/Services/AIService.php`

En kraftigt förenklad version jämfört med den ursprungliga serviceföretags-versionen:

**Funktioner**:
- `createPortfolioPrompt()` - Skapar AI-prompt baserad på Profile och Projects
- `callAnthropicApi()` - Synkrona API-anrop till Anthropic Claude
- `getChatHistory()` - Hämtar konversationshistorik från databasen

**Förenklade bort**:
- ❌ AdminDatabaseService dependencies
- ❌ MessageLog och cost tracking
- ❌ Job queue (ProcessAIResponse)
- ❌ Komplexa databas-queries (kategorier, tjänster, rabattkoder)
- ❌ Settings från JSON-filer
- ❌ Subscription limits

**Resultat**: ~290 rader kod (från original ~925 rader)

#### Controller
**Plats**: `app/Http/Controllers/AIAssistantController.php`

**Endpoints**:
- `POST /api/chat` - Skicka meddelande, få direkt svar (synkront)
- `GET /api/chat/history` - Hämta historik för en session

**Features**:
- Rate limiting: 5 requests/minut per session
- Synkrona API-anrop (ingen Job queue)
- Sparar historik i Chat-modellen efter varje svar
- Svensk felhantering

**Resultat**: ~129 rader kod (från original ~251 rader)

#### Database
**Chat Model**: `app/Models/Chat.php`
- `session_id` (string, indexed) - UUID för sessionen
- `question` (text) - Användarens fråga
- `answer` (text) - AI:ns svar
- `timestamps` - created_at, updated_at

**Migration**: `2025_10_31_063637_create_chats_table.php`

#### Validation
**ChatRequest**: `app/Http/Requests/ChatRequest.php`
- `message`: required, string, max:1000
- `session_id`: required, string, uuid

#### Routes
**Plats**: `routes/api.php`

```php
Route::prefix('chat')->group(function () {
    Route::post('/', [AIAssistantController::class, 'chat'])
        ->middleware('throttle:5,1'); // 5 req/min

    Route::get('/history', [AIAssistantController::class, 'getChatHistory']);
});
```

---

### Frontend (Alpine.js + Tailwind)

#### Chat Widget JavaScript
**Plats**: `resources/js/chat-widget.js`

**Alpine.js Component** med:
- Session management (localStorage för UUID)
- AJAX kommunikation med backend
- Historik-laddning vid första öppning
- Real-time UI-uppdateringar
- Error handling
- Keyboard shortcuts (Enter för att skicka)

#### Chat Widget CSS
**Plats**: `resources/css/chat-widget.css`

**Design**:
- Modern glassmorphism stil (matchar portfolions estetik)
- Fast knapp i nedre högra hörnet med pulsande animation
- Responsiv chat-overlay (400px desktop, fullscreen mobil)
- Smooth transitions och animationer
- Custom scrollbar
- Loading states med bouncing dots
- HTML-formatering i meddelanden (code blocks, listor, etc.)

#### Integration
**Plats**: `resources/views/layouts/app.blade.php`

Widgeten läggs till i app-layouten:
- CSRF token i head för AJAX
- Vite assets: app.css, chat-widget.css, app.js, chat-widget.js
- Alpine.js component direkt i body
- Conditional rendering (bara på publika sidor)

---

## Konfiguration

### Environment Variables

Lägg till i `.env`:

```env
# Anthropic AI API (för AI-assistenten)
ANTHROPIC_API_KEY=sk-ant-xxxxxxxxxx
ANTHROPIC_API_URL=https://api.anthropic.com/v1/messages
```

### Services Configuration

`config/services.php` innehåller:

```php
'anthropic' => [
    'api_key' => env('ANTHROPIC_API_KEY'),
    'api_url' => env('ANTHROPIC_API_URL', 'https://api.anthropic.com/v1/messages'),
],
```

---

## AI-Prompt och Beteende

### Context som AI:n får

1. **Identitet**: "Du är en AI-assistent som representerar [namn]s portfolio"
2. **Profilinformation**: Namn, bio, kontaktuppgifter från Profile-modellen
3. **Projekt**: Alla published + featured projekt med beskrivningar och tech stack
4. **Roll**: Teknisk rådgivare för webbutveckling, arkitektur och bästa praxis

### Kommunikationsstil

- Professionell men vänlig och tillgänglig
- Svara koncist och relevant på svenska
- Använd konkreta exempel från projekten när relevant
- Ge teknisk vägledning baserat på bästa praxis
- Undvik överdriven användning av utropstecken och emojis
- Formatera svar med HTML/Tailwind för bättre läsbarhet
- Max 500 tokens per svar

### HTML-Formatering

AI:n formaterar svar med Tailwind CSS-klasser:
- Rubriker: `<h3 class="text-lg font-bold mb-2">`
- Listor: `<ul class="list-disc pl-5 mb-3 space-y-1">`
- Kod: `<code class="bg-gray-100 px-2 py-0.5 rounded text-sm font-mono">`
- Informationsrutor: `<div class="bg-blue-50 border-l-4 border-blue-500 p-3 mb-3">`

---

## Installation & Setup

### 1. Migration

```bash
php artisan migrate
```

Skapar `chats`-tabellen.

### 2. Konfigurera API-nyckel

Lägg till din Anthropic API-nyckel i `.env`:

```bash
ANTHROPIC_API_KEY=sk-ant-xxxxxxxxxx
```

### 3. Bygg frontend assets

```bash
npm run build
# eller för utveckling:
npm run dev
```

### 4. Testa

Öppna sidan och klicka på chat-knappen i nedre högra hörnet. Ställ en teknisk fråga!

---

## Användning

### För Utvecklare

**Lägg till AI-assistent på andra sidor**:
Widgeten är redan inkluderad i `layouts/app.blade.php` och visas automatiskt på alla sidor som använder denna layout.

**Justera prompt**:
Redigera `AIService::createPortfolioPrompt()` för att anpassa AI:ns beteende och kontext.

**Ändra modell/parametrar**:
Redigera `AIService::callAnthropicApi()` - standard är `claude-3-7-sonnet-20250219` med 500 max tokens.

### För Besökare

1. Klicka på chat-knappen (nedre högra hörnet)
2. Ställ frågor om:
   - Teknisk rådgivning (arkitektur, ramverk, bästa praxis)
   - Projekt i portfolion
   - Kompetensområden
   - Allmän webbutveckling
3. Konversationen sparas och finns kvar vid nästa besök

---

## Säkerhet & Rate Limiting

**Rate Limiting**:
- 5 förfrågningar per minut per session
- Implementerat både i controller och route middleware
- Felmeddelande på svenska vid överskriden gräns

**Input Validation**:
- Max 1000 tecken per meddelande
- UUID-validering för session-ID
- XSS-skydd via Laravel's standard escaping

**API-säkerhet**:
- API-nyckel aldrig exponerad till frontend
- Timeout på 30 sekunder för API-anrop
- Comprehensive error logging

---

## Kostnader & Begränsningar

### Anthropic Pricing (approximativt)

**Claude 3.7 Sonnet**:
- Input: ~$3 per miljoner tokens
- Output: ~$15 per miljoner tokens

**Uppskattad kostnad per konversation**:
- Input (~1000 tokens kontext + historik): $0.003
- Output (~500 tokens svar): $0.0075
- **Total: ~$0.01 per meddelande**

Med rate limiting på 5 req/min och realistisk användning: **~$5-20/månad** för en portfolio.

### Begränsningar

- Max 500 tokens per svar (ca 300-400 ord)
- 10 meddelanden chatthistorik (för att hålla nere kostnader)
- Ingen streaming (synkrona svar)
- Ingen filuppladdning eller bildhantering

---

## Felsökning

### AI svarar inte

1. Kontrollera att API-nyckeln är korrekt i `.env`
2. Kolla logs: `storage/logs/laravel.log`
3. Verifiera att migration körts: `php artisan migrate:status`

### Chat-widgeten visas inte

1. Bygg frontend: `npm run build`
2. Kontrollera att assets laddas i browser DevTools
3. Verifiera att Alpine.js laddas från CDN

### Rate limit-problem

Justera `AIAssistantController::checkThrottling()`:
```php
$maxAttempts = 10, // Öka från 5 till 10
$decaySeconds = 60 // Behåll 1 minut
```

---

## Framtida Förbättringar

Möjliga utökningar (inte implementerade):

1. **Streaming responses** - Visa text som den genereras
2. **Markdown rendering** - Bättre formatering
3. **Kod syntax highlighting** - För kodblock
4. **Export conversation** - Spara konversationer som PDF
5. **Admin dashboard** - Se all chattstatistik
6. **Multi-language support** - Engelska/svenska toggle
7. **Voice input** - Speech-to-text integration
8. **Emoji reactions** - Feedback på svar

---

## Sammanfattning

AI-assistenten är en **förenklad, showcase-vänlig implementation** som visar upp:

✅ API-integration med moderna AI-tjänster
✅ Clean architecture med separation of concerns
✅ Modern frontend med Alpine.js och Tailwind
✅ Robust error handling och rate limiting
✅ Responsiv design och smooth UX
✅ Säker hantering av känslig data (API-nycklar)

**Total kodstorlek**: ~600 rader (backend + frontend) jämfört med original ~1200+ rader.

**Perfekt för**: Portfolio showcase som demonstrerar förmåga att bygga moderna, AI-drivna funktioner med fokus på enkelhet och användarvänlighet.
