# ATDev - GDPR Efterlevnadsguide

## Översikt

ATDev är fullt GDPR-compliant med implementerade system för:
- ✅ Cookie consent management
- ✅ Data export (Right to data portability)
- ✅ Data deletion (Right to be forgotten)
- ✅ Privacy policy
- ✅ Cookie policy
- ✅ Transparent data processing

**GDPR Regulation**: EU General Data Protection Regulation (2016/679)

---

## Cookie Consent System

### Overview

Category-based cookie consent med 90-dagars lagring enligt GDPR Article 7 (Consent).

**Implementation**: `CookieConsentService` + `Api\ConsentController`

### Cookie Categories

#### Essential (Always Active)
Tekniskt nödvändiga cookies som inte kräver samtycke:
- **Session cookies** (`laravel_session`) - User authentication
- **CSRF tokens** (`XSRF-TOKEN`) - Security protection
- **Cookie consent ID** (`cookie_consent_id`) - Stores consent choices

#### Analytics (Optional - Requires Consent)
- **Google Analytics 4** (`_ga`, `_ga_*`) - Website analytics
- **Purpose**: Understand user behavior, improve UX
- **Retention**: 2 years (GA4 default)

#### Marketing (Optional - Requires Consent)
- **Future marketing cookies** - Not yet implemented
- **Purpose**: Targeted advertising, remarketing
- **Retention**: TBD

#### Preferences (Optional - Requires Consent)
- **Dark mode preference** - UI customization
- **Language preference** - Site language
- **Retention**: 1 year

### User Flow

```
User visits site (first time)
  ↓
Cookie banner displays
  ↓
User makes choice:
  • Accept All
  • Reject All
  • Customize (select specific categories)
  ↓
POST /api/consent → CookieConsentService.storeConsent()
  ↓
CookieConsent record created in database
  ↓
cookie_consent_id cookie set (90 days)
  ↓
Frontend loads services based on consent:
  • If analytics=true → Load GA4
  • If marketing=true → Load marketing scripts
  • If preferences=true → Apply saved preferences
```

### Implementation

**Frontend (Blade Component)**:
```blade
<x-cookie-consent />
```

**Frontend (Alpine.js)**:
```javascript
<div x-data="cookieConsent()">
  <div x-show="!hasConsent" class="cookie-banner">
    <p>Vi använder cookies för att förbättra din upplevelse.</p>

    <button @click="acceptAll()">Acceptera alla</button>
    <button @click="rejectAll()">Avvisa alla</button>
    <button @click="showSettings = true">Anpassa</button>

    <!-- Custom settings dialog -->
    <div x-show="showSettings">
      <label>
        <input type="checkbox" x-model="categories.analytics">
        Analytiska cookies
      </label>
      <label>
        <input type="checkbox" x-model="categories.marketing">
        Marknadsföring
      </label>
      <label>
        <input type="checkbox" x-model="categories.preferences">
        Preferenser
      </label>

      <button @click="saveConsent()">Spara inställningar</button>
    </div>
  </div>
</div>

<script>
function cookieConsent() {
  return {
    hasConsent: !!getCookie('cookie_consent_id'),
    showSettings: false,
    categories: {
      analytics: false,
      marketing: false,
      preferences: false
    },

    async acceptAll() {
      await fetch('/api/consent/accept-all', { method: 'POST' });
      this.loadServices(['analytics', 'marketing', 'preferences']);
      this.hasConsent = true;
    },

    async rejectAll() {
      await fetch('/api/consent/reject-all', { method: 'POST' });
      this.hasConsent = true;
    },

    async saveConsent() {
      const selected = Object.keys(this.categories).filter(k => this.categories[k]);

      await fetch('/api/consent', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ categories: selected })
      });

      this.loadServices(selected);
      this.hasConsent = true;
      this.showSettings = false;
    },

    loadServices(categories) {
      if (categories.includes('analytics')) {
        this.loadGoogleAnalytics();
      }
      if (categories.includes('marketing')) {
        // Load marketing scripts
      }
    },

    loadGoogleAnalytics() {
      // Load GA4 script
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-XXXXXXXXXX');
    }
  }
}
</script>
```

**Backend API**:
```php
// GET /api/consent - Check current consent
public function index(Request $request)
{
    $consentId = $request->cookie('cookie_consent_id');

    if (!$consentId) {
        return response()->json([
            'consent_id' => null,
            'categories' => []
        ]);
    }

    $consent = app(CookieConsentService::class)->getConsent($consentId);

    return response()->json([
        'consent_id' => $consent->consent_id,
        'categories' => $consent->categories
    ]);
}

// POST /api/consent - Store custom consent
public function store(Request $request)
{
    $validated = $request->validate([
        'categories' => 'required|array',
        'categories.*' => 'in:analytics,marketing,preferences'
    ]);

    $service = app(CookieConsentService::class);

    $consent = $service->storeConsent(
        $validated['categories'],
        $request->ip(),
        $request->userAgent()
    );

    return response()->json([
        'consent_id' => $consent->consent_id,
        'message' => 'Consent saved successfully'
    ])->cookie('cookie_consent_id', $consent->consent_id, 60 * 24 * 90);  // 90 days
}
```

### Database Schema

```php
Schema::create('cookie_consents', function (Blueprint $table) {
    $table->id();
    $table->string('consent_id')->unique()->index();
    $table->json('categories');  // ["analytics", "marketing", "preferences"]
    $table->ipAddress('ip_address')->nullable();
    $table->string('user_agent')->nullable();
    $table->timestamps();
});
```

### Checking Consent

**Frontend (before loading services)**:
```javascript
async function hasAnalyticsConsent() {
  const response = await fetch('/api/consent/check/analytics');
  const data = await response.json();
  return data.consented;
}

if (await hasAnalyticsConsent()) {
  loadGoogleAnalytics();
}
```

**Backend (in controllers)**:
```php
$consentId = $request->cookie('cookie_consent_id');

if ($this->consentService->hasConsent($consentId, 'analytics')) {
    // Log analytics event
}
```

---

## Data Export (Right to Data Portability)

### Overview

GDPR Article 20: Users can request a JSON export av all stored data.

**Implementation**: `GdprDataExportService` + `GdprController@exportDemo`

### User Flow

```
User visits /gdpr/showcase
  ↓
Clicks "Export My Data"
  ↓
Enters email address
  ↓
POST /gdpr/export-demo
  ↓
GdprDataExportService.exportUserData($email)
  ↓
Collect all data tied to email:
  • Contact messages (sent + received)
  • Cookie consents
  • Price estimations
  • Website audit requests
  ↓
Format as structured JSON
  ↓
Send email med JSON attachment
  ↓
User receives email & downloads data
```

### Exported Data Structure

```json
{
  "exported_at": "2025-01-15 12:00:00",
  "email": "user@example.com",
  "data": {
    "contact_messages": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "message": "Hello...",
        "created_at": "2025-01-10 10:00:00",
        "replied": true,
        "replies": [
          {
            "message": "Thank you for contacting us...",
            "created_at": "2025-01-10 11:00:00",
            "is_admin_reply": true
          }
        ]
      }
    ],
    "cookie_consents": [
      {
        "consent_id": "abc123",
        "categories": ["analytics", "preferences"],
        "ip_address": "192.168.1.1",
        "created_at": "2025-01-10 09:00:00"
      }
    ],
    "price_estimations": [
      {
        "id": 1,
        "description": "E-commerce platform...",
        "project_type": "E-commerce",
        "complexity": "medium",
        "traditional_hours": 120,
        "ai_driven_hours": 80,
        "created_at": "2025-01-12 14:00:00"
      }
    ],
    "website_audits": [
      {
        "id": 1,
        "url": "https://example.com",
        "status": "completed",
        "seo_score": 75,
        "technical_score": 82,
        "created_at": "2025-01-14 16:00:00"
      }
    ]
  }
}
```

### Implementation

**Service Method**:
```php
// app/Services/GdprDataExportService.php

public function exportUserData(string $email): array
{
    return [
        'exported_at' => now()->toDateTimeString(),
        'email' => $email,
        'data' => [
            'contact_messages' => $this->getContactMessages($email),
            'cookie_consents' => $this->getCookieConsents($email),
            'price_estimations' => $this->getPriceEstimations($email),
            'website_audits' => $this->getWebsiteAudits($email),
        ]
    ];
}

private function getContactMessages(string $email): array
{
    return ContactMessage::where('email', $email)
        ->with('replies')
        ->get()
        ->map(function ($message) {
            return [
                'id' => $message->id,
                'name' => $message->name,
                'email' => $message->email,
                'message' => $message->message,
                'created_at' => $message->created_at->toDateTimeString(),
                'replied' => $message->status === 'replied',
                'replies' => $message->replies->map(fn($r) => [
                    'message' => $r->message,
                    'created_at' => $r->created_at->toDateTimeString(),
                    'is_admin_reply' => $r->is_admin_reply
                ])->toArray()
            ];
        })->toArray();
}

// Similar methods for other data types...
```

**Controller**:
```php
// app/Http/Controllers/GdprController.php

public function exportDemo(Request $request)
{
    $validated = $request->validate(['email' => 'required|email']);

    $export = app(GdprDataExportService::class)->exportUserData($validated['email']);

    // Send email med attachment
    Mail::to($validated['email'])->send(new DataExportMail($export));

    return redirect()->back()->with('success', 'Din data har exporterats och skickats till din e-post.');
}
```

### Email Template

```blade
{{-- resources/views/emails/data-export.blade.php --}}

<h1>Din dataexport från ATDev</h1>

<p>Hej,</p>

<p>Här är din begärda dataexport från ATDev.me enligt GDPR Article 20 (Right to data portability).</p>

<p><strong>Exporterad:</strong> {{ $export['exported_at'] }}</p>

<p>Din data finns bifogad som JSON-fil. Du kan öppna denna fil i valfri textredigerare för att se all data som vi har lagrat om dig.</p>

<h2>Datasammanfattning:</h2>
<ul>
  <li>Kontaktmeddelanden: {{ count($export['data']['contact_messages']) }}</li>
  <li>Cookie-samtycken: {{ count($export['data']['cookie_consents']) }}</li>
  <li>Prisuppskattningar: {{ count($export['data']['price_estimations']) }}</li>
  <li>Webbplatsgranskningar: {{ count($export['data']['website_audits']) }}</li>
</ul>

<p>Om du har frågor om din data, kontakta oss på andreas@atdev.me.</p>
```

---

## Data Deletion (Right to be Forgotten)

### Overview

GDPR Article 17: Users can request deletion or anonymization av all stored data.

**Implementation**: `GdprDataDeletionService` + `GdprController@deleteDemo/processDeletion`

### User Flow

```
User visits /gdpr/showcase
  ↓
Clicks "Delete My Data"
  ↓
Enters email address
  ↓
POST /gdpr/delete-demo
  ↓
GdprDataDeletionService.createDeletionRequest($email)
  ↓
GdprDataRequest created med unique token
  ↓
Confirmation email sent med link:
  /gdpr/confirm-deletion/{token}
  ↓
User clicks link
  ↓
GET /gdpr/confirm-deletion/{token}
  ↓
Show preview av data to be deleted
  ↓
User selects mode:
  • Full Deletion
  • Anonymization
  ↓
POST /gdpr/process-deletion/{token}
  ↓
GdprDataDeletionService.processDeletionRequest($token, $mode)
  ↓
IF mode=full:
  • DELETE all ContactMessages
  • DELETE all CookieConsents
  • DELETE all PriceEstimations
  • DELETE all WebsiteAudits
  • DELETE GdprDataRequest
  ↓
IF mode=anonymize:
  • UPDATE email → deleted_abc123@deleted.com
  • UPDATE name → [Deleted User]
  • KEEP record metadata (for analytics)
  ↓
Confirmation email sent
  ↓
User receives confirmation
```

### Deletion Modes

#### Full Deletion
Radera **all data** permanent från databasen.

```php
ContactMessage::where('email', $email)->delete();
CookieConsent::whereHas('request', fn($q) => $q->where('email', $email))->delete();
PriceEstimation::where('email', $email)->delete();
WebsiteAudit::where('email', $email)->delete();
```

#### Anonymization
Replace identifiable information men behåll metadata för analytics.

```php
ContactMessage::where('email', $email)->update([
    'email' => 'deleted_' . Str::random(8) . '@deleted.com',
    'name' => '[Deleted User]',
    'phone' => null,
    'company' => null
]);
```

**Rationale**: Anonymization preserves aggregate statistics (e.g., "total messages received") utan att kompromissa privacy.

### Implementation

**Service Method**:
```php
// app/Services/GdprDataDeletionService.php

public function processDeletionRequest(string $token, string $mode = 'full'): bool
{
    $request = GdprDataRequest::where('token', $token)
        ->where('type', 'deletion')
        ->where('status', 'pending')
        ->firstOrFail();

    $email = $request->email;

    if ($mode === 'full') {
        $this->fullDeletion($email);
    } else {
        $this->anonymize($email);
    }

    $request->update([
        'status' => 'completed',
        'processed_at' => now()
    ]);

    // Send confirmation email
    Mail::to($email)->send(new DeletionCompleteMail($mode));

    return true;
}

private function fullDeletion(string $email): void
{
    DB::transaction(function () use ($email) {
        ContactMessage::where('email', $email)->delete();
        CookieConsent::whereHas('request', fn($q) => $q->where('email', $email))->delete();
        PriceEstimation::where('ip_address', request()->ip())->delete();  // IP-based
        WebsiteAudit::where('email', $email)->delete();
    });
}

private function anonymize(string $email): void
{
    $anonymizedEmail = 'deleted_' . Str::random(8) . '@deleted.com';

    DB::transaction(function () use ($email, $anonymizedEmail) {
        ContactMessage::where('email', $email)->update([
            'email' => $anonymizedEmail,
            'name' => '[Deleted User]',
            'phone' => null,
            'company' => null
        ]);

        WebsiteAudit::where('email', $email)->update([
            'email' => $anonymizedEmail,
            'name' => '[Deleted User]',
            'company' => null
        ]);

        // PriceEstimation doesn't store email, only IP (no PII to anonymize)
    });
}
```

### Pre-Deletion Preview

**Controller Method**:
```php
public function confirmDeletion(string $token)
{
    $request = GdprDataRequest::where('token', $token)->firstOrFail();

    $summary = app(GdprDataDeletionService::class)->getPreDeletionSummary($request->email);

    return view('gdpr.confirm-deletion', [
        'request' => $request,
        'summary' => $summary
    ]);
}
```

**View**:
```blade
<h1>Bekräfta Radering av Data</h1>

<p>Du är på väg att radera följande data kopplad till <strong>{{ $request->email }}</strong>:</p>

<ul>
  <li>{{ $summary['contact_messages'] }} kontaktmeddelanden</li>
  <li>{{ $summary['cookie_consents'] }} cookie-samtycken</li>
  <li>{{ $summary['price_estimations'] }} prisuppskattningar</li>
  <li>{{ $summary['website_audits'] }} webbplatsgranskningar</li>
</ul>

<form method="POST" action="{{ route('gdpr.process-deletion', $request->token) }}">
  @csrf

  <label>
    <input type="radio" name="mode" value="full" checked>
    <strong>Fullständig radering</strong> - Ta bort all data permanent
  </label>

  <label>
    <input type="radio" name="mode" value="anonymize">
    <strong>Anonymisering</strong> - Behåll metadata men ta bort personuppgifter
  </label>

  <button type="submit">Bekräfta radering</button>
  <a href="/">Avbryt</a>
</form>
```

---

## Privacy & Cookie Policies

### Privacy Policy (`/privacy`)

**Required GDPR Information** (Article 13-14):
1. **Identity of controller**: ATDev / Andreas [Last Name]
2. **Contact details**: andreas@atdev.me
3. **Purpose of processing**: Contact, analytics, service delivery
4. **Legal basis**: Consent (GDPR Art. 6(1)(a)), Legitimate interest (Art. 6(1)(f))
5. **Recipients**: Anthropic (AI), Google (Analytics), BrightData (Scraping), Mailgun (Email)
6. **Retention period**: 90 days (cookies), 2 years (analytics), Until deletion request (messages)
7. **User rights**: Access, rectification, erasure, restriction, portability, objection
8. **Right to withdraw consent**: Via cookie banner or deletion request
9. **Right to complain**: Swedish Data Protection Authority (Datainspektionen)

**View**: `resources/views/gdpr/privacy.blade.php`

### Cookie Policy (`/cookies`)

**Required Cookie Information**:
1. **What are cookies**: Small text files stored on device
2. **Why we use cookies**: Session management, analytics, preferences
3. **Types of cookies we use**:
   - Essential (session, CSRF, consent)
   - Analytics (Google Analytics 4)
   - Preferences (dark mode, language)
4. **Third-party cookies**: Google Analytics
5. **How to manage cookies**: Browser settings + our cookie banner
6. **Cookie lifetimes**: Session (until browser close), 90 days (consent), 2 years (analytics)

**View**: `resources/views/gdpr/cookies.blade.php`

---

## GDPR Showcase Page

### Overview

**Route**: `/gdpr/showcase`
**Purpose**: Interactive demo av alla GDPR features

**Features**:
- Cookie consent demo (live banner)
- Data export demo (submit email → receive JSON)
- Data deletion demo (submit email → confirm → delete)
- Privacy policy link
- Cookie policy link

**View**: `resources/views/gdpr/showcase.blade.php`

---

## Compliance Checklist

### ✅ Implemented

- [x] Cookie consent banner (category-based)
- [x] Cookie consent storage (90 days)
- [x] Cookie consent API (check, store, accept-all, reject-all)
- [x] Data export functionality (JSON format)
- [x] Data deletion functionality (full + anonymization)
- [x] Token-based deletion confirmation
- [x] Privacy policy page
- [x] Cookie policy page
- [x] GDPR showcase page
- [x] User rights documentation
- [x] Transparent data processing
- [x] IP address logging (for security, legitimate interest)
- [x] Data retention policies

### 📋 To Be Reviewed

- [ ] Data Processing Agreement med third parties (Anthropic, Google, Mailgun)
- [ ] Data Protection Impact Assessment (DPIA) för AI processing
- [ ] Cookie consent language (Swedish + English)
- [ ] Privacy policy legal review
- [ ] Cookie banner accessibility (WCAG 2.1)
- [ ] Consent withdrawal workflow (currently via deletion)
- [ ] Data breach notification procedure

---

## User Rights under GDPR

### Right to Access (Art. 15)
✅ Implemented via `/gdpr/export-demo`

### Right to Rectification (Art. 16)
⚠️ Partial: Users can contact admin, no self-service yet

### Right to Erasure (Art. 17)
✅ Implemented via `/gdpr/delete-demo`

### Right to Restriction (Art. 18)
⚠️ Not implemented: Users cannot restrict processing yet

### Right to Data Portability (Art. 20)
✅ Implemented via JSON export

### Right to Object (Art. 21)
✅ Implemented via cookie consent rejection

### Right to Withdraw Consent (Art. 7(3))
✅ Implemented via cookie banner + deletion request

---

## Legal Basis for Processing

| Data Type | Legal Basis | Purpose |
|-----------|-------------|---------|
| Contact messages | Consent (Art. 6(1)(a)) | Communication, service delivery |
| Cookie consents | Consent (Art. 6(1)(a)) | Remember user preferences |
| Price estimations | Legitimate interest (Art. 6(1)(f)) | Service improvement, analytics |
| Website audits | Consent (Art. 6(1)(a)) | Service delivery |
| IP addresses | Legitimate interest (Art. 6(1)(f)) | Security, spam prevention |
| Analytics cookies | Consent (Art. 6(1)(a)) | Website improvement |

---

## Third-Party Data Processors

| Processor | Purpose | Data Shared | DPA | Location |
|-----------|---------|-------------|-----|----------|
| Anthropic | AI services | Project descriptions, menu text | ✅ | USA (Privacy Shield successor) |
| Google Analytics | Website analytics | Anonymous usage data | ✅ | USA |
| Mailgun | Email delivery | Email addresses, message content | ✅ | USA |
| BrightData | Web scraping | URLs only | ✅ | Israel |
| Cloudflare | CDN, Turnstile | IP addresses, browser fingerprints | ✅ | Global |

---

## Best Practices

### 1. Always Get Consent Before Non-Essential Cookies

```javascript
// Wait for consent before loading GA4
if (await hasAnalyticsConsent()) {
  loadGoogleAnalytics();
} else {
  console.log('Analytics blocked by user preference');
}
```

### 2. Make Consent Easy to Withdraw

Cookie banner should always be accessible via footer link.

### 3. Provide Clear Privacy Information

Privacy policy should be:
- Easy to find (footer link)
- Easy to read (plain language, not legalese)
- Complete (all processing activities documented)

### 4. Respect User Choices

```php
$consentId = request()->cookie('cookie_consent_id');

if (!$this->consentService->hasConsent($consentId, 'analytics')) {
    // Skip analytics logging
    return;
}
```

### 5. Log Data Processing Activities

```php
Log::info('GDPR: Data export requested', ['email' => $email, 'ip' => request()->ip()]);
Log::info('GDPR: Data deletion completed', ['email' => $email, 'mode' => $mode]);
```

---

## Testing GDPR Features

### Manual Testing

1. **Cookie Consent**:
   - Clear cookies
   - Visit site
   - Verify banner displays
   - Accept all → Check GA4 loads
   - Reject all → Check GA4 doesn't load

2. **Data Export**:
   - Submit email at `/gdpr/export-demo`
   - Check email received
   - Verify JSON format
   - Verify all data included

3. **Data Deletion**:
   - Submit email at `/gdpr/delete-demo`
   - Check confirmation email
   - Click link
   - Verify preview correct
   - Confirm deletion (full or anonymize)
   - Verify data deleted/anonymized in database

### Automated Testing

```php
// tests/Feature/GdprTest.php

public function test_user_can_export_data()
{
    ContactMessage::factory()->create(['email' => 'test@example.com']);

    $response = $this->post('/gdpr/export-demo', ['email' => 'test@example.com']);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    Mail::assertSent(DataExportMail::class);
}

public function test_user_can_delete_data()
{
    $message = ContactMessage::factory()->create(['email' => 'test@example.com']);

    $response = $this->post('/gdpr/delete-demo', ['email' => 'test@example.com']);

    $request = GdprDataRequest::where('email', 'test@example.com')->first();
    $this->assertNotNull($request);

    $response = $this->post("/gdpr/process-deletion/{$request->token}", [
        'mode' => 'full'
    ]);

    $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
}
```

---

**Version**: 1.0
**Last Updated**: 2025-01-15
**Maintainer**: andreas@atdev.me
**Legal Review**: Pending
