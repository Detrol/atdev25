<?php

use App\Services\AIService;
use App\Services\PriceEstimateMapper;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    // Mock the Anthropic API response (simplified - no hours/weeks)
    Http::fake([
        'api.anthropic.com/*' => Http::response([
            'content' => [
                [
                    'text' => json_encode([
                        'project_type' => 'simple',
                        'project_type_label' => 'Portfolio/Landing Page',
                        'complexity' => 3,
                        'complexity_label' => 'Bas komplexitet - standardfunktioner som kontaktformulär och projektgalleri',
                        'key_features' => [
                            'Responsiv design',
                            'Projektgalleri med filtrering',
                            'Kontaktformulär',
                            'Admin-panel för innehåll',
                        ],
                        'confidence' => 'high',
                        'notes' => 'Standardportfolio med moderna designprinciper',
                    ]),
                ],
            ],
            'usage' => [
                'input_tokens' => 500,
                'output_tokens' => 200,
            ],
        ], 200),
    ]);
});

test('price calculator endpoint requires description', function () {
    $response = $this->postJson('/api/price-estimate', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['description']);
});

test('price calculator validates minimum description length', function () {
    $response = $this->postJson('/api/price-estimate', [
        'description' => 'Too short',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['description']);
});

test('price calculator validates maximum description length', function () {
    $description = str_repeat('A', 2001);

    $response = $this->postJson('/api/price-estimate', [
        'description' => $description,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['description']);
});

test('price calculator returns successful estimation with ranges', function () {
    $description = 'Jag behöver en modern portfolio-webbplats med ett projektgalleri, kontaktformulär och admin-panel för att hantera innehåll.';

    $response = $this->postJson('/api/price-estimate', [
        'description' => $description,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'estimation' => [
                'project_type',
                'project_type_label',
                'complexity',
                'complexity_label',
                'key_features',
                'confidence',
                'notes',
                // Range fields from mapper
                'hours_range_traditional',
                'hours_range_ai',
                'hours_traditional',
                'hours_ai',
                'price_range_traditional',
                'price_range_ai',
                'price_traditional',
                'price_ai',
                'price_range_traditional_vat',
                'price_range_ai_vat',
                'price_traditional_vat',
                'price_ai_vat',
                'savings_range',
                'savings_range_vat',
                'savings',
                'savings_vat',
                'savings_percent',
                'delivery_weeks_traditional',
                'delivery_weeks_ai',
            ],
        ]);

    $estimation = $response->json('estimation');

    // Verify 80% AI savings (AI takes 20% of traditional time)
    expect($estimation['savings_percent'])->toBe(80);

    // Verify ranges are arrays
    expect($estimation['hours_range_traditional'])->toBeArray();
    expect($estimation['hours_range_ai'])->toBeArray();
    expect($estimation['price_range_traditional'])->toBeArray();
    expect($estimation['price_range_ai'])->toBeArray();

    // Verify formatted strings exist
    expect($estimation['hours_traditional'])->toBeString();
    expect($estimation['hours_ai'])->toBeString();
    expect($estimation['price_traditional'])->toBeString();
    expect($estimation['price_ai'])->toBeString();

    // Verify AI hours are 20% of traditional (80% savings)
    [$hoursMinTraditional, $hoursMaxTraditional] = $estimation['hours_range_traditional'];
    [$hoursMinAi, $hoursMaxAi] = $estimation['hours_range_ai'];

    expect($hoursMinAi)->toBe((int) round($hoursMinTraditional * 0.2));
    expect($hoursMaxAi)->toBe((int) round($hoursMaxTraditional * 0.2));
});

test('price calculator returns key features', function () {
    $description = 'Jag behöver en e-handelsplattform med användarautentisering och betalintegration.';

    $response = $this->postJson('/api/price-estimate', [
        'description' => $description,
    ]);

    $response->assertStatus(200);

    $estimation = $response->json('estimation');

    // Should return key features
    expect($estimation['key_features'])->toBeArray();
    expect($estimation['key_features'])->not->toBeEmpty();

    // Should not include recommended_tech anymore
    expect($estimation)->not->toHaveKey('recommended_tech');
});

test('price calculator respects rate limiting', function () {
    $description = 'Jag behöver en enkel webbplats med kontaktformulär.';

    // Make 5 successful requests
    for ($i = 0; $i < 5; $i++) {
        $response = $this->postJson('/api/price-estimate', [
            'description' => $description,
        ]);
        $response->assertStatus(200);
    }

    // 6th request should be rate limited by middleware
    $response = $this->postJson('/api/price-estimate', [
        'description' => $description,
    ]);

    // Laravel's throttle middleware returns 429 status
    $response->assertStatus(429);
});

test('PriceEstimateMapper returns consistent ranges for same complexity', function () {
    // Test that same project type + complexity always returns same range
    $result1 = PriceEstimateMapper::map('simple', 3);
    $result2 = PriceEstimateMapper::map('simple', 3);

    expect($result1)->toEqual($result2);

    // Verify it's a proper range
    expect($result1['hours_range_traditional'])->toBeArray();
    expect($result1['hours_traditional'])->toBeString();
    expect($result1['price_traditional'])->toBeString();
});

test('PriceEstimateMapper handles complexity levels correctly', function () {
    // Complexity 1-2 should give smaller range than complexity 3-4
    $simple1 = PriceEstimateMapper::map('simple', 2);
    $simple4 = PriceEstimateMapper::map('simple', 4);

    [$hoursMin1, $hoursMax1] = $simple1['hours_range_traditional'];
    [$hoursMin4, $hoursMax4] = $simple4['hours_range_traditional'];

    // Higher complexity should give higher hours
    expect($hoursMin4)->toBeGreaterThan($hoursMin1);
    expect($hoursMax4)->toBeGreaterThan($hoursMax1);
});

test('PriceEstimateMapper enforces 80% AI savings', function () {
    $result = PriceEstimateMapper::map('webapp', 5);

    [$hoursMinTraditional, $hoursMaxTraditional] = $result['hours_range_traditional'];
    [$hoursMinAi, $hoursMaxAi] = $result['hours_range_ai'];

    // AI should be exactly 20% of traditional (80% savings)
    expect($hoursMinAi)->toEqual(round($hoursMinTraditional * 0.2));
    expect($hoursMaxAi)->toEqual(round($hoursMaxTraditional * 0.2));
    expect($result['savings_percent'])->toBe(80);
});

test('PriceEstimateMapper calculates prices at 700 kr per hour', function () {
    $result = PriceEstimateMapper::map('simple', 3);

    [$hoursMin, $hoursMax] = $result['hours_range_traditional'];
    [$priceMin, $priceMax] = $result['price_range_traditional'];

    expect($priceMin)->toBe($hoursMin * 700);
    expect($priceMax)->toBe($hoursMax * 700);
});

test('PriceEstimateMapper calculates VAT correctly', function () {
    $result = PriceEstimateMapper::map('api', 4);

    [$priceMinExclVat, $priceMaxExclVat] = $result['price_range_traditional'];
    [$priceMinInclVat, $priceMaxInclVat] = $result['price_range_traditional_vat'];

    expect($priceMinInclVat)->toEqual(round($priceMinExclVat * 1.25));
    expect($priceMaxInclVat)->toEqual(round($priceMaxExclVat * 1.25));
});

test('AI prompt no longer includes time calculation formulas', function () {
    $aiService = app(AIService::class);

    $reflection = new ReflectionClass($aiService);
    $method = $reflection->getMethod('createPriceEstimationPrompt');
    $method->setAccessible(true);
    $prompt = $method->invoke($aiService);

    // Should NOT contain calculation formulas
    expect($prompt)->not->toContain('basTimmar × komplexitetsMultiplikator');
    expect($prompt)->not->toContain('estimated_hours_traditional');
    expect($prompt)->not->toContain('estimated_hours_ai');

    // Should NOT contain technology recommendations anymore
    expect($prompt)->not->toContain('ALLTID Laravel');
    expect($prompt)->not->toContain('recommended_tech');

    // Should have complexity guidance
    expect($prompt)->toContain('KOMPLEXITET 1-2');
    expect($prompt)->toContain('KOMPLEXITET 3-4');
});
