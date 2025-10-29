# ATDev - Portfolio Platform

En enkel och kraftfull portfolio-plattform byggd med Laravel 12 för att visa utvecklingstjänster och projekt.

## Översikt

ATDev är en minimalistisk portfolio-lösning med fokus på enkelhet och användarvänlighet. Plattformen består av:

- **Publik frontend**: Startsida med featured projekt och kontaktformulär
- **Admin-panel**: CRUD för projekt, profilinformation och kontaktmeddelanden
- **Screenshot-automation**: Automatisk screenshot av projekt med live-URL

## Funktioner

✨ **Core Features**
- Portfolio-projekt med featured-flagga
- Automatisk slug-generering från svenska titlar
- Screenshot-automation via Spatie Browsershot
- Kontaktformulär med spam-skydd (honeypot, rate limiting)
- Singleton-profil med social media-länkar
- Draft/Published status för projekt

🔒 **Säkerhet**
- Session-baserad autentisering (Laravel Fortify)
- Rate limiting (kontakt: 5/minut, 20/dag)
- Honeypot-fält mot spam-bots
- IP och user-agent-loggning

📧 **E-post & Notifikationer**
- Queued e-post via Mailgun
- Kontaktmeddelanden till info@atdev.me

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Databas**: MySQL 8
- **Auth**: Laravel Fortify
- **Screenshot**: Spatie Browsershot (Puppeteer/Chrome headless)
- **E-post**: Mailgun
- **Queue**: Database (kan uppgraderas till Redis)
- **Testing**: Pest

## Snabbstart

### Förutsättningar
- PHP 8.2+
- Composer
- MySQL 8
- Node.js & NPM

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

# Uppdatera .env med databas-credentials
# DB_CONNECTION=mysql
# DB_DATABASE=atdev_dev
# DB_USERNAME=root
# DB_PASSWORD=

# Kör migrationer och seeders
php artisan migrate
php artisan db:seed

# Länka storage för screenshots
php artisan storage:link

# Installera Puppeteer (för screenshots)
npm install -g puppeteer
# eller
npx puppeteer browsers install chrome

# Starta utvecklingsservrar
php artisan serve          # i en terminal
php artisan queue:work     # i en annan terminal
```

### Admin-inloggning

Gå till `http://127.0.0.1:8000/admin/login`

Standard-credentials:
- E-post: `admin@atdev.me`
- Lösenord: `password`

**⚠️ Viktigt**: Byt lösenord i produktion!

## Dokumentation

Fullständig dokumentation finns i `docs/`:

- **[architecture.md](docs/architecture.md)** - Arkitektur och systemdesign
- **[setup.md](docs/setup.md)** - Installation och lokal utveckling
- **[routes.md](docs/routes.md)** - Alla routes med datakontrakt
- **[models.md](docs/models.md)** - Databasschema och modeller
- **[admin.md](docs/admin.md)** - Admin-guide och workflows
- **[deployment.md](docs/deployment.md)** - Produktionsdeployment

## Projektstruktur

```
atdev/
├── app/
│   ├── Enums/              # ProjectStatus enum
│   ├── Http/
│   │   ├── Controllers/    # Publika controllers (Home, Project, Contact)
│   │   │   └── Admin/      # Admin controllers
│   │   └── Requests/       # Form validering
│   ├── Jobs/               # TakeProjectScreenshot, SendContactEmail
│   ├── Mail/               # ContactEmail Mailable
│   └── Models/             # Profile, Project, ContactMessage, User
├── database/
│   ├── migrations/         # Tabeller och index
│   └── seeders/            # Demo-data
├── docs/                   # Fullständig dokumentation
├── routes/
│   └── web.php            # Alla routes (public + admin)
└── storage/
    └── app/public/
        └── screenshots/    # Projekt-screenshots
```

## API och Datakontrakt

Backend är headless-friendly med tydliga datakontrakt dokumenterade i varje controller-metod. Frontend bygger views senare baserat på kontrakten. Se `docs/routes.md` för detaljer.

## Utveckling

### Kommandon

```bash
# Rensa cache
php artisan config:clear
php artisan cache:clear

# Kör tester
php artisan test

# Code formatting
./vendor/bin/pint

# Kör seeders igen
php artisan db:seed --class=AdminUserSeeder
```

### Screenshot-funktionalitet

Screenshots tas automatiskt när:
1. Ett nytt projekt skapas med `live_url`
2. En existerande `live_url` uppdateras
3. Manuellt via admin: POST `/admin/projects/{project}/screenshot`

Screenshots sparas i `storage/app/public/screenshots/` och är publikt tillgängliga via `/storage/screenshots/`.

## Deployment

Se `docs/deployment.md` för fullständig produktionsguide, inkl:
- Nginx/Apache-konfiguration
- Supervisor för queue workers
- SSL-setup
- Backup-strategier

## Säkerhet

- **Autentisering**: Session-baserad via Fortify
- **Rate limiting**: Kontaktformulär begränsat till 5/minut, 20/dag
- **Spam-skydd**: Honeypot + validering
- **CSRF**: Laravel CSRF-skydd aktiverat
- **Headers**: Security headers i produktion

## Support och Kontakt

För frågor eller support, kontakta info@atdev.me

## Licens

Proprietär - © 2025 ATDev
