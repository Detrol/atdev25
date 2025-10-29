# ATDev - Projekt Syfte och Kontext

## Om Projektet

**ATDev** är en kombination av Andreas Trölss initialer (AT) och "Development". Detta är en personlig portfolio-plattform skapad för att erbjuda utvecklingstjänster till potentiella kunder.

## Projektmål

Primära syfte:
- **Portfolio-showcase**: Visa upp tidigare utvecklingsprojekt på ett professionellt sätt
- **Tjänsteerbjudande**: Presentera utvecklingstjänster som erbjuds
- **Kontaktpunkt**: Enkel väg för potentiella kunder att nå ut

## Design-filosofi

### Enkelhet och fokus
Projektet bygger på principen "enkelhet först":
- **Ingen över-engineering**: Minimal komplexitet, fokus på kärnfunktionalitet
- **Headless-ready**: Backend byggd för att separera från frontend senare
- **Ingen admin-överflödig**: Endast nödvändiga admin-funktioner, ingen onödig komplexitet

### Backend-first approach
Detta projekt har byggts med en tydlig separation i åtanke:
- **Backend (denna implementation)**: Laravel 12 med tydliga datakontrakt
- **Frontend (framtida)**: Byggs av separat AI baserat på backend-kontrakten
- **Ingen UI ännu**: Controllers returnerar endast view-namn, ingen Blade/HTML implementerad

## Tekniska Val

### Varför Laravel 12?
- Mogen, stabil och väldokumenterad
- Excellent ecosystem (Fortify, Spatie-paket)
- Built-in queue system för screenshot-automation
- Perfekt för både API och traditional web apps

### Varför MySQL?
- Robust och välkänd
- Utmärkt för relationsdata
- Enkel deployment och backup

### Varför Browsershot?
- Automatisk screenshot av live-projekt
- Visar projekt visuellt i portfolion
- Professionell touch för portfolio-presentation

### Varför Mailgun?
- Pålitlig e-postleverans
- Enkel integration
- Bra för transactional e-post

## Arkitektur-beslut

### Singleton Profile
- Endast en profil behövs (personlig portfolio)
- Enklare än user-system med multiple profiles

### Ingen API-separation
- Ingen extern API behövs i detta skede
- Web routes räcker för både publik frontend och admin
- Kan enkelt läggas till senare om behov uppstår

### Featured Projects på startsidan
- Endast utvalda projekt visas på hem
- Ger kontroll över vad besökare ser först
- Sort order för finare kontroll

### Screenshot-automation
- Sparar tid vid projektuppdateringar
- Ger konsekvent utseende
- Visar projekt "live" för potentiella kunder

## Framtida Frontend

När frontend byggs kommer den använda:
- Datakontrakt från controllers (dokumenterade i `routes.md`)
- View-namn som specificerade i return-statements
- Livewire (redan installerat) för interaktiva komponenter

Frontend-AI:n kommer få:
- Alla docs-filer
- Exakt datastruktur från varje endpoint
- Information om vilket data som är available i views

## Deployment-strategi

### Lokal utveckling
- MySQL lokalt
- Queue worker lokalt
- Puppeteer lokalt

### Produktion
- atdev.me som huvuddomän
- Subdomäner för demo-projekt (*.atdev.me)
- Supervisor för queue workers
- Mailgun för e-post (info@atdev.me)

## Innehållsstrategi

### Projekt-showcase
Fokus på:
- Fullständiga case studies
- Teknologier använda
- Live-demos där möjligt
- Screenshots för visuell appeal

### Tjänster
Information om:
- Laravel-utveckling
- Fullstack-lösningar
- API-utveckling
- Konsultation

## Säkerhetsaspekter

### Spam-skydd
- Honeypot-fält i kontaktformulär
- Rate limiting (5/minut, 20/dag)
- IP-logging för spårning

### Admin-säkerhet
- Session-baserad auth (inga tokens att läcka)
- Endast login (ingen registrering = mindre attack surface)
- Admin-lösenord byts i produktion

## Utvecklings-approach

### Två-stegs process
1. **Backend (denna fas)**: 
   - Komplett datamodell
   - Admin-funktionalitet
   - Alla endpoints med datakontrakt
   - Dokumentation

2. **Frontend (nästa fas)**:
   - Separat AI bygger views
   - Använder dokumenterade kontrakt
   - Implementerar design
   - Livewire-komponenter

### Varför denna approach?
- Separation of concerns
- Tydliga gränssnitt mellan lager
- Enklare att underhålla
- Frontend kan byggas om utan att röra backend

## Målgrupp

### Primär målgrupp
- Svenska företag som behöver webbutveckling
- Startups som söker Laravel-expertis
- Företag med befintliga system som behöver expansion

### Sekundär målgrupp
- Internationella kunder (i18n-ready struktur)
- Större företag som behöver konsultation

## Framtida Expansion

### Möjliga tillägg (ej prioriterade nu)
- Blog/artiklar om utveckling
- Testimonialsystem (modell finns redan)
- Tags för projekt-filtrering
- Metrics och analytics
- Multiple admins (user-relations)
- API för externa integrationer

### Vad vi INTE bygger
- Complex CMS-funktionalitet
- E-handel
- User-facing accounts
- Social media-integration (utöver länkar)
- Comment systems

## Sammanfattning

ATDev är en **fokuserad, professionell portfolio-lösning** byggd med:
- ✅ Enkelhet i fokus
- ✅ Tydlig backend/frontend-separation
- ✅ Professionella funktioner (screenshots, spam-skydd)
- ✅ Skalbar arkitektur (utan över-engineering)
- ✅ Produktionsklar struktur
- ✅ Komplett dokumentation

Projektet är **backend-komplett** och redo för frontend-implementation.
