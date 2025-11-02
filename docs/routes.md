# ATDev - Routes & Datakontrakt

## Publika Routes

### GET `/`
**Controller**: `HomeController@index`  
**View**: `home`

**Datakontrakt**:
```php
[
    'profile' => Profile|null,  // Singleton profile
    'projects' => Collection<Project>  // Featured, published projects (sorted by sort_order, created_at desc)
]
```

**Profile-fält**: name, title, bio, avatar, hero_image, email, phone, github, linkedin, twitter

**Project-fält** (listvy): id, slug, title, summary, cover_image, technologies (array), featured

---

### GET `/tech-stack`
**Controller**: `TechStackController@index`
**View**: `tech-stack`

**Datakontrakt**:
```php
[
    'technologies' => array  // Tech stack data for visualization
]
```

**Beskrivning**: Visar interaktiv D3.js-visualisering av teknologier och deras relationer.

---

### GET `/demos`
**Controller**: `DemosController@index`
**View**: `demos`

**Datakontrakt**:
```php
[
    'demos' => array  // Array of available interactive demonstrations (empty initially)
]
```

**Beskrivning**: Showcase-sida med full-page sections för interaktiva demos. Placeholder-innehåll tills specifika demos implementeras.

**Features**:
- Apple-style full-page sections
- Smooth scroll navigation
- Glassmorphism design
- Responsive layout
- Fully functional demos (när implementerade)

**CTA på home**: Dedikerad gradient CTA-section mellan Projects och Website Audit.

---

### GET `/projects/{slug}`
**Controller**: `ProjectController@show`  
**View**: `projects.show`

**Datakontrakt**:
```php
[
    'project' => Project  // Full project object
]
```

**Project-fält** (detaljvy): Alla fält inkl. description, gallery (array), live_url, repo_url, screenshot_path, status, created_at

**Observera**: Endast `published` projekt visas för publiken (404 för draft).

---

### POST `/contact`
**Controller**: `ContactController@store`  
**Middleware**: `throttle:contact` (5/minut, 20/dag)

**Input**:
```php
[
    'name' => 'string|required|max:255',
    'email' => 'email|required|max:255',
    'message' => 'string|required|min:10|max:5000',
    'website' => 'nullable|max:0',  // Honeypot - måste vara tom
]
```

**Response**: Redirect tillbaka med success-meddelande

**Sidoeffekter**:
- Sparar ContactMessage i databas
- Dispatchar SendContactEmail job (skickar till andreas@atdev.me)

---

## Admin Routes

Alla admin-routes kräver autentisering (`auth` middleware) och har prefix `/admin`.

### GET `/admin/login`
**Fortify**: Login-formulär

---

### POST `/admin/login`
**Fortify**: Autentisering (max 5 försök/minut)

---

### POST `/admin/logout`
**Fortify**: Logga ut

---

### GET `/admin`
**Controller**: `Admin\DashboardController@index`  
**View**: `admin.dashboard`

**Datakontrakt**:
```php
[
    'projectsCount' => int,
    'unreadMessages' => int,
    'recentProjects' => Collection<Project>,  // Top 5, sorted by created_at desc
    'recentMessages' => Collection<ContactMessage>  // Top 5, sorted by created_at desc
]
```

---

### GET `/admin/projects`
**Controller**: `Admin\ProjectController@index`  
**View**: `admin.projects.index`

**Datakontrakt**:
```php
[
    'projects' => LengthAwarePaginator<Project>  // Paginated (20/page), sorted by sort_order, created_at desc
]
```

---

### GET `/admin/projects/create`
**Controller**: `Admin\ProjectController@create`  
**View**: `admin.projects.create`

**Datakontrakt**:
```php
[
    'statuses' => array  // ProjectStatus enum cases
]
```

---

### POST `/admin/projects`
**Controller**: `Admin\ProjectController@store`

**Input**: Se `ProjectRequest` validering

**Response**: Redirect till index med success-meddelande

**Sidoeffekter**: Dispatchar screenshot job om `live_url` finns

---

### GET `/admin/projects/{project}/edit`
**Controller**: `Admin\ProjectController@edit`  
**View**: `admin.projects.edit`

**Datakontrakt**:
```php
[
    'project' => Project,
    'statuses' => array  // ProjectStatus enum cases
]
```

---

### PUT `/admin/projects/{project}`
**Controller**: `Admin\ProjectController@update`

**Input**: Se `ProjectRequest` validering

**Response**: Redirect till index med success-meddelande

**Sidoeffekter**: Dispatchar screenshot job om `live_url` ändrats

---

### DELETE `/admin/projects/{project}`
**Controller**: `Admin\ProjectController@destroy`

**Response**: Redirect till index med success-meddelande

---

### POST `/admin/projects/{project}/screenshot`
**Controller**: `Admin\ProjectController@screenshot`

**Response**: Redirect tillbaka med success/error-meddelande

**Sidoeffekter**: Dispatchar screenshot job manuellt

---

### GET `/admin/profile`
**Controller**: `Admin\ProfileController@edit`  
**View**: `admin.profile.edit`

**Datakontrakt**:
```php
[
    'profile' => Profile|new Profile  // Current profile eller tom instans
]
```

---

### PUT `/admin/profile`
**Controller**: `Admin\ProfileController@update`

**Input**: Se `ProfileRequest` validering

**Response**: Redirect tillbaka med success-meddelande

**Sidoeffekter**: Skapar/uppdaterar singleton Profile

---

### GET `/admin/messages`
**Controller**: `Admin\MessageController@index`  
**View**: `admin.messages.index`

**Datakontrakt**:
```php
[
    'messages' => LengthAwarePaginator<ContactMessage>  // Paginated (20/page), sorted by created_at desc
]
```

---

### POST `/admin/messages/{message}/read`
**Controller**: `Admin\MessageController@markAsRead`

**Response**: Redirect tillbaka med success-meddelande

---

### DELETE `/admin/messages/{message}`
**Controller**: `Admin\MessageController@destroy`

**Response**: Redirect till index med success-meddelande

---

## Validering

### ContactRequest
Se `app/Http/Requests/ContactRequest.php` för fullständiga regler och meddelanden (på svenska).

### ProfileRequest
Se `app/Http/Requests/ProfileRequest.php` för validering av profilfält.

### ProjectRequest
Se `app/Http/Requests/ProjectRequest.php` för validering av projektfält, inkl. unique slug-validering.
