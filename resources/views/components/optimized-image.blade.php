{{-- Optimized Image Component with lazy loading and dimensions --}}
@props([
    'src',
    'alt',
    'width' => null,
    'height' => null,
    'loading' => 'lazy',
    'fetchpriority' => null,
    'class' => '',
])

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    @if($width) width="{{ $width }}" @endif
    @if($height) height="{{ $height }}" @endif
    loading="{{ $loading }}"
    @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
    decoding="async"
    {{ $attributes->merge(['class' => $class]) }}
>
