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
     * Tighter ranges (20-25% spread) for more consistent estimates
     */
    private const TIME_BRACKETS = [
        'simple' => [
            // Portfolio, landing pages, simple sites
            ['range' => '1-2', 'hours' => [8, 10]],      // Very simple - static content (25% spread)
            ['range' => '3-4', 'hours' => [13, 16]],     // Basic - standard features (23% spread)
            ['range' => '5-6', 'hours' => [20, 24]],     // Medium - database, admin, auth (20% spread)
            ['range' => '7-8', 'hours' => [28, 34]],     // Complex - advanced features (21% spread)
            ['range' => '9-10', 'hours' => [38, 46]],    // Very complex - enterprise (21% spread)
        ],
        'webapp' => [
            // SaaS, e-commerce, booking systems
            ['range' => '1-2', 'hours' => [40, 48]],     // Simple web app (20% spread)
            ['range' => '3-4', 'hours' => [54, 66]],     // Basic webapp (22% spread)
            ['range' => '5-6', 'hours' => [72, 88]],     // Medium - integrations, API (22% spread)
            ['range' => '7-8', 'hours' => [95, 115]],    // Complex - payments, real-time (21% spread)
            ['range' => '9-10', 'hours' => [130, 160]],  // Enterprise - microservices (23% spread)
        ],
        'api' => [
            // Backend/API development
            ['range' => '1-2', 'hours' => [18, 22]],     // Simple API (22% spread)
            ['range' => '3-4', 'hours' => [26, 32]],     // Basic API (23% spread)
            ['range' => '5-6', 'hours' => [38, 46]],     // Medium - multiple endpoints (21% spread)
            ['range' => '7-8', 'hours' => [52, 64]],     // Complex - integrations (23% spread)
            ['range' => '9-10', 'hours' => [75, 92]],    // Enterprise - scalability (23% spread)
        ],
        'maintenance' => [
            // Bug fixes, updates
            ['range' => '1-2', 'hours' => [4, 5]],       // Minor fixes (25% spread)
            ['range' => '3-4', 'hours' => [6, 8]],       // Small updates (33% spread)
            ['range' => '5-6', 'hours' => [10, 12]],     // Medium updates (20% spread)
            ['range' => '7-8', 'hours' => [15, 18]],     // Major refactoring (20% spread)
            ['range' => '9-10', 'hours' => [22, 28]],    // Complete overhaul (27% spread)
        ],
        'custom' => [
            // Specialized solutions
            ['range' => '1-2', 'hours' => [28, 34]],     // Simple custom solution (21% spread)
            ['range' => '3-4', 'hours' => [40, 48]],     // Basic custom (20% spread)
            ['range' => '5-6', 'hours' => [55, 67]],     // Medium complexity (22% spread)
            ['range' => '7-8', 'hours' => [78, 95]],     // Complex custom work (22% spread)
            ['range' => '9-10', 'hours' => [110, 135]],  // Highly specialized (23% spread)
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

        // Calculate delivery time (assuming 5h/day effective work)
        $daysMinTraditional = max(1, ceil($hoursMin / 5));
        $daysMaxTraditional = max(1, ceil($hoursMax / 5));
        $daysMinAi = max(1, ceil($hoursMinAi / 5));
        $daysMaxAi = max(1, ceil($hoursMaxAi / 5));

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

            // Delivery time
            'delivery_weeks_traditional' => self::formatDeliveryTime($daysMinTraditional, $daysMaxTraditional),
            'delivery_weeks_ai' => self::formatDeliveryTime($daysMinAi, $daysMaxAi),
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
     * Format delivery time as readable string (days or weeks)
     */
    private static function formatDeliveryTime(int $minDays, int $maxDays): string
    {
        // If 5 days or less, show in days
        if ($maxDays <= 5) {
            if ($minDays === $maxDays) {
                return "~{$minDays} ".($minDays === 1 ? 'dag' : 'dagar');
            }
            return "{$minDays}-{$maxDays} dagar";
        }

        // Convert to weeks for longer periods
        $minWeeks = ceil($minDays / 5);
        $maxWeeks = ceil($maxDays / 5);

        if ($minWeeks === $maxWeeks) {
            return "~{$minWeeks} ".($minWeeks === 1 ? 'vecka' : 'veckor');
        }

        return "{$minWeeks}-{$maxWeeks} veckor";
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
