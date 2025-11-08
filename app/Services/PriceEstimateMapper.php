<?php

namespace App\Services;

/**
 * Maps project type and complexity to predefined time and price ranges
 * to ensure consistent estimates regardless of AI variation
 */
class PriceEstimateMapper
{
    private const HOURLY_RATE = 700;

    /**
     * Predefined time brackets based on project type and complexity
     * Format: ['complexity_range' => '1-2', 'hours' => [min, max]]
     * Tighter ranges (20-25% spread) for more consistent estimates
     */
    private const TIME_BRACKETS = [
        'simple' => [
            // Portfolio, landing pages, simple sites
            ['range' => '1-2', 'hours' => [8, 10]],      // Very simple - static content (25% spread)
            ['range' => '3-4', 'hours' => [18, 24]],     // Basic - admin panel, gallery, forms (33% spread)
            ['range' => '5-6', 'hours' => [30, 38]],     // Medium - auth, CMS features (27% spread)
            ['range' => '7-8', 'hours' => [45, 55]],     // Complex - advanced features (22% spread)
            ['range' => '9-10', 'hours' => [65, 80]],    // Very complex - enterprise (23% spread)
        ],
        'webapp' => [
            // SaaS, e-commerce, booking systems
            ['range' => '1-2', 'hours' => [50, 60]],     // Simple web app (20% spread)
            ['range' => '3-4', 'hours' => [70, 85]],     // Basic webapp (21% spread)
            ['range' => '5-6', 'hours' => [95, 115]],    // Medium - integrations, API (21% spread)
            ['range' => '7-8', 'hours' => [130, 160]],   // Complex - payments, real-time (23% spread)
            ['range' => '9-10', 'hours' => [180, 220]],  // Enterprise - microservices (22% spread)
        ],
        'api' => [
            // Backend/API development
            ['range' => '1-2', 'hours' => [15, 20]],     // Simple API - few endpoints (33% spread)
            ['range' => '3-4', 'hours' => [25, 32]],     // Basic API - CRUD + auth (28% spread)
            ['range' => '5-6', 'hours' => [40, 50]],     // Medium - multiple resources (25% spread)
            ['range' => '7-8', 'hours' => [60, 75]],     // Complex - integrations (25% spread)
            ['range' => '9-10', 'hours' => [90, 115]],   // Enterprise - scalability (28% spread)
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
            ['range' => '1-2', 'hours' => [30, 38]],     // Simple custom solution (27% spread)
            ['range' => '3-4', 'hours' => [45, 55]],     // Basic custom (22% spread)
            ['range' => '5-6', 'hours' => [65, 80]],     // Medium complexity (23% spread)
            ['range' => '7-8', 'hours' => [95, 115]],    // Complex custom work (21% spread)
            ['range' => '9-10', 'hours' => [140, 170]],  // Highly specialized (21% spread)
        ],
    ];

    /**
     * Map project type and complexity to time and price estimates
     *
     * @param  string  $projectType  simple|webapp|api|maintenance|custom
     * @param  int  $complexity  1-10
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

        // Calculate dynamic discount based on project hours
        $discountData = self::calculateDynamicDiscount($hoursMin, $hoursMax);
        $aiEfficiency = $discountData['ai_efficiency'];
        $discountPercent = $discountData['discount_percent'];

        // AI-driven hours based on dynamic efficiency
        $hoursMinAi = round($hoursMin * $aiEfficiency);
        $hoursMaxAi = round($hoursMax * $aiEfficiency);

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
            'savings_percent' => $discountPercent,

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
     * Calculate dynamic discount percentage based on project hours
     *
     * Simple projects (≤8 hours) get 80% discount
     * Complex projects (≥80 hours) get 50% discount
     * Linear interpolation between 8-80 hours
     *
     * @param  int  $hoursMin  Minimum traditional hours
     * @param  int  $hoursMax  Maximum traditional hours
     * @return array ['discount_percent' => int, 'ai_efficiency' => float]
     */
    private static function calculateDynamicDiscount(int $hoursMin, int $hoursMax): array
    {
        $avgHours = ($hoursMin + $hoursMax) / 2;

        // Discount range configuration: 8-80 hours → 80-50% discount
        $minHours = 8;
        $maxHours = 80;
        $minDiscount = 80;  // Simple projects get higher discount
        $maxDiscount = 50;  // Complex projects get lower discount

        if ($avgHours <= $minHours) {
            $discountPercent = $minDiscount;
        } elseif ($avgHours >= $maxHours) {
            $discountPercent = $maxDiscount;
        } else {
            // Linear interpolation between min and max
            $discountPercent = $minDiscount - (($avgHours - $minHours) / ($maxHours - $minHours)) * ($minDiscount - $maxDiscount);
            $discountPercent = round($discountPercent);
        }

        // AI efficiency is the inverse of discount
        // 80% discount means AI takes 20% of time (0.2 efficiency)
        // 50% discount means AI takes 50% of time (0.5 efficiency)
        $aiEfficiency = 1 - ($discountPercent / 100);

        return [
            'discount_percent' => (int) $discountPercent,
            'ai_efficiency' => $aiEfficiency,
        ];
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
