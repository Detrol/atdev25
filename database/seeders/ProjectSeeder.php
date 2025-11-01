<?php

namespace Database\Seeders;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'E-handelsplattform för lokala producenter',
                'slug' => 'ehandel-lokala-producenter',
                'summary' => 'En komplett e-handelsplattform med betalintegration, lagerhantering och responsiv design.',
                'description' => "Detta projekt involverade utvecklingen av en fullskalig e-handelsplattform för små och medelstora lokala livsmedelsproducenter. Plattformen inkluderar produkthantering, ordersystem, Stripe-integration för betalningar samt ett responsivt gränssnitt byggt med Tailwind CSS.\n\nVi implementerade även ett avancerat lagerhanteringssystem som automatiskt uppdaterar lagersaldo och notifierar producenter vid låga nivåer.",
                'technologies' => ['Laravel', 'Vue.js', 'Tailwind CSS', 'Stripe', 'MySQL'],
                'status' => ProjectStatus::PUBLISHED,
                'featured' => true,
                'sort_order' => 10,
            ],
            [
                'title' => 'CRM-system för fastighetsmäklare',
                'slug' => 'crm-fastighetsmaklare',
                'summary' => 'Ett sk anpassat CRM-system med automatiserad leadhantering och integrationer med externa API:er.',
                'description' => "Detta CRM-system utvecklades specifikt för en fastighetsmäklarbyrå med behov av att hantera leads, visningar och affärer på ett effektivt sätt.\n\nSystemet integrerar med Hemnet API för automatisk import av objekt, har en avancerad kalenderfunktion för visningar samt automatiska påminnelser via e-post och SMS.",
                'technologies' => ['Laravel', 'Livewire', 'Alpine.js', 'PostgreSQL', 'Redis'],
                'status' => ProjectStatus::PUBLISHED,
                'featured' => true,
                'sort_order' => 20,
            ],
            [
                'title' => 'Bokningssystem för gym och träningsanläggningar',
                'slug' => 'bokningssystem-gym',
                'summary' => 'Ett flexibelt bokningssystem med medlemshantering, klassschema och digitala passerkort.',
                'description' => "Ett modernt bokningssystem som gör det enkelt för gymmedlemmar att boka träningstider, grupppass och personlig träning.\n\nSystemet inkluderar medlemsportal, QR-baserade digitala passerkort, realtidsuppdateringar av lediga platser samt integration med Swish för betalningar.",
                'technologies' => ['Laravel', 'React', 'TailwindCSS', 'Pusher', 'Swish API'],
                'status' => ProjectStatus::PUBLISHED,
                'featured' => true,
                'sort_order' => 30,
            ],
        ];

        foreach ($projects as $projectData) {
            Project::firstOrCreate(
                ['slug' => $projectData['slug']],
                $projectData
            );
        }

        $this->command->info(count($projects).' projects created');
    }
}
