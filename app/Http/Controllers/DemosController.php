<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DemosController extends Controller
{
    /**
     * Display the interactive demos showcase page.
     *
     * @return View
     * Data: ['demos' => array]
     * Demos: Array of available interactive demonstrations (empty for now, to be populated later)
     */
    public function index(): View
    {
        return view('demos', [
            'demos' => [
                'product_viewer' => [
                    'enabled' => true,
                    'products' => [
                        [
                            'id' => 1,
                            'name' => 'Modern Fåtölj',
                            'description' => 'Skandinavisk design med mjuka kurvor. Perfekt för moderna hem och kontor.',
                            'category' => 'Möbler',
                            'model' => '/models/armchair.glb',
                            'poster' => '/images/products/armchair.jpg',
                            'useCases' => ['Möbelbutiker', 'Inredningsdesigners', 'Homestyling'],
                            'dimensions' => '80cm × 85cm × 90cm',
                            'arScale' => '1.0',
                        ],
                        [
                            'id' => 2,
                            'name' => 'Bordslampa',
                            'description' => 'Minimalistisk bordslampa i mässing och glas. Ger varm, ambient belysning.',
                            'category' => 'Belysning',
                            'model' => '/models/lamp.glb',
                            'poster' => '/images/products/lamp.jpg',
                            'useCases' => ['Belysningsbutiker', 'Heminredning', 'Designbutiker'],
                            'dimensions' => '25cm × 25cm × 45cm',
                            'arScale' => '0.8',
                        ],
                        [
                            'id' => 3,
                            'name' => 'Dekorativ Vas',
                            'description' => 'Handgjord keramikvas med organiska former. En unik konversationsstartare.',
                            'category' => 'Heminredning',
                            'model' => '/models/vase.glb',
                            'poster' => '/images/products/vase.jpg',
                            'useCases' => ['Heminredning', 'Presentbutiker', 'Konstgallerier'],
                            'dimensions' => '20cm × 20cm × 35cm',
                            'arScale' => '0.6',
                        ],
                        [
                            'id' => 4,
                            'name' => 'Abstrakt Skulptur',
                            'description' => 'Modernistisk skulptur i polerad metall. Statement-piece för moderna rum.',
                            'category' => 'Konst',
                            'model' => '/models/sculpture.glb',
                            'poster' => '/images/products/sculpture.jpg',
                            'useCases' => ['Konstgallerier', 'Lyxbutiker', 'Företagskonst'],
                            'dimensions' => '30cm × 30cm × 50cm',
                            'arScale' => '0.7',
                        ],
                    ],
                ],
                'before_after_slider' => [
                    'enabled' => true,
                    'examples' => [
                        [
                            'id' => 1,
                            'title' => 'Kök & Inredning',
                            'category' => 'Hem & Renovering',
                            'description' => 'Visa transformationer från grå vardaglighet till färgglad modernitet. Perfekt för att demonstrera renoveringsprojekt och inspirera kunder.',
                            'beforeImage' => '/images/demos/before-after/renovation/before.jpg',
                            'afterImage' => '/images/demos/before-after/renovation/after.jpg',
                            'useCases' => ['Byggfirmor', 'Inredningsdesigners', 'Fastighetsbolag', 'Homestyling'],
                        ],
                        [
                            'id' => 2,
                            'title' => 'Digital Workspace',
                            'category' => 'Kontorsmiljö & Tech',
                            'description' => 'Jämför arbetsmiljöer före och efter modernisering. Visa värdet av uppgraderade kontor och tech-lösningar.',
                            'beforeImage' => '/images/demos/before-after/web-design/before.jpg',
                            'afterImage' => '/images/demos/before-after/web-design/after.jpg',
                            'useCases' => ['Kontorsinredning', 'IT-konsulter', 'Coworking-spaces', 'Tech-företag'],
                        ],
                        [
                            'id' => 3,
                            'title' => 'Bildbehandling',
                            'category' => 'Foto & Retuschering',
                            'description' => 'Demonstrera effekten av professionell färgkorrigering och bildbehandling. Från gråskaligt original till livfull slutprodukt.',
                            'beforeImage' => '/images/demos/before-after/photo-edit/before.jpg',
                            'afterImage' => '/images/demos/before-after/photo-edit/after.jpg',
                            'useCases' => ['Fotografer', 'Bildbehandlare', 'Marknadsavdelningar', 'Kreativa byråer'],
                        ],
                        [
                            'id' => 4,
                            'title' => 'Utemiljö & Natur',
                            'category' => 'Landskap & Trädgård',
                            'description' => 'Visualisera transformationer av utemiljöer. Från vintage sepia-ton till levande grönska och färgprakt.',
                            'beforeImage' => '/images/demos/before-after/landscape/before.jpg',
                            'afterImage' => '/images/demos/before-after/landscape/after.jpg',
                            'useCases' => ['Trädgårdsanläggare', 'Landskapsarkitekter', 'Utemiljöföretag', 'Event-platser'],
                        ],
                    ],
                ],
                'google_reviews' => [
                    'enabled' => true,
                ],
                'smart_menu' => [
                    'enabled' => true,
                    'sample_dishes' => [
                        [
                            'id' => 1,
                            'name' => 'Klassiska Köttbullar',
                            'description' => 'Hemgjorda köttbullar med gräddsås, lingonsylt och potatismos. Serveras med inlagd gurka.',
                            'category' => 'Husmanskost',
                            'typical_allergens' => ['gluten', 'lactose', 'eggs'],
                        ],
                        [
                            'id' => 2,
                            'name' => 'Caesarsallad',
                            'description' => 'Krispig sallad med grillad kyckling, krutonger, parmesan och caesardressing.',
                            'category' => 'Sallader',
                            'typical_allergens' => ['gluten', 'lactose', 'eggs', 'fish'],
                        ],
                        [
                            'id' => 3,
                            'name' => 'Laxpasta',
                            'description' => 'Färsk pasta med rökt lax, spenat, grädde och citron. Toppas med dill.',
                            'category' => 'Pastarätter',
                            'typical_allergens' => ['gluten', 'fish', 'lactose'],
                        ],
                        [
                            'id' => 4,
                            'name' => 'Vegansk Burgare',
                            'description' => 'Hembakad hamburgerbröd med vegansk bönbiff, sallad, tomat, rödlök och vegansk majonnäs.',
                            'category' => 'Vegetariskt',
                            'typical_allergens' => ['gluten', 'soy', 'sesame'],
                        ],
                        [
                            'id' => 5,
                            'name' => 'Pad Thai',
                            'description' => 'Thailändska risnudlar med räkor, jordnötter, böngroddar, ägg och tamarind.',
                            'category' => 'Asiatiskt',
                            'typical_allergens' => ['peanuts', 'crustaceans', 'eggs', 'fish'],
                        ],
                        [
                            'id' => 6,
                            'name' => 'Margherita Pizza',
                            'description' => 'Klassisk pizza med tomatsås, mozzarella, basilika och olivolja på stenbakad deg.',
                            'category' => 'Pizza',
                            'typical_allergens' => ['gluten', 'lactose'],
                        ],
                        [
                            'id' => 7,
                            'name' => 'Hummus med Bröd',
                            'description' => 'Krämig kikärtshummus med tahini, vitlök och olivolja. Serveras med varmt pitabröd.',
                            'category' => 'Förrätter',
                            'typical_allergens' => ['gluten', 'sesame'],
                        ],
                        [
                            'id' => 8,
                            'name' => 'Pannacotta',
                            'description' => 'Italiensk grädddessert med vanilj och färska bär.',
                            'category' => 'Efterrätter',
                            'typical_allergens' => ['lactose'],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
