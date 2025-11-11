{{-- Polygons & Grid Pattern
     3D Parallax bakgrund med polygoner och rutnät
--}}

<svg class="pattern-bg" viewBox="0 0 1000 1000" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <pattern id="grid-pattern" width="100" height="100" patternUnits="userSpaceOnUse">
            <path d="M 100 0 L 0 0 0 100"
                  fill="none"
                  stroke="var(--color-primary)"
                  stroke-width="0.5"
                  opacity="0.1"/>
        </pattern>
    </defs>

    {{-- Background layer (slowest) --}}
    <g class="parallax-layer-bg" data-speed="35">
        <rect width="1000" height="1000" fill="url(#grid-pattern)"/>
    </g>

    {{-- Mid layer (medium speed) --}}
    <g class="parallax-layer-mid" data-speed="65">
        <polygon points="500,100 700,400 300,400"
                 fill="none"
                 stroke="var(--color-secondary)"
                 stroke-width="2"
                 opacity="0.15"/>
        <polygon points="200,600 350,700 200,800 50,700"
                 fill="var(--color-accent)"
                 opacity="0.1"/>
    </g>

    {{-- Foreground layer (fastest) --}}
    <g class="parallax-layer-fg" data-speed="95">
        <polygon points="200,700 250,650 300,700 250,750"
                 fill="var(--color-accent)"
                 opacity="0.2"/>
        <polygon points="750,300 800,250 850,300 800,350"
                 fill="var(--color-primary)"
                 opacity="0.18"/>
        <circle cx="600" cy="600" r="8"
                fill="var(--color-secondary)"
                opacity="0.25"/>
    </g>
</svg>
