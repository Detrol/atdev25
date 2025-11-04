<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Breadcrumbs extends Component
{
    public array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Get JSON-LD structured data for breadcrumbs
     */
    public function getJsonLd(): string
    {
        $jsonLdItems = [];

        foreach ($this->items as $index => $item) {
            $jsonItem = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['label'],
            ];

            if (! empty($item['url'])) {
                $jsonItem['item'] = url($item['url']);
            }

            $jsonLdItems[] = $jsonItem;
        }

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $jsonLdItems,
        ];

        return json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function render(): View
    {
        return view('components.breadcrumbs');
    }
}
