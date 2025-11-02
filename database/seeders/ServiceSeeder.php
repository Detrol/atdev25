<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Idempotent seeder - safe to run multiple times.
     * Uses updateOrCreate to refresh services on each deployment.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Webbutveckling från Grunden',
                'slug' => 'webbutveckling-fran-grunden',
                'description' => 'Skräddarsydda webbplatser och webbapplikationer byggda med modern teknologi. Från enkla landningssidor till avancerade e-handelsplattformar och SaaS-lösningar.',
                'icon' => 'code',
                'features' => [
                    'Responsiv design för alla enheter',
                    'SEO-optimerad struktur från start',
                    'Modern tech stack (Laravel, React, Vue, Alpine.js)',
                    'Progressive Web App (PWA) möjlighet',
                    'CMS-integration för enkel innehållshantering',
                    'Säkerhet och prestanda i fokus',
                    'Fullständig dokumentation och utbildning',
                ],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Mobilapputveckling',
                'slug' => 'mobilapputveckling',
                'description' => 'Native och hybrid mobilappar för iOS och Android. Allt från MVP-prototyper till fullskaliga applikationer med backend-integration.',
                'icon' => 'rocket',
                'features' => [
                    'Native iOS (Swift) och Android (Kotlin)',
                    'Hybrid utveckling (React Native, Flutter)',
                    'API-integration med befintliga system',
                    'Push-notifikationer och realtidsfunktioner',
                    'App Store och Google Play publicering',
                    'Offline-funktionalitet',
                    'Analytics och användarspårning',
                ],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Buggfix och Felsökning',
                'slug' => 'buggfix-felsokning',
                'description' => 'Snabb och effektiv felsökning av webbplatser och applikationer. Identifiering och åtgärdande av buggar, prestandaproblem och säkerhetsbrister.',
                'icon' => 'wrench',
                'features' => [
                    'Akut problemlösning inom 24 timmar',
                    'Detaljerad rotorsaksanalys',
                    'Korrigering av kritiska säkerhetsbrister',
                    'Prestandaoptimering och flaskhalsborttagning',
                    'Cross-browser och cross-device testning',
                    'Regression testing efter fix',
                    'Dokumentation av lösningar',
                ],
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Prestandaoptimering',
                'slug' => 'prestandaoptimering',
                'description' => 'Gör din webbplats blixtsnabb. Optimering av laddningstider, databasfrågor, caching-strategier och frontend-prestanda för bättre användarupplevelse och SEO.',
                'icon' => 'rocket',
                'features' => [
                    'Core Web Vitals optimering (LCP, FID, CLS)',
                    'Databasoptimering och query-tuning',
                    'Redis/Memcached caching implementation',
                    'CDN-konfiguration och asset-optimering',
                    'Lazy loading och code splitting',
                    'Server-side och client-side optimering',
                    'Kontinuerlig prestandaövervakning',
                ],
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'API-utveckling och Integration',
                'slug' => 'api-utveckling-integration',
                'description' => 'RESTful och GraphQL API-utveckling. Integration med tredjepartstjänster som betalningslösningar, CRM-system, e-post, och sociala medier.',
                'icon' => 'puzzle-piece',
                'features' => [
                    'RESTful API design och implementation',
                    'GraphQL endpoints för flexibel data-hämtning',
                    'API-dokumentation (OpenAPI/Swagger)',
                    'Autentisering och auktorisering (OAuth2, JWT)',
                    'Rate limiting och säkerhetsåtgärder',
                    'Webhooks och event-driven arkitektur',
                    'Integration: Stripe, Klarna, Mailgun, Twilio m.fl.',
                ],
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Säkerhet och Compliance',
                'slug' => 'sakerhet-compliance',
                'description' => 'Säkerhetsanalys, penetrationstestning och implementering av säkerhetsåtgärder. GDPR-anpassning, SSL-certifikat, och säker datahantering.',
                'icon' => 'wrench',
                'features' => [
                    'OWASP Top 10 säkerhetsanalys',
                    'Penetrationstestning och sårbarhetsscanning',
                    'GDPR-compliance och cookie-hantering',
                    'SSL/TLS konfiguration och HTTPS-migration',
                    'Säker autentisering (2FA, passwordless)',
                    'Backup-strategier och disaster recovery',
                    'Säkerhetsuppdateringar och patch-management',
                ],
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Underhåll och Support',
                'slug' => 'underhall-support',
                'description' => 'Kontinuerligt underhåll, proaktiv övervakning och teknisk support. Håll din webbplats säker, uppdaterad och fungerande optimalt.',
                'icon' => 'wrench',
                'features' => [
                    'Regelbundna säkerhetsuppdateringar',
                    '24/7 uptime-övervakning med alerting',
                    'Automatiska backups med testade återställningar',
                    'Teknisk support via e-post och telefon',
                    'Månadsrapporter med analytics och insikter',
                    'Proaktiv prestandaövervakning',
                    'SLA-baserade supportavtal',
                ],
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'Modernisering och Uppgradering',
                'slug' => 'modernisering-uppgradering',
                'description' => 'Modernisera äldre webbplatser och system. Uppgradering till nya versioner av ramverk, migration till molnplattformar, och implementation av modern arkitektur.',
                'icon' => 'code',
                'features' => [
                    'Legacy-system modernisering',
                    'Framework-uppgraderingar (Laravel, WordPress, etc)',
                    'Migration till cloud (AWS, DigitalOcean, Hetzner)',
                    'Containerisering med Docker',
                    'CI/CD pipeline implementation',
                    'Refactoring för bättre maintainability',
                    'Gradvis migration utan driftavbrott',
                ],
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($services as $serviceData) {
            // Idempotent: Updates existing or creates new based on slug
            Service::updateOrCreate(
                ['slug' => $serviceData['slug']],
                $serviceData
            );
        }
    }
}
