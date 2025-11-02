{{-- Basic Meta Tags --}}
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
@if($keywords)
<meta name="keywords" content="{{ $keywords }}">
@endif
@if($author)
<meta name="author" content="{{ $author }}">
@endif

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:locale" content="{{ $locale }}">
<meta property="og:site_name" content="{{ config('seo.site_name') }}">

{{-- Twitter --}}
<meta property="twitter:card" content="{{ $twitterCard }}">
<meta property="twitter:url" content="{{ $canonical }}">
<meta property="twitter:title" content="{{ $twitterTitle }}">
<meta property="twitter:description" content="{{ $twitterDescription }}">
<meta property="twitter:image" content="{{ $twitterImage }}">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $canonical }}">

{{-- Language & hreflang --}}
<link rel="alternate" hreflang="sv" href="{{ $canonical }}">
<link rel="alternate" hreflang="x-default" href="{{ $canonical }}">

{{-- DNS Prefetch --}}
@foreach(config('seo.resource_hints.dns_prefetch', []) as $domain)
<link rel="dns-prefetch" href="{{ $domain }}">
@endforeach

{{-- Preconnect --}}
@foreach(config('seo.resource_hints.preconnect', []) as $domain)
<link rel="preconnect" href="{{ $domain }}" crossorigin>
@endforeach

{{-- Preload Critical Images --}}
@if($preloadImage)
<link rel="preload" href="{{ $preloadImage }}" as="image" fetchpriority="high">
@endif
