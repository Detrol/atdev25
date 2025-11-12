/**
 * Space Objects Animation System
 *
 * Adds subtle space-themed elements (asteroids, stars, planets, nebulae)
 * with mix of scroll-driven and continuous floating animations.
 *
 * MOBILE OPTIMIZATION:
 * - Dynamic viewport detection (updates on resize/rotation)
 * - Tighter positioning ranges to prevent objects going off-screen
 * - Reduced drift on mobile (smaller viewport = less room for movement)
 * - Container fills full viewport width (no max-width constraint)
 */

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { viewport } from './viewport-utils.js';

const isProduction = window.location.hostname === 'atdev.me';

// ==================== CONFIG ====================

// Mobile gets half the objects for performance
const CONFIG = {
    asteroids: { min: viewport.isMobile ? 1 : 2, max: viewport.isMobile ? 2 : 3 },
    stars: { min: viewport.isMobile ? 2 : 3, max: viewport.isMobile ? 2 : 4 },
    planets: { min: viewport.isMobile ? 0 : 1, max: viewport.isMobile ? 1 : 2 },
    nebulae: { min: viewport.isMobile ? 0 : 1, max: viewport.isMobile ? 1 : 1 },
    asteroidSize: { min: 2, max: 5 },
    starSize: { min: 0.3, max: 0.8 },
    planetSize: { min: 4, max: 8 },
    nebulaSize: { min: 15, max: 25 }
};

// ==================== UTILITY FUNCTIONS ====================

function random(min, max) {
    return Math.random() * (max - min) + min;
}

function randomInt(min, max) {
    return Math.floor(random(min, max + 1));
}

function randomChoice(array) {
    return array[Math.floor(Math.random() * array.length)];
}

// ==================== ASTEROID FACTORY ====================

class AsteroidFactory {
    static create(svg, index) {
        const size = random(CONFIG.asteroidSize.min, CONFIG.asteroidSize.max);
        const points = this.generateIrregularPolygon(size, randomInt(5, 8));

        const asteroid = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
        asteroid.setAttribute('points', points);
        asteroid.setAttribute('fill', randomChoice(['#6b5b5b', '#7a6a5a', '#8b7a6a']));
        asteroid.setAttribute('opacity', random(0.3, 0.5));
        asteroid.classList.add('space-asteroid');

        // Random position (tighter on mobile to prevent going off-screen)
        const ranges = viewport.getSafeRanges('asteroids');
        const x = random(ranges.x[0], ranges.x[1]);
        const y = random(ranges.y[0], ranges.y[1]);

        const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        group.setAttribute('transform', `translate(${x}, ${y})`);
        group.style.willChange = 'transform'; // GPU acceleration
        group.appendChild(asteroid);
        svg.appendChild(group);

        // Scroll-driven rotation and drift (REDUCED on mobile - less viewport space)
        const asteroidDriftRange = viewport.isMobile ? 5 : 10;

        gsap.to(group, {
            rotation: random(180, 360),
            x: `+=${random(-asteroidDriftRange, asteroidDriftRange)}`,
            y: `+=${random(-asteroidDriftRange, asteroidDriftRange)}`,
            ease: 'none',
            scrollTrigger: {
                trigger: 'body',
                start: 'top top',
                end: 'bottom bottom',
                scrub: viewport.isMobile ? 2 : 2 // Same scrub on mobile for smoother performance
            }
        });

        if (!isProduction) console.log(`  🪨 Asteroid ${index} created at (${x.toFixed(1)}, ${y.toFixed(1)})`);
    }

    static generateIrregularPolygon(radius, sides) {
        let points = '';
        for (let i = 0; i < sides; i++) {
            const angle = (i / sides) * Math.PI * 2;
            const r = radius * random(0.7, 1.3); // Irregular radius
            const x = Math.cos(angle) * r;
            const y = Math.sin(angle) * r;
            points += `${x.toFixed(2)},${y.toFixed(2)} `;
        }
        return points.trim();
    }
}

// ==================== STAR FACTORY ====================

class StarFactory {
    static create(svg, index) {
        const size = random(CONFIG.starSize.min, CONFIG.starSize.max);
        const ranges = viewport.getSafeRanges('stars');
        const x = random(ranges.x[0], ranges.x[1]);
        const y = random(ranges.y[0], ranges.y[1]);

        // 4-pointed star shape
        const starPath = this.createStarPath(size);

        const star = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        star.setAttribute('d', starPath);
        star.setAttribute('fill', randomChoice(['#ffffff', '#ffffcc', '#fff8dc']));
        star.setAttribute('filter', 'url(#starGlow)');
        star.classList.add('space-star');

        const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        group.setAttribute('transform', `translate(${x}, ${y})`);
        star.style.willChange = 'opacity'; // GPU acceleration for twinkle
        group.appendChild(star);
        svg.appendChild(group);

        // Twinkle animation (continuous)
        const timeline = gsap.timeline({ repeat: -1, yoyo: true });
        timeline.to(star, {
            opacity: random(0.2, 0.4),
            duration: random(1.5, 3),
            ease: 'sine.inOut',
            delay: random(0, 2)
        });

        if (!isProduction) console.log(`  ⭐ Star ${index} created at (${x.toFixed(1)}, ${y.toFixed(1)})`);
    }

    static createStarPath(size) {
        // Simple 4-pointed star
        const outer = size;
        const inner = size * 0.3;
        return `
            M 0 ${-outer}
            L ${inner * 0.3} ${-inner * 0.3}
            L ${outer} 0
            L ${inner * 0.3} ${inner * 0.3}
            L 0 ${outer}
            L ${-inner * 0.3} ${inner * 0.3}
            L ${-outer} 0
            L ${-inner * 0.3} ${-inner * 0.3}
            Z
        `.trim();
    }
}

// ==================== PLANET FACTORY ====================

class PlanetFactory {
    static create(svg, defs, index) {
        const size = random(CONFIG.planetSize.min, CONFIG.planetSize.max);
        const ranges = viewport.getSafeRanges('planets');
        const x = random(ranges.x[0], ranges.x[1]);
        const y = random(ranges.y[0], ranges.y[1]);

        // Create gradient for planet
        const gradientId = `planetGradient${index}`;
        this.createPlanetGradient(defs, gradientId, index);

        const planet = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        planet.setAttribute('r', size);
        planet.setAttribute('fill', `url(#${gradientId})`);
        planet.setAttribute('opacity', random(0.4, 0.6));
        planet.classList.add('space-planet');

        const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        group.setAttribute('transform', `translate(${x}, ${y})`);
        group.style.willChange = 'transform'; // GPU acceleration for drift
        group.appendChild(planet);

        // Maybe add ring
        if (Math.random() < 0.5) {
            const ring = document.createElementNS('http://www.w3.org/2000/svg', 'ellipse');
            ring.setAttribute('rx', size * 1.6);
            ring.setAttribute('ry', size * 0.4);
            ring.setAttribute('fill', 'none');
            ring.setAttribute('stroke', 'rgba(255, 255, 255, 0.2)');
            ring.setAttribute('stroke-width', '0.3');
            group.insertBefore(ring, planet); // Ring behind planet
        }

        svg.appendChild(group);

        // Slow continuous drift (REDUCED on mobile to stay within viewport, no rotation on mobile)
        const driftRange = viewport.isMobile ? 8 : 15;
        const driftDuration = viewport.isMobile ? random(25, 40) : random(40, 60);

        const animConfig = {
            x: `+=${random(-driftRange, driftRange)}`,
            y: `+=${random(-driftRange, driftRange)}`,
            duration: driftDuration,
            ease: 'sine.inOut',
            repeat: -1,
            yoyo: true
        };

        // Add rotation only on desktop (CPU intensive)
        if (!viewport.isMobile) {
            animConfig.rotation = random(10, 30);
        }

        gsap.to(group, animConfig);

        if (!isProduction) console.log(`  🪐 Planet ${index} created at (${x.toFixed(1)}, ${y.toFixed(1)})`);
    }

    static createPlanetGradient(defs, gradientId, index) {
        const gradient = document.createElementNS('http://www.w3.org/2000/svg', 'radialGradient');
        gradient.setAttribute('id', gradientId);

        const colors = [
            ['#4a90e2', '#2c5f99'],  // Blue
            ['#9b59b6', '#6c3483'],  // Purple
            ['#e74c3c', '#922b21'],  // Red
            ['#f39c12', '#b9770e']   // Orange
        ];

        const [color1, color2] = randomChoice(colors);

        const stop1 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
        stop1.setAttribute('offset', '0%');
        stop1.setAttribute('style', `stop-color:${color1};stop-opacity:1`);

        const stop2 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
        stop2.setAttribute('offset', '100%');
        stop2.setAttribute('style', `stop-color:${color2};stop-opacity:1`);

        gradient.appendChild(stop1);
        gradient.appendChild(stop2);
        defs.appendChild(gradient);
    }
}

// ==================== NEBULA FACTORY ====================

class NebulaFactory {
    static create(svg, index) {
        const size = random(CONFIG.nebulaSize.min, CONFIG.nebulaSize.max);
        const ranges = viewport.getSafeRanges('nebulae');
        const x = random(ranges.x[0], ranges.x[1]);
        const y = random(ranges.y[0], ranges.y[1]);

        // Create organic blob shape
        const path = this.createBlobPath(size);

        const nebula = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        nebula.setAttribute('d', path);
        nebula.setAttribute('fill', randomChoice([
            'rgba(147, 51, 234, 0.15)',  // Purple
            'rgba(236, 72, 153, 0.15)',  // Pink
            'rgba(59, 130, 246, 0.15)'   // Blue
        ]));
        nebula.setAttribute('filter', 'url(#nebulaBlur)');
        nebula.classList.add('space-nebula');

        const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        group.setAttribute('transform', `translate(${x}, ${y})`);
        group.style.willChange = 'transform'; // GPU acceleration for drift
        group.appendChild(nebula);
        svg.appendChild(group);

        // Very slow subtle drift (REDUCED on mobile to stay within viewport, no rotation on mobile)
        const nebulaDriftRange = viewport.isMobile ? 5 : 8;
        const nebulaDuration = viewport.isMobile ? random(40, 60) : random(60, 90);

        const animConfig = {
            x: `+=${random(-nebulaDriftRange, nebulaDriftRange)}`,
            y: `+=${random(-nebulaDriftRange, nebulaDriftRange)}`,
            duration: nebulaDuration,
            ease: 'sine.inOut',
            repeat: -1,
            yoyo: true
        };

        // Add rotation only on desktop (CPU intensive)
        if (!isMobile) {
            animConfig.rotation = random(-15, 15);
        }

        gsap.to(group, animConfig);

        if (!isProduction) console.log(`  ☁️ Nebula ${index} created at (${x.toFixed(1)}, ${y.toFixed(1)})`);
    }

    static createBlobPath(size) {
        // Create organic blob using bezier curves
        const points = 8;
        let path = '';

        for (let i = 0; i < points; i++) {
            const angle = (i / points) * Math.PI * 2;
            const nextAngle = ((i + 1) / points) * Math.PI * 2;

            const r = size * random(0.6, 1.0);
            const nextR = size * random(0.6, 1.0);

            const x = Math.cos(angle) * r;
            const y = Math.sin(angle) * r;
            const nextX = Math.cos(nextAngle) * nextR;
            const nextY = Math.sin(nextAngle) * nextR;

            // Control points for smooth curve
            const cpDist = size * 0.4;
            const cp1X = x + Math.cos(angle + Math.PI / 2) * cpDist;
            const cp1Y = y + Math.sin(angle + Math.PI / 2) * cpDist;
            const cp2X = nextX + Math.cos(nextAngle - Math.PI / 2) * cpDist;
            const cp2Y = nextY + Math.sin(nextAngle - Math.PI / 2) * cpDist;

            if (i === 0) {
                path = `M ${x} ${y}`;
            }
            path += ` C ${cp1X} ${cp1Y}, ${cp2X} ${cp2Y}, ${nextX} ${nextY}`;
        }

        return path + ' Z';
    }
}

// ==================== SVG BUILDER ====================

class SVGBuilder {
    constructor(container) {
        this.container = container;
        this.svg = this.createSVG();
        this.defs = this.createDefs();
    }

    createSVG() {
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('width', '100%');
        svg.setAttribute('height', '100%');
        svg.setAttribute('viewBox', '0 0 100 100');
        svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');
        svg.style.cssText = 'position: absolute; top: 0; left: 0; width: 100%; height: 100%;';
        this.container.appendChild(svg);
        return svg;
    }

    createDefs() {
        const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');

        // Star glow filter
        const starGlow = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
        starGlow.setAttribute('id', 'starGlow');
        starGlow.setAttribute('x', '-200%');
        starGlow.setAttribute('y', '-200%');
        starGlow.setAttribute('width', '500%');
        starGlow.setAttribute('height', '500%');

        const blur1 = document.createElementNS('http://www.w3.org/2000/svg', 'feGaussianBlur');
        blur1.setAttribute('stdDeviation', '2');
        blur1.setAttribute('result', 'coloredBlur');

        const merge1 = document.createElementNS('http://www.w3.org/2000/svg', 'feMerge');
        const mergeNode1 = document.createElementNS('http://www.w3.org/2000/svg', 'feMergeNode');
        mergeNode1.setAttribute('in', 'coloredBlur');
        const mergeNode2 = document.createElementNS('http://www.w3.org/2000/svg', 'feMergeNode');
        mergeNode2.setAttribute('in', 'SourceGraphic');
        merge1.appendChild(mergeNode1);
        merge1.appendChild(mergeNode2);

        starGlow.appendChild(blur1);
        starGlow.appendChild(merge1);
        defs.appendChild(starGlow);

        // Nebula blur filter
        const nebulaBlur = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
        nebulaBlur.setAttribute('id', 'nebulaBlur');
        nebulaBlur.setAttribute('x', '-50%');
        nebulaBlur.setAttribute('y', '-50%');
        nebulaBlur.setAttribute('width', '200%');
        nebulaBlur.setAttribute('height', '200%');

        const blur2 = document.createElementNS('http://www.w3.org/2000/svg', 'feGaussianBlur');
        blur2.setAttribute('stdDeviation', '8');
        nebulaBlur.appendChild(blur2);
        defs.appendChild(nebulaBlur);

        this.svg.appendChild(defs);
        return defs;
    }
}

// ==================== SPACE OBJECTS SYSTEM ====================

class SpaceObjectsSystem {
    constructor(container) {
        this.container = container;
        this.svgBuilder = new SVGBuilder(container);
        this.svg = this.svgBuilder.svg;
        this.defs = this.svgBuilder.defs;
    }

    init() {
        if (!isProduction) console.log('🌌 Initializing Space Objects System');

        // Create asteroids (scroll-driven)
        const asteroidCount = randomInt(CONFIG.asteroids.min, CONFIG.asteroids.max);
        for (let i = 0; i < asteroidCount; i++) {
            AsteroidFactory.create(this.svg, i);
        }

        // Create stars (twinkle)
        const starCount = randomInt(CONFIG.stars.min, CONFIG.stars.max);
        for (let i = 0; i < starCount; i++) {
            StarFactory.create(this.svg, i);
        }

        // Create planets (slow float)
        const planetCount = randomInt(CONFIG.planets.min, CONFIG.planets.max);
        for (let i = 0; i < planetCount; i++) {
            PlanetFactory.create(this.svg, this.defs, i);
        }

        // Create nebulae (subtle drift)
        const nebulaCount = randomInt(CONFIG.nebulae.min, CONFIG.nebulae.max);
        for (let i = 0; i < nebulaCount; i++) {
            NebulaFactory.create(this.svg, i);
        }

        if (!isProduction) {
            console.log(`✨ Space objects created: ${asteroidCount + starCount + planetCount + nebulaCount} total`);
        }
    }
}

// ==================== MAIN INITIALIZATION ====================

export function initSpaceObjects() {
    const isHomePage = window.location.pathname === '/' || window.location.pathname === '/home';
    if (!isHomePage) {
        if (!isProduction) console.log('🌌 Space Objects: Skipping (not home page)');
        return;
    }

    if (document.querySelector('.space-objects-container')) {
        return; // Already initialized
    }

    const container = document.createElement('div');
    container.className = 'space-objects-container';

    // Mobile: Full width & height (covers entire page, not just viewport)
    // Desktop: Centered 1400px container
    const containerStyles = viewport.isMobile
        ? `
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            min-height: 100vh;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: visible;
        `
        : `
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 1400px;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            overflow: visible;
        `;

    container.style.cssText = containerStyles;

    document.body.appendChild(container);

    const system = new SpaceObjectsSystem(container);
    system.init();
}

// Auto-initialize
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSpaceObjects);
} else {
    initSpaceObjects();
}
