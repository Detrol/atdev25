{{-- Wave Divider Component

    Konsekvent, neutral separator med parallax-effekt
    Fungerar mellan alla sektioner med tema-matchade färger

    Användning:
    <x-wave-divider color="pink-orange" position="bottom" />
--}}

@props([
    'color' => 'purple-blue',
    'position' => 'bottom' // 'top' or 'bottom'
])

@php
$colors = [
    'purple-blue' => ['primary' => '#8A2BE2', 'secondary' => '#4169E1'],
    'blue-pink' => ['primary' => '#4169E1', 'secondary' => '#FF69B4'],
    'pink-orange' => ['primary' => '#FF69B4', 'secondary' => '#FF8C00'],
    'orange-amber' => ['primary' => '#FF8C00', 'secondary' => '#FFBF00'],
    'amber-green' => ['primary' => '#FFBF00', 'secondary' => '#32CD32'],
    'green-teal' => ['primary' => '#2E8B57', 'secondary' => '#20B2AA'],
    'purple-pink' => ['primary' => '#8B5CF6', 'secondary' => '#EC4899'],
    'teal-blue' => ['primary' => '#20B2AA', 'secondary' => '#4169E1'],
    // Blended transition colors (for smoother section transitions)
    'blue-pink-orange' => ['primary' => '#B85AC9', 'secondary' => '#FF7F97'], // How I Work → Timeline
    'pink-orange-amber' => ['primary' => '#FF9957', 'secondary' => '#FFB340'], // Timeline → Services
    // CTA matching colors
    'indigo-purple-pink' => ['primary' => '#6366F1', 'secondary' => '#A855F7'], // Demos CTA
    'purple-blue-pink' => ['primary' => '#8B5CF6', 'secondary' => '#3B82F6'], // Price Calculator & Audit CTA
    'dark-gray' => ['primary' => '#1F2937', 'secondary' => '#111827'], // Footer match
];

$themeColors = $colors[$color] ?? $colors['purple-blue'];
$transform = $position === 'top' ? 'rotate(180deg)' : 'none';
$uniqueId = uniqid('wave-');
@endphp

<div class="wave-divider absolute {{ $position }}-0 left-0 right-0 pointer-events-none z-[3] overflow-hidden"
     data-wave-separator="{{ $position }}"
     style="transform: {{ $transform }}; height: 100px; opacity: 1 !important; isolation: isolate; will-change: transform;">
    <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-full">
        <defs>
            <linearGradient id="wave-gradient-{{ $uniqueId }}" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" style="stop-color:{{ $themeColors['primary'] }};stop-opacity:0.25" />
                <stop offset="50%" style="stop-color:{{ $themeColors['secondary'] }};stop-opacity:0.3" />
                <stop offset="100%" style="stop-color:{{ $themeColors['primary'] }};stop-opacity:0.25" />
            </linearGradient>
        </defs>

        <!-- Parallax Wave Layers (3 lager med olika hastigheter) -->

        <!-- Back layer (slowest) -->
        <g class="wave-layer-back" data-parallax-speed="0.3">
            <path d="M0,80 C300,50 600,50 900,80 C1050,95 1150,95 1200,80 L1200,120 L0,120 Z"
                  fill="{{ $themeColors['primary'] }}"
                  opacity="0.15" />
        </g>

        <!-- Mid layer (medium speed) -->
        <g class="wave-layer-mid" data-parallax-speed="0.5">
            <path d="M0,70 C300,40 600,40 900,70 C1050,90 1150,90 1200,70 L1200,120 L0,120 Z"
                  fill="url(#wave-gradient-{{ $uniqueId }})"
                  opacity="0.5" />
        </g>

        <!-- Front layer (fastest) -->
        <g class="wave-layer-front" data-parallax-speed="0.8">
            <path d="M0,90 C250,70 500,70 750,90 C950,105 1100,105 1200,90 L1200,120 L0,120 Z"
                  fill="{{ $themeColors['secondary'] }}"
                  opacity="0.2" />
        </g>
    </svg>
</div>
