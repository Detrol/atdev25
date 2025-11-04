{{-- Breadcrumbs Component with Structured Data --}}
@props(['items' => []])

@if(count($items) > 0)
{{-- Visual Breadcrumbs --}}
<nav aria-label="Breadcrumb" class="mb-8">
    <ol class="flex items-center flex-wrap justify-center gap-2 text-sm" itemscope itemtype="https://schema.org/BreadcrumbList">
        @foreach($items as $index => $item)
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="flex items-center gap-2">
            @if(!empty($item['url']))
            <a href="{{ $item['url'] }}"
               itemprop="item"
               class="text-white/80 hover:text-white transition-colors hover:underline">
                <span itemprop="name">{{ $item['label'] }}</span>
            </a>
            @else
            <span itemprop="name" class="text-white font-medium">{{ $item['label'] }}</span>
            @endif
            <meta itemprop="position" content="{{ $index + 1 }}">

            @if($index < count($items) - 1)
            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            @endif
        </li>
        @endforeach
    </ol>
</nav>

{{-- Structured Data (JSON-LD) --}}
<script type="application/ld+json">
{!! $getJsonLd() !!}
</script>
@endif
