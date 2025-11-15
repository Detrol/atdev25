# ATDev - Arkitektur

## Översikt

ATDev är en **AI-driven portfolio-plattform** byggd med Laravel 12 som demonstrerar moderna webbutvecklingstekniker genom att kombinera klassisk webbutveckling med avancerad AI-integration, GDPR-compliance, och real-time kommunikation.

## Arkitekturprinciper

### 1. Enkelhet först med avancerade capabilities
- Ren MVC-arkitektur med tydlig separation of concerns
- Services layer för komplex businesslogik (AI, scraping, GDPR)
- Job-baserad asynkron processing för tunga operationer
- Minimal men kraftfull feature-set

### 2. Backend-Först Mönster
- Controllers returnerar view-namn med dokumenterade datakontrakt
- Frontend kan byggas oberoende mot kontrakten
- Headless-friendly: Enkel övergång till API-driven frontend vid behov

### 3. AI-Först Design
- Anthropic Claude som core AI-engine
- AI integration i flera use cases (chat, audit, pricing, allergens)
- Strukturerad output för förutsägbar parsing
- Rate limiting och cost control

### 4. GDPR i Grunden
- Cookie consent med kategoribaserad hantering
- Data export och deletion workflows
- Anonymization som alternativ till full radering
- Privacy-first approach i all datahantering

### 5. Säkerhet & Prestanda
- Multi-layer bot protection (Turnstile + honeypot + rate limiting)
- Security headers via middleware (CSP, HSTS, etc.)
- Cache headers för static assets
- Queue-baserad processing för att hålla requests snabba

## Teknisk Stack

### Backend
- **Framework**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL 8 med utf8mb4_unicode_ci collation
- **Queue**: Database driver (local) → Redis (production)
- **Cache**: Database cache (local) → Redis (production)
- **Auth**: Laravel Fortify (session-based, admin-only)
- **Screenshots**: Spatie Browsershot (headless Chrome via Puppeteer)
- **Email**: Mailgun (production) / Log (local)
- **Testing**: Pest (feature + unit tests)

### AI & Externa Tjänster
- **AI Platform**: Anthropic Claude 3.7 Sonnet (claude-3-7-sonnet-20250219)
- **Web Scraping**: BrightData proxy service
- **Reviews API**: Google Places API
- **Bot Protection**: Cloudflare Turnstile
- **Analytics**: Google Analytics 4 (optional, GDPR-compliant)

### Frontend
- **Build Tool**: Vite (module bundler med HMR)
- **CSS**: Tailwind CSS 4.0 (utility-first)
- **JavaScript**: Alpine.js 3.x (reactive components)
- **Images**: Spatie Media Library (responsive, lazy-loading)
- **Visualization**: D3.js (tech stack page)

### DevOps
- **Web Server**: Nginx eller Apache
- **Process Manager**: Supervisor (queue workers)
- **SSL**: Let's Encrypt via Certbot
- **CDN**: Cloudflare (DNS, bot protection, caching)

## Domänmodell

### Kärnmodeller

#### User
- **Purpose**: Admin-autentisering (endast login, ingen registrering)
- **Auth**: Laravel Fortify med session-based login
- **Fields**: name, email, password, two_factor_secret, two_factor_recovery_codes
- **Seeders**: AdminUserSeeder (admin@atdev.me / password)

#### Profile (Singleton)
- **Purpose**: En enda profil per installation med personlig info
- **Static Method**: `Profile::current()` - Returns singleton or creates new
- **Fields**: tagline, bio, email, phone, location, availability_status
- **Media**: avatar, cv_pdf (Spatie Media Library)
- **Social**: GitHub, LinkedIn, Twitter (via relation eller JSON)

#### Project
- **Purpose**: Portfolio-projekt med featured-flagga och screenshot automation
- **Route Key**: `slug` (auto-generated från svenska titlar)
- **Status**: ProjectStatus enum (DRAFT, PUBLISHED)
- **Fields**:
  - Basic: title, slug, description, summary
  - URLs: live_url, github_url
  - Client: client_name, client_testimonial
  - Media: screenshot_path, screenshot_taken_at, cover_image, gallery (JSON)
  - Meta: technologies (JSON array), key_features (JSON array), featured, sort_order, status
- **Scopes**: `published()`, `featured()`
- **Jobs**: TakeProjectScreenshot dispatched on save/update if live_url present

#### ContactMessage
- **Purpose**: Kontaktmeddelanden med tvåvägskommunikation via email threading
- **Threading**: `parent_id` länkar replies till original message
- **Reply Tokens**: Unika 32-char tokens för email-baserade replies
- **Fields**:
  - Contact: name, email, phone, company, message
  - Security: website (honeypot), ip_address, user_agent
  - Threading: parent_id, is_admin_reply, reply_token, message_id
  - Status: status (pending, replied, closed), read, read_at, replied_at
- **Scopes**: `unread()`, `pending()`, `replied()`, `originalMessages()`
- **Methods**:
  - `markAsRead()` - Marks message as read
  - `markAsReplied()` - Marks as replied with timestamp
  - `createReply($text, $userId)` - Creates child reply message
  - `conversation()` - Returns full conversation thread
  - `getReplyAddress()` - Generates reply-{token}@{MAILGUN_DOMAIN}

### Innehållshanteringsmodeller

#### Service
- **Purpose**: Tjänsteerbjudanden med ikoner och features
- **Fields**: title, slug, description, icon, features (JSON array), sort_order
- **Admin**: Full CRUD i admin-panel
- **Display**: Services showcase page

#### Faq
- **Purpose**: FAQs med kategorisering och AI chat-integration
- **Fields**: question, answer, category, sort_order, visible, show_in_ai_chat
- **Scope**: `forAiChat()` - Returns FAQs to include in AI context
- **Admin**: Full CRUD i admin-panel
- **Integration**: AI assistant kan referera FAQs i svar

### AI & Analysmodeller

#### Chat
- **Purpose**: AI assistant conversation history
- **Fields**: session_id, question, answer, created_at
- **Storage**: Per-session chat log (används för context i AI-samtal)
- **Retention**: No automatic cleanup (kan implementeras senare)

#### WebsiteAudit
- **Purpose**: Lagra AI-baserade website audits med scores
- **Fields**:
  - Request: url, name, email, company, token (unique audit ID)
  - Status: status (pending, processing, completed, failed)
  - Data: collected_data (JSON), ground_truth_data (JSON), screenshot_path
  - Analysis: ai_report (JSON), validation_passed, validation_errors (JSON)
  - Scores: seo_score, technical_score, overall_score
- **Methods**: `markAsProcessing()`, `markAsCompleted()`, `markAsFailed()`
- **Job**: ProcessWebsiteAudit (180s timeout, 2 max tries)
- **Rate Limit**: 3 audits/dag per IP (admin exempt)
- **Duplicate Detection**: Checks för same URL within 7 days

#### PriceEstimation
- **Purpose**: AI-generated project price estimates med scraping
- **Fields**:
  - Input: description, service_category, website_url
  - Scraping: scraped_content, scraped_metadata, scrape_successful, scrape_error
  - AI Analysis: project_type, complexity, key_features (JSON), solution_approach
  - Traditional: hours_traditional, price_traditional, price_traditional_vat, delivery_weeks_traditional
  - AI-driven: hours_ai, price_ai, price_ai_vat, delivery_weeks_ai
  - Comparison: savings_hours, savings_price, savings_percentage
  - Meta: ip_address, session_id, created_at
- **Rate Limit**: 5/10 minuter per IP (admin exempt)
- **Admin**: Bulk delete functionality

### GDPR-Modeller

#### CookieConsent
- **Purpose**: GDPR cookie consent tracking
- **Fields**: consent_id, categories (JSON array), ip_address, user_agent, created_at
- **Categories**: analytics, marketing, preferences
- **Expiry**: 90 days (cookie: `cookie_consent_id`)
- **Service**: CookieConsentService handles storage and retrieval

#### GdprDataRequest
- **Purpose**: Track data export and deletion requests
- **Fields**: email, token, type (export/deletion), status (pending/processing/completed/failed), ip_address, processed_at
- **Methods**: `findByToken($token)` - Lookup via unique token
- **Workflow**:
  - User submits request → Creates GdprDataRequest with unique token
  - Confirmation email sent with token link
  - User confirms → Request processed
  - Email sent with result (export JSON or deletion confirmation)

## Tjänstelager

### AIService

**Purpose**: Centraliserad Anthropic Claude API-integration

**Methods**:
- `callAnthropicApi($messages, $system, $maxTokens, $temperature, $model)` - Core API call
- `getChatHistory($sessionId, $limit)` - Hämta chat-historik för context
- `createPortfolioPrompt()` - Genererar portfolio-kontext från projekt och FAQs
- `estimateProjectPrice($description, $scrapedData)` - AI price estimation
- `analyzeWebsite($groundTruthData, $screenshotPath)` - Website audit analysis
- `analyzeMenuAllergens($menuText)` - Swedish allergen detection

**Configuration**:
- **Model**: claude-3-7-sonnet-20250219
- **Max Tokens**: 500 (chat), variable för andra tasks
- **Temperature**: 0.7 (default)
- **System Prompts**: Per use case (portfolio, audit, pricing, allergens)

**Error Handling**:
- Throws `\Exception` med API error details
- Logs errors för debugging
- Returns structured output (JSON parsing från AI response)

**Rate Limiting**: Hanteras på controller-nivå (5/minut för chat, etc.)

---

### WebsiteDataCollector

**Purpose**: Samla comprehensive website data för AI audits

**Workflow**:
1. Fetch HTML via HTTP request
2. Take screenshot med Browsershot
3. Parse DOM med Symfony DomCrawler
4. Extract ground truth data:
   - Meta tags (title, description, keywords, OG tags)
   - Headings (h1-h6) med frequency analysis
   - Images (count, alt-text analysis, lazy-loading detection)
   - Links (internal vs external, broken link detection)
   - Technical metrics (HTML size, load time, viewport meta)
5. Extract CSS/HTML excerpts för AI context
6. Calculate optimization scores

**Output**: JSON structure med `ground_truth_data` för AI analysis

**Error Handling**:
- Network errors → Returns error status
- Invalid HTML → Graceful fallback
- Screenshot failure → Continues without screenshot

**Timeout**: 30s för HTTP request, 60s för screenshot

---

### BrightDataScraper

**Purpose**: Web scraping med proxy för price calculator

**Features**:
- BrightData proxy integration (authenticated)
- Technology detection på target website
- Content extraction (text, meta, structure)
- Error handling med fallback

**Configuration**:
```php
'brightdata' => [
    'api_key' => env('BRIGHTDATA_API_KEY'),
    'proxy_host' => env('BRIGHTDATA_PROXY_HOST'),
    'proxy_port' => env('BRIGHTDATA_PROXY_PORT'),
]
```

**Usage**: PriceCalculatorController använder scraper för att samla website-info innan AI estimation

**Output**: Array med `content` och `metadata`

---

### PriceEstimateMapper

**Purpose**: Map AI analysis till predefined price/time ranges

**Logic**:
- Takes AI's complexity assessment + feature analysis
- Maps to traditional hourly ranges (Simpel: 40-80h, Komplex: 200-400h, etc.)
- Generates AI-driven estimate (typically 30-50% snabbare)
- Calculates savings (hours, price, percentage)
- Includes VAT calculations (25%)

**Output**: Structured array för PriceEstimation model

**Rationale**: Kombinerar AI's flexibility med realistiska business constraints

---

### GooglePlacesService

**Purpose**: Google Places API integration för reviews demo

**Methods**:
- `searchPlaces($query)` - Text search för places
- `getPlaceReviews($placeId)` - Hämta reviews för specific place

**Configuration**:
```php
'google' => [
    'places_api_key' => env('GOOGLE_PLACES_API_KEY'),
    'default_place_id' => env('GOOGLE_PLACES_DEFAULT_PLACE_ID'),
]
```

**Rate Limiting**:
- Search: 20/minut
- Reviews: 30/minut
- Default place: 60/minut

**Usage**: Api\GoogleReviewsController för demo showcase

---

### GdprDataExportService

**Purpose**: Generate GDPR-compliant data exports

**Methods**:
- `exportUserData($email)` - Full data export som JSON
- `getDataSummary($email)` - Summary av lagrad data

**Export Includes**:
- Contact messages (sent + received)
- Cookie consent history
- Price estimations
- Website audit requests
- Metadata (IP addresses, timestamps)

**Format**: Structured JSON med categories och timestamps

**Delivery**: Email med JSON attachment eller download link

---

### GdprDataDeletionService

**Purpose**: Handle GDPR deletion requests

**Methods**:
- `createDeletionRequest($email, $ipAddress)` - Create request med token
- `getPreDeletionSummary($email)` - Preview vad som raderas
- `processDeletionRequest($token, $mode)` - Execute deletion/anonymization
- `getDeletionConfirmationEmailPreview($email)` - Email template

**Deletion Modes**:
- **Full deletion**: Radera all data kopplad till email
- **Anonymization**: Replace email med `[email protected]`, retain metadata

**Scope**:
- ContactMessage (original + replies)
- CookieConsent
- PriceEstimation
- WebsiteAudit
- GdprDataRequest (self)

**Security**: Token-based confirmation (click-to-confirm link)

---

### CookieConsentService

**Purpose**: GDPR cookie consent management

**Methods**:
- `storeConsent($categories, $ipAddress, $userAgent)` - Save consent
- `getConsent($consentId)` - Retrieve consent by ID
- `hasConsent($consentId, $category)` - Check specific category
- `acceptAll($ipAddress, $userAgent)` - Accept alla categories
- `rejectAll($ipAddress, $userAgent)` - Reject alla (only essential)

**Categories**:
- `analytics` - Google Analytics (GA4)
- `marketing` - Marketing cookies (future)
- `preferences` - User preferences (dark mode, etc.)

**Storage**:
- Database: `cookie_consents` table
- Cookie: `cookie_consent_id` (90 days, HttpOnly, SameSite=Lax)

**Integration**: Google Analytics läser consent innan load

---

### StructuredDataService

**Purpose**: Generate JSON-LD structured data för SEO

**Schema Types**:
1. **Organization**:
   - Name, URL, logo, description
   - Contact info, social profiles
   - SameAs links (GitHub, LinkedIn)

2. **Person**:
   - Name, job title, URL
   - WorksFor → Organization
   - SameAs social links

3. **WebSite**:
   - Name, URL, description
   - SearchAction potential action

4. **BreadcrumbList**:
   - Dynamic based on current page
   - Position-based navigation

**Usage**: `<x-seo-meta>` component injects schema per page

**Configuration**: Defaults i `config/seo.php`, overridable per page

---

### TurnstileService (Not in codebase - using package)

**Purpose**: Cloudflare Turnstile verification

**Protected Endpoints**:
- POST `/contact`
- POST `/audit`
- POST `/api/price-estimate`

**Verification**:
- Client sends `cf-turnstile-response` token
- Backend verifies med Cloudflare API
- Fallback on timeout/outage (honeypot + rate limiting still protect)

**Dev Mode**: `TURNSTILE_ENABLED=false` bypasses validation med hidden input

## Meddelandesystem (Messaging Architecture)

### Svar-Token System

**Workflow**:
1. User sends contact message → `ContactMessage` created med unik `reply_token` (32 chars)
2. SendContactEmail job dispatched → Email till admin med Reply-To: `reply-{token}@atdev.me`
3. Admin svarar (två sätt):
   - **Admin panel**: `/admin/messages/{id}` → Form för reply
   - **Email client**: Direct reply till notification email
4. SendReplyEmail job → Email till customer
5. Customer replies → Email går till `reply-{token}@atdev.me`
6. Mailgun webhook (`/mailgun/inbound`) → Parsear reply, creates ContactMessage med `is_admin_reply = false`
7. SendCustomerReplyNotification → Admin gets notified

**Threading Model**:
- `parent_id`: Links replies to original message
- `is_admin_reply`: Boolean för vem som skrev
- `conversation()` method: Returns hela tråden sorterad på `created_at`

**Security**:
- Reply tokens är unika och unpredictable (32 random chars)
- Mailgun webhook signature verification (HMAC-SHA256)
- Timestamp validation (max 15 min old)
- Rate limiting: 100 requests/minut

### Mailgun-Integration

**Webhook Endpoint**: `POST /mailgun/inbound`
- **Subdomain**: `webhooks.atdev.me` (DNS only, inte proxied)
- **CSRF**: Exempt (använder HMAC signature instead)
- **Verification**:
  ```php
  hash_hmac('sha256', $timestamp . $token, $signingKey) === $signature
  ```
- **Timestamp**: Max 15 minuter gammal (förhindrar replay attacks)

**Mailgun Route Configuration**:
```
Expression: match_recipient("reply-.*@atdev.me")
Action: Store and Notify → https://webhooks.atdev.me/mailgun/inbound
```

**DNS Setup**:
- MX records pekar på Mailgun's servers
- `MAILGUN_DOMAIN` i .env måste matcha MX records domain
- Reply-address genereras från: `reply-{token}@{MAILGUN_DOMAIN}`

**Se**: `docs/mailgun-setup.md` för fullständig setup-guide

## AI-Funktionsarkitektur

### AI Assistant (Chat Widget)

**Components**:
- **Backend**: AIService med `createPortfolioPrompt()` för context
- **Frontend**: Alpine.js chat widget (fixed bottom-right)
- **Database**: Chat model för historik
- **API**:
  - POST `/api/chat` (5/minut rate limit)
  - GET `/api/chat/history`

**Context Building**:
1. Fetch alla published projects
2. Fetch FAQs med `show_in_ai_chat = true`
3. Build system prompt med portfolio info
4. Add user question
5. Include previous chat history (last 10 messages)
6. Send to Anthropic Claude

**Features**:
- Session-based conversation context
- Portfolio-aware responses
- Technical advice om webbutveckling
- Kan svara om specifika projekt
- LocalStorage persistence

**Rate Limiting**: 5 requests/minut per session

---

### Webbplatsgransknings-Pipeline

**Workflow**:
1. **User submits** `/audit` form → Creates WebsiteAudit med `status = pending`
2. **Duplicate check**: Same URL within 7 days → Show existing audit
3. **ProcessWebsiteAudit job** dispatched → `status = processing`
4. **WebsiteDataCollector** runs:
   - Fetch HTML
   - Take screenshot (Browsershot)
   - Parse DOM
   - Extract ground truth data (meta, headings, images, links, technical)
   - Calculate preliminary scores
5. **AIService.analyzeWebsite()** runs:
   - Send ground truth data + screenshot to Claude
   - AI analyzes SEO, technical, UX, accessibility
   - Returns structured report med scores och recommendations
6. **Save results** → `status = completed`, scores saved
7. **Send email** → User notified med resultat
8. **Error handling** → `status = failed` if issues

**Timeout**: 180 seconds max processing time

**Rate Limit**: 3 audits/dag per IP (admin exempt)

**Ground Truth Data Includes**:
- Meta tags completeness
- Heading structure (h1-h6)
- Image optimization (alt-text, lazy-loading)
- Link health (internal vs external)
- Technical metrics (HTML size, viewport meta, load time)
- CSS/HTML excerpts för context

**AI Scoring**:
- **SEO Score**: 0-100 (meta tags, headings, content structure)
- **Technical Score**: 0-100 (performance, optimization, accessibility)
- **Overall Score**: Average of SEO + Technical

---

### Priskalkylator-Pipeline

**Workflow**:
1. **User submits** `/api/price-estimate` form → Creates PriceEstimation med `status = pending`
2. **BrightDataScraper** runs (if `website_url` provided):
   - Scrapes target website via proxy
   - Extracts technology stack, content structure
   - Saves `scraped_content` och `scraped_metadata`
   - Sets `scrape_successful = true/false`
3. **AIService.estimateProjectPrice()** runs:
   - Takes user description + scraped data
   - AI analyzes complexity, required features, solution approach
   - Returns project type, complexity level, key features
4. **PriceEstimateMapper** runs:
   - Maps AI analysis to hour/price ranges
   - Generates traditional estimate (conservative)
   - Generates AI-driven estimate (optimistic, 30-50% snabbare)
   - Calculates savings
5. **Save results** → Return estimation to user

**Rate Limit**: 5 requests/10 minuter per IP (admin exempt)

**Scraping Fallback**: If scraping fails, AI estimerar baserat på description only

**Dual Pricing**:
- **Traditional**: Conservative approach, manual processes
- **AI-driven**: Optimistic with AI assistance, automation

---

### Smart Meny (Allergenanalys)

**Purpose**: Detect allergens i Swedish menu text

**Workflow**:
1. User inputs menu item text (Swedish)
2. AIService.analyzeMenuAllergens() runs:
   - Sends text to Claude med Swedish allergen context
   - AI detects EU 14 allergener (gluten, lactose, nuts, etc.)
   - Returns dietary preferences (vegan, vegetarian, etc.)
   - Assigns severity levels (critical, high, medium, low)
3. Returns structured JSON med detected allergens

**Allergen Database**: `config/allergens.php`
- EU 14 allergens med Swedish keywords
- Dietary preferences
- Severity levels per allergen

**Rate Limit**: 10 requests/minut per IP

**Use Case**: Restaurant menu showcase, smart ordering systems

## GDPR-Arkitektur

### Cookie Consent Flow

```
User visits site
→ Cookie banner displays (if no consent_id cookie)
→ User selects categories (or Accept All / Reject All)
→ POST /api/consent → CookieConsentService.storeConsent()
→ Database: CookieConsent record created
→ Cookie: cookie_consent_id set (90 days, HttpOnly, SameSite=Lax)
→ Frontend: Google Analytics loads if analytics=true
```

**Consent Categories**:
- **Essential** (alltid aktiv): Sessions, CSRF, auth
- **Analytics** (optional): Google Analytics 4
- **Marketing** (optional): Future marketing cookies
- **Preferences** (optional): Dark mode, language

**API Endpoints**:
- GET `/api/consent` - Check consent status
- POST `/api/consent` - Store custom consent
- POST `/api/consent/accept-all` - Accept alla
- POST `/api/consent/reject-all` - Reject alla (only essential)
- GET `/api/consent/check/{category}` - Check specific category

### Data Export Flow

```
User requests export at /gdpr/export-demo
→ GdprDataExportService.exportUserData($email)
→ Collect all data tied to email:
  - Contact messages
  - Cookie consents
  - Price estimations
  - Website audits
→ Format as structured JSON
→ Send email med JSON attachment
→ User downloads data
```

**Export Format**:
```json
{
  "exported_at": "2025-01-15 12:00:00",
  "email": "user@example.com",
  "data": {
    "contact_messages": [...],
    "cookie_consents": [...],
    "price_estimations": [...],
    "website_audits": [...]
  }
}
```

### Data Deletion Flow

```
User requests deletion at /gdpr/delete-demo
→ GdprDataDeletionService.createDeletionRequest($email)
→ GdprDataRequest created med unique token
→ Confirmation email sent med link: /gdpr/confirm-deletion/{token}
→ User clicks link → Show preview av data to be deleted
→ User confirms → POST /gdpr/process-deletion/{token}
→ GdprDataDeletionService.processDeletionRequest($token, $mode)
→ IF mode=full: DELETE all records
→ IF mode=anonymize: Replace email med anonymized version
→ Confirmation email sent
```

**Anonymization Example**:
```php
// Before
email: user@example.com

// After
email: deleted_abc123@deleted.com
name: [Deleted User]
```

## Security Architecture

### Multi-Layer Bot Protection

**Layer 1: Cloudflare Turnstile**
- ML-based bot detection på client-side
- Invisible eller managed challenge
- Protected endpoints: contact, audit, price-estimate
- Fallback on outage (other layers still protect)

**Layer 2: Honeypot Fields**
- Hidden `website` field på contact form
- Bots fill it automatically
- Humans don't see it (CSS: `display:none`)
- Validation: `'website' => 'nullable|max:0'`

**Layer 3: Rate Limiting**
- IP-based throttling per endpoint
- Different limits per use case (chat: 5/min, audit: 3/day)
- Admin exempt från vissa limits
- Implemented via Laravel throttle middleware

**Layer 4: CSRF Protection**
- Laravel standard CSRF tokens
- All POST/PUT/DELETE require valid token
- Webhook endpoints exempt (use signature verification instead)

### Security Headers Middleware

**SecurityHeadersMiddleware** (`app/Http/Middleware/SecurityHeaders.php`):

Adds headers:
- **Content-Security-Policy (CSP)**: Built from `config/seo.php`
  - script-src: self, cdn.jsdelivr.net (Alpine.js), Turnstile, GA4
  - style-src: self, unsafe-inline (Tailwind)
  - img-src: self, data:, Google (GA4)
  - connect-src: self, Anthropic API, Google Analytics
- **Strict-Transport-Security (HSTS)**: `max-age=31536000; includeSubDomains`
- **X-Frame-Options**: `SAMEORIGIN`
- **X-Content-Type-Options**: `nosniff`
- **Referrer-Policy**: `strict-origin-when-cross-origin`
- **Permissions-Policy**: `geolocation=(), microphone=(), camera=()`

**Cache Headers Middleware** (`app/Http/Middleware/AddCacheHeaders.php`):

Adds caching for static assets:
- **Vite assets** (`/build/*`): `public, max-age=31536000, immutable`
- **Images** (`*.jpg, *.png, *.svg, *.webp`): `public, max-age=31536000`

### Webhook Security

**Mailgun Webhook**:
- **HMAC-SHA256 Signature Verification**:
  ```php
  $expectedSignature = hash_hmac('sha256', $timestamp . $token, $signingKey);
  if (!hash_equals($expectedSignature, $signature)) {
      abort(403, 'Invalid signature');
  }
  ```
- **Timestamp Validation**: Max 15 minuter gammal (förhindrar replay attacks)
- **CSRF Exempt**: Webhook endpoint exempt från CSRF (uses signature instead)
- **Rate Limiting**: 100 requests/minut

## Performance & Caching

### Queue System

**Local Development**:
- Driver: `database`
- Worker: `php artisan queue:work`
- Jobs table: `jobs`
- Failed jobs: `failed_jobs`

**Production**:
- Driver: `redis`
- Worker: Supervisor (auto-restart on failure)
- Connection: Redis `queue` database
- Max tries: 3 (default), 2 för ProcessWebsiteAudit

**Jobs**:
1. **TakeProjectScreenshot** (sync during dev, async in prod)
2. **SendContactEmail** (async)
3. **SendReplyEmail** (async)
4. **SendCustomerReplyNotification** (async)
5. **ProcessWebsiteAudit** (async, 180s timeout)

### Caching Strategy

**Config Cache** (production):
```bash
php artisan config:cache  # Caches all config files
php artisan route:cache   # Caches route definitions
php artisan view:cache    # Precompiles Blade templates
```

**Asset Caching**:
- Vite builds med content-hashed filenames (e.g., `app.abc123.js`)
- Cache headers: `max-age=31536000, immutable`
- Browser caches assets indefinitely, busts cache on file change

**Database Query Caching**:
- Not implemented yet (kan läggas till med Redis later)
- Candidates: Profile::current(), featured projects, FAQs

### Rate Limiting Matrix

| Endpoint                     | Limit                | Scope     | Admin Exempt |
|------------------------------|----------------------|-----------|--------------|
| POST /contact                | 5/min, 20/day        | IP        | No           |
| POST /api/chat               | 5/min                | Session   | No           |
| POST /audit                  | 3/day                | IP        | Yes          |
| POST /api/price-estimate     | 5/10min              | IP        | Yes          |
| POST /api/menu/analyze       | 10/min               | IP        | No           |
| POST /mailgun/inbound        | 100/min              | IP        | N/A          |
| GET /api/demos/google-reviews| 60/min (default)     | IP        | No           |
| POST /api/demos/google-reviews/search | 20/min    | IP        | No           |
| GET /api/demos/google-reviews/{id}    | 30/min    | IP        | No           |

## Deployment Architecture

### Production Stack

```
Internet
  ↓
Cloudflare (CDN + Bot Protection)
  ↓
Nginx (reverse proxy, SSL termination)
  ↓
PHP-FPM (Laravel app)
  ↓
MySQL 8 (database)
  ↓
Redis (cache + queue)
  ↓
Supervisor (queue workers)
```

### Supervisor Configuration

```ini
[program:atdev-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/atdev/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/atdev/storage/logs/worker.log
stopwaitsecs=3600
```

### Environment Differences

| Config                | Local           | Production      |
|-----------------------|-----------------|-----------------|
| APP_ENV               | local           | production      |
| APP_DEBUG             | true            | false           |
| QUEUE_CONNECTION      | database        | redis           |
| CACHE_STORE           | database        | redis           |
| MAIL_MAILER           | log             | mailgun         |
| TURNSTILE_ENABLED     | false           | true            |
| SESSION_DRIVER        | database        | redis           |
| SESSION_SECURE_COOKIE | false           | true            |

### Backup Strategy

1. **Database**: Daily MySQL dumps via cron
2. **Storage**: Rsync `storage/app` till backup server
3. **Code**: Git repository (GitHub/GitLab)
4. **Env**: Secure .env backup (encrypted)

Se `docs/deployment.md` för fullständig deployment-guide.

## Testing Architecture

**Framework**: Pest

**Structure**:
- `tests/Feature/` - HTTP tests, workflows, integration
- `tests/Unit/` - Services, models, isolated logic

**Database**:
- Transactions för isolation (automatic rollback efter test)
- SQLite in-memory för snabbare tests (optional)

**Coverage**:
- Controllers: HTTP status, redirects, validation
- Services: AI calls (mocked), scraping (mocked), GDPR workflows
- Models: Scopes, methods, relationships
- Jobs: Dispatched correctly, handles failures

**Running**:
```bash
php artisan test               # All tests
php artisan test --parallel    # Parallel execution
php artisan test --coverage    # Coverage report
```

## Future Enhancements

### Planned Features
- **Real-time Chat**: WebSockets för live AI chat
- **Multi-language**: i18n support (svenska + engelska)
- **API**: RESTful API med token auth för external integrations
- **Advanced Analytics**: Custom dashboard med metrics
- **A/B Testing**: Feature flags och experiment tracking

### Scaling Considerations
- **Horizontal Scaling**: Load balancer + multiple app servers
- **Database Replication**: Read replicas för queries
- **CDN**: Static assets via Cloudflare eller AWS CloudFront
- **Queue Workers**: Auto-scaling based på queue depth
- **Redis Cluster**: För high-availability caching

---

**Version**: 1.0
**Last Updated**: 2025-01-15
**Maintainer**: andreas@atdev.me
