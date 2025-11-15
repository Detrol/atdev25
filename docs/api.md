# ATDev - API-Dokumentation

## Översikt

ATDev provides a RESTful JSON API för att integrera med AI-features, GDPR-compliance, och interactive demos. Alla API routes har prefix `/api` och returnerar JSON responses.

**Base URL**: `https://atdev.me/api` (production) eller `http://127.0.0.1:8000/api` (local)

**Authentication**: Inga API keys required för public endpoints (rate-limited per IP/session)

**Rate Limiting**: Se "Rate Limits" section för detaljer per endpoint

---

## API-Kategorier

1. **AI-Assistent API** - Chat with portfolio-aware AI assistant
2. **Cookie-Samtycke API** - GDPR cookie consent management
3. **Priskalkylator API** - AI-driven project price estimation
4. **Smart Meny API** - Allergen detection in Swedish menu text
5. **Tech Stack API** - Technology stack data
6. **Google Reviews Demo API** - Google Places integration

---

## AI-Assistent API

### Översikt

Conversational AI assistant med portfolio context (projects + FAQs). Session-based för att behålla conversation history.

**Base**: `/api/chat`

---

### POST /api/chat

Send a question to the AI assistant.

**Rate Limit**: 5 requests/minute per session

**Request**:
```http
POST /api/chat HTTP/1.1
Content-Type: application/json

{
  "question": "Vad använder du för backend-teknologi?",
  "session_id": "abc123-def456-ghi789"
}
```

**Request Fields**:
- `question` (required, string, max 1000 chars) - User's question
- `session_id` (required, string) - Unique session identifier (UUID recommended)

**Response** (200 OK):
```json
{
  "answer": "Jag använder Laravel 12 som backend-ramverk tillsammans med PHP 8.2+. För databas använder jag MySQL 8, och för caching och queue i produktion används Redis. All AI-funktionalitet drivs av Anthropic Claude 3.7 Sonnet.",
  "session_id": "abc123-def456-ghi789"
}
```

**Error Responses**:
```json
// 429 Too Many Requests
{
  "message": "Too Many Attempts."
}

// 422 Validation Error
{
  "message": "The given data was invalid.",
  "errors": {
    "question": ["The question field is required."],
    "session_id": ["The session id field is required."]
  }
}
```

**Context Building**:
- Last 10 messages from session history
- All published projects (title, summary, technologies)
- FAQs with `show_in_ai_chat = true`

**Example (JavaScript)**:
```javascript
async function askAI(question, sessionId) {
  const response = await fetch('/api/chat', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
      question: question,
      session_id: sessionId
    })
  });

  if (!response.ok) {
    throw new Error(`API error: ${response.status}`);
  }

  return await response.json();
}

// Usage
const result = await askAI("Hur lång tid tar ett projekt?", "my-session-123");
console.log(result.answer);
```

---

### GET /api/chat/history

Retrieve conversation history for a session.

**Request**:
```http
GET /api/chat/history?session_id=abc123&limit=10 HTTP/1.1
```

**Query Parameters**:
- `session_id` (required, string) - Session identifier
- `limit` (optional, integer, 1-50, default: 10) - Number of messages to return

**Response** (200 OK):
```json
{
  "history": [
    {
      "question": "Vad använder du för backend-teknologi?",
      "answer": "Jag använder Laravel 12...",
      "created_at": "2025-01-15 12:00:00"
    },
    {
      "question": "Kan du bygga e-handel?",
      "answer": "Ja, jag har erfarenhet av...",
      "created_at": "2025-01-15 12:01:30"
    }
  ]
}
```

---

## Cookie-Samtycke API

### Översikt

GDPR-compliant cookie consent management med category-based permissions.

**Categories**: `analytics`, `marketing`, `preferences`

**Storage**: 90-day cookie (`cookie_consent_id`)

---

### GET /api/consent

Get current consent status.

**Response** (200 OK):
```json
{
  "consent_id": "abc123def456",
  "categories": ["analytics", "preferences"],
  "created_at": "2025-01-15"
}
```

**Response** (404 if no consent):
```json
{
  "consent_id": null,
  "categories": [],
  "created_at": null
}
```

---

### POST /api/consent

Store custom consent preferences.

**Request**:
```http
POST /api/consent HTTP/1.1
Content-Type: application/json

{
  "categories": ["analytics", "preferences"]
}
```

**Request Fields**:
- `categories` (required, array) - Array of consented categories
- Each category must be: `analytics`, `marketing`, or `preferences`

**Response** (201 Created):
```json
{
  "consent_id": "abc123def456",
  "message": "Consent saved successfully"
}
```

**Side Effects**:
- Creates `CookieConsent` record in database
- Sets `cookie_consent_id` cookie (90 days, HttpOnly, SameSite=Lax)

---

### POST /api/consent/accept-all

Accept all cookie categories.

**Request**:
```http
POST /api/consent/accept-all HTTP/1.1
```

**Response** (201 Created):
```json
{
  "consent_id": "abc123def456",
  "categories": ["analytics", "marketing", "preferences"]
}
```

---

### POST /api/consent/reject-all

Reject all non-essential cookies.

**Request**:
```http
POST /api/consent/reject-all HTTP/1.1
```

**Response** (201 Created):
```json
{
  "consent_id": "abc123def456",
  "categories": []
}
```

**Note**: Essential cookies (sessions, CSRF) are always active.

---

### GET /api/consent/check/{category}

Check if specific category is consented.

**Request**:
```http
GET /api/consent/check/analytics HTTP/1.1
```

**Response** (200 OK):
```json
{
  "consented": true
}
```

**Example (JavaScript)**:
```javascript
async function hasAnalyticsConsent() {
  const response = await fetch('/api/consent/check/analytics');
  const data = await response.json();

  if (data.consented) {
    // Load Google Analytics
    loadGoogleAnalytics();
  }
}
```

---

## Priskalkylator API

### Översikt

AI-driven project price estimation med web scraping and dual pricing (traditional vs AI-driven).

**Rate Limit**: 5 requests per 10 minutes per IP (admin exempt)

**Protected by**: Cloudflare Turnstile

---

### POST /api/price-estimate

Generate project price estimate.

**Request**:
```http
POST /api/price-estimate HTTP/1.1
Content-Type: application/json

{
  "description": "Jag behöver en e-handelsplattform för att sälja ekologiska produkter. Plattformen ska ha produkt-katalog, varukorg, betalningsintegration med Klarna och Swish, samt ett admin-gränssnitt för att hantera produkter och ordrar.",
  "service_category": "E-handel",
  "website_url": "https://example.com",
  "cf-turnstile-response": "token-from-turnstile-widget"
}
```

**Request Fields**:
- `description` (required, string, 50-2000 chars) - Detailed project description
- `service_category` (optional, string) - Service type (e.g., "Webbplats", "E-handel", "App")
- `website_url` (optional, url) - Existing website för scraping teknologier
- `cf-turnstile-response` (required in production) - Turnstile token

**Response** (200 OK):
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
  "key_features": [
    "Product catalog with search",
    "Shopping cart functionality",
    "Payment integration (Klarna, Swish)",
    "Admin dashboard",
    "Order management system"
  ],
  "solution_approach": "Laravel-based e-commerce solution med Livewire för admin interface och Stripe/Klarna för betalningar. Tailwind CSS för responsive design."
}
```

**Workflow**:
1. (Optional) BrightData scrapes `website_url` för tech stack
2. AI analyzes description + scraped data
3. PriceEstimateMapper maps to hour/price ranges
4. Returns dual pricing: traditional (conservative) vs AI-driven (optimistic)

**Price Calculation**:
- **Traditional**: Manual approach, standard timelines
- **AI-driven**: Optimized med AI/automation tools (typically 30-50% faster)
- **VAT**: 25% added to base price
- **Currency**: SEK (Swedish Krona)

---

## Smart Meny API

### Översikt

AI-powered allergen detection i Swedish menu text baserat på EU's 14 allergens.

**Rate Limit**: 10 requests/minute per IP

---

### POST /api/menu/analyze-allergens

Analyze menu text för allergens.

**Request**:
```http
POST /api/menu/analyze-allergens HTTP/1.1
Content-Type: application/json

{
  "menu_text": "Pasta carbonara med bacon, ägg, grädde och parmesan. Serveras med vitlöksbröd."
}
```

**Request Fields**:
- `menu_text` (required, string, max 1000 chars) - Menu item text in Swedish

**Response** (200 OK):
```json
{
  "allergens": [
    {
      "name": "Gluten",
      "severity": "critical",
      "found_in": "pasta, vitlöksbröd",
      "keywords": ["pasta", "bröd"]
    },
    {
      "name": "Ägg",
      "severity": "critical",
      "found_in": "carbonara sås",
      "keywords": ["ägg"]
    },
    {
      "name": "Mjölkprodukter",
      "severity": "high",
      "found_in": "grädde, parmesan",
      "keywords": ["grädde", "parmesan"]
    }
  ],
  "dietary_preferences": {
    "vegan": false,
    "vegetarian": false,
    "pescetarian": false,
    "gluten_free": false,
    "lactose_free": false
  },
  "safe_for": [],
  "warnings": [
    "Innehåller kritiska allergener: gluten, ägg",
    "Innehåller mjölkprodukter"
  ]
}
```

**EU 14 Allergens Detected**:
1. Gluten (spannmål)
2. Kräftdjur
3. Ägg
4. Fisk
5. Jordnötter
6. Sojabönor
7. Mjölk/laktos
8. Nötter (mandel, valnöt, etc.)
9. Selleri
10. Senap
11. Sesamfrön
12. Svaveldioxid/sulfiter
13. Lupin
14. Blötdjur

**Severity Levels**:
- `critical` - Life-threatening allergener (nötter, jordnötter, etc.)
- `high` - Vanliga allergier (mjölk, ägg, gluten)
- `medium` - Mindre vanliga (selleri, senap)
- `low` - Sällan förekommande (lupin)

---

## Teknisk Stack API

### Översikt

Technology stack data för visualization och external integrations.

---

### GET /api/tech-stack

Get technology stack data.

**Response** (200 OK):
```json
{
  "technologies": [
    {
      "name": "Laravel",
      "category": "Backend",
      "level": 5,
      "description": "PHP framework"
    },
    {
      "name": "Vue.js",
      "category": "Frontend",
      "level": 4,
      "description": "JavaScript framework"
    },
    {
      "name": "MySQL",
      "category": "Database",
      "level": 5,
      "description": "Relational database"
    },
    {
      "name": "Tailwind CSS",
      "category": "CSS",
      "level": 5,
      "description": "Utility-first CSS"
    }
  ]
}
```

**Categories**:
- Backend
- Frontend
- Database
- DevOps
- AI/ML
- CSS

**Level**: 1-5 (1 = beginner, 5 = expert)

---

## Google Reviews Demo API

### Overview

Google Places API integration för att visa reviews från real businesses. Demo showcase feature.

**Rate Limits**:
- Default place: 60/minute
- Search: 20/minute
- Specific place: 30/minute

**Note**: Requires `GOOGLE_PLACES_API_KEY` i .env

---

### GET /api/demos/google-reviews/default

Get default place reviews.

**Rate Limit**: 60 requests/minute

**Response** (200 OK):
```json
{
  "place": {
    "place_id": "ChIJN1t_tDeuEmsRUsoyG83frY4",
    "name": "Google Sydney",
    "formatted_address": "48 Pirrama Rd, Pyrmont NSW 2009, Australia",
    "rating": 4.5,
    "user_ratings_total": 2847,
    "website": "https://www.google.com",
    "phone_number": "+61 2 9374 4000"
  },
  "reviews": [
    {
      "author_name": "John Doe",
      "rating": 5,
      "text": "Great place to work!",
      "time": 1642102800,
      "relative_time_description": "2 months ago"
    }
  ]
}
```

**Default Place**: Configured via `GOOGLE_PLACES_DEFAULT_PLACE_ID` i .env

---

### POST /api/demos/google-reviews/search

Search för places.

**Rate Limit**: 20 requests/minute

**Request**:
```http
POST /api/demos/google-reviews/search HTTP/1.1
Content-Type: application/json

{
  "query": "restaurants in Stockholm"
}
```

**Response** (200 OK):
```json
{
  "places": [
    {
      "place_id": "ChIJ...",
      "name": "Restaurant Name",
      "formatted_address": "...",
      "rating": 4.5,
      "user_ratings_total": 123
    }
  ]
}
```

---

### GET /api/demos/google-reviews/{placeId}

Get specific place reviews.

**Rate Limit**: 30 requests/minute

**Request**:
```http
GET /api/demos/google-reviews/ChIJN1t_tDeuEmsRUsoyG83frY4 HTTP/1.1
```

**Response**: Same format som default endpoint

---

## Felhantering

### Standard Felformat

All API errors följer Laravel's standard JSON error format:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "Validation error message"
    ]
  }
}
```

### HTTP Statuskoder

| Code | Meaning | Description |
|------|---------|-------------|
| 200 | OK | Request successful |
| 201 | Created | Resource created successfully |
| 400 | Bad Request | Invalid request format |
| 404 | Not Found | Resource not found |
| 422 | Unprocessable Entity | Validation error |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Internal Server Error | Server error |

### Hastighetsbegränsningsfel

När rate limit överskrids:

```json
{
  "message": "Too Many Attempts."
}
```

**Response Headers**:
```
X-RateLimit-Limit: 5
X-RateLimit-Remaining: 0
Retry-After: 60
```

**Handling (JavaScript)**:
```javascript
async function apiCall() {
  try {
    const response = await fetch('/api/chat', {...});

    if (response.status === 429) {
      const retryAfter = response.headers.get('Retry-After');
      console.log(`Rate limited. Retry after ${retryAfter} seconds`);
      return;
    }

    return await response.json();
  } catch (error) {
    console.error('API error:', error);
  }
}
```

---

## Hastighetsbegränsningar Sammanfattning

| Endpoint | Limit | Scope | Admin Exempt |
|----------|-------|-------|--------------|
| POST /api/chat | 5/min | Session | No |
| POST /api/price-estimate | 5/10min | IP | Yes |
| POST /api/menu/analyze-allergens | 10/min | IP | No |
| GET /api/demos/google-reviews/default | 60/min | IP | No |
| POST /api/demos/google-reviews/search | 20/min | IP | No |
| GET /api/demos/google-reviews/{id} | 30/min | IP | No |

**Admin Exempt**: Authenticated admins bypass rate limits för vissa endpoints

---

## CSRF-Skydd

All POST/PUT/DELETE requests require CSRF token (except webhook endpoints).

**Include CSRF token i requests**:

```javascript
// Get token from meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Include in fetch request
fetch('/api/chat', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': csrfToken
  },
  body: JSON.stringify({...})
});
```

**Laravel Blade (meta tag)**:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

---

## Bästa Praxis

### 1. Använd Session-ID:n Konsekvent
För AI chat, använd samma `session_id` för att behålla conversation context:
```javascript
const sessionId = localStorage.getItem('chat_session_id') || generateUUID();
localStorage.setItem('chat_session_id', sessionId);
```

### 2. Hantera Hastighetsbegränsningar På Ett Bra Sätt
Implementera retry logic med exponential backoff:
```javascript
async function apiCallWithRetry(url, options, maxRetries = 3) {
  for (let i = 0; i < maxRetries; i++) {
    const response = await fetch(url, options);

    if (response.status !== 429) {
      return response;
    }

    const retryAfter = parseInt(response.headers.get('Retry-After') || '60');
    await sleep(retryAfter * 1000 * Math.pow(2, i));  // Exponential backoff
  }
  throw new Error('Max retries exceeded');
}
```

### 3. Validera Input På Klientsidan
Validera input innan API call för bättre UX:
```javascript
function validateQuestion(question) {
  if (!question || question.length < 1 || question.length > 1000) {
    throw new Error('Question must be 1-1000 characters');
  }
}
```

### 4. Cacha Svar När Det Är Lämpligt
För tech stack och andra statiska data:
```javascript
let techStackCache = null;
let cacheExpiry = null;

async function getTechStack() {
  if (techStackCache && Date.now() < cacheExpiry) {
    return techStackCache;
  }

  const response = await fetch('/api/tech-stack');
  techStackCache = await response.json();
  cacheExpiry = Date.now() + (60 * 60 * 1000);  // 1 hour

  return techStackCache;
}
```

### 5. Hantera Fel Korrekt
Always handle errors och visa user-friendly messages:
```javascript
async function askAI(question, sessionId) {
  try {
    const response = await fetch('/api/chat', {...});

    if (!response.ok) {
      if (response.status === 422) {
        const data = await response.json();
        throw new Error(data.errors.question[0]);
      }
      throw new Error('API request failed');
    }

    return await response.json();
  } catch (error) {
    console.error('Error:', error);
    showErrorMessage('Kunde inte få svar från AI-assistenten. Försök igen.');
  }
}
```

---

## Integreringsexempel

### Vue.js Integration

```vue
<template>
  <div class="chat-widget">
    <div v-for="msg in messages" :key="msg.id" class="message">
      <p><strong>Du:</strong> {{ msg.question }}</p>
      <p><strong>AI:</strong> {{ msg.answer }}</p>
    </div>

    <input v-model="question" @keyup.enter="sendMessage" />
    <button @click="sendMessage" :disabled="loading">Skicka</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      question: '',
      messages: [],
      sessionId: this.generateSessionId(),
      loading: false
    }
  },

  methods: {
    async sendMessage() {
      if (!this.question.trim() || this.loading) return;

      this.loading = true;

      try {
        const response = await fetch('/api/chat', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': this.csrfToken
          },
          body: JSON.stringify({
            question: this.question,
            session_id: this.sessionId
          })
        });

        const data = await response.json();

        this.messages.push({
          id: Date.now(),
          question: this.question,
          answer: data.answer
        });

        this.question = '';
      } catch (error) {
        console.error('Error:', error);
      } finally {
        this.loading = false;
      }
    },

    generateSessionId() {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
        const r = Math.random() * 16 | 0;
        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
      });
    }
  },

  computed: {
    csrfToken() {
      return document.querySelector('meta[name="csrf-token"]').content;
    }
  }
}
</script>
```

### Alpine.js Integration (Simpler)

```html
<div x-data="chatWidget()">
  <div x-show="open" class="chat-window">
    <template x-for="msg in messages" :key="msg.id">
      <div class="message">
        <p><strong>Du:</strong> <span x-text="msg.question"></span></p>
        <p><strong>AI:</strong> <span x-text="msg.answer"></span></p>
      </div>
    </template>

    <input x-model="question" @keyup.enter="send()" />
    <button @click="send()" :disabled="loading">Skicka</button>
  </div>
</div>

<script>
function chatWidget() {
  return {
    open: false,
    question: '',
    messages: [],
    sessionId: generateUUID(),
    loading: false,

    async send() {
      if (!this.question.trim() || this.loading) return;

      this.loading = true;

      try {
        const response = await fetch('/api/chat', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            question: this.question,
            session_id: this.sessionId
          })
        });

        const data = await response.json();

        this.messages.push({
          id: Date.now(),
          question: this.question,
          answer: data.answer
        });

        this.question = '';
      } catch (error) {
        console.error(error);
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>
```

---

## Testing

### Manual Testing med cURL

**AI Chat**:
```bash
curl -X POST https://atdev.me/api/chat \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{"question": "Hej, hur mår du?", "session_id": "test-123"}'
```

**Price Estimate**:
```bash
curl -X POST https://atdev.me/api/price-estimate \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{
    "description": "E-handelsplattform för kläder",
    "service_category": "E-handel",
    "cf-turnstile-response": "test-token"
  }'
```

### Automated Testing

Laravel Pest tests finns i `tests/Feature/Api/`:

```bash
php artisan test --filter=AIAssistantTest
php artisan test --filter=ConsentTest
php artisan test --filter=PriceCalculatorTest
```

---

## Support & Contact

För API support eller frågor:
- **Email**: andreas@atdev.me
- **Documentation**: https://atdev.me/docs
- **GitHub Issues**: [Repository]

---

**Version**: 1.0
**Last Updated**: 2025-01-15
**Maintainer**: andreas@atdev.me
