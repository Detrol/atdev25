# ATDev - Datamodeller

## Översikt

ATDev använder 10 huvudmodeller organiserade i logiska kategorier:
- **Kärnmodeller**: User, Profile, Project
- **Kommunikation**: ContactMessage
- **Innehållshantering**: Service, Faq
- **AI & Analys**: Chat, WebsiteAudit, PriceEstimation
- **GDPR**: CookieConsent, GdprDataRequest

## Kärnmodeller

### users
Admin-användare för autentisering (endast login, ingen publik registrering).

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| name | string | Användarens namn |
| email | string (unique) | E-postadress |
| password | string | Hashat lösenord (bcrypt) |
| two_factor_secret | text nullable | 2FA secret (Fortify) |
| two_factor_recovery_codes | text nullable | Recovery codes (Fortify) |
| remember_token | string nullable | Remember-token |
| email_verified_at | timestamp nullable | E-postverifiering (ej använd) |
| created_at | timestamp | |
| updated_at | timestamp | |

**Authentication**: Laravel Fortify med session-based login

**Seeder**: `AdminUserSeeder` skapar `admin@atdev.me` / `password`

**⚠️ Viktigt**: Byt lösenord i produktion!

---

### profiles
Singleton-tabell för profilinformation (endast 1 rad per installation).

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| tagline | string nullable | Kort tagline/slogan |
| bio | text | Om mig-text |
| email | string nullable | Publik kontakt-email |
| phone | string nullable | Telefonnummer |
| location | string nullable | Plats/stad |
| availability_status | string nullable | Tillgänglighet (t.ex. "Available for projects") |
| created_at | timestamp | |
| updated_at | timestamp | |

**Media (Spatie Media Library)**:
- `avatar` - Profilbild
- `cv_pdf` - CV PDF-fil

**Social Media**: Kopplas via relationer eller JSON (GitHub, LinkedIn, Twitter)

**Helper-metod**:
```php
Profile::current() // Returns singleton or creates new
```

**Singleton Pattern**: Endast en profil per installation

---

### projects
Portfolio-projekt med featured-flagga och screenshot automation.

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| slug | string (unique, indexed) | URL-slug (auto-generated från title) |
| title | string | Projekttitel |
| summary | text | Kort sammanfattning (för listvy) |
| description | longText | Fullständig beskrivning (Markdown-stöd) |
| cover_image | string nullable | URL till omslagsbild |
| gallery | json nullable | Array med bild-URL:er |
| live_url | string nullable | Länk till live-version |
| github_url | string nullable | Länk till GitHub repository |
| client_name | string nullable | Klientens namn |
| client_testimonial | text nullable | Kund-testimonial/quote |
| technologies | json | Array med teknologier (["Laravel", "Vue", ...]) |
| key_features | json | Array med key features |
| screenshot_path | string nullable | Relativ path till screenshot |
| screenshot_taken_at | timestamp nullable | När screenshot togs |
| status | enum (indexed) | 'draft' \| 'published' (ProjectStatus enum) |
| featured | boolean (indexed) | Visas på startsidan |
| sort_order | integer (indexed) | Sorteringsordning (0 = högst prio) |
| created_at | timestamp | |
| updated_at | timestamp | |

**Scopes**:
```php
Project::published() // WHERE status = 'published'
Project::featured()  // WHERE featured = true
```

**Route Key**: `slug` (route model binding använder slug istället för id)

**Auto-generation**:
- Slug: Auto-genereras från title om tom (svenska tecken OK: å → a, ä → a, ö → o)
- Screenshot: `TakeProjectScreenshot` job dispatched vid save/update if `live_url` present

**Indexes**:
- `slug` (unique)
- `status`
- `featured`
- `sort_order`

---

## Kommunikationsmodeller

### contact_messages
Kontaktmeddelanden med tvåvägskommunikation via email threading.

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| parent_id | bigint unsigned nullable | Parent message för threading |
| name | string | Avsändarens namn |
| email | string | Avsändarens e-post |
| phone | string nullable | Telefonnummer |
| company | string nullable | Företagsnamn |
| message | text | Meddelandet |
| website | string nullable | Honeypot-fält (måste vara tomt) |
| reply_token | string (unique) nullable | Unik token för email replies |
| message_id | string nullable | Email Message-ID header |
| is_admin_reply | boolean (default false) | True om meddelandet är från admin |
| status | enum | 'pending' \| 'replied' \| 'closed' |
| read | boolean (indexed, default false) | Läst-status |
| read_at | timestamp nullable | När meddelandet lästes |
| replied_at | timestamp nullable | När admin svarade |
| ip_address | ip nullable | Avsändarens IP |
| user_agent | string nullable | User agent string |
| created_at | timestamp (indexed) | |
| updated_at | timestamp | |

**Scopes**:
```php
ContactMessage::unread()            // WHERE read = false
ContactMessage::pending()           // WHERE status = 'pending'
ContactMessage::replied()           // WHERE status = 'replied'
ContactMessage::originalMessages()  // WHERE parent_id IS NULL
```

**Methods**:
```php
$message->markAsRead()              // Sets read = true, read_at = now
$message->markAsReplied()           // Sets status = 'replied', replied_at = now
$message->createReply($text, $userId) // Creates child ContactMessage
$message->conversation()            // Returns parent + all replies
$message->getReplyAddress()         // Returns reply-{token}@{MAILGUN_DOMAIN}
```

**Threading Model**:
- Original messages: `parent_id = null`
- Replies: `parent_id` points to original message
- `is_admin_reply` distinguishes admin vs customer replies

**Reply Token System**:
- Unique 32-char token generated on message creation
- Used to create reply-address: `reply-{token}@atdev.me`
- Mailgun routes replies back via webhook (`/mailgun/inbound`)

**Honeypot**: `website` field must be empty (bots fill it, humans don't see it)

**Indexes**:
- `parent_id`
- `reply_token` (unique)
- `status`
- `read`
- `created_at`

---

## Innehållshanteringsmodeller

### services
Tjänsteerbjudanden med ikoner och features.

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| title | string | Tjänstens titel |
| slug | string (unique, indexed) | URL-slug |
| description | text | Beskrivning av tjänsten |
| icon | string nullable | Ikon-namn eller path |
| features | json | Array med features/benefits |
| sort_order | integer (indexed) | Visningsordning |
| created_at | timestamp | |
| updated_at | timestamp | |

**Admin**: Full CRUD i admin-panel (`/admin/services`)

**Display**: Services showcase page (kan implementeras)

**Example features JSON**:
```json
[
  "Responsive design",
  "SEO optimization",
  "Performance tuning",
  "Custom integrations"
]
```

---

### faqs
FAQ-frågor med kategorisering och AI chat-integration.

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| question | string | FAQ-frågan |
| answer | text | Svar på frågan |
| category | string nullable | Kategori (t.ex. "Pricing", "Technical") |
| sort_order | integer (indexed) | Visningsordning inom kategori |
| visible | boolean (default true) | Visas på publika FAQ-sida |
| show_in_ai_chat | boolean (default false) | Inkluderas i AI assistant context |
| created_at | timestamp | |
| updated_at | timestamp | |

**Scopes**:
```php
Faq::forAiChat() // WHERE show_in_ai_chat = true AND visible = true
```

**Admin**: Full CRUD i admin-panel (`/admin/faqs`)

**AI Integration**: FAQs med `show_in_ai_chat = true` inkluderas i AI assistant's system prompt

**Categories**: Freetext (kan göras till enum senare)

---

## AI & Analysmodeller

### chats
AI assistant conversation history.

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| session_id | string (indexed) | Unique session identifier |
| question | text | Användarens fråga |
| answer | text | AI's svar |
| created_at | timestamp | |
| updated_at | timestamp | |

**Purpose**: Lagra chat-historik för context i AI-samtal

**Session Management**: Session ID från cookie eller localStorage

**Retention**: Ingen automatisk rensning (kan implementeras senare)

**Usage**:
```php
AIService::getChatHistory($sessionId, $limit = 10)
```

---

### website_audits
AI-baserade website audits med scores och recommendations.

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| url | string | URL för webbplats att audita |
| name | string | Användarens namn |
| email | string | Användarens email (för notification) |
| company | string nullable | Företagsnamn |
| token | string (unique, indexed) | Unik audit-token |
| status | enum | 'pending' \| 'processing' \| 'completed' \| 'failed' |
| collected_data | json nullable | Raw data från WebsiteDataCollector |
| ground_truth_data | json nullable | Processed ground truth för AI |
| screenshot_path | string nullable | Path till screenshot |
| ai_report | json nullable | AI's analysis och recommendations |
| seo_score | integer nullable | SEO score 0-100 |
| technical_score | integer nullable | Technical score 0-100 |
| overall_score | integer nullable | Overall score (average) |
| validation_passed | boolean (default false) | Om data validation passed |
| validation_errors | json nullable | Valideringsfel (om några) |
| created_at | timestamp (indexed) | |
| updated_at | timestamp | |

**Status Flow**: pending → processing → completed/failed

**Methods**:
```php
$audit->markAsProcessing() // status = 'processing'
$audit->markAsCompleted()  // status = 'completed'
$audit->markAsFailed()     // status = 'failed'
```

**Job**: `ProcessWebsiteAudit` (180s timeout, 2 max tries)

**Rate Limiting**: 3 audits/dag per IP (admin exempt)

**Duplicate Detection**: Same URL within 7 days → returns existing audit

**Public Access**: `/audit/{token}` shows results

**Indexes**:
- `token` (unique)
- `status`
- `email`
- `created_at`

---

### price_estimations
AI-genererade projektuppskattningar med web scraping.

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| description | text | Projektbeskrivning från användare |
| service_category | string nullable | Typ av tjänst (webb, app, etc.) |
| website_url | string nullable | URL till befintlig site (optional) |
| scraped_content | text nullable | Content från scraping |
| scraped_metadata | json nullable | Metadata från scraping |
| scrape_successful | boolean (default false) | Om scraping lyckades |
| scrape_error | string nullable | Scraperror (om scraping failade) |
| project_type | string nullable | AI's klassificering av projekttyp |
| complexity | string nullable | AI's complexity assessment |
| key_features | json nullable | AI-identified key features |
| solution_approach | text nullable | AI's recommended approach |
| hours_traditional | integer nullable | Traditional estimate (timmar) |
| price_traditional | decimal nullable | Traditional price (SEK ex. VAT) |
| price_traditional_vat | decimal nullable | Traditional price (SEK inkl. VAT) |
| hours_ai | integer nullable | AI-driven estimate (timmar) |
| price_ai | decimal nullable | AI price (SEK ex. VAT) |
| price_ai_vat | decimal nullable | AI price (SEK inkl. VAT) |
| delivery_weeks_traditional | integer nullable | Leveranstid traditional |
| delivery_weeks_ai | integer nullable | Leveranstid AI-driven |
| savings_hours | integer nullable | Saved hours (traditional - AI) |
| savings_price | decimal nullable | Saved price (SEK) |
| savings_percentage | decimal nullable | Savings % |
| ip_address | ip nullable | Requester IP |
| session_id | string nullable | Session ID |
| created_at | timestamp (indexed) | |
| updated_at | timestamp | |

**Dual Pricing Model**:
- **Traditional**: Conservative estimate, manual processes
- **AI-driven**: Optimistic estimate with AI/automation (30-50% snabbare)

**Workflow**:
1. User submits description (+ optional URL)
2. BrightDataScraper scrapes URL (if provided)
3. AIService analyzes description + scraped data
4. PriceEstimateMapper maps to hour/price ranges
5. Saves both traditional och AI-driven estimates

**Rate Limiting**: 5 requests/10 minuter per IP (admin exempt)

**Admin**: Bulk delete functionality (`/admin/estimations/bulk-destroy`)

**Indexes**:
- `created_at`
- `session_id`

---

## GDPR-Modeller

### cookie_consents
GDPR cookie consent tracking.

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| consent_id | string (unique, indexed) | Unique consent identifier |
| categories | json | Array of consented categories |
| ip_address | ip nullable | User's IP |
| user_agent | string nullable | User agent string |
| created_at | timestamp | When consent was given |
| updated_at | timestamp | |

**Categories** (JSON array):
```json
["analytics", "marketing", "preferences"]
```

**Available Categories**:
- `analytics` - Google Analytics 4
- `marketing` - Marketing cookies (future)
- `preferences` - User preferences (dark mode, etc.)

**Consent ID**: Stored in cookie `cookie_consent_id` (90 days, HttpOnly, SameSite=Lax)

**Service**: `CookieConsentService` handles storage and retrieval

**API**:
- GET `/api/consent` - Check consent status
- POST `/api/consent` - Store consent
- POST `/api/consent/accept-all` - Accept all
- POST `/api/consent/reject-all` - Reject all (only essential)

**Indexes**:
- `consent_id` (unique)
- `created_at`

---

### gdpr_data_requests
Track GDPR data export and deletion requests.

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| email | string | Requester's email |
| token | string (unique, indexed) | Unique confirmation token |
| type | enum | 'export' \| 'deletion' |
| status | enum | 'pending' \| 'processing' \| 'completed' \| 'failed' |
| ip_address | ip | Requester's IP |
| processed_at | timestamp nullable | When request was processed |
| created_at | timestamp | |
| updated_at | timestamp | |

**Request Types**:
- **export**: User requests JSON export av all data
- **deletion**: User requests deletion/anonymization

**Status Flow**: pending → processing → completed/failed

**Token**: Used for confirmation link (click-to-confirm)

**Confirmation URLs**:
- Export: Email with download link
- Deletion: `/gdpr/confirm-deletion/{token}`

**Services**:
- `GdprDataExportService::exportUserData($email)`
- `GdprDataDeletionService::processDeletionRequest($token, $mode)`

**Deletion Modes**:
- `full` - Delete all records
- `anonymize` - Replace email with `deleted_xxx@deleted.com`

**Indexes**:
- `token` (unique)
- `email`
- `type`
- `status`
- `created_at`

---

## Enums

### ProjectStatus
`App\Enums\ProjectStatus`

```php
enum ProjectStatus: string
{
    case DRAFT = 'draft';          // Not publicly visible
    case PUBLISHED = 'published';  // Visible on site
}
```

**Usage**:
```php
$project->status = ProjectStatus::PUBLISHED;
Project::where('status', ProjectStatus::PUBLISHED)->get();
```

---

## Relationer

### Befintliga Relationer

**ContactMessage → ContactMessage** (Threading):
```php
// Parent relationship
$message->parent();  // BelongsTo parent message

// Children relationship
$message->replies();  // HasMany child replies

// Full conversation
$message->conversation();  // Parent + all replies
```

**User → Profile** (Ej implementerad än):
```php
// Kan implementeras vid behov
$user->profile();  // HasOne
```

### Potentiella Framtida Relationer

**Project → User** (Created by):
- Användbart vid flera admins
- `projects.created_by_user_id`

**Project → Tags** (Many-to-Many):
- För filtrering och sökning
- Pivot table: `project_tag`

**Project → Category** (BelongsTo):
- Kategorisering av projekt
- `projects.category_id`

---

## Index och Prestanda

### Befintliga Index

**projects**:
- `slug` (unique)
- `status`
- `featured`
- `sort_order`

**contact_messages**:
- `parent_id`
- `reply_token` (unique)
- `status`
- `read`
- `created_at`

**services**:
- `slug` (unique)
- `sort_order`

**faqs**:
- `sort_order`

**chats**:
- `session_id`

**website_audits**:
- `token` (unique)
- `status`
- `email`
- `created_at`

**price_estimations**:
- `created_at`
- `session_id`

**cookie_consents**:
- `consent_id` (unique)
- `created_at`

**gdpr_data_requests**:
- `token` (unique)
- `email`
- `type`
- `status`
- `created_at`

### Optimerade Queries

**Startsida** (featured projects):
```sql
SELECT * FROM projects
WHERE status = 'published' AND featured = true
ORDER BY sort_order ASC, created_at DESC
```
Använder index på: `status`, `featured`, `sort_order`

**Project routing** (slug lookup):
```sql
SELECT * FROM projects WHERE slug = ?
```
Använder unique index på `slug`

**Unread messages**:
```sql
SELECT * FROM contact_messages
WHERE read = false AND parent_id IS NULL
ORDER BY created_at DESC
```
Använder index på: `read`, `created_at`

**Audit status check**:
```sql
SELECT * FROM website_audits WHERE token = ?
```
Använder unique index på `token`

---

## Factories och Seeders

### AdminUserSeeder
Skapar default admin-användare:
- Email: `admin@atdev.me`
- Password: `password`

**⚠️ Viktigt**: Byt lösenord i produktion!

### ProfileSeeder
Skapar singleton-profil med ATDev-information:
- Tagline, bio, contact info
- Social media links

### ProjectSeeder
Skapar 3 demo-projekt:
1. **E-handelsplattform** - Laravel + Vue
2. **CRM-system** - PHP + MySQL
3. **Bokningssystem** - Laravel + Livewire

Alla projekt:
- Status: `PUBLISHED`
- Featured: `true`
- Technologies: Array med relevant tech stack

### ServiceSeeder
Skapar standard-tjänster:
- Webbutveckling
- App-utveckling
- API-integration
- Konsultation

### FaqSeeder
Skapar FAQ-frågor kategoriserade efter:
- Prissättning
- Process
- Teknologi
- Support

Vissa FAQs markeras med `show_in_ai_chat = true` för AI assistant context.

---

## Databasmigrationer

**Kör migrationer**:
```bash
php artisan migrate
```

**Fresh migration med seeders**:
```bash
php artisan migrate:fresh --seed
```

**Rollback**:
```bash
php artisan migrate:rollback
```

---

## Modellkonventioner

### Namngivning
- Model: Singular PascalCase (`Project`, `ContactMessage`)
- Table: Plural snake_case (`projects`, `contact_messages`)
- Foreign keys: `{model}_id` (singular snake_case)

### Tidsstämplar
- Alla modeller använder `timestamps()` (created_at, updated_at)
- Laravel hanterar automatiskt

### Mjuk Radering
- Ej implementerat än (kan läggas till vid behov)
- Kandidater: Projects, ContactMessages

### JSON-Casting
Följande fält castas automatiskt till/från JSON:
- `projects.technologies` → array
- `projects.key_features` → array
- `projects.gallery` → array
- `services.features` → array
- `faqs.categories` (future)
- `website_audits.collected_data` → object
- `website_audits.ai_report` → object
- `price_estimations.key_features` → array
- `cookie_consents.categories` → array

### Dolda Fält
Följande fält döljs automatiskt i API responses:
- `users.password`
- `users.remember_token`
- `users.two_factor_secret`
- `users.two_factor_recovery_codes`

---

**Version**: 2.0
**Last Updated**: 2025-01-15
**Maintainer**: andreas@atdev.me
