<?php

use App\Services\AIService;
use App\Services\PriceEstimateMapper;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    // Disable reCAPTCHA validation in tests
    config(['recaptcha.enabled' => false]);

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
    $response = $this->postJson('/api/price-estimate', [
        'g-recaptcha-response' => 'test-token',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['description']);
});

test('price calculator validates minimum description length', function () {
    $response = $this->postJson('/api/price-estimate', [
        'g-recaptcha-response' => 'test-token',
        'description' => 'Too short',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['description']);
});

test('price calculator validates maximum description length', function () {
    $description = str_repeat('A', 2001);

    $response = $this->postJson('/api/price-estimate', [
        'g-recaptcha-response' => 'test-token',
        'description' => $description,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['description']);
});

test('price calculator returns successful estimation with ranges', function () {
    $description = 'Jag behöver en modern portfolio-webbplats med ett projektgalleri, kontaktformulär och admin-panel för att hantera innehåll.';

    $response = $this->postJson('/api/price-estimate', [
        'g-recaptcha-response' => 'test-token',
        'service_category' => 'web_development',
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

    // Verify dynamic AI savings (50-80% based on project hours)
    expect($estimation['savings_percent'])->toBeInt();
    expect($estimation['savings_percent'])->toBeGreaterThanOrEqual(50);
    expect($estimation['savings_percent'])->toBeLessThanOrEqual(80);

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

    // Verify AI hours are less than traditional (dynamic efficiency based on hours)
    [$hoursMinTraditional, $hoursMaxTraditional] = $estimation['hours_range_traditional'];
    [$hoursMinAi, $hoursMaxAi] = $estimation['hours_range_ai'];

    expect($hoursMinAi)->toBeLessThan($hoursMinTraditional);
    expect($hoursMaxAi)->toBeLessThan($hoursMaxTraditional);

    // AI efficiency should be between 0.2 and 0.5 (80% to 50% discount)
    $avgTraditional = ($hoursMinTraditional + $hoursMaxTraditional) / 2;
    $avgAi = ($hoursMinAi + $hoursMaxAi) / 2;
    $efficiency = $avgAi / $avgTraditional;
    expect($efficiency)->toBeGreaterThanOrEqual(0.2);
    expect($efficiency)->toBeLessThanOrEqual(0.5);
});

test('price calculator returns key features', function () {
    $description = 'Jag behöver en e-handelsplattform med användarautentisering och betalintegration.';

    $response = $this->postJson('/api/price-estimate', [
        'g-recaptcha-response' => 'test-token',
        'service_category' => 'web_development',
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
            'g-recaptcha-response' => 'test-token',
            'service_category' => 'web_development',
            'description' => $description,
        ]);
        $response->assertStatus(200);
    }

    // 6th request should be rate limited by middleware
    $response = $this->postJson('/api/price-estimate', [
        'g-recaptcha-response' => 'test-token',
        'service_category' => 'web_development',
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

test('PriceEstimateMapper calculates dynamic discount based on hours', function () {
    // Test simple project (8-10 hours) should get ~80% discount
    $simpleResult = PriceEstimateMapper::map('simple', 1);
    expect($simpleResult['savings_percent'])->toBe(80);

    // Test medium project (~30-40 hours) should get ~65-70% discount
    $mediumResult = PriceEstimateMapper::map('simple', 5);
    expect($mediumResult['savings_percent'])->toBeGreaterThanOrEqual(65);
    expect($mediumResult['savings_percent'])->toBeLessThanOrEqual(70);

    // Test complex project (≥80 hours) should get 50% discount
    $complexResult = PriceEstimateMapper::map('webapp', 5); // 95-115 hours
    expect($complexResult['savings_percent'])->toBe(50);

    // Verify AI hours match the discount percentage
    foreach ([$simpleResult, $mediumResult, $complexResult] as $result) {
        [$hoursMinTrad, $hoursMaxTrad] = $result['hours_range_traditional'];
        [$hoursMinAi, $hoursMaxAi] = $result['hours_range_ai'];

        $expectedEfficiency = 1 - ($result['savings_percent'] / 100);
        expect($hoursMinAi)->toEqual(round($hoursMinTrad * $expectedEfficiency));
        expect($hoursMaxAi)->toEqual(round($hoursMaxTrad * $expectedEfficiency));
    }
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
