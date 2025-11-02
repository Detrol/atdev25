{{-- Responsive Image Component with srcset --}}
@props([
    'media',
    'alt' => '',
    'sizes' => '(max-width: 768px) 128px, 256px',
    'class' => '',
    'width' => null,
    'height' => null,
    'fetchpriority' => 'auto',
    'loading' => 'lazy',
    'decoding' => 'async'
])

@if($media)
<img
    srcset="{{ $media->getUrl('tiny') }} 128w,
            {{ $media->getUrl('small') }} 256w,
            {{ $media->getUrl('medium') }} 512w,
            {{ $media->getUrl('optimized') }} 800w"
    sizes="{{ $sizes }}"
    src="{{ $media->getUrl('small') }}"
    alt="{{ $alt }}"
    @if($width) width="{{ $width }}" @endif
    @if($height) height="{{ $height }}" @endif
    fetchpriority="{{ $fetchpriority }}"
    loading="{{ $loading }}"
    decoding="{{ $decoding }}"
    class="{{ $class }}">
@endif
