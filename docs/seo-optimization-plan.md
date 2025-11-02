# SEO Optimization Plan: ATDev Portfolio

**Created**: 2025-11-02
**Project**: ATDev - AI-Driven Laravel 12 Portfolio
**Language**: Swedish (svenska)
**Architecture**: Backend-first with Blade components

---

## Executive Summary

This plan transforms ATDev from basic SEO implementation to a comprehensive, Swedish-optimized, search-engine-friendly portfolio platform. The plan addresses:

- Structured data (Schema.org JSON-LD) for enhanced rich snippets
- Dynamic XML sitemap generation
- Optimized robots.txt configuration
- Reusable meta tags component system
- Image optimization (alt texts, lazy loading, responsive images)
- Semantic HTML audit and improvements
- Performance optimizations (resource hints, preloading)
- Swedish language SEO best practices

---

## Current State Assessment

### ✅ What's Working
- Basic meta tags (title, description, keywords)
- Open Graph tags for social sharing
- Twitter Card implementation
- Canonical URLs
- Swedish language declaration (`lang="sv"`)
- Proper viewport configuration
- Some lazy loading on images

### ❌ Critical Gaps
- **No structured data (JSON-LD)** - Missing Person, Organization, WebSite, BreadcrumbList schemas
- **No XML sitemap** - Search engines can't discover all pages efficiently
- **No robots.txt** - Missing crawler directives (admin panel not blocked)
- **Inconsistent alt texts** - Many images lack descriptive Swedish alt attributes
- **No resource hints** - Missing dns-prefetch, preconnect for external resources
- **Meta tags not reusable** - Hardcoded in layout, difficult to customize per page
- **Missing og:locale** - Swedish locale not declared for Facebook
- **No breadcrumbs** - Poor navigation signals for crawlers

---

## Implementation Plan

### Phase 1: Foundation (Week 1) - HIGH PRIORITY

#### Milestone 1.1: Robots.txt Configuration
**Goal**: Control crawler access and provide sitemap location

**Tasks**:
1. Create `/public/robots.txt` with Swedish-friendly configuration
2. Block admin routes (`/admin/*`)
3. Block webhook routes (`/mailgun/*`)
4. Reference sitemap location
5. Set crawl-delay for politeness

**Acceptance Criteria**:
- Admin panel blocked from all crawlers
- Sitemap URL declared
- Verified with Google Search Console

**File**: `/public/robots.txt`
```txt
# ATDev - Robots Configuration
User-agent: *
Disallow: /admin/
Disallow: /mailgun/
Disallow: /audit/
Allow: /
Allow: /projects/
Sitemap: https://atdev.me/sitemap.xml

# Bad bots
User-agent: AhrefsBot
Crawl-delay: 10

User-agent: SemrushBot
Crawl-delay: 10
```

---

#### Milestone 1.2: XML Sitemap Generation
**Goal**: Dynamic sitemap with Swedish content, proper priorities, and change frequencies

**Technical Approach**:
- Controller: `App\Http\Controllers\SitemapController`
- Route: `GET /sitemap.xml`
- Cache: 24 hours (Redis/database cache)
- Priority logic: Home (1.0) > Projects (0.8) > Static pages (0.6)

**Tasks**:
1. Create `SitemapController` with XML generation
2. Include all published projects (slug-based URLs)
3. Include static pages (home, tech-stack, demos, privacy, cookies)
4. Set proper `<lastmod>` from `updated_at` timestamps
5. Set Swedish locale in sitemap (`hreflang="sv"`)
6. Add route to `/web.php`
7. Cache sitemap for 24 hours
8. Add Artisan command `php artisan sitemap:generate` for manual regeneration

**Acceptance Criteria**:
- Valid XML format (test with Google Search Console)
- All published projects included
- Proper priorities and change frequencies
- Swedish locale declared
- Cache invalidates on project publish/unpublish

**Data Contract**:
```php
[
    'pages' => [
        ['loc' => '/', 'priority' => 1.0, 'changefreq' => 'daily'],
        ['loc' => '/tech-stack', 'priority' => 0.6, 'changefreq' => 'weekly'],
        ['loc' => '/demos', 'priority' => 0.6, 'changefreq' => 'weekly'],
        ['loc' => '/privacy', 'priority' => 0.3, 'changefreq' => 'monthly'],
        ['loc' => '/cookies', 'priority' => 0.3, 'changefreq' => 'monthly'],
    ],
    'projects' => Project::published()->with(['slug', 'updated_at'])->get()
]
```

**Files to Create**:
- `/app/Http/Controllers/SitemapController.php`
- `/resources/views/sitemap.blade.php` (XML template)
- `/app/Console/Commands/GenerateSitemap.php`

---

#### Milestone 1.3: Meta Tags Component Refactoring
**Goal**: Reusable, DRY meta tags system with per-page customization

**Technical Approach**:
- Blade Component: `<x-seo-meta />`
- Props: title, description, image, type, noindex, nofollow
- Automatic fallbacks to site defaults
- Swedish locale support

**Tasks**:
1. Create `app/View/Components/SeoMeta.php`
2. Create `resources/views/components/seo-meta.blade.php`
3. Extract meta tags from `layouts/app.blade.php` to component
4. Add Swedish locale (`og:locale="sv_SE"`)
5. Add structured data slot for JSON-LD injection
6. Update all views to use component
7. Add config file `config/seo.php` for defaults

**Acceptance Criteria**:
- Component renders all meta tags (title, description, OG, Twitter)
- Per-page customization works via props
- Swedish locale declared in all pages
- Fallback to site defaults when props not provided

**Component Usage Example**:
```blade
<x-seo-meta
    :title="$project->title . ' - ATDev Portfolio'"
    :description="$project->summary"
    :image="asset('storage/' . $project->cover_image)"
    type="article"
>
    <!-- Structured data slot -->
    <x-slot:structuredData>
        {!! $projectSchema !!}
    </x-slot:structuredData>
</x-seo-meta>
```

**Files to Create**:
- `/app/View/Components/SeoMeta.php`
- `/resources/views/components/seo-meta.blade.php`
- `/config/seo.php`

---

### Phase 2: Structured Data (Week 2) - HIGH PRIORITY

#### Milestone 2.1: Base Structured Data Service
**Goal**: Centralized service for generating Schema.org JSON-LD

**Technical Approach**:
- Service: `App\Services\StructuredDataService`
- Methods: `person()`, `organization()`, `website()`, `breadcrumbList()`, `project()`
- Swedish language support in schemas
- Follows Schema.org best practices

**Tasks**:
1. Create `StructuredDataService` with schema generators
2. Add method `person()` - Andreas Tröls profile
3. Add method `organization()` - ATDev company info
4. Add method `website()` - Search box markup
5. Add method `breadcrumbList()` - Navigation breadcrumbs
6. Add method `project()` - Individual project schema
7. Add Swedish language attributes where applicable
8. Register service in service provider

**Acceptance Criteria**:
- Valid JSON-LD output (test with Google Rich Results Test)
- All methods return proper Schema.org types
- Swedish language fields (`inLanguage: "sv"`)
- Service can be injected into controllers/components

**Files to Create**:
- `/app/Services/StructuredDataService.php`

---

#### Milestone 2.2: Homepage Structured Data
**Goal**: Person + Organization + WebSite schemas on homepage

**Tasks**:
1. Inject `StructuredDataService` into `HomeController`
2. Generate Person schema from `Profile::current()`
3. Generate Organization schema (ATDev company)
4. Generate WebSite schema with SearchAction
5. Pass schemas to view
6. Render JSON-LD in `<x-seo-meta>` structured data slot
7. Add breadcrumbs schema (Home only)

**Acceptance Criteria**:
- Valid Person schema with Swedish bio
- Valid Organization schema with social links
- Valid WebSite schema with search functionality
- Google Rich Results Test passes
- Structured data visible in page source

**Data Contract** (added to `HomeController::index()`):
```php
[
    'profile' => Profile::current(),
    'services' => Collection<Service>,
    'projects' => Collection<Project>,
    'schemas' => [
        'person' => StructuredDataService::person($profile),
        'organization' => StructuredDataService::organization($profile),
        'website' => StructuredDataService::website(),
    ]
]
```

**Schema Example (Person)**:
```json
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Andreas Tröls",
  "jobTitle": "AI-Driven Utvecklare",
  "description": "Utvecklare med 20+ års erfarenhet...",
  "url": "https://atdev.me",
  "image": "https://atdev.me/storage/avatar.webp",
  "sameAs": [
    "https://github.com/andreastroels",
    "https://linkedin.com/in/andreastroels"
  ],
  "knowsAbout": ["Laravel", "AI", "React", "Prompt Engineering"],
  "inLanguage": "sv"
}
```

---

#### Milestone 2.3: Project Page Structured Data
**Goal**: CreativeWork/SoftwareApplication schemas for projects

**Tasks**:
1. Update `ProjectController::show()` to generate project schema
2. Use `CreativeWork` or `SoftwareApplication` type depending on project
3. Include technologies as `keywords`
4. Add breadcrumb navigation schema
5. Include author (Person schema reference)
6. Add Swedish description
7. Include screenshots/images

**Acceptance Criteria**:
- Valid CreativeWork schema for each project
- Breadcrumbs schema (Home > Projects > {Title})
- Technologies listed in keywords
- Author linked to Person schema
- Google Rich Results Test passes

**Schema Example (Project)**:
```json
{
  "@context": "https://schema.org",
  "@type": "CreativeWork",
  "name": "AI-Driven Website Audit",
  "description": "Ett smart verktyg för att analysera webbplatser...",
  "url": "https://atdev.me/projects/ai-driven-website-audit",
  "image": "https://atdev.me/storage/projects/audit-cover.png",
  "author": {
    "@type": "Person",
    "@id": "https://atdev.me/#person"
  },
  "inLanguage": "sv",
  "keywords": ["Laravel", "AI", "Browsershot", "Anthropic Claude"],
  "datePublished": "2024-10-15",
  "dateModified": "2024-11-01"
}
```

---

### Phase 3: Image Optimization (Week 3) - MEDIUM PRIORITY

#### Milestone 3.1: Alt Text Audit and Fixes
**Goal**: All images have descriptive Swedish alt texts

**Tasks**:
1. Audit all Blade templates for `<img>` tags
2. Add descriptive Swedish alt texts to:
   - Hero avatar image
   - Work/about image
   - Project cover images
   - Project gallery images
   - Timeline technology icons
   - Demo component images
3. Update image components to require alt text
4. Add validation in admin panel for image uploads (require alt text)

**Acceptance Criteria**:
- 100% of images have non-empty alt attributes
- Alt texts are descriptive in Swedish
- Decorative images use `alt=""`
- Admin panel enforces alt text input

**Pattern**:
```blade
<!-- Before -->
<img src="{{ $profile->getFirstMediaUrl('avatar', 'optimized') }}">

<!-- After -->
<img
    src="{{ $profile->getFirstMediaUrl('avatar', 'optimized') }}"
    alt="Andreas Tröls - AI-driven utvecklare med 20+ års erfarenhet"
    loading="lazy"
>
```

**Files to Update**:
- `/resources/views/components/home/hero.blade.php`
- `/resources/views/components/home/about.blade.php`
- `/resources/views/components/home/projects.blade.php`
- `/resources/views/components/home/timeline.blade.php`
- `/resources/views/projects/show.blade.php`
- All admin views with images

---

#### Milestone 3.2: Responsive Images (srcset)
**Goal**: Serve appropriately sized images for different devices

**Technical Approach**:
- Use Spatie Media Library's responsive image features
- Generate multiple sizes: 320w, 640w, 1024w, 1920w
- Implement `srcset` and `sizes` attributes
- WebP format for modern browsers, fallback to JPEG

**Tasks**:
1. Update Profile media conversions to generate multiple sizes
2. Update Project image handling to support responsive images
3. Create Blade component `<x-responsive-image />`
4. Implement srcset generation in component
5. Add WebP conversion for all images
6. Update existing image tags to use component

**Acceptance Criteria**:
- Images served in multiple sizes
- WebP format used when supported
- Lazy loading implemented
- Lighthouse score improves for "Properly sized images"

**Component Usage**:
```blade
<x-responsive-image
    :media="$profile->getFirstMedia('avatar')"
    alt="Andreas Tröls - Utvecklare"
    sizes="(max-width: 640px) 320px, (max-width: 1024px) 640px, 1024px"
    class="rounded-full"
/>
```

**Files to Create**:
- `/app/View/Components/ResponsiveImage.php`
- `/resources/views/components/responsive-image.blade.php`

---

#### Milestone 3.3: Image Performance Optimization
**Goal**: Minimize Cumulative Layout Shift (CLS) and improve loading

**Tasks**:
1. Add explicit width/height to all images
2. Implement aspect-ratio CSS for responsive scaling
3. Add `decoding="async"` to non-critical images
4. Add `fetchpriority="high"` to hero images
5. Preload critical images (hero avatar)
6. Add `loading="lazy"` to below-fold images
7. Optimize image compression (85% quality for WebP)

**Acceptance Criteria**:
- CLS score < 0.1 in Lighthouse
- Hero image loads within 1 second
- Below-fold images lazy load
- No layout shift during image loading

**Implementation Example**:
```blade
<!-- Hero image - critical, preloaded -->
<link
    rel="preload"
    as="image"
    href="{{ $profile->getFirstMediaUrl('avatar', 'optimized') }}"
    fetchpriority="high"
>

<img
    src="{{ $profile->getFirstMediaUrl('avatar', 'optimized') }}"
    alt="Andreas Tröls - AI-driven utvecklare"
    width="800"
    height="800"
    fetchpriority="high"
    class="w-64 h-64 rounded-full"
>

<!-- Gallery images - lazy loaded -->
<img
    src="{{ asset('storage/' . $image) }}"
    alt="{{ $project->title }} - Skärmbild {{ $loop->iteration }}"
    width="1200"
    height="800"
    loading="lazy"
    decoding="async"
>
```

---

### Phase 4: Semantic HTML & Accessibility (Week 4) - MEDIUM PRIORITY

#### Milestone 4.1: Semantic HTML Audit
**Goal**: Proper HTML5 semantic structure for better crawling

**Tasks**:
1. Audit current HTML structure in all templates
2. Replace generic `<div>` with semantic tags:
   - `<header>`, `<nav>`, `<main>`, `<article>`, `<section>`, `<aside>`, `<footer>`
3. Ensure proper heading hierarchy (h1 → h2 → h3, no skips)
4. Add ARIA landmarks where needed
5. Add skip-to-content link for accessibility
6. Validate with W3C HTML Validator

**Acceptance Criteria**:
- All pages use semantic HTML5 tags
- Heading hierarchy is correct (no skipped levels)
- One `<h1>` per page
- ARIA landmarks present
- W3C validation passes with no critical errors

**Structure Example**:
```blade
<body>
    <a href="#main-content" class="sr-only focus:not-sr-only">Hoppa till innehåll</a>

    <header role="banner">
        <x-navigation />
    </header>

    <main id="main-content" role="main">
        <article>
            <header>
                <h1>{{ $project->title }}</h1>
            </header>
            <section aria-label="Projektöversikt">
                <!-- Content -->
            </section>
        </article>
    </main>

    <footer role="contentinfo">
        <x-footer />
    </footer>
</body>
```

---

#### Milestone 4.2: Heading Hierarchy Fix
**Goal**: Logical heading structure on all pages

**Audit Results** (to be verified):
- Homepage: h1 (name) → h2 (sections) → h3 (subsections)
- Project pages: h1 (project title) → h2 (sections) → h3 (details)
- Tech Stack: h1 (title) → h2 (categories) → h3 (technologies)

**Tasks**:
1. Map current heading structure across all pages
2. Identify and fix heading level skips
3. Ensure one h1 per page (main title)
4. Use h2 for major sections
5. Use h3 for subsections
6. Update CSS to maintain visual hierarchy

**Acceptance Criteria**:
- No heading level skips (h1 → h3)
- One h1 per page
- Logical content hierarchy
- Visual design maintained

---

#### Milestone 4.3: Breadcrumb Navigation
**Goal**: Visual and structured breadcrumbs for better UX and SEO

**Technical Approach**:
- Blade Component: `<x-breadcrumbs />`
- Dynamic generation based on route
- Schema.org BreadcrumbList integration
- Swedish labels

**Tasks**:
1. Create `Breadcrumbs` Blade component
2. Add breadcrumb rendering logic
3. Integrate with `StructuredDataService::breadcrumbList()`
4. Add to project detail pages
5. Style with Tailwind CSS
6. Add microdata/JSON-LD

**Acceptance Criteria**:
- Breadcrumbs visible on all deep pages
- Clickable navigation works
- BreadcrumbList schema generated
- Google Rich Results Test passes

**Component Usage**:
```blade
<x-breadcrumbs :items="[
    ['label' => 'Hem', 'url' => route('home')],
    ['label' => 'Projekt', 'url' => null],
    ['label' => $project->title, 'url' => null]
]" />
```

**Schema Output**:
```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Hem",
      "item": "https://atdev.me"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Projekt",
      "item": "https://atdev.me/projects"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "AI Website Audit",
      "item": "https://atdev.me/projects/ai-website-audit"
    }
  ]
}
```

---

### Phase 5: Performance Optimization (Week 5) - LOW PRIORITY

#### Milestone 5.1: Resource Hints
**Goal**: Speed up external resource loading

**Tasks**:
1. Add DNS prefetch for external domains:
   - `cdn.jsdelivr.net` (Alpine.js)
   - `cdn.simpleicons.org` (technology icons)
   - Google Fonts (if used)
2. Add preconnect for critical external resources
3. Add preload for critical assets (fonts, hero image)
4. Test with WebPageTest to verify improvements

**Acceptance Criteria**:
- DNS resolution time reduced
- External resources load faster
- Lighthouse performance score improves

**Implementation** (in `<x-seo-meta>` component):
```blade
<!-- DNS Prefetch -->
<link rel="dns-prefetch" href="//cdn.jsdelivr.net">
<link rel="dns-prefetch" href="//cdn.simpleicons.org">

<!-- Preconnect for critical resources -->
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

<!-- Preload critical assets -->
<link rel="preload" as="style" href="{{ Vite::asset('resources/css/app.css') }}">
<link rel="preload" as="script" href="{{ Vite::asset('resources/js/app.js') }}">
@if($heroImage)
    <link rel="preload" as="image" href="{{ $heroImage }}" fetchpriority="high">
@endif
```

---

#### Milestone 5.2: Content Security Policy (CSP)
**Goal**: Security headers for better trust signals

**Tasks**:
1. Define CSP policy in `config/seo.php`
2. Add CSP middleware
3. Allow inline scripts for Alpine.js
4. Allow external resources (CDNs)
5. Test and refine policy

**Acceptance Criteria**:
- CSP header present
- No console errors from CSP violations
- Security headers visible in browser DevTools

**Implementation**:
```php
// config/seo.php
'csp' => [
    'default-src' => "'self'",
    'script-src' => "'self' 'unsafe-inline' https://cdn.jsdelivr.net",
    'style-src' => "'self' 'unsafe-inline'",
    'img-src' => "'self' data: https:",
    'font-src' => "'self' data:",
],
```

---

#### Milestone 5.3: Open Graph Image Generation
**Goal**: Dynamic OG images for better social sharing

**Technical Approach**:
- Generate OG images dynamically using Browsershot
- Template-based design with project info
- Cache generated images
- Fallback to default OG image

**Tasks**:
1. Create OG image template Blade view
2. Create `GenerateOgImage` action
3. Add to project creation/update workflow
4. Store in `public/og-images/{slug}.png`
5. Update `<x-seo-meta>` to use dynamic OG images
6. Add fallback logic

**Acceptance Criteria**:
- Projects have unique OG images
- Images display correctly on Facebook, Twitter, LinkedIn
- Cached to avoid regeneration
- Fallback works when generation fails

**Files to Create**:
- `/app/Actions/GenerateOgImage.php`
- `/resources/views/og-templates/project.blade.php`

---

### Phase 6: Swedish SEO Best Practices (Week 6) - LOW PRIORITY

#### Milestone 6.1: Swedish Keyword Optimization
**Goal**: Target Swedish search terms effectively

**Tasks**:
1. Research Swedish keywords for:
   - AI-utveckling
   - Webbutvecklare Stockholm
   - Laravel-utvecklare Sverige
   - Prompt engineering
2. Update meta descriptions with Swedish keywords
3. Optimize heading tags with Swedish terms
4. Add Swedish synonyms in content
5. Update `keywords` meta tag (low priority, but keep for completeness)

**Target Keywords**:
- Primary: "AI-driven webbutvecklare", "Laravel-utvecklare Sverige"
- Secondary: "prompt engineering expert", "webbutveckling Stockholm"
- Long-tail: "skräddarsydd AI-assistent för företag"

**Acceptance Criteria**:
- Meta descriptions contain target keywords
- Headings use natural Swedish phrasing
- Content reads naturally (not keyword-stuffed)

---

#### Milestone 6.2: hreflang Implementation (Future-Proofing)
**Goal**: Prepare for potential multilingual expansion

**Tasks**:
1. Add `hreflang` tags for Swedish version
2. Add `x-default` fallback
3. Prepare structure for English version (future)

**Implementation**:
```blade
<link rel="alternate" hreflang="sv" href="https://atdev.me/">
<link rel="alternate" hreflang="x-default" href="https://atdev.me/">
```

**Note**: Only implement if English version is planned. Otherwise, skip.

---

#### Milestone 6.3: Local Business Schema (Optional)
**Goal**: If targeting local Stockholm clients, add LocalBusiness schema

**Schema Example**:
```json
{
  "@context": "https://schema.org",
  "@type": "ProfessionalService",
  "name": "ATDev - AI-Driven Utveckling",
  "description": "Webbutvecklare specialiserad på AI-integration",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Stockholm",
    "addressCountry": "SE"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "59.3293",
    "longitude": "18.0686"
  },
  "url": "https://atdev.me",
  "telephone": "+46-XXX-XXX-XXX",
  "priceRange": "$$",
  "areaServed": "Sverige"
}
```

**Note**: Only implement if Andreas wants to emphasize local Stockholm presence.

---

## Technical Architecture

### Folder Structure (New Files)
```
app/
├── Actions/
│   ├── GenerateOgImage.php
│   └── GenerateStructuredData.php
├── Console/Commands/
│   └── GenerateSitemap.php
├── Http/
│   ├── Controllers/
│   │   └── SitemapController.php
│   └── Middleware/
│       └── SecurityHeaders.php
├── Services/
│   └── StructuredDataService.php
└── View/Components/
    ├── Breadcrumbs.php
    ├── ResponsiveImage.php
    └── SeoMeta.php

config/
└── seo.php

public/
└── robots.txt

resources/views/
├── components/
│   ├── breadcrumbs.blade.php
│   ├── responsive-image.blade.php
│   └── seo-meta.blade.php
├── og-templates/
│   └── project.blade.php
└── sitemap.blade.php
```

---

## Testing & Validation Checklist

### Pre-Launch Testing
- [ ] Validate all JSON-LD with Google Rich Results Test
- [ ] Test sitemap.xml in Google Search Console
- [ ] Verify robots.txt with Google's robots.txt Tester
- [ ] Run Lighthouse audit (target: 90+ SEO score)
- [ ] Validate HTML with W3C Validator
- [ ] Test meta tags with Facebook Sharing Debugger
- [ ] Test Twitter Cards with Twitter Card Validator
- [ ] Check mobile-friendliness with Google Mobile-Friendly Test
- [ ] Verify Swedish language detection in search engines

### Performance Metrics
- [ ] Lighthouse SEO score: 95+
- [ ] Lighthouse Performance score: 90+
- [ ] Lighthouse Accessibility score: 95+
- [ ] First Contentful Paint: < 1.5s
- [ ] Largest Contentful Paint: < 2.5s
- [ ] Cumulative Layout Shift: < 0.1
- [ ] Time to Interactive: < 3.5s

### SEO Metrics (Post-Launch)
- [ ] Google Search Console coverage: No errors
- [ ] Index coverage: 100% of published pages
- [ ] Mobile usability: No issues
- [ ] Core Web Vitals: All "Good"
- [ ] Rich results appearing in search (Person, Organization)

---

## Risks & Mitigations

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Structured data errors | High | Medium | Use Google Rich Results Test before deployment |
| Sitemap cache issues | Medium | Low | Implement cache tags, manual invalidation command |
| Image optimization breaks layout | High | Medium | Test responsive images on multiple devices |
| Swedish characters in slugs | Medium | Low | Already handled by `GenerateSlug` action |
| OG image generation fails | Low | Medium | Implement fallback to static OG image |
| CSP blocks Alpine.js | High | Medium | Test CSP thoroughly, use `unsafe-inline` if needed |

---

## Success Metrics

### Immediate (Week 1-2)
- Robots.txt deployed and verified
- Sitemap.xml indexed by Google
- Meta tags component refactored and deployed
- Basic structured data live (Person, Organization, WebSite)

### Short-term (Month 1)
- All images have alt texts
- Structured data on all pages
- Breadcrumbs implemented
- Lighthouse SEO score 95+
- Google Rich Results appearing

### Long-term (3-6 months)
- Organic traffic increase by 50%
- Featured snippets for target keywords
- Improved rankings for "AI-utvecklare Sverige"
- Social sharing CTR increase by 30%
- Backlinks from tech communities

---

## Maintenance Plan

### Daily
- Monitor Google Search Console for errors
- Check sitemap status

### Weekly
- Review new project pages for SEO compliance
- Audit new images for alt texts
- Check structured data validity

### Monthly
- Update sitemap if static pages added
- Review keyword rankings
- Analyze organic traffic trends
- Check Core Web Vitals

### Quarterly
- Full SEO audit with Lighthouse
- Competitor analysis
- Update structured data schemas if Schema.org updates
- Review and update target keywords

---

## Implementation Timeline

| Phase | Duration | Priority | Dependencies |
|-------|----------|----------|--------------|
| Phase 1: Foundation | 5 days | HIGH | None |
| Phase 2: Structured Data | 5 days | HIGH | Phase 1.3 (SeoMeta component) |
| Phase 3: Image Optimization | 7 days | MEDIUM | None (parallel) |
| Phase 4: Semantic HTML | 5 days | MEDIUM | None (parallel) |
| Phase 5: Performance | 3 days | LOW | Phase 1.3, 3.2 |
| Phase 6: Swedish SEO | 3 days | LOW | Phase 2 |

**Total estimated time**: 4-6 weeks (1 developer, part-time)

---

## Code Examples

### SeoMeta Component (Complete)

**File**: `/app/View/Components/SeoMeta.php`
```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SeoMeta extends Component
{
    public string $title;
    public string $description;
    public string $image;
    public string $type;
    public string $url;
    public bool $noindex;
    public bool $nofollow;

    public function __construct(
        ?string $title = null,
        ?string $description = null,
        ?string $image = null,
        string $type = 'website',
        ?string $url = null,
        bool $noindex = false,
        bool $nofollow = false
    ) {
        $this->title = $title ?? config('seo.default_title');
        $this->description = $description ?? config('seo.default_description');
        $this->image = $image ?? asset('images/og-default.jpg');
        $this->type = $type;
        $this->url = $url ?? url()->current();
        $this->noindex = $noindex;
        $this->nofollow = $nofollow;
    }

    public function render()
    {
        return view('components.seo-meta');
    }

    public function robotsContent(): string
    {
        $directives = [];

        if ($this->noindex) {
            $directives[] = 'noindex';
        } else {
            $directives[] = 'index';
        }

        if ($this->nofollow) {
            $directives[] = 'nofollow';
        } else {
            $directives[] = 'follow';
        }

        return implode(', ', $directives);
    }
}
```

**File**: `/resources/views/components/seo-meta.blade.php`
```blade
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="author" content="Andreas Tröls">
<meta name="robots" content="{{ $robotsContent() }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:locale" content="sv_SE">
<meta property="og:site_name" content="ATDev">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $url }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

<!-- Canonical URL -->
<link rel="canonical" href="{{ $url }}">

<!-- Language -->
<link rel="alternate" hreflang="sv" href="{{ $url }}">

<!-- DNS Prefetch & Preconnect -->
<link rel="dns-prefetch" href="//cdn.jsdelivr.net">
<link rel="dns-prefetch" href="//cdn.simpleicons.org">
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

<!-- Structured Data Slot -->
@if(isset($structuredData))
    {{ $structuredData }}
@endif
```

---

### StructuredDataService (Simplified Example)

**File**: `/app/Services/StructuredDataService.php`
```php
<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\Project;

class StructuredDataService
{
    public function person(Profile $profile): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            '@id' => url('/#person'),
            'name' => 'Andreas Tröls',
            'jobTitle' => 'AI-Driven Utvecklare',
            'description' => 'Utvecklare med 20+ års erfarenhet inom webbutveckling och AI-integration',
            'url' => url('/'),
            'image' => $profile->getFirstMediaUrl('avatar', 'optimized'),
            'sameAs' => array_filter([
                $profile->github,
                $profile->linkedin,
                $profile->twitter,
            ]),
            'knowsAbout' => [
                'Laravel',
                'React',
                'AI-integration',
                'Prompt Engineering',
                'Webbutveckling',
            ],
            'inLanguage' => 'sv',
        ];

        return $this->toJsonLd($data);
    }

    public function organization(Profile $profile): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => url('/#organization'),
            'name' => 'ATDev',
            'url' => url('/'),
            'logo' => asset('images/logo.png'),
            'description' => 'AI-driven webbutveckling med 20+ års erfarenhet',
            'founder' => [
                '@id' => url('/#person'),
            ],
            'sameAs' => array_filter([
                $profile->github,
                $profile->linkedin,
            ]),
        ];

        return $this->toJsonLd($data);
    }

    public function website(): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => url('/#website'),
            'url' => url('/'),
            'name' => 'ATDev - AI-Driven Utveckling',
            'description' => 'Portfolio och showcase för AI-driven webbutveckling',
            'inLanguage' => 'sv',
            'publisher' => [
                '@id' => url('/#organization'),
            ],
        ];

        return $this->toJsonLd($data);
    }

    public function project(Project $project): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => $project->title,
            'description' => $project->summary,
            'url' => route('projects.show', $project),
            'image' => asset('storage/' . $project->cover_image),
            'author' => [
                '@id' => url('/#person'),
            ],
            'inLanguage' => 'sv',
            'keywords' => $project->technologies,
            'datePublished' => $project->created_at->toIso8601String(),
            'dateModified' => $project->updated_at->toIso8601String(),
        ];

        if ($project->live_url) {
            $data['url'] = $project->live_url;
        }

        return $this->toJsonLd($data);
    }

    public function breadcrumbList(array $items): string
    {
        $listItems = [];
        foreach ($items as $index => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['label'],
                'item' => $item['url'] ?? null,
            ];
        }

        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];

        return $this->toJsonLd($data);
    }

    protected function toJsonLd(array $data): string
    {
        return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
    }
}
```

---

### SitemapController (Complete)

**File**: `/app/Http/Controllers/SitemapController.php`
```php
<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Cache::remember('sitemap', 60 * 60 * 24, function () {
            return $this->generateSitemap();
        });

        return response($sitemap)
            ->header('Content-Type', 'application/xml');
    }

    protected function generateSitemap(): string
    {
        $staticPages = [
            ['loc' => url('/'), 'priority' => 1.0, 'changefreq' => 'daily', 'lastmod' => now()],
            ['loc' => route('tech-stack'), 'priority' => 0.6, 'changefreq' => 'weekly', 'lastmod' => now()],
            ['loc' => route('demos'), 'priority' => 0.6, 'changefreq' => 'weekly', 'lastmod' => now()],
            ['loc' => route('gdpr.privacy'), 'priority' => 0.3, 'changefreq' => 'monthly', 'lastmod' => now()],
            ['loc' => route('gdpr.cookies'), 'priority' => 0.3, 'changefreq' => 'monthly', 'lastmod' => now()],
        ];

        $projects = Project::published()
            ->select(['slug', 'updated_at'])
            ->get()
            ->map(fn($project) => [
                'loc' => route('projects.show', $project),
                'priority' => 0.8,
                'changefreq' => 'monthly',
                'lastmod' => $project->updated_at,
            ]);

        $urls = collect($staticPages)->merge($projects);

        return view('sitemap', compact('urls'))->render();
    }
}
```

**File**: `/resources/views/sitemap.blade.php`
```blade
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach($urls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>
        <lastmod>{{ $url['lastmod']->toIso8601String() }}</lastmod>
        <changefreq>{{ $url['changefreq'] }}</changefreq>
        <priority>{{ $url['priority'] }}</priority>
        <xhtml:link rel="alternate" hreflang="sv" href="{{ $url['loc'] }}" />
    </url>
@endforeach
</urlset>
```

**Route** (add to `/routes/web.php`):
```php
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
```

---

### Config File for SEO Defaults

**File**: `/config/seo.php`
```php
<?php

return [
    'default_title' => 'ATDev - AI-Driven Utveckling | 20+ Års Erfarenhet',

    'default_description' => 'Utvecklare med 20+ års erfarenhet kombinerar AI och automation för att leverera högkvalitativa webbapplikationer till en bråkdel av priset. Specialist på Laravel, React och AI-integration.',

    'default_keywords' => 'webbutveckling, AI-utveckling, Laravel, React, prompt engineering, AI-expert, Andreas Tröls, ATDev',

    'default_image' => '/images/og-default.jpg',

    'site_name' => 'ATDev',

    'author' => 'Andreas Tröls',

    'locale' => 'sv_SE',

    'twitter_handle' => '@atdev', // If applicable

    'organization' => [
        'name' => 'ATDev',
        'url' => 'https://atdev.me',
        'logo' => '/images/logo.png',
    ],

    'person' => [
        'name' => 'Andreas Tröls',
        'job_title' => 'AI-Driven Utvecklare',
    ],
];
```

---

## Post-Implementation Checklist

### Week 1 (Foundation)
- [ ] Deploy robots.txt
- [ ] Deploy sitemap.xml
- [ ] Refactor meta tags to SeoMeta component
- [ ] Submit sitemap to Google Search Console
- [ ] Submit sitemap to Bing Webmaster Tools

### Week 2 (Structured Data)
- [ ] Deploy StructuredDataService
- [ ] Add Person + Organization + WebSite schemas to homepage
- [ ] Add Project schema to project pages
- [ ] Validate with Google Rich Results Test
- [ ] Monitor Google Search Console for structured data errors

### Week 3 (Images)
- [ ] Audit and fix all alt texts
- [ ] Implement responsive images
- [ ] Add image performance optimizations
- [ ] Test on mobile devices
- [ ] Run Lighthouse audit

### Week 4 (Semantic HTML)
- [ ] Audit HTML structure
- [ ] Fix heading hierarchy
- [ ] Add breadcrumbs
- [ ] Validate with W3C
- [ ] Test accessibility with screen reader

### Week 5 (Performance)
- [ ] Add resource hints
- [ ] Implement CSP headers
- [ ] Generate dynamic OG images
- [ ] Test with WebPageTest
- [ ] Optimize Core Web Vitals

### Week 6 (Swedish SEO)
- [ ] Research Swedish keywords
- [ ] Update content with keywords
- [ ] Add hreflang tags (if needed)
- [ ] Consider LocalBusiness schema (if applicable)
- [ ] Final full-site SEO audit

---

## Google Search Console Setup

1. **Verify ownership**:
   - Add HTML meta tag verification
   - Or use Google Analytics property
   - Or upload verification file

2. **Submit sitemap**:
   ```
   https://atdev.me/sitemap.xml
   ```

3. **Enable all reports**:
   - Performance
   - Index Coverage
   - Mobile Usability
   - Core Web Vitals
   - Rich Results

4. **Set up alerts**:
   - Index coverage issues
   - Manual actions
   - Security issues

---

## Resources & References

### SEO Tools
- [Google Search Console](https://search.google.com/search-console)
- [Google Rich Results Test](https://search.google.com/test/rich-results)
- [Google Mobile-Friendly Test](https://search.google.com/test/mobile-friendly)
- [Lighthouse CI](https://github.com/GoogleChrome/lighthouse-ci)
- [Schema Markup Validator](https://validator.schema.org/)
- [Facebook Sharing Debugger](https://developers.facebook.com/tools/debug/)
- [Twitter Card Validator](https://cards-dev.twitter.com/validator)

### Documentation
- [Schema.org Documentation](https://schema.org/docs/documents.html)
- [Google Search Central](https://developers.google.com/search/docs)
- [Open Graph Protocol](https://ogp.me/)
- [Laravel SEO Best Practices](https://laravel.com/docs/routing#seo)

### Swedish SEO Resources
- [Google Trends Sweden](https://trends.google.se/trends/)
- [Swedish Keyword Research Tools](https://www.google.se/intl/sv/business/keyword-planner/)

---

## Conclusion

This comprehensive plan addresses all critical SEO gaps in the ATDev portfolio platform while respecting the backend-first architecture and Swedish language requirements. Implementation is prioritized by impact, with foundation and structured data taking precedence.

**Key Success Factors**:
1. Structured data for rich snippets in Swedish search results
2. Dynamic sitemap for efficient crawling
3. Image optimization for better performance and accessibility
4. Semantic HTML for improved content understanding
5. Swedish keyword targeting for relevant organic traffic

**Expected Outcomes** (6 months post-implementation):
- 50%+ increase in organic traffic
- Featured snippets for target keywords
- 95+ Lighthouse SEO score
- Rich results (Person, Organization) in SERPs
- Improved Core Web Vitals (all green)

---

**Document Version**: 1.0
**Last Updated**: 2025-11-02
**Next Review**: After Phase 1 completion
