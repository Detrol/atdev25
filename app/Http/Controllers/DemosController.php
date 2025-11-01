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
            ],
        ]);
    }
}
