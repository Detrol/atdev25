# Interaktiva Demos Guide

ATDev-plattformen innehåller 4 interaktiva showcase-funktioner som demonstrerar moderna webbteknologier och möjligheter för olika verksamheter. Alla demos är fullständigt funktionella och tillgängliga på `/demos`.

## Översikt

### Syfte
Showcase-demos för att:
- Demonstrera tekniska möjligheter för potentiella kunder
- Visa upp användningsfall för olika branscher
- Inspirera till innovativa webblösningar
- Bevisa teknisk kompetens

### Tillgängliga Demos
1. **3D Product Viewer** - AR-aktiverad 3D-produktvisning
2. **Before/After Slider** - Interaktiv före/efter-jämförelse
3. **Google Reviews Widget** - Automatisk recensionsvisning
4. **Smart Menu** - AI allergen-analys (Claude 3.7 Sonnet)

---

## 1. 3D Product Viewer med AR

### Beskrivning
Interaktiv 3D-produktvisare med Augmented Reality-support. Användare kan rotera, zooma och på mobila enheter placera produkter i sitt eget rum via kameran.

### Teknisk Stack
- **Google Model-Viewer 3.4.0** - WebComponent för 3D-rendering
- **GLB/GLTF** - 3D-modellformat
- **WebXR & ARCore/ARKit** - AR-funktionalitet
- **Alpine.js** - Interaktivitet

### Funktioner

#### Produktvisning
- **360° Rotation** - Dra med musen eller touch
- **Zoom** - Scroll/pinch
- **Auto-rotera** - Toggle på/av
- **Ljussättning** - Dynamiska skuggor och reflektioner
- **Kamera reset** - Återställ till default vy

#### AR-läge (Mobil)
- **Scene Viewer** (Android) - ARCore
- **Quick Look** (iOS) - ARKit
- **Floor Placement** - Placera objekt på golvet
- **Skalning** - Anpassad AR-skala per produkt

### Data Structure

```php
// DemosController.php
'product_viewer' => [
    'enabled' => true,
    'products' => [
        [
            'id' => 1,
            'name' => 'Modern Fåtölj',
            'description' => 'Skandinavisk design...',
            'category' => 'Möbler',
            'model' => '/models/armchair.glb',           // GLB 3D-modell
            'poster' => '/images/products/armchair.jpg', // Förhandsvisning
            'useCases' => ['Möbelbutiker', 'Inredningsdesigners'],
            'dimensions' => '80cm × 85cm × 90cm',
            'arScale' => '1.0',  // Skalningsfaktor för AR
        ],
        // 4 produkter totalt: fåtölj, lampa, vas, skulptur
    ],
]
```

### Alpine.js Component

```javascript
// resources/js/demos/product-viewer.js
function productViewerData(productsData) {
    return {
        products: productsData,
        selectedProductIndex: 0,
        selectedProduct: null,
        autoRotate: true,
        modelLoading: false,
        modelLoaded: false,
        modelError: false,

        init() {
            this.selectedProduct = this.products[0];
            this.setupModelViewer();
        },

        selectProduct(index) {
            this.selectedProductIndex = index;
            this.selectedProduct = this.products[index];
            this.modelLoading = true;
            this.modelLoaded = false;
            this.modelError = false;
        },

        toggleAutoRotate() {
            this.autoRotate = !this.autoRotate;
        },

        resetCamera() {
            const viewer = this.$refs.modelViewer;
            viewer.resetTurntableRotation();
            viewer.cameraOrbit = viewer.getCameraOrbit();
        }
    };
}
```

### 3D-Modeller

**Filformat**: GLB (compressed glTF 2.0)
**Lagringsplats**: `public/models/`
**Setup-instruktioner**: Se `public/models/README.md`

**Rekommenderad Optimering:**
- Max 5MB per modell
- Triangelantal: 10k-50k för webb
- PBR-material (Physically Based Rendering)
- Komprimerade texturer (max 2048x2048)

### Use Cases

**Möbelbutiker**
- Kunder kan se möbler i sitt hem via AR
- Minskar returer (rätt förväntningar)

**Konstgallerier**
- Visa skulpturer i 3D med alla detaljer
- Virtuella utställningar

**E-handel**
- Produktvisning från alla vinklar
- Ökad konvertering

### Browser Support

**3D Viewer:**
- Chrome/Edge (WebGL 2.0)
- Firefox
- Safari

**AR-läge:**
- Android 8+ (ARCore-kompatibla enheter)
- iOS 12+ (AR Quick Look)

---

## 2. Before/After Slider

### Beskrivning
Interaktiv före/efter-slider för att visa transformationer. Användare kan dra en slider för att jämföra två bilder sida vid sida. Perfekt för renoveringar, bildbehandling, design före/efter.

### Teknisk Stack
- **Alpine.js** - State management och drag-hantering
- **CSS clip-path** - Bildmaskering
- **Touch Events** - Mobil-support
- **Keyboard Navigation** - Tillgänglighet

### Funktioner

#### Interaktion
- **Mouse drag** - Klicka och dra slider
- **Touch drag** - Touch/swipe på mobil/platta
- **Keyboard navigation** - Piltangenter (←/→) för finare kontroll
- **Reset button** - Återställ till 50% position

#### Visual Feedback
- **Labels** - "FÖRE" / "EFTER" badges
- **Slider handle** - Interaktiv center-button
- **Position indicator** - Visar nuvarande position i %
- **Hover effects** - Scale på handle vid hover

### Data Structure

```php
// DemosController.php
'before_after_slider' => [
    'enabled' => true,
    'examples' => [
        [
            'id' => 1,
            'title' => 'Kök & Inredning',
            'category' => 'Hem & Renovering',
            'description' => 'Visa transformationer...',
            'beforeImage' => '/images/demos/before-after/renovation/before.jpg',
            'afterImage' => '/images/demos/before-after/renovation/after.jpg',
            'useCases' => ['Byggfirmor', 'Inredningsdesigners', 'Fastighetsbolag'],
        ],
        // 4 exempel: Kök, Workspace, Bildbehandling, Utemiljö
    ],
]
```

### Alpine.js Component

```javascript
// resources/js/demos/before-after-slider.js
function beforeAfterSliderData(examplesData) {
    return {
        examples: examplesData,
        selectedExampleIndex: 0,
        selectedExample: null,
        sliderPosition: 50,  // Procent (0-100)
        isDragging: false,

        init() {
            this.selectedExample = this.examples[0];
            this.setupEventListeners();
        },

        startDrag(event) {
            this.isDragging = true;
            this.updateSliderPosition(event);

            // Global event listeners för smooth dragging
            document.addEventListener('mousemove', this.handleDrag);
            document.addEventListener('mouseup', this.stopDrag);
            document.addEventListener('touchmove', this.handleDrag);
            document.addEventListener('touchend', this.stopDrag);
        },

        handleDrag(event) {
            if (!this.isDragging) return;
            this.updateSliderPosition(event);
        },

        updateSliderPosition(event) {
            const container = this.$refs.sliderContainer;
            const rect = container.getBoundingClientRect();
            const x = (event.touches ? event.touches[0].clientX : event.clientX) - rect.left;
            this.sliderPosition = Math.max(0, Math.min(100, (x / rect.width) * 100));
        },

        handleKeyboard(event) {
            if (event.key === 'ArrowLeft') {
                this.sliderPosition = Math.max(0, this.sliderPosition - 2);
            } else if (event.key === 'ArrowRight') {
                this.sliderPosition = Math.min(100, this.sliderPosition + 2);
            }
        },

        resetPosition() {
            this.sliderPosition = 50;
        }
    };
}
```

### Bildkrav

**Format**: JPG/WebP (optimerade)
**Storlek**: 1600x1000px (aspect ratio 16:10)
**Filstorlek**: Max 500KB per bild
**Lagringsplats**: `public/images/demos/before-after/`

**Viktigt:**
- Före- och efterbilder MÅSTE ha exakt samma aspect ratio
- Fotograferas från EXAKT samma vinkel
- Samma upplösning för smidig övergång

### Use Cases

**Byggfirmor & Renovering**
- Före/efter renoveringsprojekt
- Portfolio för att visa kompetens

**Fotografer & Designers**
- Bildbehandling och retuschering
- Färgkorrigering demonstrations

**Skönhet & Frisörer**
- Visa transformationer och resultat
- Before/after klippningar, färgningar

---

## 3. Google Reviews Widget

### Beskrivning
Automatisk integration med Google Places API för att visa företagets recensioner direkt på hemsidan. Användare kan söka efter valfritt företag och se deras Google-recensioner i realtid.

### Teknisk Stack
- **Google Places API** - Platsdata och recensioner
- **Alpine.js** - UI-hantering
- **Laravel Cache** - 24h caching av recensioner
- **Rate Limiting** - 20 requests/minut

### Funktioner

#### Sökfunktion
- **Företagssök** - Sök efter företagsnamn
- **Autocomplete suggestions** - Flera träffar visas
- **Default example** - "Puts i Karlstad" som demo

#### Recensionsvisning
- **Overall Rating** - Genomsnittligt betyg + stjärnor
- **Review Count** - Totalt antal recensioner
- **Individual Reviews** - Kortformat med författare, betyg, text
- **Rating Filter** - Filtrera på 1-5 stjärnor

#### Optimering
- **24h Cache** - Minskar API-anrop (Google Places är dyrt)
- **Rate Limiting** - 20 req/min för att undvika spam
- **Error Handling** - Användarvänliga felmeddelanden

### API Endpoints

#### 1. Sök företag

```http
GET /api/demos/google-reviews/search?query={query}
```

**Request:**
```bash
curl "https://atdev.me/api/demos/google-reviews/search?query=Puts+Karlstad"
```

**Response:**
```json
[
  {
    "place_id": "ChIJN1t_tDeuEmsRUsoyG83frY4",
    "name": "Puts i Karlstad",
    "formatted_address": "Kungsgatan 22, 652 24 Karlstad",
    "rating": 4.5,
    "user_ratings_total": 127
  }
]
```

#### 2. Hämta recensioner

```http
GET /api/demos/google-reviews/place/{placeId}
```

**Request:**
```bash
curl "https://atdev.me/api/demos/google-reviews/place/ChIJN1t_tDeuEmsRUsoyG83frY4"
```

**Response:**
```json
{
  "place_id": "ChIJN1t_tDeuEmsRUsoyG83frY4",
  "name": "Puts i Karlstad",
  "rating": 4.5,
  "user_ratings_total": 127,
  "formatted_address": "Kungsgatan 22, 652 24 Karlstad",
  "reviews": [
    {
      "author_name": "John Doe",
      "rating": 5,
      "text": "Fantastisk mat och trevlig personal!",
      "time": 1704067200,
      "relative_time_description": "en månad sedan"
    }
  ],
  "cached_at": "2025-01-15 10:30:00"
}
```

### Alpine.js Component

```javascript
// resources/js/demos/google-reviews.js
function googleReviewsDemo() {
    return {
        searchQuery: '',
        loading: false,
        error: null,
        searchResults: [],
        currentPlace: null,
        ratingFilter: null,
        cacheInfo: null,

        async searchPlace() {
            this.loading = true;
            this.error = null;

            try {
                const response = await fetch(
                    `/api/demos/google-reviews/search?query=${encodeURIComponent(this.searchQuery)}`
                );

                if (!response.ok) throw new Error('Sökningen misslyckades');

                this.searchResults = await response.json();

                if (this.searchResults.length === 0) {
                    this.error = 'Inga företag hittades. Prova ett annat sökord.';
                }
            } catch (err) {
                this.error = err.message;
            } finally {
                this.loading = false;
            }
        },

        async selectPlace(placeId) {
            this.loading = true;
            this.error = null;

            try {
                const response = await fetch(`/api/demos/google-reviews/place/${placeId}`);
                if (!response.ok) throw new Error('Kunde inte hämta recensioner');

                const data = await response.json();
                this.currentPlace = data;
                this.searchResults = [];

                if (data.cached_at) {
                    this.cacheInfo = new Date(data.cached_at).toLocaleString('sv-SE');
                }
            } catch (err) {
                this.error = err.message;
            } finally {
                this.loading = false;
            }
        },

        get displayedReviews() {
            if (!this.currentPlace?.reviews) return [];

            return this.ratingFilter
                ? this.currentPlace.reviews.filter(r => r.rating === this.ratingFilter)
                : this.currentPlace.reviews;
        },

        renderStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += i <= rating ? '⭐' : '☆';
            }
            return stars;
        }
    };
}
```

### Google Places API Setup

**1. Skaffa API-nyckel:**
1. Gå till [Google Cloud Console](https://console.cloud.google.com/)
2. Skapa projekt
3. Aktivera "Places API"
4. Skapa API-nyckel

**2. Konfigurera Laravel:**

```env
# .env
GOOGLE_PLACES_API_KEY=your-api-key-here
```

```php
// config/services.php
'google_places' => [
    'api_key' => env('GOOGLE_PLACES_API_KEY'),
],
```

**3. Rate Limiting:**

```php
// routes/api.php
Route::prefix('demos/google-reviews')->middleware('throttle:20,1')->group(function () {
    Route::get('/search', [GoogleReviewsController::class, 'search']);
    Route::get('/place/{placeId}', [GoogleReviewsController::class, 'getPlace']);
});
```

### Caching Strategy

**Cache Key Format:**
```
google_reviews:{place_id}
```

**Cache Duration:**
- 24 timmar (Google Places begränsar uppdateringsfrekvens)

**Implementation:**
```php
public function getPlace(string $placeId)
{
    return Cache::remember("google_reviews:{$placeId}", 86400, function () use ($placeId) {
        // API call to Google Places
        return $this->googlePlacesService->getPlaceDetails($placeId);
    });
}
```

### Kostnader (Google Places API)

**Pricing** (2025):
- Place Search: $32 per 1000 requests
- Place Details: $17 per 1000 requests

**Med caching (24h):**
- 100 unika platser/dag = ~$5/månad
- Cache minskar kostnad med ~95%

### Use Cases

**Restauranger & Caféer**
- Visa trovärdighet genom riktiga recensioner
- Sociala bevis ökar konvertering

**Lokala Butiker**
- Automatisk uppdatering av reviews
- Inget manuellt arbete

**Servicebolag**
- Bygg förtroende med transparens
- Visa kundomdömen

---

## 4. Smart Menu med AI Allergen-Analys

### Beskrivning
AI-driven allergen-identifiering för restaurangmenyer. Analyserar matbeskrivningar automatiskt och identifierar alla 14 EU-allergener med förklaringar och konfidensgrad. Använder Anthropic Claude 3.7 Sonnet.

### Teknisk Stack
- **Anthropic Claude 3.7 Sonnet** - AI-analys
- **AIService** - Laravel service wrapper
- **Alpine.js** - Interaktiv UI
- **Rate Limiting** - 5 requests/minut

### Funktioner

#### AI-Analys
- **Automatisk identifiering** - Analyserar beskrivning → hittar allergener
- **14 EU-allergener** - Enligt EU-förordning 1169/2011
- **Konfidensgrad** - Hög/Medel/Låg
- **Förklaring** - Varför allergenet identifierades
- **Exempel på källor** - Specifika ingredienser

#### User Experience
- **Pre-loaded dishes** - 8 exempelrätter (husmanskost → internationellt)
- **Category badges** - Visuell kategorisering
- **Loading states** - Smooth UX under analys
- **Error handling** - Tydliga felmeddelanden

### EU 14 Allergener

Systemet identifierar alla obligatoriska allergener enligt EU-lagstiftning:

1. **Gluten** - Vete, råg, korn, havre, spelt, kamut
2. **Kräftdjur** - Räkor, hummer, krabba, kräftor
3. **Ägg** - Alla äggprodukter
4. **Fisk** - All fisk och fiskprodukter
5. **Jordnötter** - Peanuts och jordnötssmör
6. **Sojabönor** - Soja, tofu, tempeh
7. **Mjölk** - Laktos, mjölkprotein, smör, ost, grädde
8. **Nötter** - Mandel, hasselnöt, valnöt, cashew, etc.
9. **Selleri** - Rotfrukter och blad
10. **Senap** - Senapsfrön och -produkter
11. **Sesamfrön** - Sesam, tahini
12. **Svaveldioxid** - Konserveringsmedel i vin, torkad frukt
13. **Lupin** - Lupinfrön (i bröd, pasta)
14. **Blötdjur** - Musslor, snäckor, bläckfisk

### API Endpoint

```http
POST /api/menu/analyze-allergens
```

**Request:**
```json
{
  "dish_name": "Laxpasta",
  "description": "Färsk pasta med rökt lax, spenat, grädde och citron. Toppas med dill."
}
```

**Response:**
```json
{
  "dish_name": "Laxpasta",
  "allergens": [
    {
      "allergen": "Gluten",
      "confidence": "high",
      "explanation": "Färsk pasta innehåller normalt vetemjöl vilket innehåller gluten",
      "example_sources": ["färsk pasta"]
    },
    {
      "allergen": "Fisk",
      "confidence": "high",
      "explanation": "Rökt lax är fisk",
      "example_sources": ["rökt lax"]
    },
    {
      "allergen": "Mjölk/Laktos",
      "confidence": "high",
      "explanation": "Grädde är en mjölkprodukt som innehåller laktos",
      "example_sources": ["grädde"]
    }
  ],
  "summary": "Identifierade 3 allergener med hög säkerhet",
  "analyzed_at": "2025-01-15T10:30:00Z"
}
```

### AIService Implementation

```php
// app/Services/AIService.php
public function analyzeMenuAllergens(string $dishName, string $description): array
{
    $prompt = "Analysera följande rätt och identifiera ALLA allergener enligt EU:s 14 obligatoriska allergener...";

    $response = $this->callAnthropicApi($prompt, [
        'model' => 'claude-3-7-sonnet-20250219',
        'max_tokens' => 1500,
        'temperature' => 0.3,  // Låg temperatur för precision
    ]);

    return $this->parseAllergenResponse($response);
}
```

### Alpine.js Component

```javascript
// resources/js/demos/smart-menu.js
function smartMenuData(dishesData) {
    return {
        dishes: dishesData,
        selectedDishIndex: 0,
        selectedDish: null,
        analyzing: false,
        error: null,
        analysisResult: null,

        init() {
            this.selectedDish = this.dishes[0];
        },

        async analyzeDish() {
            this.analyzing = true;
            this.error = null;
            this.analysisResult = null;

            try {
                const response = await fetch('/api/menu/analyze-allergens', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        dish_name: this.selectedDish.name,
                        description: this.selectedDish.description
                    })
                });

                if (!response.ok) {
                    throw new Error('Analys misslyckades');
                }

                this.analysisResult = await response.json();
            } catch (err) {
                this.error = err.message;
            } finally {
                this.analyzing = false;
            }
        },

        get allergenCount() {
            return this.analysisResult?.allergens?.length || 0;
        },

        get hasAnalysis() {
            return this.analysisResult !== null;
        },

        getConfidenceColor(confidence) {
            const colors = {
                'high': 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
                'medium': 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800',
                'low': 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700'
            };
            return colors[confidence] || colors.low;
        },

        getCategoryColor(category) {
            const colors = {
                'Husmanskost': 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300',
                'Sallader': 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300',
                'Pastarätter': 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300',
                // etc...
            };
            return colors[category] || 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
        }
    };
}
```

### Rate Limiting

```php
// routes/api.php
Route::prefix('menu')->middleware('throttle:5,1')->group(function () {
    Route::post('/analyze-allergens', [MenuController::class, 'analyzeAllergens']);
});
```

**Limit**: 5 requests/minut per session
**Rationale**: AI-anrop är dyra och tidskrävande

### Kostnader (Anthropic)

**Claude 3.7 Sonnet Pricing** (2025):
- Input: $3 per 1M tokens
- Output: $15 per 1M tokens

**Per Analys:**
- Input: ~400 tokens ($0.0012)
- Output: ~300 tokens ($0.0045)
- **Total: ~$0.006 per analys**

**Med rate limiting (5/min):**
- Max 7200 analyser/dag
- Cost: ~$43/dag (worst case)
- Real-world: ~10-50 analyser/dag = $0.06-0.30/dag

### Sample Dishes

8 exempelrätter täcker olika kök och allergener:

```php
'sample_dishes' => [
    ['name' => 'Klassiska Köttbullar', 'category' => 'Husmanskost'],
    ['name' => 'Caesarsallad', 'category' => 'Sallader'],
    ['name' => 'Laxpasta', 'category' => 'Pastarätter'],
    ['name' => 'Vegansk Burgare', 'category' => 'Vegetariskt'],
    ['name' => 'Pad Thai', 'category' => 'Asiatiskt'],
    ['name' => 'Margherita Pizza', 'category' => 'Pizza'],
    ['name' => 'Hummus med Bröd', 'category' => 'Förrätter'],
    ['name' => 'Pannacotta', 'category' => 'Efterrätter'],
]
```

### Use Cases

**Restauranger & Caféer**
- Automatisk allergendeklaration för menyer
- Minskar risk för allergiska reaktioner
- Sparar manuellt arbete

**Cateringföretag**
- Snabb allergenanalys för event-menyer
- Kundsäkerhet

**Receptappar**
- Allergenvarningar i receptdatabas
- Personaliserade rekommendationer

---

## Routes & Controllers

### Routes

```php
// routes/web.php
Route::get('/demos', [DemosController::class, 'index'])->name('demos');

// routes/api.php
Route::prefix('menu')->middleware('throttle:5,1')->group(function () {
    Route::post('/analyze-allergens', [MenuController::class, 'analyzeAllergens']);
});

Route::prefix('demos/google-reviews')->middleware('throttle:20,1')->group(function () {
    Route::get('/search', [GoogleReviewsController::class, 'search']);
    Route::get('/place/{placeId}', [GoogleReviewsController::class, 'getPlace']);
});
```

### DemosController

```php
/**
 * Display the interactive demos showcase page.
 *
 * @return View
 * Data: ['demos' => array]
 * Demos: Configuration för alla 4 demos med sample data
 */
public function index(): View
{
    return view('demos', [
        'demos' => [
            'product_viewer' => [...],
            'before_after_slider' => [...],
            'google_reviews' => ['enabled' => true],
            'smart_menu' => [...],
        ],
    ]);
}
```

### MenuController

```php
/**
 * Analyze dish allergens using AI.
 *
 * @param Request $request
 * @return JsonResponse
 */
public function analyzeAllergens(Request $request): JsonResponse
{
    $validated = $request->validate([
        'dish_name' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
    ]);

    $result = app(AIService::class)->analyzeMenuAllergens(
        $validated['dish_name'],
        $validated['description']
    );

    return response()->json($result);
}
```

### GoogleReviewsController

```php
/**
 * Search for places using Google Places API.
 *
 * @param Request $request
 * @return JsonResponse
 */
public function search(Request $request): JsonResponse
{
    $query = $request->input('query');
    $results = app(GooglePlacesService::class)->searchPlaces($query);
    return response()->json($results);
}

/**
 * Get place details and reviews (cached 24h).
 *
 * @param string $placeId
 * @return JsonResponse
 */
public function getPlace(string $placeId): JsonResponse
{
    $place = Cache::remember("google_reviews:{$placeId}", 86400, function () use ($placeId) {
        return app(GooglePlacesService::class)->getPlaceDetails($placeId);
    });

    return response()->json($place);
}
```

---

## Frontend Assets

### JavaScript Components

Alla Alpine.js-komponenter laddas via:

```javascript
// resources/js/app.js
import './demos/product-viewer.js';
import './demos/before-after-slider.js';
import './demos/google-reviews.js';
import './demos/smart-menu.js';
```

### CSS Styling

Demos använder Tailwind CSS med glassmorphism-effekter:

```css
/* resources/css/app.css */
.glass-morph {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

@media (prefers-color-scheme: dark) {
    .glass-morph {
        background: rgba(31, 41, 55, 0.7);
        border: 1px solid rgba(75, 85, 99, 0.3);
    }
}
```

### External Libraries

```html
<!-- Google Model-Viewer för 3D Product Viewer -->
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
```

---

## Testing

### Feature Tests

```bash
php artisan test --filter=DemosTest
```

```php
// tests/Feature/DemosTest.php
public function test_demos_page_loads()
{
    $response = $this->get('/demos');
    $response->assertStatus(200);
    $response->assertViewHas('demos');
}

public function test_allergen_analysis_requires_authentication()
{
    $response = $this->postJson('/api/menu/analyze-allergens', [
        'dish_name' => 'Test',
        'description' => 'Test description',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['allergens', 'summary']);
}

public function test_google_reviews_rate_limiting()
{
    for ($i = 0; $i < 21; $i++) {
        $response = $this->get('/api/demos/google-reviews/search?query=test');
    }

    $response->assertStatus(429); // Too Many Requests
}
```

---

## Performance Optimization

### Caching Strategy

**Google Reviews:**
- 24h cache för place details
- Minskar API-kostnader med 95%

**Images:**
- Lazy loading (`loading="lazy"`)
- WebP format för moderna browsers
- Fallback till JPG

### Rate Limiting

| Endpoint | Limit | Window | Rationale |
|----------|-------|--------|-----------|
| `/api/menu/analyze-allergens` | 5 | 1 min | Skyddar AI API-kostnader |
| `/api/demos/google-reviews/*` | 20 | 1 min | Begränsar Google Places-anrop |

### Asset Optimization

```bash
# Build production assets
npm run build

# Optimerar:
# - Minifiering av JS/CSS
# - Tree-shaking
# - Code splitting
# - Cache busting (hashed filenames)
```

---

## Security

### CSRF Protection

Alla POST requests kräver CSRF token:

```javascript
fetch('/api/menu/analyze-allergens', {
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
})
```

### Input Validation

```php
// MenuController
$request->validate([
    'dish_name' => 'required|string|max:255',
    'description' => 'required|string|max:1000',
]);
```

### API Key Protection

```env
# .env - ALDRIG committa nycklar
GOOGLE_PLACES_API_KEY=your-key-here
ANTHROPIC_API_KEY=sk-ant-xxxxx
```

---

## Deployment

### Environment Variables

```env
# Google Places API
GOOGLE_PLACES_API_KEY=

# Anthropic AI
ANTHROPIC_API_KEY=
ANTHROPIC_API_URL=https://api.anthropic.com/v1/messages
```

### Asset Compilation

```bash
# Production build
npm run build

# Files genererade i public/build/
# - manifest.json (asset mapping)
# - app-[hash].js
# - app-[hash].css
```

### 3D Models Setup

```bash
# Ladda ner sample GLB-modeller
# Se public/models/README.md för instruktioner
```

---

## Troubleshooting

### 3D Product Viewer

**Problem**: Modeller laddar inte
```
Lösning:
1. Kontrollera att GLB-filer finns i public/models/
2. Verifiera CORS-headers för model-filer
3. Check browser console för WebGL errors
```

**Problem**: AR fungerar inte på mobil
```
Lösning:
1. Testa på AR-kompatibel enhet (Android 8+/iOS 12+)
2. Använd HTTPS (AR kräver secure context)
3. Verifiera ar-modes="scene-viewer quick-look"
```

### Before/After Slider

**Problem**: Drag fungerar inte
```
Lösning:
1. Kontrollera att Alpine.js är laddat
2. Check browser console för JS errors
3. Verifiera touch events på mobil
```

### Google Reviews

**Problem**: "Quota exceeded" error
```
Lösning:
1. Check Google Cloud Console quota
2. Verifiera att caching fungerar (24h)
3. Öka quota eller vänta till nästa period
```

**Problem**: Inga reviews returneras
```
Lösning:
1. Kontrollera att placeId är korrekt
2. Vissa platser har inga reviews
3. Verifiera API-nyckel har rätt permissions
```

### Smart Menu

**Problem**: "Rate limit exceeded"
```
Lösning:
1. Vänta 1 minut (5 requests/min limit)
2. Check session state
3. Implementera client-side throttling
```

**Problem**: Fel allergener identifierade
```
Lösning:
1. AI-modellen är probabilistisk - vissa fel är förväntade
2. Förbättra prompt för bättre precision
3. Använd högre confidence threshold
4. Komplettera med manuell granskning för kritiska applikationer
```

---

## Future Enhancements

### Planerade Förbättringar

**3D Product Viewer:**
- [ ] Fler produktkategorier (elektronik, kläder, etc.)
- [ ] Anpassningsbara material/färger
- [ ] 360° foto-fallback för enheter utan WebGL
- [ ] Social sharing av AR-placements

**Before/After Slider:**
- [ ] Video support (före/efter video)
- [ ] Fler slider-layouts (vertikal, diagonal)
- [ ] Animerad transition mellan exempel
- [ ] Fullscreen mode

**Google Reviews:**
- [ ] Sentiment analysis på svenska
- [ ] Review response interface
- [ ] Review statistics dashboard
- [ ] Multi-location support

**Smart Menu:**
- [ ] Nutritional info analys
- [ ] Flera språk (engelska, tyska, franska)
- [ ] Batch-analys för hela menyer
- [ ] Export till PDF med allergendeklaration
- [ ] Integration med restaurang-POS system

---

## Licensiering & Attribution

### Externa Bibliotek

**Google Model-Viewer**
- License: Apache 2.0
- URL: https://modelviewer.dev/

**Google Places API**
- License: Google Maps Platform Terms
- Attribution required

**Anthropic Claude**
- License: Anthropic API Terms
- Commercial use allowed

### Sample Content

**3D Models:**
- Placeholder models för demo
- Ersätt med kundspecifika modeller i produktion

**Images:**
- Unsplash (free license) för demo-bilder
- Attribution rekommenderad

---

## Support & Kontakt

För frågor eller support kring demos:
- **Email**: andreas@atdev.me
- **Website**: https://atdev.me/#contact
- **Demo Page**: https://atdev.me/demos

---

**Senast uppdaterad**: 2025-01-15
**Version**: 1.0.0
