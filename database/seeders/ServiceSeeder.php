<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Nya Hemsidor',
                'slug' => 'nya-hemsidor',
                'description' => 'Skräddarsydda webbplatser och webbapplikationer byggda med modern teknologi. Från enkla portfolios till avancerade e-handelsplattformar.',
                'icon' => 'code',
                'features' => [
                    'Responsiv design för alla enheter',
                    'SEO-optimerad struktur',
                    'Modern tech stack (Laravel, React, Vue)',
                    'CMS-integration för enkel innehållshantering',
                    'Säkerhet och prestanda i fokus',
                    'Utbildning och dokumentation',
                ],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Lösningar & Moduler',
                'slug' => 'losningar-moduler',
                'description' => 'Anpassade lösningar, nya moduler och uppgraderingar för befintliga system. Integration med tredjepartstjänster och API-utveckling.',
                'icon' => 'puzzle-piece',
                'features' => [
                    'Custom modulutveckling',
                    'API-integration (betalningar, CRM, etc)',
                    'Uppgradering av äldre system',
                    'Prestandaoptimering',
                    'Säkerhetsförbättringar',
                    'Skalbarhetslösningar',
                ],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Underhåll & Support',
                'slug' => 'underhall-support',
                'description' => 'Kontinuerligt underhåll, bugfixar, säkerhetsuppdateringar och teknisk support för att hålla din webbplats säker och uppdaterad.',
                'icon' => 'wrench',
                'features' => [
                    'Regelbundna säkerhetsuppdateringar',
                    'Prestandaövervakning',
                    'Backup och katastrofåterställning',
                    'Bugfixar och felsökning',
                    'Teknisk support via e-post/telefon',
                    'Månadsrapporter och analys',
                ],
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
