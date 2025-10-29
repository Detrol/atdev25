# ATDev - Datamodeller

## Database Schema

### users
Admin-användare för autentisering.

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| name | string | Användarens namn |
| email | string (unique) | E-postadress |
| password | string | Hashat lösenord |
| remember_token | string nullable | Remember-token |
| email_verified_at | timestamp nullable | E-postverifiering |
| created_at | timestamp | |
| updated_at | timestamp | |

**Seeder**: AdminUserSeeder skapar `admin@atdev.me` / `password`

---

### profiles
Singleton-tabell för profilinformation (endast 1 rad).

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| name | string | Fullständigt namn |
| title | string | Yrkestitel |
| bio | text | Om mig-text |
| avatar | string nullable | URL till profilbild |
| hero_image | string nullable | URL till hero-bild |
| email | string nullable | Publik e-post |
| phone | string nullable | Telefonnummer |
| github | string nullable | GitHub-länk |
| linkedin | string nullable | LinkedIn-länk |
| twitter | string nullable | Twitter/X-länk |
| created_at | timestamp | |
| updated_at | timestamp | |

**Helper**: `Profile::current()` returnerar singleton-instansen.

---

### projects
Portfolio-projekt.

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| slug | string (unique, indexed) | URL-slug |
| title | string | Projekttitel |
| summary | text | Kort sammanfattning |
| description | longText | Fullständig beskrivning |
| cover_image | string nullable | URL till omslagsbild |
| gallery | json nullable | Array med bild-URL:er |
| live_url | string nullable | Länk till live-version |
| repo_url | string nullable | Länk till repository |
| technologies | json nullable | Array med teknologier |
| screenshot_path | string nullable | Relativ sökväg till screenshot |
| screenshot_taken_at | timestamp nullable | Tidstämpel för screenshot |
| status | enum (indexed) | 'draft' \| 'published' |
| featured | boolean (indexed) | Visas på startsidan |
| sort_order | integer (indexed) | Sorteringsordning (0 = högst) |
| created_at | timestamp | |
| updated_at | timestamp | |

**Scopes**:
- `published()`: WHERE status = 'published'
- `featured()`: WHERE featured = true

**Events**:
- creating/updating: Auto-genererar slug från title om slug är tom

**Route-binding**: Använder `slug` som route key

---

### contact_messages
Inkommande kontaktmeddelanden.

| Kolumn | Typ | Beskrivning |
|--------|-----|-------------|
| id | bigint unsigned | Primary key |
| name | string | Avsändarens namn |
| email | string | Avsändarens e-post |
| message | text | Meddelandet |
| ip_address | ip nullable | IP-adress |
| user_agent | string nullable | User agent |
| read | boolean (indexed, default false) | Läst-status |
| created_at | timestamp (indexed) | |
| updated_at | timestamp | |

**Scopes**:
- `unread()`: WHERE read = false

**Methods**:
- `markAsRead()`: Sätter read = true

---

## Enums

### ProjectStatus
`App\Enums\ProjectStatus`

```php
enum ProjectStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
}
```

---

## Relationer

Inga relationer mellan modellerna i nuvarande implementation (enkelhet först). Om behov uppstår kan relationer läggas till senare:
- Project -> User (created_by, kan läggas till vid flera admins)
- Project -> Tags (many-to-many, kan läggas till för filtrering)

---

## Index och prestanda

**Befintliga index**:
- `projects.slug` (unique)
- `projects.status`
- `projects.featured`
- `projects.sort_order`
- `contact_messages.read`
- `contact_messages.created_at`

**Queries som optimeras**:
- Hämta featured published projects (startsida)
- Hitta project via slug
- Sortera projekt efter sort_order + created_at
- Filtrera olästa meddelanden

---

## Factories och Seeders

### AdminUserSeeder
Skapar admin@atdev.me / password

### ProfileSeeder
Skapar singleton-profil med ATDev-information

### ProjectSeeder
Skapar 3 demo-projekt:
1. E-handelsplattform för lokala producenter
2. CRM-system för fastighetsmäklare
3. Bokningssystem för gym

Alla skapade projekt är `published` och `featured`.
