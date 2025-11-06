<?php

use Illuminate\Support\Facades\Http;

/**
 * Test that the price calculator returns consistent results
 * even when AI might vary slightly in complexity assignment
 */
test('price calculator returns consistent ranges for same description (5 runs)', function () {
    $description = 'Jag behöver en modern portfolio-webbplats med ett projektgalleri, kontaktformulär och admin-panel för att hantera innehåll.';

    $results = [];

    // Run the same estimation 5 times
    for ($i = 0; $i < 5; $i++) {
        // Mock AI response with slightly varying complexity (but still in same bracket)
        $complexity = rand(3, 4); // Both 3 and 4 should map to same bracket [3-4]

        Http::fake([
            'api.anthropic.com/*' => Http::response([
                'content' => [
                    [
                        'text' => json_encode([
                            'project_type' => 'simple',
                            'project_type_label' => 'Portfolio/Landing Page',
                            'complexity' => $complexity,
                            'complexity_label' => 'Bas komplexitet med admin-panel',
                            'key_features' => [
                                'Responsiv design',
                                'Projektgalleri',
                                'Kontaktformulär',
                                'Admin-panel',
                            ],
                            'confidence' => 'high',
                            'notes' => 'Standard portfolio',
                        ]),
                    ],
                ],
                'usage' => [
                    'input_tokens' => 500,
                    'output_tokens' => 200,
                ],
            ], 200),
        ]);

        $response = $this->postJson('/api/price-estimate', [
            'service_category' => 'web_development',
            'description' => $description,
        ]);

        $response->assertStatus(200);
        $results[] = $response->json('estimation');
    }

    // Verify all results have the same ranges
    // (because complexity 3 and 4 both map to bracket [3-4] = 12-18 hours)
    $firstResult = $results[0];

    foreach ($results as $index => $result) {
        // All should have same project type
        expect($result['project_type'])->toBe($firstResult['project_type'])
            ->and($result['project_type'])->toBe('simple');

        // Complexity might vary between 3 and 4, but both map to same bracket
        expect($result['complexity'])->toBeGreaterThanOrEqual(3)
            ->and($result['complexity'])->toBeLessThanOrEqual(4);

        // But the RANGES should be identical (both map to [12-18])
        expect($result['hours_range_traditional'])->toEqual($firstResult['hours_range_traditional']);
        expect($result['hours_range_ai'])->toEqual($firstResult['hours_range_ai']);
        expect($result['price_range_traditional'])->toEqual($firstResult['price_range_traditional']);
        expect($result['price_range_ai'])->toEqual($firstResult['price_range_ai']);

        // Formatted strings should also be identical
        expect($result['hours_traditional'])->toBe($firstResult['hours_traditional']);
        expect($result['hours_ai'])->toBe($firstResult['hours_ai']);
        expect($result['price_traditional'])->toBe($firstResult['price_traditional']);
        expect($result['price_ai'])->toBe($firstResult['price_ai']);
    }
});

test('price calculator handles complexity variation within same bracket consistently', function () {
    // Test that complexity 3 and 4 give same ranges (both in bracket 3-4)
    $callCount = 0;

    Http::fake(function () use (&$callCount) {
        $callCount++;
        $complexity = $callCount === 1 ? 3 : 4;

        return Http::response([
            'content' => [
                [
                    'text' => json_encode([
                        'project_type' => 'simple',
                        'project_type_label' => 'Portfolio',
                        'complexity' => $complexity,
                        'complexity_label' => $complexity === 3 ? 'Bas' : 'Lätt medel',
                        'key_features' => ['Galleri', 'Kontakt', 'Admin'],
                        'confidence' => 'high',
                        'notes' => '',
                    ]),
                ],
            ],
            'usage' => ['input_tokens' => 500, 'output_tokens' => 200],
        ], 200);
    });

    $response1 = $this->postJson('/api/price-estimate', [
        'service_category' => 'web_development',
        'description' => 'En enkel portfolio-webbplats med galleri och kontaktformulär.',
    ]);

    $response1->assertStatus(200);
    $result1 = $response1->json('estimation');

    $response2 = $this->postJson('/api/price-estimate', [
        'service_category' => 'web_development',
        'description' => 'En enkel portfolio-webbplats med galleri och kontaktformulär.',
    ]);

    $response2->assertStatus(200);
    $result2 = $response2->json('estimation');

    // Both should map to same bracket (3-4 = 12-18 hours)
    expect($result1['hours_range_traditional'])->toEqual($result2['hours_range_traditional']);
    expect($result1['hours_range_ai'])->toEqual($result2['hours_range_ai']);
    expect($result1['price_range_traditional'])->toEqual($result2['price_range_traditional']);
    expect($result1['price_range_ai'])->toEqual($result2['price_range_ai']);

    // Formatted strings should also be identical
    expect($result1['hours_traditional'])->toBe($result2['hours_traditional']);
    expect($result1['hours_ai'])->toBe($result2['hours_ai']);
    expect($result1['price_traditional'])->toBe($result2['price_traditional']);
    expect($result1['price_ai'])->toBe($result2['price_ai']);
});

test('price calculator gives different ranges for different complexity brackets', function () {
    // Complexity 2 (bracket 1-2) vs complexity 5 (bracket 5-7) should be different
    $callCount = 0;

    Http::fake(function () use (&$callCount) {
        $callCount++;
        $complexity = $callCount === 1 ? 2 : 5;

        return Http::response([
            'content' => [
                [
                    'text' => json_encode([
                        'project_type' => 'simple',
                        'project_type_label' => $complexity === 2 ? 'Simple site' : 'Medium site',
                        'complexity' => $complexity,
                        'complexity_label' => $complexity === 2 ? 'Mycket enkelt' : 'Medel komplexitet',
                        'key_features' => $complexity === 2 ? ['Kontakt'] : ['Auth', 'Admin', 'Database'],
                        'confidence' => 'high',
                        'notes' => '',
                    ]),
                ],
            ],
            'usage' => ['input_tokens' => 500, 'output_tokens' => 200],
        ], 200);
    });

    $response1 = $this->postJson('/api/price-estimate', [
        'service_category' => 'web_development',
        'description' => 'En väldigt enkel statisk sida med bara kontaktformulär.',
    ]);

    $response1->assertStatus(200);
    $result1 = $response1->json('estimation');

    $response2 = $this->postJson('/api/price-estimate', [
        'service_category' => 'web_development',
        'description' => 'En avancerad sida med autentisering, admin-panel och databas.',
    ]);

    $response2->assertStatus(200);
    $result2 = $response2->json('estimation');

    // These should have DIFFERENT ranges
    expect($result1['hours_range_traditional'])->not->toEqual($result2['hours_range_traditional']);

    // Complexity 5-7 should give higher hours than 1-2
    $hours1Max = $result1['hours_range_traditional'][1];
    $hours2Min = $result2['hours_range_traditional'][0];
    expect($hours2Min)->toBeGreaterThan($hours1Max);
});
