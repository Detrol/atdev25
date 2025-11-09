{{-- Geometric Lines Pattern
     3D Parallax bakgrund med horisontella/vertikala linjer och dots
--}}

<svg class="pattern-bg" viewBox="0 0 1000 1000" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
    {{-- Background layer (slowest movement) --}}
    <g class="parallax-layer-bg" data-speed="30">
        <line x1="0" y1="200" x2="1000" y2="200"
              stroke="var(--color-primary)"
              stroke-width="2"
              opacity="0.15"/>
        <line x1="0" y1="600" x2="1000" y2="600"
              stroke="var(--color-secondary)"
              stroke-width="1"
              opacity="0.1"/>
    </g>

    {{-- Mid layer (medium speed) --}}
    <g class="parallax-layer-mid" data-speed="60">
        <line x1="250" y1="0" x2="250" y2="1000"
              stroke="var(--color-accent)"
              stroke-width="1.5"
              opacity="0.2"/>
        <line x1="750" y1="0" x2="750" y2="1000"
              stroke="var(--color-primary)"
              stroke-width="1"
              opacity="0.15"/>
    </g>

    {{-- Foreground layer (fastest movement) --}}
    <g class="parallax-layer-fg" data-speed="100">
        <circle cx="150" cy="150" r="4"
                fill="var(--color-accent)"
                opacity="0.3"/>
        <circle cx="850" cy="850" r="6"
                fill="var(--color-secondary)"
                opacity="0.25"/>
        <circle cx="500" cy="500" r="5"
                fill="var(--color-primary)"
                opacity="0.2"/>
    </g>
</svg>
