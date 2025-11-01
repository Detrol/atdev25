<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Allergener (EU:s 14 Obligatoriska Allergener)
    |--------------------------------------------------------------------------
    |
    | Svenska allergener och ingredienskeywords för AI-driven allergendetektering.
    | Används i Smart Menu demo för att identifiera allergener i matbeskrivningar.
    |
    */

    'allergens' => [
        'gluten' => [
            'name' => 'Gluten',
            'name_en' => 'Gluten',
            'icon' => '🌾',
            'color' => 'orange',
            'severity' => 'high',
            'keywords' => [
                'vete', 'råg', 'korn', 'havre', 'spelt', 'pasta', 'bröd', 'mjöl',
                'krutonger', 'panko', 'seitan', 'bulgur', 'couscous', 'durumvete',
                'vetemjöl', 'rågmjöl', 'kornmjöl', 'dinkel'
            ],
        ],

        'lactose' => [
            'name' => 'Laktos/Mjölk',
            'name_en' => 'Lactose/Milk',
            'icon' => '🥛',
            'color' => 'blue',
            'severity' => 'high',
            'keywords' => [
                'mjölk', 'grädde', 'smör', 'ost', 'yoghurt', 'kvarg', 'keso',
                'parmesan', 'mozzarella', 'cheddar', 'gorgonzola', 'brie', 'camembert',
                'crème fraiche', 'kesella', 'filmjölk', 'vispgrädde', 'matfettsmjölk',
                'vassle', 'kasein', 'laktos', 'mjölkprotein'
            ],
        ],

        'eggs' => [
            'name' => 'Ägg',
            'name_en' => 'Eggs',
            'icon' => '🥚',
            'color' => 'yellow',
            'severity' => 'high',
            'keywords' => [
                'ägg', 'äggula', 'äggvita', 'majonnäs', 'aioli', 'carbonara',
                'hollandaise', 'béarnaise', 'äggnudlar', 'omelette', 'maräng',
                'albumin', 'lecithin', 'lysozym'
            ],
        ],

        'fish' => [
            'name' => 'Fisk',
            'name_en' => 'Fish',
            'icon' => '🐟',
            'color' => 'cyan',
            'severity' => 'high',
            'keywords' => [
                'fisk', 'lax', 'torsk', 'sill', 'makrill', 'tonfisk', 'ansjovis',
                'sardiner', 'röding', 'gädda', 'abborre', 'kaviar', 'fiskbuljong',
                'fisksås', 'worcestersås', 'colatura', 'garum'
            ],
        ],

        'crustaceans' => [
            'name' => 'Skaldjur',
            'name_en' => 'Crustaceans',
            'icon' => '🦐',
            'color' => 'red',
            'severity' => 'high',
            'keywords' => [
                'räka', 'krabba', 'hummer', 'kräfta', 'langust', 'scampi',
                'skaldjur', 'räksmör', 'krabbsmör', 'skaldjursbuljong'
            ],
        ],

        'mollusks' => [
            'name' => 'Blötdjur',
            'name_en' => 'Mollusks',
            'icon' => '🦪',
            'color' => 'purple',
            'severity' => 'high',
            'keywords' => [
                'ostron', 'mussla', 'blåmussla', 'snäcka', 'bläckfisk', 'tioarmad bläckfisk',
                'åttaarmad bläckfisk', 'inkfish', 'calamares', 'pulpo', 'blötdjur'
            ],
        ],

        'nuts' => [
            'name' => 'Nötter',
            'name_en' => 'Tree Nuts',
            'icon' => '🥜',
            'color' => 'brown',
            'severity' => 'critical',
            'keywords' => [
                'mandel', 'hasselnöt', 'valnöt', 'cashewnöt', 'pekannöt', 'paranöt',
                'pistagenöt', 'macadamianöt', 'queenslandnöt', 'nötter', 'marzipan',
                'nougat', 'pralin', 'gianduja', 'mandelmjöl', 'nötsmör'
            ],
        ],

        'peanuts' => [
            'name' => 'Jordnötter',
            'name_en' => 'Peanuts',
            'icon' => '🥜',
            'color' => 'amber',
            'severity' => 'critical',
            'keywords' => [
                'jordnöt', 'jordnötter', 'jordnötssmör', 'peanut', 'peanutbutter',
                'arachis', 'satay', 'sataysås'
            ],
        ],

        'soy' => [
            'name' => 'Soja',
            'name_en' => 'Soy',
            'icon' => '🫘',
            'color' => 'green',
            'severity' => 'medium',
            'keywords' => [
                'soja', 'sojabönor', 'sojamjölk', 'tofu', 'tempeh', 'miso', 'tamari',
                'sojaprotein', 'sojasås', 'edamame', 'sojaböna', 'lecithin'
            ],
        ],

        'celery' => [
            'name' => 'Selleri',
            'name_en' => 'Celery',
            'icon' => '🥬',
            'color' => 'lime',
            'severity' => 'medium',
            'keywords' => [
                'selleri', 'selleristjälk', 'selleripulver', 'sellerifrö', 'rotselleri',
                'bladselleri', 'celery'
            ],
        ],

        'mustard' => [
            'name' => 'Senap',
            'name_en' => 'Mustard',
            'icon' => '🌱',
            'color' => 'yellow',
            'severity' => 'medium',
            'keywords' => [
                'senap', 'senapsfrö', 'dijonsenap', 'grovkornig senap', 'senapspulver',
                'mustard', 'senapskorn'
            ],
        ],

        'sesame' => [
            'name' => 'Sesam',
            'name_en' => 'Sesame',
            'icon' => '🌾',
            'color' => 'stone',
            'severity' => 'medium',
            'keywords' => [
                'sesam', 'sesamfrö', 'tahini', 'sesamolja', 'halva', 'sesame',
                'sesamkorn', 'gomashio'
            ],
        ],

        'sulfites' => [
            'name' => 'Sulfiter',
            'name_en' => 'Sulfites',
            'icon' => '🍷',
            'color' => 'grape',
            'severity' => 'medium',
            'keywords' => [
                'sulfit', 'svaveldioxid', 'vin', 'torkad frukt', 'konserveringsmedel',
                'E220', 'E221', 'E222', 'E223', 'E224', 'E226', 'E227', 'E228'
            ],
        ],

        'lupin' => [
            'name' => 'Lupin',
            'name_en' => 'Lupin',
            'icon' => '🌸',
            'color' => 'pink',
            'severity' => 'low',
            'keywords' => [
                'lupin', 'lupinfrö', 'lupinmjöl', 'lupinböna', 'lupinprotein'
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Dietpreferenser
    |--------------------------------------------------------------------------
    |
    | Vanliga dietpreferenser och deras exkluderingar.
    |
    */

    'dietary_preferences' => [
        'vegan' => [
            'name' => 'Vegansk',
            'name_en' => 'Vegan',
            'icon' => '🌱',
            'excludes' => ['lactose', 'eggs', 'fish', 'crustaceans', 'mollusks'],
            'description' => 'Inga animaliska produkter',
        ],

        'vegetarian' => [
            'name' => 'Vegetarisk',
            'name_en' => 'Vegetarian',
            'icon' => '🥗',
            'excludes' => ['fish', 'crustaceans', 'mollusks'],
            'description' => 'Inget kött eller fisk',
        ],

        'pescetarian' => [
            'name' => 'Pescetarian',
            'name_en' => 'Pescetarian',
            'icon' => '🐟',
            'excludes' => [],
            'description' => 'Kött undviks, fisk OK',
        ],

        'gluten_free' => [
            'name' => 'Glutenfri',
            'name_en' => 'Gluten-Free',
            'icon' => '🚫🌾',
            'excludes' => ['gluten'],
            'description' => 'Inget gluten',
        ],

        'lactose_free' => [
            'name' => 'Laktosfri',
            'name_en' => 'Lactose-Free',
            'icon' => '🚫🥛',
            'excludes' => ['lactose'],
            'description' => 'Ingen laktos/mjölk',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Severity Levels
    |--------------------------------------------------------------------------
    |
    | Allvarlighetsgrader för olika allergener.
    |
    */

    'severity_levels' => [
        'critical' => ['nuts', 'peanuts'],
        'high' => ['gluten', 'lactose', 'eggs', 'fish', 'crustaceans', 'mollusks'],
        'medium' => ['soy', 'celery', 'mustard', 'sesame', 'sulfites'],
        'low' => ['lupin'],
    ],
];
