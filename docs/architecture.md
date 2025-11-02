# ATDev - Arkitektur

## Översikt

ATDev är en portfolio-plattform byggd med Laravel 12 för att visa utvecklingstjänster och projekt. Applikationen består av en enkel publik frontend och ett administratörsområde för innehållshantering.

## Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Databas**: MySQL 8
- **Queue**: Database driver (kan uppgraderas till Redis vid behov)
- **Cache**: Database cache (kan uppgraderas till Redis)
- **Auth**: Laravel Fortify (session-baserad)
- **Screenshots**: Spatie Browsershot (Puppeteer/Chrome headless)
- **E-post**: Mailgun
- **Testing**: Pest

## Arkitekturprinciper

### Enkelhet först
Projektet fokuserar på en enkel, ren arkitektur utan över-engineering:
- Minimal antal modeller (Profile, Project, ContactMessage, User)
- Controllers returnerar view-namn med datakontrakt (frontend bygger vyerna senare)
- Ingen extern API - endast web-routes

### I18n-redo struktur
Även om projektet primärt är på svenska, är modellerna utformade för att kunna stödja flera språk senare:
- Text-fält kan konverteras till JSON-format med språknycklar vid behov
- Slug-generering fungerar med svenska tecken

### Headless-friendly
Backend är förberedd för separation av frontend:
- Controllers har tydliga datakontrakt dokumenterade i kommentarer
- Modeller har scopes och helper-metoder för vanliga queries
- Screenshot-funktionalitet arbetar asynkront via queue-system

## Domänmodell

### Profile (Singleton)
En enda profil per installation som innehåller personlig information, hero-bild och sociala länkar.

### Project
Portfolio-projekt med:
- Slug-baserad routing
- Status (draft/published)
- Featured-flagga för startsidan
- Automatisk screenshot-funktion av live_url
- Teknologier och galleri som JSON

### ContactMessage
Inkommande meddelanden från kontaktformulär med:
- IP och user-agent för spårning
- Read-flagga för att markera lästa meddelanden
- Queued e-postnotifikationer

### User
Admin-användare för autentisering (endast login, ingen registrering).

## Säkerhet

### Authentication
- Laravel Fortify för sessionsbaserad autentisering
- Admin-routes skyddade med `auth` middleware
- Prefix `/admin` för alla admin-endpoints

### Rate Limiting
- **Kontaktformulär**: 5 requests/minut, 20/dag per IP
- **Login**: 5 försök/minut (Fortify default)

### Spam-skydd kontakt
- Honeypot-fält (`website`) som måste vara tomt
- Validering av alla input-fält
- IP och user-agent loggas

## Queue System

Jobs som körs asynkront:
1. **TakeProjectScreenshot**: Tar screenshot av projekt-URL med Browsershot
2. **SendContactEmail**: Skickar kontaktmeddelanden till andreas@atdev.me

För lokal utveckling: `php artisan queue:work`
För produktion: Supervisor eller motsvarande för queue worker

## Filhantering

Screenshots sparas i `storage/app/public/screenshots/` och är publikt tillgängliga via `/storage/screenshots/` efter `php artisan storage:link`.

## Deployment Checklist

Se `deployment.md` för fullständig guide.
