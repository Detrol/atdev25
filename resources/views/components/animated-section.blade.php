{{-- Animated Section Component

    Återanvändbar section med:
    - Per-sektion färgtema
    - 3D parallax bakgrund
    - Dynamic color morphing till nästa sektion

    Användning:
    <x-animated-section
        theme="purple-blue"
        next-theme="blue-pink"
        pattern="geometric-lines"
        id="om-oss">
        <!-- Ditt innehåll -->
    </x-animated-section>
--}}

@props([
    'theme',
    'nextTheme' => null,
    'pattern' => 'geometric-lines',
    'id' => null,
    'scrollMode' => 'normal', // 'layered' or 'normal'
])

<section
    {{ $attributes->merge(['class' => 'animated-section relative z-10 min-h-screen py-16']) }}
    data-theme="{{ $theme }}"
    @if($nextTheme) data-next-theme="{{ $nextTheme }}" @endif
    @if($id) id="{{ $id }}" @endif
    data-scroll-mode="{{ $scrollMode }}"
>
    {{-- Background Overlay (subtle theme tint) - LIVE animated --}}
    <div class="absolute inset-0 pointer-events-none z-0"
         style="background-color: var(--color-bg-overlay, transparent);"></div>

    {{-- Background Pattern (3D Parallax) --}}
    @if($pattern)
        <div class="pattern-container z-[1]">
            @include("components.patterns.{$pattern}")
        </div>
    @endif

    {{-- Content Layer --}}
    <div class="animated-section-content relative z-20 w-full min-h-screen flex items-center">
        <div class="w-full">
            {{ $slot }}
        </div>
    </div>
</section>
