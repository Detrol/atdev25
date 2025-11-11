{{-- Waves Pattern
     3D Parallax bakgrund med flytande vågor och bubblor
--}}

<svg class="pattern-bg" viewBox="0 0 1000 1000" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
    {{-- Background layer (slowest movement) --}}
    <g class="parallax-layer-bg" data-speed="30">
        <path d="M 0 200 Q 250 150, 500 200 T 1000 200 L 1000 0 L 0 0 Z"
              fill="var(--color-primary)"
              opacity="0.08"/>
        <path d="M 0 800 Q 250 750, 500 800 T 1000 800 L 1000 1000 L 0 1000 Z"
              fill="var(--color-secondary)"
              opacity="0.06"/>
    </g>

    {{-- Mid layer (medium speed) --}}
    <g class="parallax-layer-mid" data-speed="60">
        <path d="M 0 400 Q 250 350, 500 400 T 1000 400"
              stroke="var(--color-accent)"
              stroke-width="2"
              fill="none"
              opacity="0.15"/>
        <path d="M 0 600 Q 250 650, 500 600 T 1000 600"
              stroke="var(--color-primary)"
              stroke-width="1.5"
              fill="none"
              opacity="0.12"/>
    </g>

    {{-- Foreground layer (fastest movement) --}}
    <g class="parallax-layer-fg" data-speed="100">
        <circle cx="200" cy="300" r="8"
                fill="var(--color-accent)"
                opacity="0.25"/>
        <circle cx="700" cy="700" r="10"
                fill="var(--color-secondary)"
                opacity="0.2"/>
        <circle cx="500" cy="500" r="6"
                fill="var(--color-primary)"
                opacity="0.3"/>
        <circle cx="150" cy="650" r="5"
                fill="var(--color-accent)"
                opacity="0.2"/>
        <circle cx="850" cy="250" r="7"
                fill="var(--color-secondary)"
                opacity="0.25"/>
    </g>
</svg>
