<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class BrightDataScraper
{
    protected Client $client;
    protected string $apiKey;
    protected string $apiEndpoint = 'https://api.brightdata.com/request';
    protected string $zone = 'web_unlocker1';

    public function __construct()
    {
        $this->apiKey = config('services.brightdata.api_key');

        if (empty($this->apiKey)) {
            throw new \RuntimeException('BrightData API key not configured');
        }

        // Initialize Guzzle for API requests
        $this->client = new Client([
            'timeout' => 30,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
        ]);
    }

    /**
     * Scrape a website and return structured data
     *
     * @throws \Exception
     */
    public function scrape(string $url): array
    {
        // Cache key based on URL
        $cacheKey = 'brightdata_scrape_' . md5($url);

        return Cache::remember($cacheKey, 3600, function () use ($url) {
            try {
                Log::info('BrightData scraping started', ['url' => $url]);

                // BrightData Web Unlocker API request
                $response = $this->client->post($this->apiEndpoint, [
                    'json' => [
                        'zone' => $this->zone,
                        'url' => $url,
                        'format' => 'raw', // Returns raw HTML
                    ],
                ]);

                $html = (string) $response->getBody();
                $statusCode = $response->getStatusCode();

                if ($statusCode !== 200) {
                    throw new \Exception("Unexpected status code: {$statusCode}");
                }

                if (empty($html)) {
                    throw new \Exception('Empty response from BrightData');
                }

                Log::info('BrightData scraping successful', [
                    'url' => $url,
                    'html_length' => strlen($html),
                ]);

                return $this->parseHtml($url, $html);
            } catch (GuzzleException $e) {
                Log::error('BrightData scraping failed', [
                    'url' => $url,
                    'error' => $e->getMessage(),
                ]);

                throw new \Exception('Kunde inte hämta webbplatsen: ' . $e->getMessage());
            }
        });
    }

    /**
     * Parse HTML and extract structured data
     */
    protected function parseHtml(string $url, string $html): array
    {
        $crawler = new Crawler($html);

        return [
            'url' => $url,
            'meta' => $this->extractMeta($crawler),
            'structure' => $this->extractStructure($crawler),
            'technologies' => $this->detectTechnologies($html, $crawler),
            'scraped_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Extract meta information
     */
    protected function extractMeta(Crawler $crawler): array
    {
        try {
            $title = $crawler->filterXPath('//title')->count() > 0
                ? $crawler->filterXPath('//title')->text()
                : '';
        } catch (\Exception $e) {
            $title = '';
        }

        try {
            $description = $crawler->filterXPath('//meta[@name="description"]')->count() > 0
                ? $crawler->filterXPath('//meta[@name="description"]')->attr('content')
                : '';
        } catch (\Exception $e) {
            $description = '';
        }

        try {
            $keywords = $crawler->filterXPath('//meta[@name="keywords"]')->count() > 0
                ? $crawler->filterXPath('//meta[@name="keywords"]')->attr('content')
                : '';
        } catch (\Exception $e) {
            $keywords = '';
        }

        return [
            'title' => trim($title),
            'description' => trim($description),
            'keywords' => trim($keywords),
        ];
    }

    /**
     * Extract DOM structure information
     */
    protected function extractStructure(Crawler $crawler): array
    {
        return [
            'total_images' => $crawler->filter('img')->count(),
            'total_links' => $crawler->filter('a')->count(),
            'total_forms' => $crawler->filter('form')->count(),
            'has_navigation' => $crawler->filter('nav')->count() > 0,
            'has_footer' => $crawler->filter('footer')->count() > 0,
            'has_header' => $crawler->filter('header')->count() > 0,
            'total_sections' => $crawler->filter('section')->count(),
            'total_articles' => $crawler->filter('article')->count(),
        ];
    }

    /**
     * Detect technologies used on the website
     */
    protected function detectTechnologies(string $html, Crawler $crawler): array
    {
        $technologies = [];

        // WordPress
        if (str_contains($html, 'wp-content') || str_contains($html, 'wp-includes')) {
            $technologies[] = 'WordPress';
        }

        // React
        if (str_contains($html, 'react') || str_contains($html, '__REACT')) {
            $technologies[] = 'React';
        }

        // Vue.js
        if (str_contains($html, 'vue') || str_contains($html, 'data-v-')) {
            $technologies[] = 'Vue.js';
        }

        // Angular
        if (str_contains($html, 'ng-') || str_contains($html, 'angular')) {
            $technologies[] = 'Angular';
        }

        // jQuery
        if (str_contains($html, 'jquery')) {
            $technologies[] = 'jQuery';
        }

        // Bootstrap
        if (str_contains($html, 'bootstrap')) {
            $technologies[] = 'Bootstrap';
        }

        // Tailwind CSS
        if (str_contains($html, 'tailwind')) {
            $technologies[] = 'Tailwind CSS';
        }

        // Google Analytics
        if (str_contains($html, 'google-analytics') || str_contains($html, 'gtag')) {
            $technologies[] = 'Google Analytics';
        }

        // WooCommerce
        if (str_contains($html, 'woocommerce')) {
            $technologies[] = 'WooCommerce';
        }

        // Shopify
        if (str_contains($html, 'shopify')) {
            $technologies[] = 'Shopify';
        }

        // Laravel (harder to detect from frontend)
        if (str_contains($html, 'laravel')) {
            $technologies[] = 'Laravel';
        }

        // Next.js
        if (str_contains($html, '_next/') || str_contains($html, '__NEXT_DATA__')) {
            $technologies[] = 'Next.js';
        }

        return array_unique($technologies);
    }

    /**
     * Generate human-readable summary for price estimation
     */
    public function generateSummary(array $scrapedData): string
    {
        $meta = $scrapedData['meta'];
        $structure = $scrapedData['structure'];
        $technologies = $scrapedData['technologies'];

        $summary = "Befintlig webbplats: {$scrapedData['url']}\n\n";

        if (!empty($meta['title'])) {
            $summary .= "Titel: {$meta['title']}\n";
        }

        if (!empty($meta['description'])) {
            $summary .= "Beskrivning: {$meta['description']}\n\n";
        }

        $summary .= "Teknisk struktur:\n";
        $summary .= "- {$structure['total_images']} bilder\n";
        $summary .= "- {$structure['total_links']} länkar\n";
        $summary .= "- {$structure['total_forms']} formulär\n";

        if (!empty($technologies)) {
            $summary .= "\nIdentifierade teknologier: " . implode(', ', $technologies) . "\n";
        }

        $summary .= "\n[Användaren vill modernisera/uppdatera denna webbplats]";

        return $summary;
    }
}
