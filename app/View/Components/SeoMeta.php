<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SeoMeta extends Component
{
    public string $title;

    public string $description;

    public ?string $keywords;

    public ?string $author;

    public ?string $ogTitle;

    public ?string $ogDescription;

    public ?string $ogImage;

    public ?string $ogType;

    public ?string $twitterTitle;

    public ?string $twitterDescription;

    public ?string $twitterImage;

    public ?string $twitterCard;

    public string $canonical;

    public string $locale;

    public ?string $preloadImage;

    /**
     * Create a new component instance.
     */
    public function __construct(
        ?string $title = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $author = null,
        ?string $ogTitle = null,
        ?string $ogDescription = null,
        ?string $ogImage = null,
        ?string $ogType = 'website',
        ?string $twitterTitle = null,
        ?string $twitterDescription = null,
        ?string $twitterImage = null,
        ?string $twitterCard = 'summary_large_image',
        ?string $canonical = null,
        ?string $locale = null,
        ?string $preloadImage = null
    ) {
        $this->title = $title ?? config('seo.default_title');
        $this->description = $description ?? config('seo.default_description');
        $this->keywords = $keywords ?? config('seo.default_keywords');
        $this->author = $author ?? config('seo.author');
        $this->ogTitle = $ogTitle ?? $this->title;
        $this->ogDescription = $ogDescription ?? $this->description;
        $this->ogImage = $ogImage ?? asset(config('seo.default_image'));
        $this->ogType = $ogType;
        $this->twitterTitle = $twitterTitle ?? $this->ogTitle;
        $this->twitterDescription = $twitterDescription ?? $this->ogDescription;
        $this->twitterImage = $twitterImage ?? $this->ogImage;
        $this->twitterCard = $twitterCard;
        $this->canonical = $canonical ?? url()->current();
        $this->locale = $locale ?? config('seo.locale');
        $this->preloadImage = $preloadImage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.seo-meta');
    }
}
