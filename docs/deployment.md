# ATDev - Deployment

## Produktionsmiljö

### Förutsättningar
- PHP 8.2+ med extensions: pdo_mysql, mbstring, xml, bcmath, curl
- Composer
- MySQL 8+
- Node.js & NPM
- Puppeteer/Chrome headless (för screenshots)
- Process manager (Supervisor) för queue worker
- Web server (Nginx/Apache) med mod_rewrite

### Domän och subdomäner
- Huvuddomän: `atdev.me`
- E-post: `andreas@atdev.me`
- Subdomäner för demos: `*.atdev.me` (konfigureras vid behov)

## Deployment-steg

### 1. Klona och installera

```bash
git clone <repository> /var/www/atdev
cd /var/www/atdev
composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### 2. Konfigurera .env

```env
APP_NAME=ATDev
APP_ENV=production
APP_KEY=  # Genereras med php artisan key:generate
APP_DEBUG=false
APP_URL=https://atdev.me

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=atdev_prod
DB_USERNAME=atdev_user
DB_PASSWORD=STRONG_PASSWORD_HERE

# Mailgun
MAIL_MAILER=mailgun
MAIL_FROM_ADDRESS="andreas@atdev.me"
MAIL_FROM_NAME="${APP_NAME}"
MAILGUN_DOMAIN=atdev.me
MAILGUN_SECRET=YOUR_MAILGUN_API_KEY
MAILGUN_ENDPOINT=api.mailgun.net

# Queue
QUEUE_CONNECTION=database  # eller redis

# Cache
CACHE_STORE=database  # eller redis

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
```

### 3. Skapa databas och användare

```bash
mysql -u root -p <<EOF
CREATE DATABASE atdev_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'atdev_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON atdev_prod.* TO 'atdev_user'@'localhost';
FLUSH PRIVILEGES;
EOF
```

### 4. Kör migrationer

```bash
php artisan migrate --force
php artisan db:seed --force
```

**Viktigt**: Byt admin-lösenord direkt efter första inloggningen!

### 5. Optimera för produktion

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### 6. Sätt korrekta fil-rättigheter

```bash
chown -R www-data:www-data /var/www/atdev
chmod -R 755 /var/www/atdev
chmod -R 775 /var/www/atdev/storage
chmod -R 775 /var/www/atdev/bootstrap/cache
```

### 7. Konfigurera Queue Worker (Supervisor)

Skapa `/etc/supervisor/conf.d/atdev-worker.conf`:

```ini
[program:atdev-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/atdev/artisan queue:work --sleep=3 --tries=3 --max-time=3600
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

Starta worker:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start atdev-worker:*
```

### 8. Konfigurera Web Server

#### Nginx exempel

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name atdev.me www.atdev.me;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name atdev.me www.atdev.me;

    root /var/www/atdev/public;
    index index.php;

    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/key.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Uppgradering/Deployment

När du uppdaterar koden:

```bash
cd /var/www/atdev

# Sätt app i underhållsläge
php artisan down

# Hämta senaste koden
git pull origin main

# Uppdatera dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Kör migrationer
php artisan migrate --force

# Rensa och återbygga cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Starta om queue workers
sudo supervisorctl restart atdev-worker:*

# Ta upp app igen
php artisan up
```

## Laravel Forge Deployment

### Automatisk ServiceSeeder-synkning

ServiceSeeder är idempotent och säker att köra vid varje deployment. Den uppdaterar tjänster med senaste innehåll utan att skapa dupliceringar.

**Forge Deployment Script** (redigera i Forge dashboard → Site → Deployment Script):

```bash
cd /home/forge/atdev.me
git pull origin $FORGE_SITE_BRANCH

$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock

if [ -f artisan ]; then
    # Migrations
    $FORGE_PHP artisan migrate --force

    # Sync services (idempotent - säkert att köra varje gång)
    $FORGE_PHP artisan db:seed --class=ServiceSeeder --force

    # Cache optimization
    $FORGE_PHP artisan config:clear
    $FORGE_PHP artisan cache:clear
    $FORGE_PHP artisan config:cache
    $FORGE_PHP artisan route:cache
    $FORGE_PHP artisan view:cache

    # Restart queue
    $FORGE_PHP artisan queue:restart
fi
```

**Nyckelfunktioner:**
- `ServiceSeeder` använder `updateOrCreate()` baserat på slug
- Uppdaterar befintliga tjänster med nytt innehåll från kod
- Skapar inga dupliceringar vid upprepade körningar
- Säkert att köra på varje deploy

**Verifiera efter deploy:**
```bash
php artisan tinker --execute="App\Models\Service::count()"
# Förväntat resultat: 8
```

### Quick Deploy (utan Forge)

Om du inte använder Forge, lägg till i `composer.json` under `scripts`:

```json
"post-autoload-dump": [
    "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
    "@php artisan package:discover --ansi",
    "@php artisan db:seed --class=ServiceSeeder --force --no-interaction || true"
]
```

**Obs**: Detta kör seedern även lokalt vid `composer install`. Använd Forge-metoden för bättre kontroll.

## Monitorering

### Hälsokontroll
Laravel har en inbyggd health check på `/up` som kan användas för monitoring.

### Loggar
- Applikationsloggar: `storage/logs/laravel.log`
- Queue worker loggar: `storage/logs/worker.log`
- Nginx loggar: `/var/log/nginx/`

### Viktiga saker att övervaka
- Queue-status: `php artisan queue:work` ska köra
- Disk-utrymme i `storage/app/public/screenshots/`
- Databas-storlek och prestanda
- Screenshot-jobb-fel i logs

## Backup

### Databas
```bash
mysqldump -u atdev_user -p atdev_prod > backup_$(date +%Y%m%d).sql
```

### Filer
Backup av `storage/app/public/` (screenshots och uploads)

## Säkerhet

### Checklista
- [x] APP_DEBUG=false i produktion
- [x] Stark databas-lösenord
- [x] SSL-certifikat installerat
- [x] Admin-lösenord bytt från default
- [x] Fil-rättigheter korrekt satta
- [x] .env-filen inte tillgänglig via web
- [x] Mailgun API-nycklar konfigurerade
- [x] Säkerhetsheaders i Nginx/Apache

### Uppdateringar
Håll Laravel, Composer-paket och systempaket uppdaterade:
```bash
composer update
php artisan route:clear && php artisan config:clear
```

## Felsökning

### Queue jobbar inte
```bash
sudo supervisorctl status atdev-worker:*
sudo supervisorctl restart atdev-worker:*
tail -f storage/logs/worker.log
```

### Screenshot-fel
- Verifiera Puppeteer: `npx puppeteer browsers list`
- Kontrollera att Chrome headless kan köras av www-data användaren
- Öka timeout i `TakeProjectScreenshot` job vid behov

### 500-fel
- Kontrollera `storage/logs/laravel.log`
- Verifiera fil-rättigheter
- Rensa cache: `php artisan config:clear && php artisan cache:clear`
