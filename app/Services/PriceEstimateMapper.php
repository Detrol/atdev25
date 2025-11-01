<?php

namespace App\Services;

/**
 * Maps project type and complexity to predefined time and price ranges
 * to ensure consistent estimates regardless of AI variation
 */
class PriceEstimateMapper
{
    private const HOURLY_RATE = 700;

    private const AI_EFFICIENCY = 0.5; // 50% time savings with AI

    /**
     * Predefined time brackets based on project type and complexity
     * Format: ['complexity_range' => '1-2', 'hours' => [min, max]]
     */
    private const TIME_BRACKETS = [
        'simple' => [
            // Portfolio, landing pages, simple sites
            ['range' => '1-2', 'hours' => [8, 12]],      // Very simple - static content
            ['range' => '3-4', 'hours' => [12, 18]],     // Basic - standard features
            ['range' => '5-7', 'hours' => [18, 30]],     // Medium - database, admin, auth
            ['range' => '8-10', 'hours' => [30, 50]],    // Complex - advanced features
        ],
        'webapp' => [
            // SaaS, e-commerce, booking systems
            ['range' => '1-3', 'hours' => [40, 60]],     // Simple web app
            ['range' => '4-6', 'hours' => [60, 100]],    // Medium - integrations, API
            ['range' => '7-8', 'hours' => [100, 140]],   // Complex - payments, real-time
            ['range' => '9-10', 'hours' => [140, 200]],  // Enterprise - microservices
        ],
        'api' => [
            // Backend/API development
            ['range' => '1-3', 'hours' => [20, 35]],     // Simple API
            ['range' => '4-6', 'hours' => [35, 60]],     // Medium - multiple endpoints
            ['range' => '7-8', 'hours' => [60, 90]],     // Complex - integrations
            ['range' => '9-10', 'hours' => [90, 130]],   // Enterprise - scalability
        ],
        'maintenance' => [
            // Bug fixes, updates
            ['range' => '1-3', 'hours' => [4, 8]],       // Minor fixes
            ['range' => '4-6', 'hours' => [8, 15]],      // Medium updates
            ['range' => '7-8', 'hours' => [15, 25]],     // Major refactoring
            ['range' => '9-10', 'hours' => [25, 40]],    // Complete overhaul
        ],
        'custom' => [
            // Specialized solutions
            ['range' => '1-3', 'hours' => [30, 50]],     // Simple custom solution
            ['range' => '4-6', 'hours' => [50, 80]],     // Medium complexity
            ['range' => '7-8', 'hours' => [80, 120]],    // Complex custom work
            ['range' => '9-10', 'hours' => [120, 180]],  // Highly specialized
        ],
    ];

    /**
     * Map project type and complexity to time and price estimates
     *
     * @param  string  $projectType  simple|webapp|api|maintenance|custom
     * @param  int  $complexity  1-10
     * @return array
     */
    public static function map(string $projectType, int $complexity): array
    {
        // Validate inputs
        if (! isset(self::TIME_BRACKETS[$projectType])) {
            $projectType = 'custom'; // Fallback to custom if unknown type
        }

        $complexity = max(1, min(10, (int) $complexity)); // Clamp to 1-10

        // Find matching time bracket
        $timeRange = self::findTimeBracket($projectType, $complexity);

        // Calculate traditional and AI-driven estimates
        $hoursMin = $timeRange[0];
        $hoursMax = $timeRange[1];

        // AI-driven is 50% of traditional
        $hoursMinAi = round($hoursMin * self::AI_EFFICIENCY);
        $hoursMaxAi = round($hoursMax * self::AI_EFFICIENCY);

        // Calculate price ranges
        $priceMinTraditional = $hoursMin * self::HOURLY_RATE;
        $priceMaxTraditional = $hoursMax * self::HOURLY_RATE;
        $priceMinAi = $hoursMinAi * self::HOURLY_RATE;
        $priceMaxAi = $hoursMaxAi * self::HOURLY_RATE;

        // Calculate with VAT (25%)
        $priceMinTraditionalVat = round($priceMinTraditional * 1.25);
        $priceMaxTraditionalVat = round($priceMaxTraditional * 1.25);
        $priceMinAiVat = round($priceMinAi * 1.25);
        $priceMaxAiVat = round($priceMaxAi * 1.25);

        // Calculate delivery weeks (assuming 20h/week effective work)
        $weeksMinTraditional = max(1, ceil($hoursMin / 20));
        $weeksMaxTraditional = max(1, ceil($hoursMax / 20));
        $weeksMinAi = max(1, ceil($hoursMinAi / 20));
        $weeksMaxAi = max(1, ceil($hoursMaxAi / 20));

        // Calculate savings
        $savingsMin = $priceMinTraditional - $priceMaxAi; // Conservative (worst case)
        $savingsMax = $priceMaxTraditional - $priceMinAi; // Optimistic (best case)
        $savingsMinVat = $priceMinTraditionalVat - $priceMaxAiVat;
        $savingsMaxVat = $priceMaxTraditionalVat - $priceMinAiVat;

        return [
            // Time ranges
            'hours_range_traditional' => [$hoursMin, $hoursMax],
            'hours_range_ai' => [$hoursMinAi, $hoursMaxAi],

            // Formatted time strings
            'hours_traditional' => self::formatHourRange($hoursMin, $hoursMax),
            'hours_ai' => self::formatHourRange($hoursMinAi, $hoursMaxAi),

            // Price ranges (excluding VAT)
            'price_range_traditional' => [$priceMinTraditional, $priceMaxTraditional],
            'price_range_ai' => [$priceMinAi, $priceMaxAi],

            // Formatted price strings
            'price_traditional' => self::formatPriceRange($priceMinTraditional, $priceMaxTraditional),
            'price_ai' => self::formatPriceRange($priceMinAi, $priceMaxAi),

            // Price ranges (including VAT)
            'price_range_traditional_vat' => [$priceMinTraditionalVat, $priceMaxTraditionalVat],
            'price_range_ai_vat' => [$priceMinAiVat, $priceMaxAiVat],

            // Formatted price strings with VAT
            'price_traditional_vat' => self::formatPriceRange($priceMinTraditionalVat, $priceMaxTraditionalVat),
            'price_ai_vat' => self::formatPriceRange($priceMinAiVat, $priceMaxAiVat),

            // Savings
            'savings_range' => [$savingsMin, $savingsMax],
            'savings_range_vat' => [$savingsMinVat, $savingsMaxVat],
            'savings' => self::formatPriceRange($savingsMin, $savingsMax),
            'savings_vat' => self::formatPriceRange($savingsMinVat, $savingsMaxVat),
            'savings_percent' => 50,

            // Delivery weeks
            'delivery_weeks_traditional' => self::formatWeekRange($weeksMinTraditional, $weeksMaxTraditional),
            'delivery_weeks_ai' => self::formatWeekRange($weeksMinAi, $weeksMaxAi),
        ];
    }

    /**
     * Find the time bracket for given project type and complexity
     */
    private static function findTimeBracket(string $projectType, int $complexity): array
    {
        $brackets = self::TIME_BRACKETS[$projectType];

        foreach ($brackets as $bracket) {
            // Parse range string like '1-2' or '3-4'
            [$min, $max] = explode('-', $bracket['range']);
            $min = (int) $min;
            $max = (int) $max;

            if ($complexity >= $min && $complexity <= $max) {
                return $bracket['hours'];
            }
        }

        // Fallback to last bracket if somehow not found
        $lastBracket = end($brackets);

        return $lastBracket['hours'];
    }

    /**
     * Format hour range as readable string
     */
    private static function formatHourRange(int $min, int $max): string
    {
        if ($min === $max) {
            return "~{$min} timmar";
        }

        return "{$min}-{$max} timmar";
    }

    /**
     * Format price range as readable string
     */
    private static function formatPriceRange(int $min, int $max): string
    {
        if ($min === $max) {
            return number_format($min, 0, ',', ' ').' kr';
        }

        $minFormatted = number_format($min, 0, ',', ' ');
        $maxFormatted = number_format($max, 0, ',', ' ');

        return "{$minFormatted}-{$maxFormatted} kr";
    }

    /**
     * Format week range as readable string
     */
    private static function formatWeekRange(int $min, int $max): string
    {
        if ($min === $max) {
            return "~{$min} ".($min === 1 ? 'vecka' : 'veckor');
        }

        return "{$min}-{$max} veckor";
    }

    /**
     * Get all available project types
     */
    public static function getAvailableProjectTypes(): array
    {
        return array_keys(self::TIME_BRACKETS);
    }

    /**
     * Get complexity range for project type (for reference)
     */
    public static function getComplexityRanges(string $projectType): array
    {
        if (! isset(self::TIME_BRACKETS[$projectType])) {
            return [];
        }

        $ranges = [];
        foreach (self::TIME_BRACKETS[$projectType] as $bracket) {
            [$min, $max] = explode('-', $bracket['range']);
            $ranges[] = [
                'complexity_min' => (int) $min,
                'complexity_max' => (int) $max,
                'hours_min' => $bracket['hours'][0],
                'hours_max' => $bracket['hours'][1],
            ];
        }

        return $ranges;
    }
}
