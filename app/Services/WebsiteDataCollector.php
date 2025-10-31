<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class WebsiteDataCollector
{
    private ?string $html = null;

    private ?Crawler $crawler = null;

    private float $loadTime = 0;

    /**
     * Collect all data from a website
     */
    public function collect(string $url): array
    {
        try {
            // Fetch HTML and take screenshot
            $startTime = microtime(true);
            $this->html = $this->fetchHtml($url);
            $this->loadTime = microtime(true) - $startTime;

            if (empty($this->html)) {
                throw new Exception('Failed to fetch HTML from URL');
            }

            // Initialize DOM crawler
            $this->crawler = new Crawler($this->html);

            // Take screenshot
            $screenshotPath = $this->takeScreenshot($url);

            // Collect all data
            return [
                'url' => $url,
                'html_length' => strlen($this->html),
                'load_time' => round($this->loadTime, 2),
                'screenshot_path' => $screenshotPath,
                'meta' => $this->extractMetaTags(),
                'headings' => $this->extractHeadings(),
                'images' => $this->analyzeImages(),
                'links' => $this->analyzeLinks(),
                'performance' => $this->calculatePerformance(),
                'technical' => $this->analyzeTechnical(),
                'content' => $this->analyzeContent(),
            ];
        } catch (Exception $e) {
            Log::error('WebsiteDataCollector failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Fetch HTML using Browsershot
     */
    private function fetchHtml(string $url): string
    {
        try {
            return Browsershot::url($url)
                ->waitUntilNetworkIdle()
                ->timeout(30)
                ->bodyHtml();
        } catch (Exception $e) {
            Log::error('Browsershot HTML fetch failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            throw new Exception('Kunde inte hämta webbplatsens innehåll. Kontrollera att URL:en är korrekt.');
        }
    }

    /**
     * Take screenshot of the website
     */
    private function takeScreenshot(string $url): ?string
    {
        try {
            $filename = 'audits/'.md5($url.time()).'.png';
            $path = storage_path('app/public/'.$filename);

            // Ensure directory exists
            $directory = dirname($path);
            if (! file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            Browsershot::url($url)
                ->waitUntilNetworkIdle()
                ->windowSize(1920, 1080)
                ->timeout(30)
                ->save($path);

            return $filename;
        } catch (Exception $e) {
            Log::warning('Screenshot failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Extract meta tags
     */
    private function extractMetaTags(): array
    {
        $meta = [
            'title' => '',
            'description' => '',
            'keywords' => '',
            'og_tags' => [],
            'twitter_tags' => [],
            'canonical' => '',
            'robots' => '',
        ];

        // Title
        $titleNode = $this->crawler->filter('title')->first();
        if ($titleNode->count() > 0) {
            $meta['title'] = $titleNode->text();
        }

        // Meta description
        $descNode = $this->crawler->filter('meta[name="description"]')->first();
        if ($descNode->count() > 0) {
            $meta['description'] = $descNode->attr('content') ?? '';
        }

        // Keywords
        $keywordsNode = $this->crawler->filter('meta[name="keywords"]')->first();
        if ($keywordsNode->count() > 0) {
            $meta['keywords'] = $keywordsNode->attr('content') ?? '';
        }

        // Canonical
        $canonicalNode = $this->crawler->filter('link[rel="canonical"]')->first();
        if ($canonicalNode->count() > 0) {
            $meta['canonical'] = $canonicalNode->attr('href') ?? '';
        }

        // Robots
        $robotsNode = $this->crawler->filter('meta[name="robots"]')->first();
        if ($robotsNode->count() > 0) {
            $meta['robots'] = $robotsNode->attr('content') ?? '';
        }

        // Open Graph tags
        $this->crawler->filter('meta[property^="og:"]')->each(function (Crawler $node) use (&$meta) {
            $property = $node->attr('property');
            $content = $node->attr('content');
            if ($property && $content) {
                $meta['og_tags'][str_replace('og:', '', $property)] = $content;
            }
        });

        // Twitter tags
        $this->crawler->filter('meta[name^="twitter:"]')->each(function (Crawler $node) use (&$meta) {
            $name = $node->attr('name');
            $content = $node->attr('content');
            if ($name && $content) {
                $meta['twitter_tags'][str_replace('twitter:', '', $name)] = $content;
            }
        });

        return $meta;
    }

    /**
     * Extract headings structure
     */
    private function extractHeadings(): array
    {
        $headings = [];

        for ($i = 1; $i <= 6; $i++) {
            $this->crawler->filter("h{$i}")->each(function (Crawler $node) use (&$headings, $i) {
                $headings[] = [
                    'level' => $i,
                    'text' => trim($node->text()),
                ];
            });
        }

        return $headings;
    }

    /**
     * Analyze images
     */
    private function analyzeImages(): array
    {
        $images = [];
        $totalImages = 0;
        $withoutAlt = 0;

        $this->crawler->filter('img')->each(function (Crawler $node) use (&$images, &$totalImages, &$withoutAlt) {
            $totalImages++;
            $alt = $node->attr('alt');
            $src = $node->attr('src');

            if (empty($alt)) {
                $withoutAlt++;
            }

            if ($src) {
                $images[] = [
                    'src' => $src,
                    'alt' => $alt ?? '',
                    'has_alt' => ! empty($alt),
                ];
            }
        });

        return [
            'total' => $totalImages,
            'without_alt' => $withoutAlt,
            'with_alt' => $totalImages - $withoutAlt,
            'alt_percentage' => $totalImages > 0 ? round((($totalImages - $withoutAlt) / $totalImages) * 100) : 0,
            'images' => array_slice($images, 0, 10), // First 10 for analysis
        ];
    }

    /**
     * Analyze links
     */
    private function analyzeLinks(): array
    {
        $internal = 0;
        $external = 0;
        $broken = 0; // Would need actual checking

        $this->crawler->filter('a')->each(function (Crawler $node) use (&$internal, &$external) {
            $href = $node->attr('href');
            if (empty($href) || $href === '#') {
                return;
            }

            // Simple check for external links
            if (str_starts_with($href, 'http://') || str_starts_with($href, 'https://')) {
                $external++;
            } else {
                $internal++;
            }
        });

        return [
            'total' => $internal + $external,
            'internal' => $internal,
            'external' => $external,
        ];
    }

    /**
     * Calculate performance metrics
     */
    private function calculatePerformance(): array
    {
        $pageSize = strlen($this->html);

        // Estimate based on content
        $scripts = $this->crawler->filter('script')->count();
        $stylesheets = $this->crawler->filter('link[rel="stylesheet"]')->count();
        $images = $this->crawler->filter('img')->count();

        return [
            'load_time' => $this->loadTime,
            'page_size' => $pageSize,
            'page_size_formatted' => $this->formatBytes($pageSize),
            'scripts_count' => $scripts,
            'stylesheets_count' => $stylesheets,
            'images_count' => $images,
            'total_resources' => $scripts + $stylesheets + $images,
        ];
    }

    /**
     * Analyze technical aspects
     */
    private function analyzeTechnical(): array
    {
        // Check for common frameworks/technologies
        $technologies = [];

        if (str_contains($this->html, 'wp-content')) {
            $technologies[] = 'WordPress';
        }
        if (str_contains($this->html, 'laravel')) {
            $technologies[] = 'Laravel';
        }
        if (str_contains($this->html, 'react')) {
            $technologies[] = 'React';
        }
        if (str_contains($this->html, 'vue')) {
            $technologies[] = 'Vue.js';
        }
        if (str_contains($this->html, 'angular')) {
            $technologies[] = 'Angular';
        }
        if (str_contains($this->html, 'tailwind')) {
            $technologies[] = 'Tailwind CSS';
        }
        if (str_contains($this->html, 'bootstrap')) {
            $technologies[] = 'Bootstrap';
        }

        // Check for HTTPS
        $hasSSL = str_starts_with($this->html, 'https://');

        // Check for viewport meta
        $hasViewport = $this->crawler->filter('meta[name="viewport"]')->count() > 0;

        // Check for schema.org markup
        $hasSchema = $this->crawler->filter('[itemscope], script[type="application/ld+json"]')->count() > 0;

        return [
            'technologies' => $technologies,
            'has_ssl' => $hasSSL,
            'mobile_friendly' => $hasViewport,
            'has_schema' => $hasSchema,
        ];
    }

    /**
     * Analyze content quality
     */
    private function analyzeContent(): array
    {
        // Get main text content (exclude scripts, styles)
        $text = $this->crawler->filter('body')->first()->text();
        $wordCount = str_word_count($text);

        // Count paragraphs
        $paragraphs = $this->crawler->filter('p')->count();

        return [
            'word_count' => $wordCount,
            'paragraph_count' => $paragraphs,
            'has_sufficient_content' => $wordCount > 300,
        ];
    }

    /**
     * Format bytes to human-readable format
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision).' '.$units[$i];
    }
}
