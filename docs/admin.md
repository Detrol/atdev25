# ATDev - Admin Guide

## Inloggning

URL: `/admin/login`

Admin-användare skapas via `AdminUserSeeder` med credentials från environment variables:

```env
# .env
ADMIN_NAME="Ditt Namn"
ADMIN_EMAIL=din.email@example.com
ADMIN_PASSWORD=ditt_säkra_lösenord
```

**Skapa admin-användare:**
```bash
php artisan db:seed --class=AdminUserSeeder
```

**VIKTIGT**: Använd aldrig hårdkodade credentials i produktion! Alla credentials läses från `.env`.

## Dashboard

URL: `/admin`

**Data Contract:**
```php
[
    'projectsCount' => int,
    'servicesCount' => int,
    'unreadMessages' => int,
    'recentProjects' => Collection<Project>,  // 5 senaste
    'recentMessages' => Collection<ContactMessage>,  // 5 senaste
]
```

Visar översikt:
- Antal projekt
- Antal tjänster (services)
- Antal olästa meddelanden
- 5 senaste projekt
- 5 senaste kontaktmeddelanden

## Hantera Projekt

### Lista projekt
URL: `/admin/projects`

- Visar alla projekt (paginated, 20/sida)
- Sorterat efter sort_order, sedan created_at desc
- Länkar till redigera/radera

### Skapa projekt
URL: `/admin/projects/create`

Fält:
- **Title** (required): Projekttitel
- **Slug** (optional): Auto-genereras från titel om tom
- **Summary** (required, max 500 tecken): Kort beskrivning för listvy
- **Description** (required): Fullständig beskrivning (stöder markdown)
- **Cover Image** (optional): URL till omslagsbild
- **Gallery** (optional, array): URL:er till galleribilder
- **Live URL** (optional): Länk till live-version (triggar auto-screenshot)
- **Repo URL** (optional): Länk till repository (t.ex. GitHub)
- **Technologies** (optional, array): Teknologier som används (t.ex. Laravel, Vue.js)
- **Status** (required): Draft / Published
- **Featured** (boolean): Visas på startsidan
- **Sort Order** (integer): Lägre nummer = högre prioritet (0 = högst)

Vid sparning med `live_url`: Screenshot-jobb dispatchar automatiskt.

### Redigera projekt
URL: `/admin/projects/{project}/edit`

Samma fält som "Skapa projekt".

Om `live_url` ändras: Nytt screenshot-jobb dispatchar.

### Manuell screenshot
URL: POST `/admin/projects/{project}/screenshot`

Triggar screenshot-jobb manuellt (kräver att projektet har `live_url`).

### Radera projekt
URL: DELETE `/admin/projects/{project}`

Raderar projektet permanent.

## Hantera Tjänster (Services)

### Lista tjänster
URL: `/admin/services`

**Data Contract:**
```php
['services' => Collection<Service>]  // Ordered by sort_order
```

Visar alla tjänster sorterade efter `sort_order`.

### Skapa tjänst
URL: `/admin/services/create`

**Fält:**
- **Title** (required): Tjänstens titel
- **Slug** (optional): Auto-genereras från title om tom
- **Description** (required): Beskrivning av tjänsten
- **Icon** (optional): Font Awesome icon-klass (t.ex. `fa-code`)
- **Sort Order** (integer): Sorteringsordning (lägre = högre prioritet)

**Validering:** Se `ServiceRequest`

### Redigera tjänst
URL: `/admin/services/{service}/edit`

Samma fält som "Skapa tjänst".

### Radera tjänst
URL: DELETE `/admin/services/{service}`

Raderar tjänsten permanent.

---

## Hantera FAQs

### Lista FAQs
URL: `/admin/faqs`

**Data Contract:**
```php
['faqs' => LengthAwarePaginator<Faq>]  // 20 per sida
```

Visar alla FAQs sorterade efter `sort_order`, sedan `created_at desc`.
Paginated: 20 per sida.

### Skapa FAQ
URL: `/admin/faqs/create`

**Fält:**
- **Question** (required): Frågan
- **Answer** (required): Svaret (stöder markdown)
- **Category** (optional): Kategori (t.ex. "Allmänt", "Tekniskt")
- **Tags** (optional): Kommaseparerade taggar (konverteras till array)
- **Sort Order** (integer): Sorteringsordning
- **Published** (boolean): Synlig på publika sidan

**Validering:** Se `FaqRequest`

**Tags-format:**
```
Laravel, Vue.js, API  →  ['Laravel', 'Vue.js', 'API']
```

### Redigera FAQ
URL: `/admin/faqs/{faq}/edit`

**Data Contract:**
```php
[
    'faq' => Faq,
    'tagsString' => string  // Comma-separated tags för formuläret
]
```

Samma fält som "Skapa FAQ".

**OBS**: Tags visas som kommaseparerad sträng i formuläret, konverteras till array vid sparning.

### Radera FAQ
URL: DELETE `/admin/faqs/{faq}`

Raderar FAQ permanent.

---

## Hantera Profil

### Redigera profil
URL: `/admin/profile`

Singleton - endast en profil per installation.

Fält:
- **Name** (required): Fullständigt namn
- **Title** (required): Yrkestitel (t.ex. "Fullstack-utvecklare")
- **Bio** (required): Om mig-text
- **Avatar** (optional): URL till profilbild
- **Hero Image** (optional): URL till hero-bild för startsidan
- **Email** (optional): Publik e-postadress
- **Phone** (optional): Telefonnummer
- **GitHub** (optional): Länk till GitHub-profil
- **LinkedIn** (optional): Länk till LinkedIn-profil
- **Twitter** (optional): Länk till Twitter/X-profil

## Hantera Kontaktmeddelanden

### Lista meddelanden
URL: `/admin/messages`

**Data Contract:**
```php
['messages' => LengthAwarePaginator<ContactMessage>]  // 20 per sida
```

**Features:**
- Visar endast **original messages** (ej replies)
- Eager loads: `replies`, `adminReplier`, `priceEstimation`
- Sorterat efter `created_at desc`
- Paginated: 20 per sida

**Visar per meddelande:**
- Namn, E-post
- Meddelande (excerpt)
- Status badges (Pending/Replied)
- Reply count badge
- Läst/oläst status
- Kopplad prisestimering (om finns)
- Tidstämpel

### Visa konversation
URL: `/admin/messages/{message}`

**Data Contract:**
```php
[
    'message' => ContactMessage,  // Original message
    'conversation' => Collection<ContactMessage>,  // Hela tråden
]
```

**Features:**
- Visar **hela konversationstråden** (original + alla replies)
- Markeras automatiskt som läst vid visning
- Visuell skillnad mellan admin-svar och användarsvar
- Kopplad prisestimering visas (om finns)
- Reply-formulär längst ner

**Konversationsflöde:**
1. Original message från användare
2. Admin reply #1
3. User reply #1 (via email till `reply-{token}@atdev.me`)
4. Admin reply #2
5. ... och så vidare

### Svara på meddelande
URL: POST `/admin/messages/{message}/reply`

**Request Data:**
```php
[
    'message' => 'required|string|max:5000'
]
```

**Workflow:**
1. Hittar root-meddelande (om detta är ett svar)
2. Skapar reply via `$message->createReply($text, auth()->id())`
3. Dispatchar `SendReplyEmail` job
4. Uppdaterar original message: `status = 'replied'`

**Email skickas till:**
- Användarens email
- Reply-To: `reply-{token}@atdev.me` (för fortsatt konversation)

**Validering:** Se `ReplyRequest`

### Markera som läst
URL: POST `/admin/messages/{message}/read`

Sätter `read = true` för meddelandet.

**OBS**: Sker automatiskt när man visar konversationen.

### Radera meddelande
URL: DELETE `/admin/messages/{message}`

Raderar meddelandet permanent.

**OBS**: Raderar även alla replies i tråden (cascade delete).

---

## Hantera Website Audits

### Lista granskningar
URL: `/admin/audits`

**Data Contract:**
```php
[
    'audits' => LengthAwarePaginator<WebsiteAudit>,  // 20 per sida
    'stats' => [
        'total' => int,
        'completed' => int,
        'processing' => int,
        'pending' => int,
        'failed' => int,
        'avg_score' => float|null,
    ]
]
```

**Features:**
- Visar alla website audits sorterade efter `created_at desc`
- Paginated: 20 per sida
- Statistik-översikt (totalt, completed, processing, pending, failed, avg score)

**Visar per audit:**
- URL
- Status (pending/processing/completed/failed)
- Overall score (0-100)
- Tidstämpel
- Länk till detaljvy

**Status-filter:**
- Pending (ej startad än)
- Processing (pågående analys)
- Completed (färdig med resultat)
- Failed (misslyckades)

### Visa granskning
URL: `/admin/audits/{audit}`

**Data Contract:**
```php
['audit' => WebsiteAudit]
```

**Visar:**
- **Overall Score** (0-100)
- **Status**
- **Analyzed URL**
- **Accessibility Score** + recommendations
- **SEO Score** + recommendations
- **Performance Score** + recommendations
- **Best Practices Score** + recommendations
- **Content Quality Score** + recommendations
- **Structured Data** (hämtat från sidan)
- **Timestamp** (när granskningen gjordes)

**Resultatformat:**
Alla scores visas med färgkodning:
- 90-100: Grön (Excellent)
- 70-89: Gul (Good)
- 50-69: Orange (Needs Improvement)
- 0-49: Röd (Poor)

### Radera granskning
URL: DELETE `/admin/audits/{audit}`

Raderar granskningsresultatet permanent.

**Use Case**: Ta bort gamla/testgranskningar för att hålla databasen ren.

---

## Hantera Prisertimeringar

### Lista estimeringar
URL: `/admin/estimations`

**Data Contract:**
```php
['estimations' => LengthAwarePaginator<PriceEstimation>]  // 20 per sida
```

**Features:**
- Visar alla price estimations sorterade efter `created_at desc`
- Eager loads: `contactMessage` (kopplade kontaktmeddelanden)
- Paginated: 20 per sida
- Bulk delete-funktionalitet

**Visar per estimation:**
- Project Type (t.ex. "E-commerce Platform")
- Complexity (simple/medium/complex/very_complex)
- Key Features (bullet list)
- Solution Approach (AI-genererad lösningsförslag)
- Kopplat kontaktmeddelande (om finns)
- Tidstämpel

### Visa estimering
URL: `/admin/estimations/{estimation}`

**Data Contract:**
```php
['estimation' => PriceEstimation]  // Med contactMessage eager loaded
```

**Visar:**
- **Project Type**: AI-identifierad projekttyp
- **Complexity Level**: simple | medium | complex | very_complex
- **Key Features**: Array med huvudfunktioner som AI identifierat
- **Solution Approach**: Detaljerat lösningsförslag från AI
- **Original Description**: Vad användaren skrev
- **Scraped Data** (om finns): Data från web scraping (konkurrerande produkter/priser)
- **Kopplat meddelande**: Länk till kontaktmeddelande (om finns)
- **Timestamp**: När estimeringen gjordes

**AI-analys:**
Estimeringen genereras av Claude 3.7 Sonnet baserat på:
- Användarens projektbeskrivning
- Web scraping av liknande produkter (via BrightData)
- Tidigare projekt-erfarenhet

### Radera estimering
URL: DELETE `/admin/estimations/{estimation}`

Raderar estimeringen permanent.

**OBS**: Raderar INTE kopplade kontaktmeddelanden (endast estimeringen).

### Bulk-radera estimeringar
URL: DELETE `/admin/estimations/bulk-destroy`

**Request Data:**
```php
[
    'ids' => 'required|array|min:1',
    'ids.*' => 'exists:price_estimations,id'
]
```

**Workflow:**
1. Välj flera estimations via checkboxes
2. Klicka "Radera valda"
3. Validerar att alla IDs existerar
4. Raderar alla valda estimations i en transaktion

**Use Case**: Rensa gamla/testestimeringar effektivt.

**Success Message:**
- `1 prisestimering raderad!` (singular)
- `{count} prisertimeringar raderade!` (plural)

---

## Tips och Tricks

### Screenshot-kvalitet
Screenshots tas med Browsershot i 1920x1080 resolution. För bäst resultat:
- Se till att live-URL:en är tillgänglig och responsiv
- Undvik webbplatser med långsam laddning
- Screenshots sparas som PNG i `storage/app/public/screenshots/`
- Queue worker måste köra: `php artisan queue:work`

### Slug-hantering
- Slug genereras automatiskt från titel om du lämnar fältet tomt
- Svenska tecken konverteras (ä→a, ö→o, å→a)
- Slug måste vara unik
- Gäller för: Projekt, Services

### Featured-projekt
Endast featured + published projekt visas på startsidan. Använd `sort_order` för att kontrollera ordningen.

### Offentlig vs Draft
- **Draft**: Endast synlig i admin
- **Published**: Synlig på publika sidan (om featured visas även på startsidan)

### Meddelandetrådar
- Admin kan svara från admin-panelen ELLER direkt via email
- Användare kan fortsätta konversationen via email till `reply-{token}@atdev.me`
- Mailgun webhook hanterar inkommande svar automatiskt
- Hela konversationen visas i en tråd i admin

### Queue Jobs
Följande operationer körs asynkront via queue:
- **Screenshot-tagning** (`TakeProjectScreenshot`)
- **Email-notifieringar** (`SendContactEmail`, `SendReplyEmail`)

**Starta queue worker:**
```bash
php artisan queue:work
```

**Produktion** (använd Supervisor för att hålla worker igång):
```bash
php artisan queue:work --tries=3 --timeout=90
```

### Bulk Operations
**Price Estimations** stöder bulk delete:
1. Välj flera estimations via checkboxes
2. Klicka "Radera valda"
3. Alla valda raderas i en transaktion

**Future**: Bulk operations planeras för Messages och Audits.

### AI-Funktioner
**Website Audits** och **Price Estimations** använder AI:
- Audits: Ground truth data + AI-analys (Claude 3.7 Sonnet)
- Estimations: AI-driven projekttypning + web scraping

**Rate Limiting:**
- Audits: Ej rate-limited (endast för inloggade användare)
- Estimations: 5/min per IP på publika endpoints

### Services Ordering
Använd `sort_order` för att kontrollera ordningen tjänster visas:
- **Lägre nummer = högre prioritet**
- 0 = högst upp
- Tjänster utan `sort_order` sorteras efter `created_at`

### FAQs Management
**Tags-format:**
- Skriv kommaseparerade taggar: `Laravel, Vue.js, API`
- Konverteras automatiskt till array vid sparning: `['Laravel', 'Vue.js', 'API']`
- Används för filtrering på publika FAQ-sidan

**Categories:**
- Valfri kategorisering (t.ex. "Allmänt", "Tekniskt", "Priser")
- Används för gruppering i FAQ-vyn

## Logga ut

URL: POST `/admin/logout`
