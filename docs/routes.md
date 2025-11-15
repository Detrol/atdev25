# ATDev - Routes & Datakontrakt

## Översikt

ATDev har **65+ routes** organiserade i följande kategorier:
- **Public Routes**: Startsida, projekt, kontakt, tech stack, demos
- **SEO Routes**: Sitemap
- **Website Audit Routes**: Public audit submission och status
- **GDPR Routes**: Privacy policy, cookie policy, showcase, export/deletion
- **Webhook Routes**: Mailgun inbound email webhook
- **API Routes**: AI chat, consent, price calculator, menu analysis, Google reviews
- **Admin Routes**: Dashboard, projects, services, FAQs, messages, audits, estimations

---

## Publika Routes

### GET `/`
**Controller**: `HomeController@index`
**View**: `home`

**Datakontrakt**:
```php
[
    'profile' => Profile|null,  // Singleton profile
    'projects' => Collection<Project>  // Featured, published projects
]
```

**Project-sortering**: sort_order ASC, created_at DESC
**Profile-fält**: tagline, bio, email, phone, location, availability_status, avatar (media)
**Project-fält** (listvy): id, slug, title, summary, cover_image, technologies (array), featured

---

### GET `/tech-stack`
**Controller**: `TechStackController@index`
**View**: `tech-stack`

**Datakontrakt**:
```php
[
    'technologies' => array  // Tech stack data for D3.js visualization
]
```

**Beskrivning**: Interaktiv D3.js-visualisering av teknologier och deras relationer.

**API endpoint**: `GET /api/tech-stack` returnerar JSON för API consumers

---

### GET `/demos`
**Controller**: `DemosController@index`
**View**: `demos`

**Datakontrakt**:
```php
[
    'demos' => array  // Array of available interactive demonstrations
]
```

**Beskrivning**: Showcase-sida med full-page sections för interaktiva demos:
- 3D Product Viewer (AR-enabled)
- Before/After Slider
- Google Reviews Widget
- Smart Menu (Allergen Analysis)

**Features**:
- Apple-style full-page sections
- Smooth scroll navigation
- Glassmorphism design
- Fully functional demos

---

### GET `/projects/{slug}`
**Controller**: `ProjectController@show`
**View**: `projects.show`

**Datakontrakt**:
```php
[
    'project' => Project  // Full project object
]
```

**Route Binding**: Använder `slug` (not ID)
**Project-fält**: Alla fält inkl. description, gallery (array), live_url, github_url, client_name, client_testimonial, screenshot_path
**Visibility**: Endast `published` projekt (404 för draft)

---

### POST `/contact`
**Controller**: `ContactController@store`
**Middleware**: `throttle:contact` (5/minut, 20/dag per IP)

**Input**:
```php
[
    'name' => 'required|string|max:255',
    'email' => 'required|email|max:255',
    'phone' => 'nullable|string|max:20',
    'company' => 'nullable|string|max:255',
    'message' => 'required|string|min:10|max:5000',
    'website' => 'nullable|max:0',  // Honeypot - must be empty
    'cf-turnstile-response' => 'required|turnstile'  // Bot protection
]
```

**Response**: Redirect back with success message

**Side Effects**:
- Creates `ContactMessage` with unique `reply_token`
- Dispatches `SendContactEmail` job → andreas@atdev.me

**Security**:
- Cloudflare Turnstile verification
- Honeypot field (`website`)
- Rate limiting
- IP and user-agent logging

---

## SEO-Routes

### GET `/sitemap.xml`
**Controller**: `SitemapController@index`
**Content-Type**: `application/xml`

**Includes**:
- Home page
- Tech stack page
- Demos page
- All published projects (`/projects/{slug}`)
- Privacy policy
- Cookie policy

**Generated dynamically** via `SitemapController`

**Command**: `php artisan sitemap:generate` (future: static file generation)

---

## Webbplatsgranskning-Routes

### GET `/audit`
**Controller**: `WebsiteAuditController@index`
**View**: `audit.index`

**Beskrivning**: Public form för att submitta website audit request

---

### POST `/audit`
**Controller**: `WebsiteAuditController@store`
**Middleware**: `throttle:3,1440` (3 requests/day per IP, admin exempt)

**Input**:
```php
[
    'url' => 'required|url|max:255',
    'name' => 'required|string|max:255',
    'email' => 'required|email|max:255',
    'company' => 'nullable|string|max:255',
    'cf-turnstile-response' => 'required|turnstile'
]
```

**Response**: Redirect to `/audit/{token}`

**Side Effects**:
- Creates `WebsiteAudit` with unique token
- Checks for duplicate URL (same URL within 7 days → returns existing)
- Dispatches `ProcessWebsiteAudit` job (180s timeout)

**Duplicate Detection**: Same URL within 7 days redirects to existing audit

**Rate Limiting**: 3/day per IP (admin exempt)

---

### GET `/audit/{token}`
**Controller**: `WebsiteAuditController@show`
**View**: `audit.show`

**Datakontrakt**:
```php
[
    'audit' => WebsiteAudit  // Full audit object with status, scores, report
]
```

**Status Values**:
- `pending` - Just created
- `processing` - Job running
- `completed` - Analysis done, scores available
- `failed` - Job failed

**Public Access**: Anyone med token kan se resultat

---

## GDPR-Routes

### GET `/privacy`
**Controller**: `GdprController@privacy`
**View**: `gdpr.privacy`

**Beskrivning**: Comprehensive privacy policy

---

### GET `/cookies`
**Controller**: `GdprController@cookies`
**View**: `gdpr.cookies`

**Beskrivning**: Detailed cookie policy

---

### GET `/gdpr/showcase`
**Controller**: `GdprController@showcase`
**View**: `gdpr.showcase`

**Beskrivning**: Interactive demo av alla GDPR features (consent, export, deletion)

---

### POST `/gdpr/export-demo`
**Controller**: `GdprController@exportDemo`

**Input**:
```php
[
    'email' => 'required|email'
]
```

**Response**: Redirect back with success message

**Side Effects**:
- Calls `GdprDataExportService::exportUserData($email)`
- Sends email med JSON attachment

---

### POST `/gdpr/delete-demo`
**Controller**: `GdprController@deleteDemo`

**Input**:
```php
[
    'email' => 'required|email'
]
```

**Response**: Redirect to confirmation page

**Side Effects**:
- Creates `GdprDataRequest` med unique token
- Sends confirmation email med link

---

### GET `/gdpr/confirm-deletion/{token}`
**Controller**: `GdprController@confirmDeletion`
**View**: `gdpr.confirm-deletion`

**Datakontrakt**:
```php
[
    'request' => GdprDataRequest,
    'summary' => array  // Preview av data som raderas
]
```

**Beskrivning**: Shows what data will be deleted before user confirms

---

### POST `/gdpr/process-deletion/{token}`
**Controller**: `GdprController@processDeletion`

**Input**:
```php
[
    'mode' => 'required|in:full,anonymize'
]
```

**Response**: Redirect back with success message

**Side Effects**:
- Calls `GdprDataDeletionService::processDeletionRequest($token, $mode)`
- Deletes or anonymizes all user data
- Sends confirmation email

**Modes**:
- `full` - Complete deletion
- `anonymize` - Replace email med `deleted_xxx@deleted.com`

---

## Webhook-Routes

### POST `/mailgun/inbound`
**Controller**: `WebhookController@handleInbound`
**Middleware**: `throttle:100,1` (100/minut)
**CSRF**: Exempt (uses HMAC signature verification)

**Subdomain**: `webhooks.atdev.me` (DNS only, not proxied)

**Input** (Mailgun POST data):
```php
[
    'timestamp' => 'required',
    'token' => 'required',
    'signature' => 'required',
    'recipient' => 'required',  // reply-{token}@atdev.me
    'sender' => 'required',
    'subject' => 'required',
    'body-plain' => 'required',
    'message-id' => 'required'
]
```

**Security Verification**:
```php
hash_hmac('sha256', $timestamp . $token, $signingKey) === $signature
```

**Timestamp Validation**: Max 15 minuter gammal (replay attack prevention)

**Side Effects**:
1. Extracts `reply_token` från recipient email
2. Finds original `ContactMessage` via token
3. Creates child reply med `is_admin_reply = false`
4. Dispatches `SendCustomerReplyNotification` to admin

**Mailgun Route Configuration**:
```
Expression: match_recipient("reply-.*@atdev.me")
Action: Store and Notify → https://webhooks.atdev.me/mailgun/inbound
```

**⚠️ Important**: `MAILGUN_DOMAIN` i .env måste matcha MX records domain

---

## API-Routes

Alla API routes har prefix `/api` och returnerar JSON.

### AI-Assistent API

#### POST `/api/chat`
**Controller**: `AIAssistantController@chat`
**Middleware**: `throttle:5,1` (5 requests/minut per session)

**Input**:
```php
[
    'question' => 'required|string|max:1000',
    'session_id' => 'required|string'
]
```

**Response**:
```json
{
  "answer": "AI's response text",
  "session_id": "abc123"
}
```

**Side Effects**:
- Saves question + answer to `chats` table
- Uses previous chat history for context (last 10 messages)
- Includes portfolio context (projects + FAQs)

**Rate Limiting**: 5/minut per session

---

#### GET `/api/chat/history`
**Controller**: `AIAssistantController@history`

**Query Params**:
```php
[
    'session_id' => 'required|string',
    'limit' => 'nullable|integer|min:1|max:50'  // Default: 10
]
```

**Response**:
```json
{
  "history": [
    {
      "question": "...",
      "answer": "...",
      "created_at": "2025-01-15 12:00:00"
    }
  ]
}
```

---

### Cookie-Samtycke API

#### GET `/api/consent`
**Controller**: `Api\ConsentController@index`

**Response**:
```json
{
  "consent_id": "abc123",
  "categories": ["analytics", "preferences"],
  "created_at": "2025-01-15"
}
```

**Beskrivning**: Returns current consent status from cookie

---

#### POST `/api/consent`
**Controller**: `Api\ConsentController@store`

**Input**:
```php
[
    'categories' => 'required|array',
    'categories.*' => 'in:analytics,marketing,preferences'
]
```

**Response**:
```json
{
  "consent_id": "abc123",
  "message": "Consent saved successfully"
}
```

**Side Effects**:
- Creates `CookieConsent` record
- Sets `cookie_consent_id` cookie (90 days)

---

#### POST `/api/consent/accept-all`
**Controller**: `Api\ConsentController@acceptAll`

**Response**:
```json
{
  "consent_id": "abc123",
  "categories": ["analytics", "marketing", "preferences"]
}
```

---

#### POST `/api/consent/reject-all`
**Controller**: `Api\ConsentController@rejectAll`

**Response**:
```json
{
  "consent_id": "abc123",
  "categories": []
}
```

**Beskrivning**: Only essential cookies (sessions, CSRF)

---

#### GET `/api/consent/check/{category}`
**Controller**: `Api\ConsentController@check`

**Response**:
```json
{
  "consented": true
}
```

**Categories**: `analytics`, `marketing`, `preferences`

---

### Priskalkylator API

#### POST `/api/price-estimate`
**Controller**: `PriceCalculatorController@store`
**Middleware**: `throttle:5,10` (5 requests/10 minuter per IP, admin exempt)

**Input**:
```php
[
    'description' => 'required|string|min:50|max:2000',
    'service_category' => 'nullable|string',
    'website_url' => 'nullable|url',
    'cf-turnstile-response' => 'required|turnstile'
]
```

**Response**:
```json
{
  "project_type": "E-commerce Platform",
  "complexity": "medium",
  "traditional": {
    "hours": 120,
    "price": 120000,
    "price_vat": 150000,
    "delivery_weeks": 12
  },
  "ai_driven": {
    "hours": 80,
    "price": 80000,
    "price_vat": 100000,
    "delivery_weeks": 8
  },
  "savings": {
    "hours": 40,
    "price": 40000,
    "percentage": 33.3
  },
  "key_features": ["..."],
  "solution_approach": "..."
}
```

**Side Effects**:
1. Scrapes `website_url` via BrightData (if provided)
2. AI analyzes description + scraped data
3. PriceEstimateMapper maps to ranges
4. Saves `PriceEstimation` to database

**Rate Limiting**: 5/10min per IP (admin exempt)

---

### Smart Meny API

#### POST `/api/menu/analyze-allergens`
**Controller**: `SmartMenuController@analyzeAllergens`
**Middleware**: `throttle:10,1` (10/minut per IP)

**Input**:
```php
[
    'menu_text' => 'required|string|max:1000'
]
```

**Response**:
```json
{
  "allergens": [
    {
      "name": "Gluten",
      "severity": "critical",
      "found_in": "pasta, bread"
    }
  ],
  "dietary_preferences": ["vegetarian"],
  "safe_for": ["vegan", "lactose-free"]
}
```

**Beskrivning**: AI-powered allergen detection i Swedish menu text (EU 14 allergens)

**Rate Limiting**: 10/minut per IP

---

### Teknisk Stack API

#### GET `/api/tech-stack`
**Controller**: `TechStackController@api`

**Response**:
```json
{
  "technologies": [
    {
      "name": "Laravel",
      "category": "Backend",
      "level": 5
    }
  ]
}
```

---

### Google Reviews Demo API

#### GET `/api/demos/google-reviews/default`
**Controller**: `Api\GoogleReviewsController@default`
**Middleware**: `throttle:60,1` (60/minut)

**Response**:
```json
{
  "place": {
    "name": "...",
    "rating": 4.5,
    "user_ratings_total": 123
  },
  "reviews": [...]
}
```

**Beskrivning**: Returns default place reviews (från `GOOGLE_PLACES_DEFAULT_PLACE_ID`)

---

#### POST `/api/demos/google-reviews/search`
**Controller**: `Api\GoogleReviewsController@search`
**Middleware**: `throttle:20,1` (20/minut)

**Input**:
```php
[
    'query' => 'required|string|max:255'
]
```

**Response**:
```json
{
  "places": [
    {
      "place_id": "ChIJ...",
      "name": "Restaurant Name",
      "rating": 4.5
    }
  ]
}
```

---

#### GET `/api/demos/google-reviews/{placeId}`
**Controller**: `Api\GoogleReviewsController@show`
**Middleware**: `throttle:30,1` (30/minut)

**Response**:
```json
{
  "place": {...},
  "reviews": [...]
}
```

---

## Admin Routes

Alla admin routes kräver autentisering (`auth` middleware) och har prefix `/admin`.

### Authentication

#### GET `/admin/login`
**Fortify**: Login form

---

#### POST `/admin/login`
**Fortify**: Authenticate (max 5 försök/minut)

**Input**:
```php
[
    'email' => 'required|email',
    'password' => 'required',
    'remember' => 'nullable|boolean'
]
```

---

#### POST `/admin/logout`
**Fortify**: Logout

---

### Dashboard

#### GET `/admin`
**Controller**: `Admin\DashboardController@index`
**View**: `admin.dashboard`

**Datakontrakt**:
```php
[
    'projectsCount' => int,
    'servicesCount' => int,
    'unreadMessages' => int,
    'pendingAudits' => int,
    'recentProjects' => Collection<Project>,  // Top 5
    'recentMessages' => Collection<ContactMessage>,  // Top 5
    'recentAudits' => Collection<WebsiteAudit>  // Top 5
]
```

---

### Projects

#### GET `/admin/projects`
**Controller**: `Admin\ProjectController@index`
**View**: `admin.projects.index`

**Datakontrakt**:
```php
[
    'projects' => LengthAwarePaginator<Project>  // 20/page
]
```

---

#### GET `/admin/projects/create`
**Controller**: `Admin\ProjectController@create`
**View**: `admin.projects.create`

**Datakontrakt**:
```php
[
    'statuses' => array  // ProjectStatus enum cases
]
```

---

#### POST `/admin/projects`
**Controller**: `Admin\ProjectController@store`

**Input**: See `ProjectRequest` validation

**Response**: Redirect to index with success

**Side Effects**: Dispatches `TakeProjectScreenshot` if `live_url` present

---

#### GET `/admin/projects/{project}/edit`
**Controller**: `Admin\ProjectController@edit`
**View**: `admin.projects.edit`

**Datakontrakt**:
```php
[
    'project' => Project,
    'statuses' => array
]
```

---

#### PUT `/admin/projects/{project}`
**Controller**: `Admin\ProjectController@update`

**Input**: See `ProjectRequest` validation

**Side Effects**: Dispatches screenshot if `live_url` changed

---

#### DELETE `/admin/projects/{project}`
**Controller**: `Admin\ProjectController@destroy`

---

#### POST `/admin/projects/{project}/screenshot`
**Controller**: `Admin\ProjectController@screenshot`

**Beskrivning**: Manually trigger screenshot job

**Side Effects**: Dispatches `TakeProjectScreenshot` immediately

---

### Services

#### Resource: `/admin/services`
**Controller**: `Admin\ServiceController`

**Standard CRUD**:
- GET `/admin/services` - Index
- GET `/admin/services/create` - Create form
- POST `/admin/services` - Store
- GET `/admin/services/{service}/edit` - Edit form
- PUT `/admin/services/{service}` - Update
- DELETE `/admin/services/{service}` - Destroy

---

### FAQs

#### Resource: `/admin/faqs`
**Controller**: `Admin\FaqController`

**Standard CRUD**:
- GET `/admin/faqs` - Index
- GET `/admin/faqs/create` - Create form
- POST `/admin/faqs` - Store
- GET `/admin/faqs/{faq}/edit` - Edit form
- PUT `/admin/faqs/{faq}` - Update
- DELETE `/admin/faqs/{faq}` - Destroy

---

### Profile

#### GET `/admin/profile`
**Controller**: `Admin\ProfileController@edit`
**View**: `admin.profile.edit`

**Datakontrakt**:
```php
[
    'profile' => Profile|new Profile
]
```

---

#### PUT `/admin/profile`
**Controller**: `Admin\ProfileController@update`

**Input**: See `ProfileRequest` validation

**Side Effects**: Creates/updates singleton Profile

---

### Messages

#### GET `/admin/messages`
**Controller**: `Admin\MessageController@index`
**View**: `admin.messages.index`

**Datakontrakt**:
```php
[
    'messages' => LengthAwarePaginator<ContactMessage>  // Original messages only
]
```

**Scope**: Only shows original messages (`parent_id = null`), not replies

---

#### GET `/admin/messages/{message}`
**Controller**: `Admin\MessageController@show`
**View**: `admin.messages.show`

**Datakontrakt**:
```php
[
    'message' => ContactMessage,  // Original message
    'conversation' => Collection<ContactMessage>  // Parent + all replies
]
```

**Side Effects**: Marks message as read automatically

---

#### POST `/admin/messages/{message}/reply`
**Controller**: `Admin\MessageController@reply`

**Input**:
```php
[
    'message' => 'required|string|max:5000'
]
```

**Response**: Redirect back with success

**Side Effects**:
- Creates child reply via `$message->createReply($text, auth()->id())`
- Marks original as replied
- Dispatches `SendReplyEmail` job

---

#### POST `/admin/messages/{message}/read`
**Controller**: `Admin\MessageController@markAsRead`

**Side Effects**: Calls `$message->markAsRead()`

---

#### DELETE `/admin/messages/{message}`
**Controller**: `Admin\MessageController@destroy`

**Beskrivning**: Deletes message and all replies (cascade)

---

### Website Audits

#### GET `/admin/audits`
**Controller**: `Admin\WebsiteAuditController@index`
**View**: `admin.audits.index`

**Datakontrakt**:
```php
[
    'audits' => LengthAwarePaginator<WebsiteAudit>  // 20/page
]
```

---

#### GET `/admin/audits/{audit}`
**Controller**: `Admin\WebsiteAuditController@show`
**View**: `admin.audits.show`

**Datakontrakt**:
```php
[
    'audit' => WebsiteAudit  // Full audit with AI report
]
```

---

#### DELETE `/admin/audits/{audit}`
**Controller**: `Admin\WebsiteAuditController@destroy`

---

### Price Estimations

#### GET `/admin/estimations`
**Controller**: `Admin\PriceEstimationController@index`
**View**: `admin.estimations.index`

**Datakontrakt**:
```php
[
    'estimations' => LengthAwarePaginator<PriceEstimation>  // 20/page
]
```

---

#### GET `/admin/estimations/{estimation}`
**Controller**: `Admin\PriceEstimationController@show`
**View**: `admin.estimations.show`

**Datakontrakt**:
```php
[
    'estimation' => PriceEstimation  // Full estimation with AI analysis
]
```

---

#### DELETE `/admin/estimations/{estimation}`
**Controller**: `Admin\PriceEstimationController@destroy`

---

#### DELETE `/admin/estimations/bulk-destroy`
**Controller**: `Admin\PriceEstimationController@bulkDestroy`

**Input**:
```php
[
    'ids' => 'required|array',
    'ids.*' => 'exists:price_estimations,id'
]
```

**Beskrivning**: Bulk delete selected estimations

---

## Rate Limiting Summary

| Route | Limit | Scope | Admin Exempt |
|-------|-------|-------|--------------|
| POST /contact | 5/min, 20/day | IP | No |
| POST /audit | 3/day | IP | Yes |
| POST /api/chat | 5/min | Session | No |
| POST /api/price-estimate | 5/10min | IP | Yes |
| POST /api/menu/analyze-allergens | 10/min | IP | No |
| POST /mailgun/inbound | 100/min | IP | N/A |
| GET /api/demos/google-reviews/default | 60/min | IP | No |
| POST /api/demos/google-reviews/search | 20/min | IP | No |
| GET /api/demos/google-reviews/{id} | 30/min | IP | No |

---

## Validation Requests

### ContactRequest
`app/Http/Requests/ContactRequest.php`

**Rules**:
- name: required, string, max:255
- email: required, email, max:255
- phone: nullable, string, max:20
- company: nullable, string, max:255
- message: required, string, min:10, max:5000
- website: nullable, max:0 (honeypot)
- cf-turnstile-response: required, turnstile

---

### ProjectRequest
`app/Http/Requests/ProjectRequest.php`

**Rules**:
- title: required, string, max:255
- slug: nullable, string, unique (excluding self), max:255
- summary: nullable, string, max:500
- description: nullable, string
- live_url: nullable, url
- github_url: nullable, url
- technologies: nullable, array
- status: required, ProjectStatus enum
- featured: required, boolean
- sort_order: required, integer, min:0

---

### ProfileRequest
`app/Http/Requests/ProfileRequest.php`

**Rules**:
- tagline: nullable, string, max:255
- bio: nullable, string
- email: nullable, email, max:255
- phone: nullable, string, max:20
- location: nullable, string, max:255
- availability_status: nullable, string, max:255

---

### WebsiteAuditRequest
`app/Http/Requests/WebsiteAuditRequest.php`

**Rules**:
- url: required, url, max:255
- name: required, string, max:255
- email: required, email, max:255
- company: nullable, string, max:255
- cf-turnstile-response: required, turnstile

---

**Version**: 2.0
**Last Updated**: 2025-01-15
**Maintainer**: andreas@atdev.me
