<?php

use App\Services\AIService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    // Mock the Anthropic API response
    Http::fake([
        'api.anthropic.com/*' => Http::response([
            'content' => [
                [
                    'text' => json_encode([
                        'project_type' => 'simple',
                        'project_type_label' => 'Portfolio/Landing Page',
                        'complexity' => 3,
                        'complexity_label' => 'Bas komplexitet - standardfunktioner som kontaktformulär och projektgalleri',
                        'estimated_hours_traditional' => 15,
                        'estimated_hours_ai' => 7.5,
                        'recommended_tech' => ['Laravel', 'Alpine.js', 'Tailwind CSS', 'MySQL'],
                        'key_features' => [
                            'Responsiv design',
                            'Projektgalleri med filtrering',
                            'Kontaktformulär',
                            'Admin-panel för innehåll',
                        ],
                        'delivery_weeks_traditional' => 1,
                        'delivery_weeks_ai' => 1,
                        'confidence' => 'high',
                        'notes' => 'Standardportfolio med moderna designprinciper',
                    ]),
                ],
            ],
            'usage' => [
                'input_tokens' => 500,
                'output_tokens' => 300,
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

test('price calculator returns successful estimation', function () {
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
                'estimated_hours_traditional',
                'estimated_hours_ai',
                'recommended_tech',
                'key_features',
                'delivery_weeks_traditional',
                'delivery_weeks_ai',
                'confidence',
                'notes',
                'price_traditional',
                'price_ai',
                'savings',
                'savings_percent',
                'price_traditional_vat',
                'price_ai_vat',
                'savings_vat',
            ],
        ]);

    $estimation = $response->json('estimation');

    // Verify 50% AI savings
    expect($estimation['savings_percent'])->toBe(50);
    // AI hours might be rounded, so we check it's approximately 50%
    expect($estimation['estimated_hours_ai'])->toBeGreaterThanOrEqual($estimation['estimated_hours_traditional'] * 0.45);
    expect($estimation['estimated_hours_ai'])->toBeLessThanOrEqual($estimation['estimated_hours_traditional'] * 0.55);

    // Verify pricing at 700 kr/h
    expect($estimation['price_traditional'])->toBe($estimation['estimated_hours_traditional'] * 700);
    expect($estimation['price_ai'])->toBe($estimation['estimated_hours_ai'] * 700);
    expect($estimation['savings'])->toBe($estimation['price_traditional'] - $estimation['price_ai']);

    // Verify VAT calculations (25%)
    expect($estimation['price_traditional_vat'])->toBe((int) round($estimation['price_traditional'] * 1.25));
    expect($estimation['price_ai_vat'])->toBe((int) round($estimation['price_ai'] * 1.25));
});

test('price calculator enforces Laravel technology stack', function () {
    $description = 'Jag behöver en e-handelsplattform med användarautentisering och betalintegration.';

    $response = $this->postJson('/api/price-estimate', [
        'description' => $description,
    ]);

    $response->assertStatus(200);

    $estimation = $response->json('estimation');
    $techStack = implode(' ', $estimation['recommended_tech']);

    // Should always include Laravel
    expect($techStack)->toContain('Laravel');

    // Should use Tailwind CSS
    expect($techStack)->toContain('Tailwind');

    // Should NOT recommend Next.js or React standalone
    expect($techStack)->not->toContain('Next.js');
    expect($techStack)->not->toContain('React');
    expect($techStack)->not->toContain('Express');
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

test('AI service uses formula-based calculations', function () {
    // This test verifies the prompt contains mathematical formulas
    $aiService = app(AIService::class);

    $reflection = new ReflectionClass($aiService);
    $method = $reflection->getMethod('createPriceEstimationPrompt');
    $method->setAccessible(true);
    $prompt = $method->invoke($aiService);

    // Verify formula-based structure exists
    expect($prompt)->toContain('basTimmar: 12'); // Simple project base
    expect($prompt)->toContain('×0.6'); // Complexity multiplier 1
    expect($prompt)->toContain('×6.0'); // Complexity multiplier 10
    expect($prompt)->toContain('+8h'); // Admin panel feature
    expect($prompt)->toContain('-4h'); // Laravel auth optimization
    expect($prompt)->toContain('ALLTID Laravel'); // Technology enforcement
    expect($prompt)->toContain('× 0.5'); // 50% AI efficiency
});
