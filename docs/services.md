# ATDev - Tjänstelager Dokumentation

## Översikt

ATDev's business logic är organiserad i **11 service classes** som hanterar komplex funktionalitet utanför controllers och models. Services följer Single Responsibility Principle och är fully testable.

**Location**: `app/Services/`

## Tjänstekategorier

1. **AI Services** - AIService
2. **Data Collection** - WebsiteDataCollector
3. **Web Scraping** - BrightDataScraper
4. **Data Mapping** - PriceEstimateMapper
5. **External APIs** - GooglePlacesService
6. **GDPR Services** - GdprDataExportService, GdprDataDeletionService, CookieConsentService
7. **SEO Services** - StructuredDataService
8. **Security Services** - TurnstileService (package-based)

---

## AIService

**Location**: `app/Services/AIService.php`
**Purpose**: Centraliserad Anthropic Claude API-integration för alla AI-features

### Configuration

```php
// config/services.php
'anthropic' => [
    'api_key' => env('ANTHROPIC_API_KEY'),
    'url' => env('ANTHROPIC_URL', 'https://api.anthropic.com/v1'),
],
```

### Methods

#### callAnthropicApi()

**Purpose**: Core AI API call method

**Signature**:
```php
public function callAnthropicApi(
    array $messages,
    string $system,
    int $maxTokens = 500,
    float $temperature = 0.7,
    string $model = 'claude-sonnet-4-5-20250929'
): array
```

**Parameters**:
- `$messages` - Array of message objects: `[['role' => 'user', 'content' => 'question'], ...]`
- `$system` - System prompt defining AI's role och context
- `$maxTokens` - Max response length (default: 500, max: 8192)
- `$temperature` - Creativity level 0.0-1.0 (default: 0.7)
- `$model` - Claude model version (default: claude-sonnet-4-5-20250929)

**Returns**: Array med AI response

**Throws**: `\Exception` on API error

**Example**:
```php
use App\Services\AIService;

$aiService = new AIService();

$messages = [
    ['role' => 'user', 'content' => 'Vad är Laravel?']
];

$system = 'Du är en hjälpsam programmeringskonsult.';

try {
    $response = $aiService->callAnthropicApi($messages, $system, 300, 0.7);
    $answer = $response['content'][0]['text'];
    echo $answer;
} catch (\Exception $e) {
    Log::error('AI API error: ' . $e->getMessage());
}
```

---

#### getChatHistory()

**Purpose**: Retrieve chat history för conversation context

**Signature**:
```php
public function getChatHistory(string $sessionId, int $limit = 10): Collection
```

**Parameters**:
- `$sessionId` - Unique session identifier
- `$limit` - Number of messages to retrieve (default: 10)

**Returns**: Collection of Chat models (question + answer pairs)

**Example**:
```php
$history = $aiService->getChatHistory('session-abc123', 5);

foreach ($history as $chat) {
    echo "Q: {$chat->question}\n";
    echo "A: {$chat->answer}\n";
}
```

---

#### createPortfolioPrompt()

**Purpose**: Build system prompt med portfolio context (projects + FAQs)

**Signature**:
```php
public function createPortfolioPrompt(): string
```

**Returns**: String system prompt

**Context Includes**:
- All published projects (title, summary, technologies)
- FAQs with `show_in_ai_chat = true`
- General portfolio information

**Example**:
```php
$systemPrompt = $aiService->createPortfolioPrompt();

// Returns:
// "Du är en AI-assistent för ATDev's portfolio...
// Projekt: E-handelsplattform (Laravel, Vue.js)...
// FAQ: Hur lång tid tar ett projekt? Svar: ..."
```

---

#### estimateProjectPrice()

**Purpose**: AI-driven project price estimation

**Signature**:
```php
public function estimateProjectPrice(
    string $description,
    array $scrapedData = []
): array
```

**Parameters**:
- `$description` - User's project description
- `$scrapedData` - Optional scraped website data (content, technologies)

**Returns**: Array with AI analysis:
```php
[
    'project_type' => 'E-commerce Platform',
    'complexity' => 'medium',  // simple, medium, complex, very_complex
    'key_features' => ['...'],
    'solution_approach' => '...'
]
```

**Example**:
```php
$description = "Jag behöver en e-handelsplattform...";
$scrapedData = ['technologies' => ['WordPress'], 'content' => '...'];

$analysis = $aiService->estimateProjectPrice($description, $scrapedData);

echo "Project type: {$analysis['project_type']}\n";
echo "Complexity: {$analysis['complexity']}\n";
```

---

#### analyzeWebsite()

**Purpose**: AI-based website audit analysis

**Signature**:
```php
public function analyzeWebsite(
    array $groundTruthData,
    string $screenshotPath = null
): array
```

**Parameters**:
- `$groundTruthData` - Structured website data från WebsiteDataCollector
- `$screenshotPath` - Optional screenshot path för visual analysis

**Returns**: Array with audit results:
```php
[
    'seo_score' => 75,  // 0-100
    'technical_score' => 82,  // 0-100
    'overall_score' => 78,  // Average
    'recommendations' => [...]
]
```

**Ground Truth Data Structure**:
```php
[
    'meta' => ['title' => '...', 'description' => '...'],
    'headings' => ['h1' => [...], 'h2' => [...]],
    'images' => ['total' => 10, 'with_alt' => 7],
    'links' => ['internal' => 15, 'external' => 5],
    'technical' => ['html_size' => 45000, 'load_time' => 1.2]
]
```

**Example**:
```php
$collector = new WebsiteDataCollector();
$groundTruth = $collector->collect('https://example.com');

$report = $aiService->analyzeWebsite($groundTruth, '/path/to/screenshot.png');

echo "SEO Score: {$report['seo_score']}/100\n";
foreach ($report['recommendations'] as $rec) {
    echo "- {$rec}\n";
}
```

---

#### analyzeMenuAllergens()

**Purpose**: Detect allergens i Swedish menu text

**Signature**:
```php
public function analyzeMenuAllergens(string $menuText): array
```

**Parameters**:
- `$menuText` - Menu item description in Swedish

**Returns**: Array with allergen analysis:
```php
[
    'allergens' => [
        ['name' => 'Gluten', 'severity' => 'critical', 'found_in' => 'pasta'],
        ...
    ],
    'dietary_preferences' => ['vegan' => false, 'vegetarian' => true],
    'warnings' => [...]
]
```

**Example**:
```php
$menuText = "Pasta carbonara med bacon, ägg och grädde";

$analysis = $aiService->analyzeMenuAllergens($menuText);

foreach ($analysis['allergens'] as $allergen) {
    echo "{$allergen['name']} ({$allergen['severity']})\n";
}
```

---

### Error Handling

All AIService methods throw `\Exception` on API errors:

```php
try {
    $response = $aiService->callAnthropicApi($messages, $system);
} catch (\Exception $e) {
    // Log error
    Log::error('AI API Error', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);

    // Return fallback response
    return ['error' => 'AI service temporarily unavailable'];
}
```

### Testing

Mock AIService i tests:

```php
use App\Services\AIService;
use Mockery;

public function test_ai_estimation()
{
    $mock = Mockery::mock(AIService::class);

    $mock->shouldReceive('estimateProjectPrice')
        ->once()
        ->with('Project description', [])
        ->andReturn([
            'project_type' => 'E-commerce',
            'complexity' => 'medium'
        ]);

    $this->app->instance(AIService::class, $mock);

    // Test logic...
}
```

---

## WebsiteDataCollector

**Location**: `app/Services/WebsiteDataCollector.php`
**Purpose**: Collect comprehensive website data för AI audits

### Methods

#### collect()

**Purpose**: Fetch and analyze website data

**Signature**:
```php
public function collect(string $url): array
```

**Workflow**:
1. Fetch HTML via HTTP request (30s timeout)
2. Take screenshot med Browsershot (60s timeout)
3. Parse DOM med Symfony DomCrawler
4. Extract ground truth data
5. Calculate preliminary scores

**Returns**: Array with collected data:
```php
[
    'url' => 'https://example.com',
    'html' => '<!DOCTYPE html>...',
    'screenshot_path' => '/path/to/screenshot.png',
    'ground_truth' => [
        'meta' => [...],
        'headings' => [...],
        'images' => [...],
        'links' => [...],
        'technical' => [...]
    ]
]
```

**Example**:
```php
use App\Services\WebsiteDataCollector;

$collector = new WebsiteDataCollector();

try {
    $data = $collector->collect('https://example.com');

    echo "Page title: {$data['ground_truth']['meta']['title']}\n";
    echo "H1 count: " . count($data['ground_truth']['headings']['h1']) . "\n";
    echo "Images: {$data['ground_truth']['images']['total']}\n";
    echo "Images with alt: {$data['ground_truth']['images']['with_alt']}\n";
} catch (\Exception $e) {
    Log::error("Data collection failed: {$e->getMessage()}");
}
```

### Ground Truth Data Structure

```php
[
    'meta' => [
        'title' => 'Page Title',
        'description' => 'Meta description',
        'keywords' => 'keyword1, keyword2',
        'og_title' => 'OG Title',
        'og_description' => 'OG Description',
        'og_image' => 'https://...',
        'viewport' => 'width=device-width...'
    ],
    'headings' => [
        'h1' => ['Main Heading'],
        'h2' => ['Subheading 1', 'Subheading 2'],
        'h3' => [...],
        'h4' => [...],
        'h5' => [...],
        'h6' => [...]
    ],
    'images' => [
        'total' => 15,
        'with_alt' => 12,
        'without_alt' => 3,
        'lazy_loaded' => 10,
        'alt_texts' => ['Alt text 1', ...]
    ],
    'links' => [
        'total' => 50,
        'internal' => 35,
        'external' => 15,
        'broken' => 0  // (not implemented yet)
    ],
    'technical' => [
        'html_size' => 45678,  // bytes
        'load_time' => 1.234,  // seconds
        'has_viewport_meta' => true,
        'has_favicon' => true,
        'html_excerpts' => ['<html>...', ...]
    ]
]
```

### Error Handling

```php
try {
    $data = $collector->collect($url);
} catch (\Exception $e) {
    // Network error, invalid HTML, screenshot failure, etc.
    return [
        'error' => true,
        'message' => $e->getMessage(),
        'url' => $url
    ];
}
```

---

## BrightDataScraper

**Location**: `app/Services/BrightDataScraper.php`
**Purpose**: Web scraping med authenticated proxy för price calculator

### Configuration

```php
// config/services.php
'brightdata' => [
    'api_key' => env('BRIGHTDATA_API_KEY'),
    'proxy_host' => env('BRIGHTDATA_PROXY_HOST', 'proxy.brightdata.com'),
    'proxy_port' => env('BRIGHTDATA_PROXY_PORT', 22225),
],
```

### Methods

#### scrape()

**Purpose**: Scrape website via BrightData proxy

**Signature**:
```php
public function scrape(string $url): array
```

**Returns**:
```php
[
    'success' => true,
    'content' => 'HTML content...',
    'metadata' => [
        'technologies' => ['WordPress', 'PHP', 'MySQL'],
        'title' => 'Page Title',
        'description' => 'Meta description',
        'server' => 'nginx'
    ]
]
```

**Error Response**:
```php
[
    'success' => false,
    'error' => 'Connection timeout',
    'content' => null,
    'metadata' => []
]
```

**Example**:
```php
use App\Services\BrightDataScraper;

$scraper = new BrightDataScraper();

$result = $scraper->scrape('https://example.com');

if ($result['success']) {
    echo "Technologies: " . implode(', ', $result['metadata']['technologies']);
} else {
    echo "Scraping failed: {$result['error']}";
}
```

### Proxy Configuration

BrightData proxy använder authenticated HTTP proxy:

```php
// Internal proxy setup (handled by service)
$proxy = config('services.brightdata.proxy_host') . ':' . config('services.brightdata.proxy_port');
$auth = 'username:' . config('services.brightdata.api_key');

// Guzzle client with proxy
$client = new \GuzzleHttp\Client([
    'proxy' => "http://{$auth}@{$proxy}",
    'timeout' => 30,
    'verify' => false
]);
```

---

## PriceEstimateMapper

**Location**: `app/Services/PriceEstimateMapper.php`
**Purpose**: Map AI complexity analysis till concrete hour/price ranges

### Methods

#### map()

**Purpose**: Convert AI analysis to pricing estimates

**Signature**:
```php
public function map(array $aiAnalysis): array
```

**Input** (från AIService):
```php
[
    'project_type' => 'E-commerce Platform',
    'complexity' => 'medium',  // simple, medium, complex, very_complex
    'key_features' => ['Product catalog', 'Payment integration', ...]
]
```

**Output**:
```php
[
    'traditional' => [
        'hours' => 120,
        'price' => 120000,  // SEK ex. VAT
        'price_vat' => 150000,  // SEK inkl. 25% VAT
        'delivery_weeks' => 12
    ],
    'ai_driven' => [
        'hours' => 80,
        'price' => 80000,
        'price_vat' => 100000,
        'delivery_weeks' => 8
    ],
    'savings' => [
        'hours' => 40,
        'price' => 40000,
        'percentage' => 33.3
    ]
]
```

**Complexity Mapping**:

| Complexity | Traditional Hours | AI-Driven Hours | Hourly Rate |
|-----------|-------------------|-----------------|-------------|
| simple | 40-80h | 25-50h | 1000 SEK |
| medium | 80-160h | 50-100h | 1000 SEK |
| complex | 160-320h | 100-200h | 1000 SEK |
| very_complex | 320-640h | 200-400h | 1000 SEK |

**Example**:
```php
use App\Services\PriceEstimateMapper;

$mapper = new PriceEstimateMapper();

$aiAnalysis = [
    'project_type' => 'E-commerce',
    'complexity' => 'medium',
    'key_features' => [...]
];

$pricing = $mapper->map($aiAnalysis);

echo "Traditional: {$pricing['traditional']['hours']}h = {$pricing['traditional']['price_vat']} SEK\n";
echo "AI-driven: {$pricing['ai_driven']['hours']}h = {$pricing['ai_driven']['price_vat']} SEK\n";
echo "Savings: {$pricing['savings']['percentage']}%\n";
```

### Pricing Logic

**Traditional Estimate**:
- Conservative approach
- Manual processes
- Standard timelines
- Higher contingency buffer

**AI-Driven Estimate**:
- Optimistic with AI/automation
- 30-50% faster than traditional
- Modern tooling & frameworks
- Lower contingency buffer

**VAT Calculation**:
```php
$priceVat = $price * 1.25;  // 25% Swedish VAT
```

---

## GooglePlacesService

**Location**: `app/Services/GooglePlacesService.php`
**Purpose**: Google Places API integration för reviews demo

### Configuration

```php
// config/services.php
'google' => [
    'places_api_key' => env('GOOGLE_PLACES_API_KEY'),
    'default_place_id' => env('GOOGLE_PLACES_DEFAULT_PLACE_ID'),
    'ga4_measurement_id' => env('GOOGLE_GA4_MEASUREMENT_ID'),
],
```

### Methods

#### searchPlaces()

**Purpose**: Search för places by text query

**Signature**:
```php
public function searchPlaces(string $query): array
```

**Example**:
```php
use App\Services\GooglePlacesService;

$service = new GooglePlacesService();

$results = $service->searchPlaces('restaurants in Stockholm');

foreach ($results['places'] as $place) {
    echo "{$place['name']} - Rating: {$place['rating']}\n";
}
```

**Returns**:
```php
[
    'places' => [
        [
            'place_id' => 'ChIJ...',
            'name' => 'Restaurant Name',
            'formatted_address' => 'Address...',
            'rating' => 4.5,
            'user_ratings_total' => 123
        ],
        ...
    ]
]
```

---

#### getPlaceReviews()

**Purpose**: Get reviews för specific place

**Signature**:
```php
public function getPlaceReviews(string $placeId): array
```

**Example**:
```php
$data = $service->getPlaceReviews('ChIJN1t_tDeuEmsRUsoyG83frY4');

echo "Place: {$data['place']['name']}\n";
echo "Rating: {$data['place']['rating']}/5\n";
echo "Total reviews: {$data['place']['user_ratings_total']}\n\n";

foreach ($data['reviews'] as $review) {
    echo "{$review['author_name']}: {$review['rating']}/5\n";
    echo "{$review['text']}\n\n";
}
```

**Returns**:
```php
[
    'place' => [
        'place_id' => '...',
        'name' => 'Place Name',
        'formatted_address' => '...',
        'rating' => 4.5,
        'user_ratings_total' => 2847,
        'website' => 'https://...',
        'phone_number' => '+46...'
    ],
    'reviews' => [
        [
            'author_name' => 'John Doe',
            'rating' => 5,
            'text' => 'Great place!',
            'time' => 1642102800,
            'relative_time_description' => '2 months ago'
        ],
        ...
    ]
]
```

---

## GDPR Services

### GdprDataExportService

**Location**: `app/Services/GdprDataExportService.php`
**Purpose**: Generate GDPR-compliant data exports

#### exportUserData()

**Signature**:
```php
public function exportUserData(string $email): array
```

**Returns**:
```php
[
    'exported_at' => '2025-01-15 12:00:00',
    'email' => 'user@example.com',
    'data' => [
        'contact_messages' => [...],
        'cookie_consents' => [...],
        'price_estimations' => [...],
        'website_audits' => [...]
    ]
]
```

**Example**:
```php
use App\Services\GdprDataExportService;

$service = new GdprDataExportService();

$export = $service->exportUserData('user@example.com');

// Save as JSON file
file_put_contents(
    "export_{$export['email']}.json",
    json_encode($export, JSON_PRETTY_PRINT)
);

// Or send via email
Mail::to($export['email'])->send(new DataExportMail($export));
```

#### getDataSummary()

**Signature**:
```php
public function getDataSummary(string $email): array
```

**Returns**:
```php
[
    'contact_messages' => 5,
    'cookie_consents' => 2,
    'price_estimations' => 1,
    'website_audits' => 3
]
```

---

### GdprDataDeletionService

**Location**: `app/Services/GdprDataDeletionService.php`
**Purpose**: Handle GDPR deletion requests

#### createDeletionRequest()

**Signature**:
```php
public function createDeletionRequest(string $email, string $ipAddress): GdprDataRequest
```

**Returns**: GdprDataRequest model med unique token

**Example**:
```php
use App\Services\GdprDataDeletionService;

$service = new GdprDataDeletionService();

$request = $service->createDeletionRequest('user@example.com', '192.168.1.1');

// Send confirmation email with link
$confirmLink = route('gdpr.confirm-deletion', ['token' => $request->token]);
Mail::to($request->email)->send(new ConfirmDeletionMail($confirmLink));
```

---

#### getPreDeletionSummary()

**Signature**:
```php
public function getPreDeletionSummary(string $email): array
```

**Returns**: Preview av data som kommer raderas

---

#### processDeletionRequest()

**Signature**:
```php
public function processDeletionRequest(string $token, string $mode = 'full'): bool
```

**Parameters**:
- `$token` - Unique confirmation token
- `$mode` - 'full' (delete all) or 'anonymize' (replace email)

**Example**:
```php
// Full deletion
$success = $service->processDeletionRequest('abc123token', 'full');

// Anonymization
$success = $service->processDeletionRequest('abc123token', 'anonymize');

if ($success) {
    // Send confirmation email
    Mail::to($originalEmail)->send(new DeletionCompleteMail());
}
```

**Deletion Scope**:
- ContactMessage (original + replies)
- CookieConsent
- PriceEstimation
- WebsiteAudit
- GdprDataRequest (self)

**Anonymization**:
```php
// Before
email: user@example.com
name: John Doe

// After
email: deleted_abc123@deleted.com
name: [Deleted User]
```

---

### CookieConsentService

**Location**: `app/Services/CookieConsentService.php`
**Purpose**: GDPR cookie consent management

#### storeConsent()

**Signature**:
```php
public function storeConsent(
    array $categories,
    string $ipAddress,
    string $userAgent
): CookieConsent
```

**Example**:
```php
use App\Services\CookieConsentService;

$service = new CookieConsentService();

$consent = $service->storeConsent(
    ['analytics', 'preferences'],
    request()->ip(),
    request()->userAgent()
);

// Set cookie
cookie()->queue('cookie_consent_id', $consent->consent_id, 60 * 24 * 90);  // 90 days
```

---

#### getConsent()

**Signature**:
```php
public function getConsent(string $consentId): ?CookieConsent
```

---

#### hasConsent()

**Signature**:
```php
public function hasConsent(string $consentId, string $category): bool
```

**Example**:
```php
$consentId = request()->cookie('cookie_consent_id');

if ($service->hasConsent($consentId, 'analytics')) {
    // Load Google Analytics
    echo "<script>/* GA4 code */</script>";
}
```

---

#### acceptAll()

**Signature**:
```php
public function acceptAll(string $ipAddress, string $userAgent): CookieConsent
```

**Returns**: CookieConsent med all categories: `['analytics', 'marketing', 'preferences']`

---

#### rejectAll()

**Signature**:
```php
public function rejectAll(string $ipAddress, string $userAgent): CookieConsent
```

**Returns**: CookieConsent med empty categories (only essential cookies)

---

## StructuredDataService

**Location**: `app/Services/StructuredDataService.php`
**Purpose**: Generate JSON-LD structured data för SEO

### Methods

#### generateOrganizationSchema()

**Purpose**: Schema.org Organization markup

**Returns**:
```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "ATDev",
  "url": "https://atdev.me",
  "logo": "https://atdev.me/logo.png",
  "description": "...",
  "contactPoint": {
    "@type": "ContactPoint",
    "email": "andreas@atdev.me",
    "contactType": "customer service"
  },
  "sameAs": [
    "https://github.com/atdev",
    "https://linkedin.com/in/..."
  ]
}
```

---

#### generatePersonSchema()

**Purpose**: Schema.org Person markup

---

#### generateWebSiteSchema()

**Purpose**: Schema.org WebSite markup med SearchAction

---

#### generateBreadcrumbSchema()

**Purpose**: Dynamic breadcrumb navigation

**Example**:
```php
use App/Services/StructuredDataService;

$service = new StructuredDataService();

$breadcrumbs = $service->generateBreadcrumbSchema([
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Projects', 'url' => '/projects'],
    ['name' => 'Project Name', 'url' => '/projects/slug']
]);

// Inject in <head>
echo '<script type="application/ld+json">' . json_encode($breadcrumbs) . '</script>';
```

---

## Service Registration

All services registered i `AppServiceProvider`:

```php
// app/Providers/AppServiceProvider.php

public function register(): void
{
    $this->app->singleton(AIService::class);
    $this->app->singleton(WebsiteDataCollector::class);
    $this->app->singleton(BrightDataScraper::class);
    $this->app->singleton(PriceEstimateMapper::class);
    $this->app->singleton(GooglePlacesService::class);
    $this->app->singleton(GdprDataExportService::class);
    $this->app->singleton(GdprDataDeletionService::class);
    $this->app->singleton(CookieConsentService::class);
    $this->app->singleton(StructuredDataService::class);
}
```

**Dependency Injection**:
```php
// Controller
public function __construct(private AIService $aiService) {}

// Method injection
public function analyze(WebsiteDataCollector $collector) {
    $data = $collector->collect($url);
}

// Manual resolution
$service = app(AIService::class);
```

---

## Testing Services

### Unit Testing

```php
use App\Services\AIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AIServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_portfolio_prompt()
    {
        // Create test data
        Project::factory()->create(['status' => 'published']);
        Faq::factory()->create(['show_in_ai_chat' => true]);

        $service = new AIService();
        $prompt = $service->createPortfolioPrompt();

        $this->assertStringContainsString('portfolio', $prompt);
        $this->assertStringContainsString('projekt', $prompt);
    }

    public function test_handles_api_errors_gracefully()
    {
        $service = new AIService();

        $this->expectException(\Exception::class);

        // Invalid API key akan throw exception
        config(['services.anthropic.api_key' => 'invalid']);
        $service->callAnthropicApi([], '');
    }
}
```

### Mocking Services

```php
use Mockery;

public function test_controller_uses_ai_service()
{
    $mock = Mockery::mock(AIService::class);

    $mock->shouldReceive('estimateProjectPrice')
        ->once()
        ->andReturn([
            'project_type' => 'E-commerce',
            'complexity' => 'medium'
        ]);

    $this->app->instance(AIService::class, $mock);

    $response = $this->post('/api/price-estimate', [...]);

    $response->assertStatus(200);
}
```

---

## Best Practices

### 1. Always Use Dependency Injection

```php
// Good
public function __construct(private AIService $aiService) {}

// Bad
public function index()
{
    $service = new AIService();  // Don't do this
}
```

### 2. Handle Errors Gracefully

```php
try {
    $result = $this->aiService->callAnthropicApi($messages, $system);
} catch (\Exception $e) {
    Log::error('AI Error', ['message' => $e->getMessage()]);
    return response()->json(['error' => 'Service temporarily unavailable'], 503);
}
```

### 3. Use Type Hints

```php
public function collect(string $url): array
{
    // Type safety
}
```

### 4. Keep Services Focused

Each service should have ONE clear responsibility (SRP).

### 5. Log Important Operations

```php
Log::info('Website audit started', ['url' => $url]);

$data = $collector->collect($url);

Log::info('Website audit completed', [
    'url' => $url,
    'seo_score' => $data['seo_score']
]);
```

---

## Performance Considerations

### Caching

Consider caching expensive operations:

```php
public function getTechStack(): array
{
    return Cache::remember('tech_stack', 3600, function () {
        // Expensive operation
        return $this->buildTechStack();
    });
}
```

### Async Processing

Use queues för time-consuming operations:

```php
// Instead of:
$audit = $aiService->analyzeWebsite($data);

// Use:
ProcessWebsiteAudit::dispatch($websiteAudit);
```

### Timeouts

Set appropriate timeouts:

```php
$client = new \GuzzleHttp\Client([
    'timeout' => 30,  // 30 seconds max
    'connect_timeout' => 5  // 5 seconds to connect
]);
```

---

**Version**: 1.0
**Last Updated**: 2025-01-15
**Maintainer**: andreas@atdev.me
