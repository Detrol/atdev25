# ATDev - Admin Guide

## Inloggning

URL: `/admin/login`

Standard-credentials (lokal utveckling):
- E-post: `admin@atdev.me`
- Lösenord: `password`

**OBS**: Byt lösenord i produktion!

## Dashboard

URL: `/admin`

Visar översikt:
- Antal projekt
- Antal olästa meddelanden
- 5 senaste projekt
- 5 senaste kontaktmeddelanden

## Hantera Projekt

### Lista projekt
URL: `/admin/projects`

- Visar alla projekt (paginated, 20/sida)
- Sorterat efter sort_order, sedan created_at desc
- Länkar till redigera/radera

### Skapa projekt
URL: `/admin/projects/create`

Fält:
- **Title** (required): Projekttitel
- **Slug** (optional): Auto-genereras från titel om tom
- **Summary** (required, max 500 tecken): Kort beskrivning för listvy
- **Description** (required): Fullständig beskrivning (stöder markdown)
- **Cover Image** (optional): URL till omslagsbild
- **Gallery** (optional, array): URL:er till galleribilder
- **Live URL** (optional): Länk till live-version (triggar auto-screenshot)
- **Repo URL** (optional): Länk till repository (t.ex. GitHub)
- **Technologies** (optional, array): Teknologier som används (t.ex. Laravel, Vue.js)
- **Status** (required): Draft / Published
- **Featured** (boolean): Visas på startsidan
- **Sort Order** (integer): Lägre nummer = högre prioritet (0 = högst)

Vid sparning med `live_url`: Screenshot-jobb dispatchar automatiskt.

### Redigera projekt
URL: `/admin/projects/{project}/edit`

Samma fält som "Skapa projekt".

Om `live_url` ändras: Nytt screenshot-jobb dispatchar.

### Manuell screenshot
URL: POST `/admin/projects/{project}/screenshot`

Triggar screenshot-jobb manuellt (kräver att projektet har `live_url`).

### Radera projekt
URL: DELETE `/admin/projects/{project}`

Raderar projektet permanent.

## Hantera Profil

### Redigera profil
URL: `/admin/profile`

Singleton - endast en profil per installation.

Fält:
- **Name** (required): Fullständigt namn
- **Title** (required): Yrkestitel (t.ex. "Fullstack-utvecklare")
- **Bio** (required): Om mig-text
- **Avatar** (optional): URL till profilbild
- **Hero Image** (optional): URL till hero-bild för startsidan
- **Email** (optional): Publik e-postadress
- **Phone** (optional): Telefonnummer
- **GitHub** (optional): Länk till GitHub-profil
- **LinkedIn** (optional): Länk till LinkedIn-profil
- **Twitter** (optional): Länk till Twitter/X-profil

## Hantera Kontaktmeddelanden

### Lista meddelanden
URL: `/admin/messages`

- Visar alla mottagna meddelanden (paginated, 20/sida)
- Sorterat efter created_at desc
- Visar read-status

Fält per meddelande:
- Namn
- E-post
- Meddelande
- IP-adress
- Skapad tidstämpel

### Markera som läst
URL: POST `/admin/messages/{message}/read`

Sätter `read = true` för meddelandet.

### Radera meddelande
URL: DELETE `/admin/messages/{message}`

Raderar meddelandet permanent.

## Tips och Tricks

### Screenshot-kvalitet
Screenshots tas med Browsershot i 1920x1080 resolution. För bäst resultat:
- Se till att live-URL:en är tillgänglig och responsiv
- Undvik webbplatser med långsam laddning
- Screenshots sparas som PNG i `storage/app/public/screenshots/`

### Slug-hantering
- Slug genereras automatiskt från titel om du lämnar fältet tomt
- Svenska tecken konverteras (ä→a, ö→o, å→a)
- Slug måste vara unik

### Featured-projekt
Endast featured + published projekt visas på startsidan. Använd `sort_order` för att kontrollera ordningen.

### Offentlig vs Draft
- **Draft**: Endast synlig i admin
- **Published**: Synlig på publika sidan (om featured visas även på startsidan)

## Logga ut

URL: POST `/admin/logout`
