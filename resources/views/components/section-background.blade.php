{{-- Reusable Section Background Orbs Component --}}
@props(['theme' => 'purple', 'density' => 'normal'])

@php
// Define orb configurations per theme with more intense colors for light mode visibility
$themes = [
    'purple' => [
        ['color' => 'rgba(168, 85, 247, 0.95)', 'size' => '350px', 'pos' => 'top-[15%] left-[8%]', 'anim' => 'animate-microbe-wander'],
        ['color' => 'rgba(139, 92, 246, 0.85)', 'size' => '300px', 'pos' => 'bottom-[20%] right-[10%]', 'anim' => 'animate-microbe-pulse'],
    ],
    'blue' => [
        ['color' => 'rgba(59, 130, 246, 0.95)', 'size' => '330px', 'pos' => 'top-[10%] right-[12%]', 'anim' => 'animate-microbe-2'],
        ['color' => 'rgba(96, 165, 250, 0.85)', 'size' => '280px', 'pos' => 'bottom-[15%] left-[15%]', 'anim' => 'animate-microbe-drift'],
    ],
    'pink' => [
        ['color' => 'rgba(236, 72, 153, 0.95)', 'size' => '340px', 'pos' => 'top-[20%] left-[5%]', 'anim' => 'animate-microbe-3'],
        ['color' => 'rgba(244, 114, 182, 0.85)', 'size' => '290px', 'pos' => 'bottom-[25%] right-[8%]', 'anim' => 'animate-microbe-1'],
    ],
    'mixed' => [
        ['color' => 'rgba(168, 85, 247, 0.9)', 'size' => '320px', 'pos' => 'top-[12%] left-[10%]', 'anim' => 'animate-microbe-wander'],
        ['color' => 'rgba(59, 130, 246, 0.9)', 'size' => '300px', 'pos' => 'bottom-[18%] right-[12%]', 'anim' => 'animate-microbe-2'],
        ['color' => 'rgba(236, 72, 153, 0.8)', 'size' => '280px', 'pos' => 'top-[50%] left-[50%] -translate-x-1/2 -translate-y-1/2', 'anim' => 'animate-microbe-pulse'],
    ],
];

// Get orbs for selected theme
$orbs = $themes[$theme] ?? $themes['purple'];

// Reduce orb count for sparse density
if ($density === 'sparse') {
    $orbs = array_slice($orbs, 0, 2); // Only first 2 orbs
}
@endphp

{{-- Background container --}}
<div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
    @foreach($orbs as $orb)
    <div class="absolute {{ $orb['pos'] }} {{ $orb['anim'] }} opacity-50 dark:opacity-40"
         style="width: {{ $orb['size'] }}; height: {{ $orb['size'] }}; background: radial-gradient(circle, {{ $orb['color'] }} 0%, transparent 70%); filter: blur(40px); will-change: transform;">
    </div>
    @endforeach
</div>
