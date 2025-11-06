<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        // FAQ 1
        Faq::updateOrCreate(
            ['sort_order' => 1], // Match på sort_order
            [
                'question' => 'Använder du WordPress eller andra CMS?',
                'answer' => file_get_contents(__DIR__ . '/faq_content/faq_1.html'),
                'tags' => ['wordpress', 'cms', 'laravel', 'custom'],
                'active' => true,
                'show_in_ai_chat' => true,
                'show_in_price_calculator' => false,
                'show_in_public_faq' => true,
            ]
        );

        // FAQ 2
        Faq::updateOrCreate(
            ['sort_order' => 2],
            [
                'question' => 'Vilka språk och ramverk kan du arbeta med?',
                'answer' => file_get_contents(__DIR__ . '/faq_content/faq_2.html'),
                'tags' => ['laravel', 'php', 'vue', 'react', 'teknologi', 'ramverk'],
                'active' => true,
                'show_in_ai_chat' => true,
                'show_in_price_calculator' => false,
                'show_in_public_faq' => true,
            ]
        );

        // FAQ 3
        Faq::updateOrCreate(
            ['sort_order' => 3],
            [
                'question' => 'Hur får jag bästa prisuppskattning?',
                'answer' => file_get_contents(__DIR__ . '/faq_content/faq_3.html'),
                'tags' => ['priser', 'kalkylator', 'offert', 'estimering'],
                'active' => true,
                'show_in_ai_chat' => true,
                'show_in_price_calculator' => true,
                'show_in_public_faq' => true,
            ]
        );

        // FAQ 4
        Faq::updateOrCreate(
            ['sort_order' => 4],
            [
                'question' => 'Kan du hjälpa mig med SEO och GDPR-anpassning?',
                'answer' => file_get_contents(__DIR__ . '/faq_content/faq_4.html'),
                'tags' => ['seo', 'gdpr', 'säkerhet', 'integritet'],
                'active' => true,
                'show_in_ai_chat' => true,
                'show_in_price_calculator' => false,
                'show_in_public_faq' => true,
            ]
        );

        // FAQ 5
        Faq::updateOrCreate(
            ['sort_order' => 5],
            [
                'question' => 'Ingår design och logotyp?',
                'answer' => file_get_contents(__DIR__ . '/faq_content/faq_5.html'),
                'tags' => ['design', 'logotyp', 'grafisk profil', 'ai'],
                'active' => true,
                'show_in_ai_chat' => true,
                'show_in_price_calculator' => true,
                'show_in_public_faq' => true,
            ]
        );

        // FAQ 6
        Faq::updateOrCreate(
            ['sort_order' => 6],
            [
                'question' => 'Kan jag själv redigera innehållet efteråt?',
                'answer' => file_get_contents(__DIR__ . '/faq_content/faq_6.html'),
                'tags' => ['admin', 'cms', 'redigering', 'innehållshantering'],
                'active' => true,
                'show_in_ai_chat' => true,
                'show_in_price_calculator' => true,
                'show_in_public_faq' => true,
            ]
        );

        // FAQ 7
        Faq::updateOrCreate(
            ['sort_order' => 7],
            [
                'question' => 'Hjälper du med hosting och domän?',
                'answer' => file_get_contents(__DIR__ . '/faq_content/faq_7.html'),
                'tags' => ['hosting', 'domän', 'server', 'vps', 'support'],
                'active' => true,
                'show_in_ai_chat' => true,
                'show_in_price_calculator' => false,
                'show_in_public_faq' => true,
            ]
        );

        // FAQ 8
        Faq::updateOrCreate(
            ['sort_order' => 8],
            [
                'question' => 'Hur går betalningen till?',
                'answer' => file_get_contents(__DIR__ . '/faq_content/faq_8.html'),
                'tags' => ['betalning', 'priser', 'faktura', 'villkor'],
                'active' => true,
                'show_in_ai_chat' => true,
                'show_in_price_calculator' => true,
                'show_in_public_faq' => true,
            ]
        );

        // FAQ 9
        Faq::updateOrCreate(
            ['sort_order' => 9],
            [
                'question' => 'Vad behöver jag förbereda innan vi startar?',
                'answer' => file_get_contents(__DIR__ . '/faq_content/faq_9.html'),
                'tags' => ['förberedelse', 'start', 'process', 'planering'],
                'active' => true,
                'show_in_ai_chat' => true,
                'show_in_price_calculator' => false,
                'show_in_public_faq' => true,
            ]
        );

        // FAQ 10
        Faq::updateOrCreate(
            ['sort_order' => 10],
            [
                'question' => 'Ingår framtida support och uppdateringar?',
                'answer' => file_get_contents(__DIR__ . '/faq_content/faq_10.html'),
                'tags' => ['support', 'uppdateringar', 'underhåll', 'garanti'],
                'active' => true,
                'show_in_ai_chat' => true,
                'show_in_price_calculator' => false,
                'show_in_public_faq' => true,
            ]
        );
    }
}
