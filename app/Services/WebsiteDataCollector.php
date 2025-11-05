<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class WebsiteDataCollector
{
    private ?string $html = null;

    private ?Crawler $crawler = null;

    private float $loadTime = 0;

    private string $currentUrl = '';

    private int $externalCssFetchedCount = 0;

    private int $externalCssFailedCount = 0;

    /**
     * Collect all data from a website
     */
    public function collect(string $url): array
    {
        Log::info('WebsiteDataCollector: Starting collection', ['url' => $url]);

        try {
            // Store current URL for helper methods
            $this->currentUrl = $url;

            // Fetch HTML and take screenshot
            Log::info('WebsiteDataCollector: Fetching HTML...');
            $startTime = microtime(true);
            $this->html = $this->fetchHtml($url);
            $this->loadTime = microtime(true) - $startTime;
            Log::info('WebsiteDataCollector: HTML fetched', [
                'load_time' => round($this->loadTime, 2),
                'html_size' => strlen($this->html),
            ]);

            if (empty($this->html)) {
                throw new Exception('Failed to fetch HTML from URL');
            }

            // Initialize DOM crawler
            Log::info('WebsiteDataCollector: Initializing DOM crawler');
            $this->crawler = new Crawler($this->html);

            // Take screenshot
            Log::info('WebsiteDataCollector: Taking screenshot...');
            $screenshotPath = $this->takeScreenshot($url);
            Log::info('WebsiteDataCollector: Screenshot complete', ['path' => $screenshotPath]);

            // Collect all data
            Log::info('WebsiteDataCollector: Extracting data...');

            // Collect ground truth (100% accurate, deterministic facts)
            Log::info('WebsiteDataCollector: Collecting ground truth...');
            $groundTruth = $this->collectGroundTruth();

            // Get HTML excerpt for AI context (first 5KB of body)
            $htmlExcerpt = $this->getHtmlExcerpt();

            // Get CSS excerpts for AI context
            Log::info('WebsiteDataCollector: Getting CSS excerpts...');
            $cssExcerpts = $this->getCssExcerpts();

            $data = [
                'url' => $url,
                'html_length' => strlen($this->html),
                'load_time' => round($this->loadTime, 2),
                'screenshot_path' => $screenshotPath,

                // Ground truth - 100% accurate measurements
                'ground_truth' => $groundTruth,

                // Context for AI interpretation
                'html_excerpt' => $htmlExcerpt,
                'css_excerpts' => $cssExcerpts,

                // Legacy data (for backwards compatibility during transition)
                'meta' => $this->extractMetaTags(),
                'headings' => $this->extractHeadings(),
                'images' => $this->analyzeImages(),
                'links' => $this->analyzeLinks(),
                'technical_optimization' => $this->analyzeTechnicalOptimization(),
                'technical' => $this->analyzeTechnical($url),
                'content' => $this->analyzeContent(),
            ];

            Log::info('WebsiteDataCollector: Collection complete', [
                'data_keys' => array_keys($data),
                'total_images' => $groundTruth['dom_structure']['total_images'] ?? 0,
                'media_queries' => $groundTruth['css']['media_queries_total'] ?? 0,
            ]);

            return $data;
        } catch (Exception $e) {
            Log::error('WebsiteDataCollector failed', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Collect ground truth - objective, measurable facts only
     * NO interpretation, ONLY counts and percentages
     * This provides 100% accurate data that AI must cite exactly
     */
    private function collectGroundTruth(): array
    {
        Log::info('Collecting ground truth data...');

        // DOM Structure
        $totalElements = $this->crawler->filter('*')->count();
        $totalImages = $this->crawler->filter('img')->count();
        $imagesWithAlt = $this->crawler->filter('img[alt]')->count();
        $imagesWithSrcset = $this->crawler->filter('img[srcset]')->count();
        $imagesWithLazy = $this->crawler->filter('img[loading="lazy"]')->count();
        $imagesWithDimensions = $this->crawler->filter('img[width][height]')->count();

        $buttons = $this->crawler->filter('button, input[type="submit"], input[type="button"]')->count();
        $links = $this->crawler->filter('a')->count();
        $forms = $this->crawler->filter('form')->count();

        // Headings
        $headingCounts = [
            'h1' => $this->crawler->filter('h1')->count(),
            'h2' => $this->crawler->filter('h2')->count(),
            'h3' => $this->crawler->filter('h3')->count(),
            'h4' => $this->crawler->filter('h4')->count(),
            'h5' => $this->crawler->filter('h5')->count(),
            'h6' => $this->crawler->filter('h6')->count(),
        ];

        // Meta Tags - exact presence checks
        $hasViewport = $this->crawler->filter('meta[name="viewport"]')->count() > 0;
        $hasDescription = $this->crawler->filter('meta[name="description"]')->count() > 0;
        $hasTitle = $this->crawler->filter('title')->count() > 0;
        $hasCanonical = $this->crawler->filter('link[rel="canonical"]')->count() > 0;
        $hasOgTags = $this->crawler->filter('meta[property^="og:"]')->count() > 0;
        $hasSchema = $this->crawler->filter('[itemscope], script[type="application/ld+json"]')->count() > 0;

        // Get lengths for character counts
        $titleLength = 0;
        $descriptionLength = 0;

        $titleNode = $this->crawler->filter('title')->first();
        if ($titleNode->count() > 0) {
            $titleLength = strlen($titleNode->text());
        }

        $descNode = $this->crawler->filter('meta[name="description"]')->first();
        if ($descNode->count() > 0) {
            $descriptionLength = strlen($descNode->attr('content') ?? '');
        }

        // CSS - count stylesheets and inline styles
        $externalStylesheets = $this->crawler->filter('link[rel="stylesheet"]')->count();
        $inlineStyleTags = $this->crawler->filter('style')->count();

        // Count elements with style attribute (excluding Alpine.js bindings)
        $elementsWithStyleAttr = 0;
        $this->crawler->filter('[style]')->each(function (Crawler $node) use (&$elementsWithStyleAttr) {
            $styleAttr = $node->attr('style') ?? '';
            // Only count if it's actual CSS, not Alpine.js bindings like "display: none"
            if (!empty(trim($styleAttr))) {
                $elementsWithStyleAttr++;
            }
        });

        // Media queries - fetch and count from external CSS + inline
        $mediaQueriesTotal = $this->fetchAndCountCssMediaQueries();

        // JavaScript
        $externalScripts = $this->crawler->filter('script[src]')->count();
        $inlineScripts = $this->crawler->filter('script:not([src])')->count();
        $scriptsWithDefer = $this->crawler->filter('script[defer]')->count();
        $scriptsWithAsync = $this->crawler->filter('script[async]')->count();

        // Security
        $hasHttps = str_starts_with($this->currentUrl, 'https://');

        // Calculate percentages
        $imagesWithAltPercent = $this->calculatePercentage($imagesWithAlt, $totalImages);
        $imagesWithLazyPercent = $this->calculatePercentage($imagesWithLazy, $totalImages);
        $imagesWithSrcsetPercent = $this->calculatePercentage($imagesWithSrcset, $totalImages);
        $imagesWithDimensionsPercent = $this->calculatePercentage($imagesWithDimensions, $totalImages);

        return [
            'dom_structure' => [
                'total_elements' => $totalElements,
                'total_images' => $totalImages,
                'images_with_alt' => $imagesWithAlt,
                'images_without_alt' => $totalImages - $imagesWithAlt,
                'images_with_srcset' => $imagesWithSrcset,
                'images_with_lazy_loading' => $imagesWithLazy,
                'images_with_dimensions' => $imagesWithDimensions,
                'buttons' => $buttons,
                'links' => $links,
                'forms' => $forms,
                'headings' => $headingCounts,
            ],
            'meta_tags' => [
                'has_viewport' => $hasViewport,
                'has_description' => $hasDescription,
                'has_title' => $hasTitle,
                'has_canonical' => $hasCanonical,
                'has_og_tags' => $hasOgTags,
                'has_schema_markup' => $hasSchema,
                'title_length' => $titleLength,
                'description_length' => $descriptionLength,
            ],
            'css' => [
                'external_stylesheets' => $externalStylesheets,
                'inline_style_tags' => $inlineStyleTags,
                'elements_with_style_attr' => $elementsWithStyleAttr,
                'media_queries_total' => $mediaQueriesTotal,
                'external_css_fetched' => $this->externalCssFetchedCount,
                'external_css_failed' => $this->externalCssFailedCount,
            ],
            'javascript' => [
                'external_scripts' => $externalScripts,
                'inline_scripts' => $inlineScripts,
                'scripts_with_defer' => $scriptsWithDefer,
                'scripts_with_async' => $scriptsWithAsync,
            ],
            'security' => [
                'has_https' => $hasHttps,
            ],
            'percentages' => [
                'images_with_alt_percent' => $imagesWithAltPercent,
                'images_with_lazy_percent' => $imagesWithLazyPercent,
                'images_with_srcset_percent' => $imagesWithSrcsetPercent,
                'images_with_dimensions_percent' => $imagesWithDimensionsPercent,
            ],
        ];
    }

    /**
     * Fetch external CSS files and count media queries
     * Returns total count of @media queries from inline + external CSS
     */
    private function fetchAndCountCssMediaQueries(): int
    {
        $totalMediaQueries = 0;

        // Count media queries in inline <style> tags
        $this->crawler->filter('style')->each(function (Crawler $node) use (&$totalMediaQueries) {
            $cssContent = $node->text();
            $totalMediaQueries += substr_count(strtolower($cssContent), '@media');
        });

        // Fetch and count from external stylesheets (max 10 files)
        $stylesheets = $this->crawler->filter('link[rel="stylesheet"]');
        $fetchedCount = 0;
        $maxFetch = 10; // Limit to prevent excessive requests

        $stylesheets->each(function (Crawler $node) use (&$totalMediaQueries, &$fetchedCount, $maxFetch) {
            if ($fetchedCount >= $maxFetch) {
                return; // Stop after 10 files
            }

            $href = $node->attr('href');
            if (empty($href)) {
                return;
            }

            // Convert relative URLs to absolute
            $absoluteUrl = $this->makeAbsoluteUrl($href);

            try {
                // Check cache first (1 hour TTL)
                $cacheKey = 'css_media_queries_' . md5($absoluteUrl);
                $mediaQueryCount = Cache::remember($cacheKey, 3600, function () use ($absoluteUrl) {
                    // Fetch CSS with 5 second timeout
                    $response = Http::timeout(5)->get($absoluteUrl);

                    if ($response->successful()) {
                        $cssContent = $response->body();
                        return substr_count(strtolower($cssContent), '@media');
                    }

                    return 0;
                });

                $totalMediaQueries += $mediaQueryCount;
                $this->externalCssFetchedCount++;
                $fetchedCount++;

            } catch (\Exception $e) {
                Log::warning('Failed to fetch CSS file', [
                    'url' => $absoluteUrl,
                    'error' => $e->getMessage(),
                ]);
                $this->externalCssFailedCount++;
            }
        });

        return $totalMediaQueries;
    }

    /**
     * Convert relative URL to absolute URL
     */
    private function makeAbsoluteUrl(string $url): string
    {
        // Already absolute
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        // Protocol-relative URL (//example.com/style.css)
        if (str_starts_with($url, '//')) {
            $protocol = parse_url($this->currentUrl, PHP_URL_SCHEME);
            return $protocol . ':' . $url;
        }

        // Parse current URL
        $parsed = parse_url($this->currentUrl);
        $scheme = $parsed['scheme'] ?? 'https';
        $host = $parsed['host'] ?? '';

        // Absolute path (/css/style.css)
        if (str_starts_with($url, '/')) {
            return $scheme . '://' . $host . $url;
        }

        // Relative path (css/style.css or ../css/style.css)
        $path = $parsed['path'] ?? '/';
        $directory = dirname($path);

        // Normalize directory path
        if ($directory === '.') {
            $directory = '/';
        }

        return $scheme . '://' . $host . $directory . '/' . $url;
    }

    /**
     * Calculate percentage safely (handles division by zero)
     */
    private function calculatePercentage(int $part, int $total): float
    {
        if ($total === 0) {
            return 0.0;
        }

        return round(($part / $total) * 100, 1);
    }

    /**
     * Get HTML excerpt for AI context (first 5KB of body)
     * Provides structural context without overwhelming tokens
     */
    private function getHtmlExcerpt(): array
    {
        $bodyHtml = '';
        $maxSize = 5120; // 5KB limit

        try {
            $bodyNode = $this->crawler->filter('body')->first();
            if ($bodyNode->count() > 0) {
                $bodyHtml = $bodyNode->html();
            }
        } catch (\Exception $e) {
            Log::warning('Failed to extract body HTML', ['error' => $e->getMessage()]);
            $bodyHtml = '';
        }

        $originalSize = strlen($bodyHtml);
        $truncated = $originalSize > $maxSize;

        if ($truncated) {
            $bodyHtml = substr($bodyHtml, 0, $maxSize) . '... [truncated]';
        }

        return [
            'content' => $bodyHtml,
            'size' => strlen($bodyHtml),
            'original_size' => $originalSize,
            'truncated' => $truncated,
        ];
    }

    /**
     * Get CSS excerpts for AI context (first 10KB)
     * Provides context without overwhelming tokens
     */
    private function getCssExcerpts(): array
    {
        $excerpts = [];
        $totalSize = 0;
        $maxSize = 10240; // 10KB limit

        // Collect inline CSS first (from <style> tags)
        $this->crawler->filter('style')->each(function (Crawler $node) use (&$excerpts, &$totalSize, $maxSize) {
            if ($totalSize >= $maxSize) {
                return;
            }

            $cssContent = $node->text();
            $remainingSize = $maxSize - $totalSize;

            if (strlen($cssContent) > $remainingSize) {
                $cssContent = substr($cssContent, 0, $remainingSize) . '... [truncated]';
            }

            $excerpts[] = [
                'type' => 'inline',
                'content' => $cssContent,
                'size' => strlen($cssContent),
            ];

            $totalSize += strlen($cssContent);
        });

        // Collect external CSS excerpts (already cached from fetchAndCountCssMediaQueries)
        $stylesheets = $this->crawler->filter('link[rel="stylesheet"]');
        $fetchedCount = 0;
        $maxFetch = 5; // Limit to 5 files for excerpts

        $stylesheets->each(function (Crawler $node) use (&$excerpts, &$totalSize, &$fetchedCount, $maxSize, $maxFetch) {
            if ($totalSize >= $maxSize || $fetchedCount >= $maxFetch) {
                return;
            }

            $href = $node->attr('href');
            if (empty($href)) {
                return;
            }

            $absoluteUrl = $this->makeAbsoluteUrl($href);

            try {
                // Check cache first (same key as media query fetch, so it's already cached)
                $cacheKey = 'css_content_' . md5($absoluteUrl);
                $cssContent = Cache::remember($cacheKey, 3600, function () use ($absoluteUrl) {
                    $response = Http::timeout(5)->get($absoluteUrl);

                    if ($response->successful()) {
                        return $response->body();
                    }

                    return null;
                });

                if ($cssContent) {
                    $remainingSize = $maxSize - $totalSize;

                    if (strlen($cssContent) > $remainingSize) {
                        $cssContent = substr($cssContent, 0, $remainingSize) . '... [truncated]';
                    }

                    $excerpts[] = [
                        'type' => 'external',
                        'url' => $absoluteUrl,
                        'content' => $cssContent,
                        'size' => strlen($cssContent),
                    ];

                    $totalSize += strlen($cssContent);
                    $fetchedCount++;
                }

            } catch (\Exception $e) {
                // Silently skip failed CSS files
                Log::debug('Failed to fetch CSS excerpt', ['url' => $absoluteUrl]);
            }
        });

        return [
            'excerpts' => $excerpts,
            'total_size' => $totalSize,
            'truncated' => $totalSize >= $maxSize,
        ];
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
            $filename = 'audits/'.md5($url.time()).'.webp';
            $path = storage_path('app/public/'.$filename);

            // Ensure directory exists
            $directory = dirname($path);
            if (! file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            Browsershot::url($url)
                ->waitUntilNetworkIdle()
                ->windowSize(1920, 1080)
                ->setScreenshotType('webp', 85)  // WebP with 85% quality for optimization
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
     * Analyze technical optimization opportunities
     */
    private function analyzeTechnicalOptimization(): array
    {
        return [
            'code_quality' => $this->analyzeCodeQuality(),
            'image_optimization' => $this->analyzeImageOptimization(),
            'accessibility' => $this->analyzeAccessibility(),
            'best_practices' => $this->analyzeBestPractices(),
            'mobile_responsiveness' => $this->analyzeMobileResponsiveness(),
            'cta_effectiveness' => $this->analyzeCTAElements(),
            'trust_signals' => $this->analyzeTrustSignals(),
        ];
    }

    /**
     * Analyze code quality issues
     */
    private function analyzeCodeQuality(): array
    {
        // Detect inline styles in body
        $inlineStyles = $this->crawler->filter('body style')->count();
        $elementsWithStyleAttr = $this->crawler->filter('body [style]')->count();

        // Detect inline scripts in body
        $inlineScripts = $this->crawler->filter('body script:not([src])')->count();
        $elementsWithOnclick = $this->crawler->filter('body [onclick], body [onload], body [onchange]')->count();

        // Check for render-blocking resources in head
        $blockingScripts = $this->crawler->filter('head script:not([defer]):not([async])')->count();

        // Check if HTML appears minified (look for excessive whitespace/comments)
        $hasHtmlComments = str_contains($this->html, '<!--');
        $whitespaceRatio = (strlen($this->html) - strlen(preg_replace('/\s+/', '', $this->html))) / strlen($this->html);

        return [
            'inline_styles_count' => $inlineStyles,
            'elements_with_style_attr' => $elementsWithStyleAttr,
            'inline_scripts_count' => $inlineScripts,
            'inline_event_handlers' => $elementsWithOnclick,
            'blocking_scripts_in_head' => $blockingScripts,
            'has_html_comments' => $hasHtmlComments,
            'whitespace_ratio' => round($whitespaceRatio, 3),
            'appears_minified' => $whitespaceRatio < 0.05 && ! $hasHtmlComments,
        ];
    }

    /**
     * Analyze image optimization
     */
    private function analyzeImageOptimization(): array
    {
        $images = $this->crawler->filter('img');
        $totalImages = $images->count();

        if ($totalImages === 0) {
            return [
                'total_images' => 0,
                'with_lazy_loading' => 0,
                'with_dimensions' => 0,
                'with_srcset' => 0,
                'with_alt' => 0,
                'modern_formats_detected' => false,
            ];
        }

        $lazyImages = 0;
        $withDimensions = 0;
        $withSrcset = 0;
        $withAlt = 0;
        $modernFormats = false;

        $images->each(function (Crawler $img) use (&$lazyImages, &$withDimensions, &$withSrcset, &$withAlt, &$modernFormats) {
            // Check lazy loading
            if ($img->attr('loading') === 'lazy') {
                $lazyImages++;
            }

            // Check dimensions
            if ($img->attr('width') && $img->attr('height')) {
                $withDimensions++;
            }

            // Check srcset
            if ($img->attr('srcset')) {
                $withSrcset++;
            }

            // Check alt text
            if ($img->attr('alt') !== null) {
                $withAlt++;
            }

            // Check for modern formats (WebP, AVIF)
            $src = $img->attr('src') ?? '';
            if (str_contains($src, '.webp') || str_contains($src, '.avif')) {
                $modernFormats = true;
            }
        });

        // Check for <picture> elements (best practice for responsive images)
        $pictureElements = $this->crawler->filter('picture')->count();

        return [
            'total_images' => $totalImages,
            'with_lazy_loading' => $lazyImages,
            'with_dimensions' => $withDimensions,
            'with_srcset' => $withSrcset,
            'with_alt' => $withAlt,
            'picture_elements' => $pictureElements,
            'modern_formats_detected' => $modernFormats,
            'lazy_loading_percentage' => $totalImages > 0 ? round(($lazyImages / $totalImages) * 100, 1) : 0,
            'dimensions_percentage' => $totalImages > 0 ? round(($withDimensions / $totalImages) * 100, 1) : 0,
        ];
    }

    /**
     * Analyze accessibility features
     */
    private function analyzeAccessibility(): array
    {
        // ARIA landmarks
        $ariaLandmarks = $this->crawler->filter('[role="main"], [role="navigation"], [role="banner"], [role="contentinfo"], [role="complementary"]')->count();

        // Semantic HTML5 landmarks
        $semanticLandmarks = $this->crawler->filter('main, nav, header, footer, aside')->count();

        // Form labels
        $forms = $this->crawler->filter('form')->count();
        $inputs = $this->crawler->filter('input:not([type="hidden"]), textarea, select')->count();
        $labels = $this->crawler->filter('label')->count();

        // Heading hierarchy
        $headings = [
            'h1' => $this->crawler->filter('h1')->count(),
            'h2' => $this->crawler->filter('h2')->count(),
            'h3' => $this->crawler->filter('h3')->count(),
            'h4' => $this->crawler->filter('h4')->count(),
            'h5' => $this->crawler->filter('h5')->count(),
            'h6' => $this->crawler->filter('h6')->count(),
        ];

        // Check if heading hierarchy is properly maintained
        $hasProperHierarchy = $this->checkHeadingHierarchy($headings);

        // Buttons vs links
        $buttons = $this->crawler->filter('button')->count();
        $links = $this->crawler->filter('a')->count();

        return [
            'aria_landmarks' => $ariaLandmarks,
            'semantic_landmarks' => $semanticLandmarks,
            'has_landmarks' => $ariaLandmarks > 0 || $semanticLandmarks > 0,
            'form_count' => $forms,
            'input_count' => $inputs,
            'label_count' => $labels,
            'label_ratio' => $inputs > 0 ? round(($labels / $inputs), 2) : 0,
            'headings' => $headings,
            'has_proper_heading_hierarchy' => $hasProperHierarchy,
            'button_count' => $buttons,
            'link_count' => $links,
        ];
    }

    /**
     * Check if heading hierarchy is properly maintained
     */
    private function checkHeadingHierarchy(array $headings): bool
    {
        // Should have exactly one H1
        if ($headings['h1'] !== 1) {
            return false;
        }

        // Check no skipped levels
        $hasContent = false;
        foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $level) {
            if ($headings[$level] > 0) {
                if ($hasContent === false) {
                    $hasContent = true;
                }
            } elseif ($hasContent) {
                // Found a gap in hierarchy
                $restHasContent = false;
                foreach (array_slice(['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], array_search($level, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])) as $laterLevel) {
                    if ($headings[$laterLevel] > 0) {
                        $restHasContent = true;
                        break;
                    }
                }
                if ($restHasContent) {
                    return false; // Skipped a level
                }
            }
        }

        return true;
    }

    /**
     * Analyze best practices
     */
    private function analyzeBestPractices(): array
    {
        // Semantic HTML5 usage
        $semanticTags = $this->crawler->filter('article, section, nav, header, footer, aside, main, figure, figcaption')->count();

        // Deprecated tags
        $deprecatedTags = $this->crawler->filter('font, center, marquee, blink, big, strike, tt')->count();

        // Calculate DOM depth (approximate)
        $domDepth = $this->calculateMaxDomDepth();

        // Check for excessive DOM size
        $totalElements = $this->crawler->filter('*')->count();

        // Meta viewport
        $hasViewport = $this->crawler->filter('meta[name="viewport"]')->count() > 0;

        // Charset declaration
        $hasCharset = $this->crawler->filter('meta[charset]')->count() > 0 ||
                     str_contains($this->html, 'charset=');

        // Doctype
        $hasDoctype = str_starts_with(trim($this->html), '<!DOCTYPE') ||
                     str_starts_with(trim($this->html), '<!doctype');

        return [
            'semantic_tags_count' => $semanticTags,
            'deprecated_tags_count' => $deprecatedTags,
            'max_dom_depth' => $domDepth,
            'total_elements' => $totalElements,
            'has_viewport_meta' => $hasViewport,
            'has_charset_declaration' => $hasCharset,
            'has_doctype' => $hasDoctype,
            'excessive_dom_size' => $totalElements > 1500,
            'excessive_dom_depth' => $domDepth > 32,
        ];
    }

    /**
     * Calculate maximum DOM depth (simplified approach)
     */
    private function calculateMaxDomDepth(): int
    {
        try {
            $maxDepth = 0;
            $this->crawler->filter('body *')->each(function (Crawler $node) use (&$maxDepth) {
                $depth = 0;
                $current = $node->getNode(0);

                while ($current !== null && $current->parentNode !== null) {
                    $depth++;
                    $current = $current->parentNode;

                    // Stop at body to avoid counting html/head
                    if ($current->nodeName === 'body') {
                        break;
                    }
                }

                if ($depth > $maxDepth) {
                    $maxDepth = $depth;
                }
            });

            return $maxDepth;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Analyze mobile responsiveness
     */
    private function analyzeMobileResponsiveness(): array
    {
        // Check viewport meta
        $hasViewport = $this->crawler->filter('meta[name="viewport"]')->count() > 0;

        // Count media queries in inline styles and style tags
        $mediaQueryCount = 0;
        $styleBlocks = $this->crawler->filter('style')->each(function (Crawler $node) {
            return $node->text();
        });

        foreach ($styleBlocks as $style) {
            $mediaQueryCount += substr_count(strtolower($style), '@media');
        }

        // Also check for media queries in link rel stylesheets (can't read external CSS, but can check link attributes)
        $mediaQueryCount += $this->crawler->filter('link[rel="stylesheet"][media]')->count();

        // Detect mobile menu indicators
        $mobileMenuIndicators = [
            '.mobile-menu', '.mobile-nav', '.hamburger', '.menu-toggle',
            '[class*="mobile-menu"]', '[class*="hamburger"]',
            '.navbar-toggler', '.nav-toggle',
        ];

        $hasMobileMenu = false;
        foreach ($mobileMenuIndicators as $selector) {
            try {
                if ($this->crawler->filter($selector)->count() > 0) {
                    $hasMobileMenu = true;
                    break;
                }
            } catch (\Exception $e) {
                // Ignore invalid selectors
            }
        }

        // Check for responsive image techniques
        $responsiveImages = $this->crawler->filter('img[srcset], picture')->count();

        // Detect touch-friendly indicators (buttons with reasonable sizing classes)
        $touchFriendlyClasses = [
            '[class*="btn-lg"]', '[class*="btn-large"]',
            '[class*="touch-target"]', '[class*="tap-target"]',
        ];

        $hasTouchTargets = false;
        foreach ($touchFriendlyClasses as $selector) {
            try {
                if ($this->crawler->filter($selector)->count() > 0) {
                    $hasTouchTargets = true;
                    break;
                }
            } catch (\Exception $e) {
                // Ignore
            }
        }

        return [
            'has_viewport' => $hasViewport,
            'media_query_count' => $mediaQueryCount,
            'has_mobile_menu' => $hasMobileMenu,
            'responsive_images' => $responsiveImages,
            'has_touch_targets' => $hasTouchTargets,
            'mobile_optimized' => $hasViewport && $mediaQueryCount > 0,
        ];
    }

    /**
     * Analyze CTA (Call-to-Action) effectiveness
     */
    private function analyzeCTAElements(): array
    {
        // Count buttons and links
        $buttons = $this->crawler->filter('button, input[type="submit"], input[type="button"], .btn, [class*="button"]')->count();
        $links = $this->crawler->filter('a')->count();

        // Find phone numbers (tel: links and common phone patterns in text)
        $telLinks = $this->crawler->filter('a[href^="tel:"]')->count();

        // Simple Swedish phone number pattern in href
        $phonePattern = '/(\+46|0)[\s-]?\d{1,3}[\s-]?\d{5,8}/';
        $phoneInText = preg_match_all($phonePattern, $this->html);

        // Find email addresses (mailto: links and patterns)
        $mailtoLinks = $this->crawler->filter('a[href^="mailto:"]')->count();
        $emailPattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';
        $emailInText = preg_match_all($emailPattern, $this->html);

        // Detect contact forms
        $contactForms = $this->crawler->filter('form')->count();
        $formsWithEmail = $this->crawler->filter('form input[type="email"], form input[name*="email"], form input[id*="email"]')->count();

        // Check if CTA appears in first screen (approximate - first 1000 chars of body HTML)
        $bodyHtml = '';
        try {
            $bodyNode = $this->crawler->filter('body')->first();
            if ($bodyNode->count() > 0) {
                $bodyHtml = $bodyNode->html();
            }
        } catch (\Exception $e) {
            // Ignore
        }

        $firstScreenHtml = substr($bodyHtml, 0, 1500);
        $ctaInFirstScreen = (
            stripos($firstScreenHtml, '<button') !== false ||
            stripos($firstScreenHtml, 'type="submit"') !== false ||
            stripos($firstScreenHtml, 'class="btn') !== false ||
            stripos($firstScreenHtml, 'href="tel:') !== false ||
            stripos($firstScreenHtml, 'href="mailto:') !== false
        );

        // Analyze button text quality (look for generic vs specific)
        $genericButtonTexts = ['klicka här', 'click here', 'läs mer', 'read more', 'skicka', 'send', 'submit'];
        $hasGenericButtons = false;

        $buttonTexts = $this->crawler->filter('button, input[type="submit"], .btn')->each(function (Crawler $node) {
            return strtolower(trim($node->text() ?: $node->attr('value') ?: ''));
        });

        foreach ($buttonTexts as $text) {
            foreach ($genericButtonTexts as $generic) {
                if ($text === $generic) {
                    $hasGenericButtons = true;
                    break 2;
                }
            }
        }

        return [
            'button_count' => $buttons,
            'link_count' => $links,
            'phone_visible' => $telLinks > 0 || $phoneInText > 0,
            'tel_links' => $telLinks,
            'email_visible' => $mailtoLinks > 0 || $emailInText > 0,
            'mailto_links' => $mailtoLinks,
            'contact_forms' => $contactForms,
            'forms_with_email_field' => $formsWithEmail,
            'cta_in_first_screen' => $ctaInFirstScreen,
            'has_generic_button_text' => $hasGenericButtons,
            'cta_to_content_ratio' => $buttons > 0 ? round($buttons / max($links, 1), 3) : 0,
        ];
    }

    /**
     * Analyze trust signals
     */
    private function analyzeTrustSignals(): array
    {
        // SSL is checked in analyzeTechnical, but we'll note it here too for context
        $hasSSL = str_contains($this->html, 'https://');

        // Privacy policy links (Swedish and English)
        $privacyPolicyKeywords = [
            'integritetspolicy', 'privacy policy', 'integritet', 'privacy',
            'personuppgiftspolicy', 'dataskyddspolicy', 'gdpr',
        ];

        $hasPrivacyPolicy = false;
        $privacyLinks = $this->crawler->filter('a')->each(function (Crawler $node) {
            return strtolower($node->text().' '.$node->attr('href'));
        });

        foreach ($privacyLinks as $linkText) {
            foreach ($privacyPolicyKeywords as $keyword) {
                if (str_contains($linkText, $keyword)) {
                    $hasPrivacyPolicy = true;
                    break 2;
                }
            }
        }

        // Cookie consent banners (common class names and text)
        $cookieConsentIndicators = [
            '[class*="cookie"]', '[id*="cookie"]',
            '[class*="consent"]', '[id*="consent"]',
            '[class*="gdpr"]', '[id*="gdpr"]',
        ];

        $hasCookieConsent = false;
        foreach ($cookieConsentIndicators as $selector) {
            try {
                if ($this->crawler->filter($selector)->count() > 0) {
                    $hasCookieConsent = true;
                    break;
                }
            } catch (\Exception $e) {
                // Ignore
            }
        }

        // Also check text content for cookie-related words
        if (! $hasCookieConsent) {
            $cookieTextPatterns = ['vi använder cookies', 'we use cookies', 'denna webbplats använder cookies'];
            foreach ($cookieTextPatterns as $pattern) {
                if (stripos($this->html, $pattern) !== false) {
                    $hasCookieConsent = true;
                    break;
                }
            }
        }

        // Footer company info (Swedish patterns)
        $footerContent = '';
        try {
            $footer = $this->crawler->filter('footer');
            if ($footer->count() > 0) {
                $footerContent = strtolower($footer->text());
            }
        } catch (\Exception $e) {
            // Ignore
        }

        // Look for org number pattern (XXXXXX-XXXX)
        $hasOrgNumber = preg_match('/\d{6}-?\d{4}/', $footerContent);

        // Look for address indicators
        $addressKeywords = ['address', 'adress', 'väg', 'gata', 'box', 'sweden', 'sverige'];
        $hasAddress = false;
        foreach ($addressKeywords as $keyword) {
            if (str_contains($footerContent, $keyword)) {
                $hasAddress = true;
                break;
            }
        }

        // Certification badges (common patterns)
        $certificationIndicators = [
            'iso', 'ssl', 'secure', 'certified', 'certifierad',
            'trustpilot', 'verified', 'verifierad',
        ];

        $hasCertifications = false;
        foreach ($certificationIndicators as $keyword) {
            if (stripos($this->html, $keyword) !== false) {
                $hasCertifications = true;
                break;
            }
        }

        return [
            'has_ssl' => $hasSSL,
            'has_privacy_policy' => $hasPrivacyPolicy,
            'has_cookie_consent' => $hasCookieConsent,
            'footer_has_company_info' => $hasAddress || $hasOrgNumber,
            'has_org_number' => (bool) $hasOrgNumber,
            'has_address' => $hasAddress,
            'displays_certifications' => $hasCertifications,
            'trust_score' => $this->calculateTrustScore([
                'ssl' => $hasSSL,
                'privacy' => $hasPrivacyPolicy,
                'cookie' => $hasCookieConsent,
                'company_info' => $hasAddress || $hasOrgNumber,
            ]),
        ];
    }

    /**
     * Calculate trust score (0-100)
     */
    private function calculateTrustScore(array $signals): int
    {
        $score = 0;
        $score += $signals['ssl'] ? 40 : 0; // SSL is critical
        $score += $signals['privacy'] ? 20 : 0;
        $score += $signals['cookie'] ? 20 : 0;
        $score += $signals['company_info'] ? 20 : 0;

        return $score;
    }

    /**
     * Analyze technical aspects
     */
    private function analyzeTechnical(string $url): array
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
        $hasSSL = str_starts_with($url, 'https://');

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
