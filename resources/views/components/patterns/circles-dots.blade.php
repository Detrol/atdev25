{{-- Circles & Dots Pattern
     3D Parallax bakgrund med cirklar i olika storlekar
--}}

<svg class="pattern-bg" viewBox="0 0 1000 1000" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
    {{-- Background layer (slowest) --}}
    <g class="parallax-layer-bg" data-speed="40">
        <circle cx="200" cy="200" r="80"
                fill="none"
                stroke="var(--color-primary)"
                stroke-width="2"
                opacity="0.1"/>
        <circle cx="800" cy="800" r="120"
                fill="none"
                stroke="var(--color-secondary)"
                stroke-width="1.5"
                opacity="0.08"/>
    </g>

    {{-- Mid layer (medium speed) --}}
    <g class="parallax-layer-mid" data-speed="70">
        <circle cx="500" cy="300" r="50"
                fill="var(--color-accent)"
                opacity="0.12"/>
        <circle cx="300" cy="700" r="40"
                fill="var(--color-primary)"
                opacity="0.15"/>
        <circle cx="700" cy="500" r="35"
                fill="var(--color-secondary)"
                opacity="0.1"/>
    </g>

    {{-- Foreground layer (fastest) --}}
    <g class="parallax-layer-fg" data-speed="110">
        {{-- Dot grid --}}
        <circle cx="100" cy="100" r="3" fill="var(--color-text)" opacity="0.2"/>
        <circle cx="900" cy="100" r="3" fill="var(--color-text)" opacity="0.2"/>
        <circle cx="100" cy="900" r="3" fill="var(--color-text)" opacity="0.2"/>
        <circle cx="900" cy="900" r="3" fill="var(--color-text)" opacity="0.2"/>
        <circle cx="500" cy="100" r="2" fill="var(--color-accent)" opacity="0.25"/>
        <circle cx="500" cy="900" r="2" fill="var(--color-accent)" opacity="0.25"/>
    </g>
</svg>
