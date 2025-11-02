# ATDev - Setup

## Förutsättningar

- PHP 8.2 eller högre
- Composer
- MySQL 8
- Node.js & NPM
- Node/Puppeteer (för screenshots)

## Lokal Installation

### 1. Klona och installera dependencies

```bash
git clone <repository-url> atdev
cd atdev
composer install
npm install
```

### 2. Konfigurera miljövariabler

```bash
cp .env.example .env
php artisan key:generate
```

Uppdatera `.env`:

```env
APP_NAME=ATDev
APP_URL=http://atdev.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=atdev_dev
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=mailgun
MAIL_FROM_ADDRESS="andreas@atdev.me"
MAIL_FROM_NAME="${APP_NAME}"

MAILGUN_DOMAIN=
MAILGUN_SECRET=
MAILGUN_ENDPOINT=api.mailgun.net
```

### 3. Skapa databas

```bash
mysql -u root -e "CREATE DATABASE atdev_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 4. Kör migrationer och seeders

```bash
php artisan migrate
php artisan db:seed
```

Detta skapar:
- Admin-användare: `admin@atdev.me` / `password`
- En demo-profil
- 3 exempel-projekt

### 5. Länka storage för screenshots

```bash
php artisan storage:link
```

### 6. Installera Puppeteer (för screenshots)

```bash
npm install -g puppeteer
# eller
npx puppeteer browsers install chrome
```

### 7. Starta utvecklingsservrar

I separata terminaler:

```bash
# Laravel server
php artisan serve

# Queue worker
php artisan queue:work

# Frontend build (vid behov)
npm run dev
```

Applikationen är nu tillgänglig på `http://127.0.0.1:8000`

## Admin-inloggning

1. Gå till `http://127.0.0.1:8000/admin/login`
2. Logga in med:
   - E-post: `admin@atdev.me`
   - Lösenord: `password`

**Viktigt**: Byt lösenord i produktion!

## Utvecklingskommandon

### Rensa cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Kör tester
```bash
php artisan test
```

### Linting
```bash
./vendor/bin/pint
```

## Felsökning

### Screenshot fungerar inte
- Kontrollera att Puppeteer/Chrome är installerat: `npx puppeteer browsers list`
- Verifiera att queue worker körs: `php artisan queue:work`
- Kontrollera loggar: `storage/logs/laravel.log`

### E-post skickas inte
- I lokal utveckling används `MAIL_MAILER=log`, kontrollera `storage/logs/laravel.log`
- För produktion, konfigurera Mailgun-credentials i `.env`

### Admin kan inte logga in
- Verifiera att Fortify är registrerad i `bootstrap/providers.php`
- Kontrollera att migrations har körts
- Kör `php artisan db:seed --class=AdminUserSeeder` för att återskapa admin-användare

## Nästa steg

Se `admin.md` för guide till admin-funktionalitet.
