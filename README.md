# ATDev - AI-Driven Portfolio Platform

En modern portfolio-plattform byggd med Laravel 12 som kombinerar klassisk webbutveckling med avancerad AI-integration för att demonstrera fullstack-kapacitet och moderna utvecklingstekniker.

## 🎯 Översikt

ATDev är inte bara en portfolio – det är en **showcase av moderna webbutvecklingstekniker** med fokus på:
- AI-integration (Anthropic Claude)
- GDPR-compliance
- Real-time kommunikation
- Modern UI/UX med interaktiva demos
- SEO-optimering
- Säkerhet och prestanda

## ✨ Huvudfunktioner

### 🤖 AI-Drivna Funktioner
- **AI-Assistent**: Chatbot med portfolio-kontext (Anthropic Claude 4.5 Sonnet)
- **Website Audit**: Omfattande AI-baserad webbplatsanalys med ground truth data
- **Priskalkylator**: AI-driven projektuppskattning med web scraping (BrightData)
- **Smart Menu**: AI allergen-analys på svenska (EU 14 allergener)

### 💬 Kommunikation
- **Tvåvägsmeddelandesystem**: Email-baserad threading via Mailgun webhooks
- **Reply Token System**: Säker konversationshantering med unika tokens
- **Admin Email Integration**: Svara från inbox eller admin-panel
- **Real-time Notifikationer**: Queue-baserad email-leverans

### 🔐 GDPR-Efterlevnad
- **Cookie Consent Management**: Kategoribaserad samtyckshantering (90 dagars lagring)
- **Data Export**: JSON-export av all användardata
- **Data Deletion**: Anonymisering eller fullständig radering
- **Privacy & Cookie Policies**: Detaljerade policydokument
- **GDPR Showcase**: Interaktiv demo av alla GDPR-funktioner

### 🛡️ Säkerhet & Botskydd
- **Cloudflare Turnstile**: ML-baserad bot-detektion (kontakt, audit, priskalkylator)
- **Multi-layer Spam Protection**: Turnstile + honeypot + rate limiting + CSRF
- **Security Headers**: CSP, HSTS, Referrer-Policy, Permissions-Policy
- **Webhook Signature Verification**: HMAC-SHA256 för Mailgun
- **Rate Limiting Matrix**: Per-endpoint throttling

### 🎨 Interaktiva Demos
- **3D Product Viewer**: AR-aktiverad produktvisualisering
- **Before/After Slider**: Bildj jämförelsewidget
- **Google Reviews**: Live reviews-integration (Google Places API)
- **Smart Menu**: AI-driven allergen-detektor
- **Tech Stack Visualization**: D3.js-baserad teknologivisualisering

### 📊 Portfolio & Innehåll
- **Project Showcase**: Featured projects med screenshot-automation
- **Screenshot Automation**: Spatie Browsershot (headless Chrome)
- **Services Management**: CRUD för tjänsteerbjudanden
- **FAQ System**: Kategoriserade FAQs med AI chat-integration
- **Singleton Profile**: Centraliserad profilinformation

### 🔍 SEO & Prestanda
- **Dynamic Sitemap**: Auto-genererad XML sitemap
- **Structured Data**: JSON-LD schema (Organization, Person, WebSite, BreadcrumbList)
- **Meta Tag Management**: Per-sida SEO-optimering
- **Cache Headers**: Optimerad static asset caching
- **Lazy Loading**: Responsive images med optimering

### 📱 Utvecklarupplevelse
- **Unified Dev Command**: `composer dev` (kör alla services med concurrently)
- **Hot Reload**: Vite watch mode för frontend
- **Code Formatting**: Laravel Pint
- **Testing**: Pest med feature/unit tests
- **Queue System**: Database (local) → Redis (production)

## 🏗️ Teknisk Stack

### Backend
- **Framework**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL 8
- **Auth**: Laravel Fortify (session-based)
- **Queue**: Database driver (upgrade to Redis in production)
- **Email**: Mailgun (production)
- **Testing**: Pest

### AI & Externa Tjänster
- **AI**: Anthropic Claude 4.5 Sonnet
- **Scraping**: BrightData proxy service
- **Reviews**: Google Places API
- **Bot Protection**: Cloudflare Turnstile
- **Screenshots**: Spatie Browsershot (Puppeteer)

### Frontend
- **Build Tool**: Vite
- **CSS**: Tailwind CSS 4.0
- **JavaScript**: Alpine.js 3.x
- **Images**: Responsive lazy-loading med Spatie Media Library
- **Visualization**: D3.js (Tech Stack)

### DevOps & Infrastruktur
- **Deployment**: Nginx/Apache
- **Process Manager**: Supervisor (queue workers)
- **Cache**: Redis (production)
- **SSL**: Let's Encrypt
- **CDN**: Cloudflare

## 🚀 Snabbstart

### Förutsättningar
- PHP 8.2+
- Composer
- MySQL 8
- Node.js & NPM
- (Optional) Puppeteer för screenshots

### Installation

```bash
# Klona och installera
git clone <repository> atdev
cd atdev
composer install
npm install

# Konfigurera miljö
cp .env.example .env
php artisan key:generate

# Skapa databas
mysql -u root -e "CREATE DATABASE atdev_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Uppdatera .env med credentials
# Se .env.example för alla required variabler

# Kör migrationer och seeders
php artisan migrate
php artisan db:seed

# Länka storage för screenshots
php artisan storage:link

# Installera Puppeteer (för screenshots)
npm install -g puppeteer
# eller
npx puppeteer browsers install chrome

# Bygg frontend assets
npm run build

# Starta utvecklingsmiljö (all-in-one command)
composer dev

# ELLER starta services separat:
# Terminal 1: php artisan serve
# Terminal 2: php artisan queue:work
# Terminal 3: npm run dev
```

### API Keys (Optional för full funktionalitet)

```env
# AI Features
ANTHROPIC_API_KEY=sk-ant-xxxxxxxxxx

# Bot Protection
TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=0x4AAA...
TURNSTILE_SECRET_KEY=0x4AAA...

# Email & Messaging
MAILGUN_DOMAIN=mg.atdev.me
MAILGUN_SECRET=your-api-key
MAILGUN_WEBHOOK_SIGNING_KEY=your-signing-key

# Web Scraping (Price Calculator)
BRIGHTDATA_API_KEY=your-key
BRIGHTDATA_PROXY_HOST=proxy.brightdata.com
BRIGHTDATA_PROXY_PORT=22225

# Google Reviews Demo
GOOGLE_PLACES_API_KEY=your-key
GOOGLE_PLACES_DEFAULT_PLACE_ID=ChIJ...

# Analytics (Optional)
GOOGLE_GA4_MEASUREMENT_ID=G-XXXXXXXXXX
```

### Admin-inloggning

Gå till `http://127.0.0.1:8000/admin/login`

Standard-credentials (från `AdminUserSeeder`):
- E-post: `admin@atdev.me`
- Lösenord: `password`

**⚠️ VIKTIGT**: Byt lösenord i produktion!

## 📚 Dokumentation

Komplett dokumentation finns i `docs/`:

### Grundläggande Dokumentation
- **[architecture.md](docs/architecture.md)** - System design, services, patterns
- **[setup.md](docs/setup.md)** - Installation och lokal utveckling
- **[routes.md](docs/routes.md)** - Alla routes med datakontrakt
- **[models.md](docs/models.md)** - Databasschema och modeller
- **[api.md](docs/api.md)** - API-dokumentation (AI, GDPR, Demos)
- **[services.md](docs/services.md)** - Services layer (AI, scraping, GDPR)

### Funktionsdokumentation
- **[ai-assistant.md](docs/ai-assistant.md)** - AI chatbot implementation guide
- **[gdpr.md](docs/gdpr.md)** - GDPR compliance guide
- **[demos.md](docs/demos.md)** - Interactive demos implementation
- **[mailgun-setup.md](docs/mailgun-setup.md)** - Messaging system setup
- **[admin.md](docs/admin.md)** - Admin panel guide

### Planering & Arkitektur
- **[PROJECT.md](docs/PROJECT.md)** - Project philosophy och context
- **[frontend.md](docs/frontend.md)** - Frontend patterns och components
- **[seo-optimization-plan.md](docs/seo-optimization-plan.md)** - SEO strategy
- **[deployment.md](docs/deployment.md)** - Production deployment
- **[showcase-ideas.md](docs/showcase-ideas.md)** - Future showcase features
- **[audit-debugging.md](docs/audit-debugging.md)** - Website audit troubleshooting

## 🛠️ Utveckling

### Viktiga Kommandon

```bash
# Development (all services via concurrently)
composer dev

# Frontend build (REQUIRED after CSS/JS changes)
npm run build   # Production build
npm run dev     # Watch mode

# Testing
php artisan test                    # All tests
php artisan test --filter=TestName  # Specific test
php artisan test --coverage         # With coverage

# Code formatting
./vendor/bin/pint

# Queue worker (required for screenshots & emails)
php artisan queue:work

# Database
php artisan migrate:fresh --seed
php artisan db:seed --class=ProjectSeeder
php artisan db:seed --class=AdminUserSeeder

# Cache management
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# SEO
php artisan sitemap:generate  # Generate sitemap.xml
```

### Köjobb

Följande jobb körs asynkront (kräver queue worker):

1. **TakeProjectScreenshot** - Screenshot capture via Browsershot
2. **SendContactEmail** - Notification till admin om nytt kontaktmeddelande
3. **SendReplyEmail** - Reply från admin till kund
4. **SendCustomerReplyNotification** - Notification om kundreply via email
5. **ProcessWebsiteAudit** - AI-baserad website audit (180s timeout)

**Local development**: `php artisan queue:work`
**Production**: Supervisor eller motsvarande

### Screenshot-funktionalitet

Screenshots tas automatiskt när:
1. Nytt projekt skapas med `live_url`
2. Existerande `live_url` uppdateras
3. Manuellt via admin: POST `/admin/projects/{project}/screenshot`

Screenshots sparas i `storage/app/public/screenshots/{slug}-{timestamp}.png` och är publikt tillgängliga via `/storage/screenshots/`.

### Hastighetsbegränsning

Definierat per endpoint (se `docs/api.md` för fullständig lista):
- **Contact**: 5/minut, 20/dag per IP
- **AI Chat**: 5/minut per session
- **Website Audit**: 3/dag per IP (admin exempt)
- **Price Estimate**: 5/10 minuter per IP (admin exempt)
- **Smart Menu**: 10/minut per IP
- **Mailgun Webhook**: 100/minut

### Frontend Build-krav

**⚠️ VIKTIGT**: Kör alltid `npm run build` efter ändringar i:
- `resources/css/app.css`
- `resources/js/app.js`
- Blade templates med Tailwind-klasser

Vite kompilerar assets till `public/build/`. Utan rebuild syns ändringar inte i browsern.

## 🗂️ Projektstruktur

```
atdev/
├── app/
│   ├── Enums/              # ProjectStatus
│   ├── Http/
│   │   ├── Controllers/    # Public + API controllers
│   │   │   ├── Admin/      # Admin controllers (CRUD)
│   │   │   └── Api/        # API controllers
│   │   ├── Requests/       # Form validation
│   │   └── Middleware/     # SecurityHeaders, AddCacheHeaders
│   ├── Jobs/               # 5 queue jobs
│   ├── Mail/               # Email mailables
│   ├── Models/             # 10 Eloquent models
│   ├── Providers/          # Service providers
│   ├── Services/           # 11 service classes (AI, GDPR, scraping, etc.)
│   └── View/Components/    # Blade components (SEO, demos, UI)
├── config/
│   ├── allergens.php       # EU 14 allergens + dietary preferences
│   ├── seo.php             # SEO meta, schema, CSP, security headers
│   └── services.php        # Anthropic, Mailgun, Google, BrightData
├── database/
│   ├── migrations/         # 10 tables + indexes
│   └── seeders/            # AdminUser, Profile, Projects, Services, FAQs
├── docs/                   # Comprehensive documentation (13 files)
├── public/
│   └── build/              # Vite compiled assets
├── resources/
│   ├── css/
│   │   └── app.css         # Tailwind CSS 4.0
│   ├── js/
│   │   └── app.js          # Alpine.js integration
│   └── views/              # Blade templates
│       ├── components/     # Reusable components
│       ├── admin/          # Admin panel views
│       ├── demos/          # Interactive demo pages
│       └── gdpr/           # GDPR compliance pages
├── routes/
│   ├── web.php             # Public + Admin + GDPR + Webhook routes
│   └── api.php             # AI, Consent, Calculator, Menu, Reviews
├── storage/
│   └── app/public/
│       └── screenshots/    # Auto-generated project screenshots
├── tests/
│   ├── Feature/            # HTTP, workflow tests
│   └── Unit/               # Service, model tests
├── CLAUDE.md               # AI assistant project guide
└── README.md               # This file
```

## 🔒 Säkerhet

### Autentisering
- Laravel Fortify (session-based)
- Admin routes: `auth` middleware + `/admin` prefix
- No public registration (admin-only)

### Flerlagers Spamskydd
1. **Cloudflare Turnstile** - ML bot detection
2. **Honeypot Fields** - Hidden `website` field (bots fill it)
3. **Rate Limiting** - IP-based per endpoint
4. **CSRF Tokens** - Laravel standard protection

### Säkerhetsheaders (via middleware)
- Content-Security-Policy (CSP)
- Strict-Transport-Security (HSTS)
- X-Frame-Options
- X-Content-Type-Options
- Referrer-Policy
- Permissions-Policy

### Webhook-säkerhet
- **Mailgun**: HMAC-SHA256 signature verification
- **Timestamp Validation**: Max 15 minuter (replay attack prevention)
- **CSRF Exempt**: `/mailgun/inbound` (uses signature instead)

### Datasekretess
- IP och user-agent loggning för spam prevention
- GDPR-compliant data export och deletion
- Cookie consent med 90-dagars lagring
- Anonymization option för deletion requests

## 🚢 Driftsättning

### Produktionschecklista

```bash
# Dependencies
composer install --no-dev --optimize-autoloader

# Database
php artisan migrate --force

# Optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# SEO
php artisan sitemap:generate

# Assets
npm run build

# Storage
php artisan storage:link

# Queue Worker (Supervisor)
sudo supervisorctl restart atdev-worker
```

### Miljöskillnader

**Lokal → Produktion**:
- `QUEUE_CONNECTION`: `database` → `redis`
- `CACHE_STORE`: `database` → `redis`
- `MAIL_MAILER`: `log` → `mailgun`
- `APP_ENV`: `local` → `production`
- `APP_DEBUG`: `true` → `false`
- `TURNSTILE_ENABLED`: `false` → `true`

Se `docs/deployment.md` för fullständig guide inkl:
- Nginx/Apache configuration
- Supervisor setup
- SSL via Let's Encrypt
- Backup strategies
- Cloudflare integration

## 🧪 Testning

**Ramverk**: Pest

```bash
# Kör alla tester
php artisan test

# Specifikt test
php artisan test --filter=ProjectTest

# Med coverage
php artisan test --coverage

# Parallell exekvering
php artisan test --parallel
```

**Teststruktur**:
- **Feature tests**: HTTP requests, workflows, integration
- **Unit tests**: Services, models, isolated logic
- **Database**: Transactions för isolation

## 🎨 Frontend-integration

### Nuvarande Status
Controllers returnerar view-namn med **dokumenterade datakontrakt**. Frontend kan byggas oberoende mot dessa kontrakt.

### Integrationsmönster
1. Kolla controller i `app/Http/Controllers/`
2. Läs data contract från comments eller `docs/routes.md`
3. Bygg view med exakt struktur som dokumenterats
4. Alpine.js för interaktivitet (`x-data`, `x-show`, `x-transition`)
5. Livewire tillgängligt men inte implementerat än

### Assets
- **Tailwind CSS 4.0**: Utility-first CSS via Vite
- **Alpine.js 3.x**: Loaded via CDN för reaktivitet
- **Vite**: Module bundler med hot reload

## 🤝 Bidra

Detta är ett proprietärt portfolio-projekt, men feedback är välkommet:
- Rapportera buggar via GitHub Issues
- Föreslå förbättringar
- Dela best practices

## 📞 Support & Kontakt

För frågor eller samarbeten, kontakta:
- **Email**: andreas@atdev.me
- **Website**: https://atdev.me
- **GitHub**: [Repository]

## 📄 Licens

Proprietär - © 2025 ATDev

---

**Built with ❤️ using Laravel 12, Anthropic Claude, and modern web standards**
