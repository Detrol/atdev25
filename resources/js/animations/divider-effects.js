/**
 * Wave Divider Effects - Nebula Interior
 *
 * Adds twinkling stars and floating stardust particles inside wave dividers
 * to create a subtle nebula/space effect.
 */

import { gsap } from 'gsap';

const isProduction = window.location.hostname === 'atdev.me';

// ==================== CONFIG ====================

const CONFIG = {
    stars: { min: 3, max: 5 },        // Per divider
    particles: { min: 4, max: 6 },    // Per divider
    starSize: { min: 0.3, max: 0.8 },
    particleSize: { min: 0.15, max: 0.4 }
};

// ==================== UTILITY FUNCTIONS ====================

function random(min, max) {
    return Math.random() * (max - min) + min;
}

function randomInt(min, max) {
    return Math.floor(random(min, max + 1));
}

// ==================== STAR FACTORY ====================

class StarFactory {
    static create(svg, dividerElement, color, index) {
        const size = random(CONFIG.starSize.min, CONFIG.starSize.max);

        // Position within divider bounds (viewBox 0-1200, height 0-120)
        const x = random(50, 1150);
        const y = random(20, 100); // Keep within wave area

        // Simple 4-pointed sparkle
        const starPath = this.createSparkle(size);

        const star = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        star.setAttribute('d', starPath);
        star.setAttribute('fill', color);
        star.setAttribute('opacity', '0.6');
        star.classList.add('divider-star');

        const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        group.setAttribute('transform', `translate(${x}, ${y})`);
        group.appendChild(star);
        svg.appendChild(group);

        // Twinkle animation (slow, subtle)
        const timeline = gsap.timeline({ repeat: -1, yoyo: true });
        timeline.to(star, {
            opacity: random(0.1, 0.3),
            duration: random(2, 4),
            ease: 'sine.inOut',
            delay: random(0, 3)
        });

        // Parallax effect on scroll (subtle vertical movement)
        gsap.to(group, {
            y: random(5, 15), // Stars move slower for depth
            ease: 'none',
            scrollTrigger: {
                trigger: dividerElement,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1
            }
        });

        if (!isProduction && index === 0) {
            console.log(`  ⭐ Divider star created at (${x.toFixed(0)}, ${y.toFixed(0)})`);
        }
    }

    static createSparkle(size) {
        // Simple 4-pointed star/sparkle
        const outer = size * 10; // Scale up for viewBox
        const inner = outer * 0.3;
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

// ==================== PARTICLE FACTORY ====================

class ParticleFactory {
    static create(svg, dividerElement, color, index) {
        const size = random(CONFIG.particleSize.min, CONFIG.particleSize.max);

        // Position within divider bounds
        const x = random(50, 1150);
        const y = random(20, 100);

        const particle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        particle.setAttribute('cx', x);
        particle.setAttribute('cy', y);
        particle.setAttribute('r', size * 10); // Scale for viewBox
        particle.setAttribute('fill', color);
        particle.setAttribute('opacity', '0.4');
        particle.classList.add('divider-particle');

        // Add soft glow
        particle.style.filter = 'url(#dividerGlow)';

        svg.appendChild(particle);

        // Slow floating animation
        const floatDistance = random(15, 30);
        const floatAngle = random(0, Math.PI * 2);

        gsap.to(particle, {
            attr: {
                cx: `+=${Math.cos(floatAngle) * floatDistance}`,
                cy: `+=${Math.sin(floatAngle) * floatDistance}`
            },
            duration: random(20, 35),
            ease: 'sine.inOut',
            repeat: -1,
            yoyo: true
        });

        // Subtle opacity pulse
        gsap.to(particle, {
            opacity: random(0.1, 0.2),
            duration: random(3, 6),
            ease: 'sine.inOut',
            repeat: -1,
            yoyo: true,
            delay: random(0, 2)
        });

        // Parallax effect on scroll (faster than stars for depth layering)
        gsap.to(particle, {
            attr: {
                cy: `+=${random(20, 35)}` // Particles move faster (closer to viewer)
            },
            ease: 'none',
            scrollTrigger: {
                trigger: dividerElement,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 0.5 // Faster scrub for more responsive feel
            }
        });

        if (!isProduction && index === 0) {
            console.log(`  ✨ Divider particle created at (${x.toFixed(0)}, ${y.toFixed(0)})`);
        }
    }
}

// ==================== DIVIDER EFFECTS SYSTEM ====================

class DividerEffectsSystem {
    constructor(divider, index) {
        this.divider = divider;
        this.index = index;
        this.svg = divider.querySelector('svg');
        this.defs = null;

        if (!this.svg) {
            console.warn('No SVG found in divider', divider);
            return;
        }

        this.init();
    }

    init() {
        // Get or create defs
        this.defs = this.svg.querySelector('defs');
        if (!this.defs) {
            this.defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
            this.svg.insertBefore(this.defs, this.svg.firstChild);
        }

        // Add glow filter for particles
        this.createGlowFilter();

        // Extract color from divider
        const color = this.extractColor();

        // Create stars
        const starCount = randomInt(CONFIG.stars.min, CONFIG.stars.max);
        for (let i = 0; i < starCount; i++) {
            StarFactory.create(this.svg, this.divider, color, i);
        }

        // Create particles
        const particleCount = randomInt(CONFIG.particles.min, CONFIG.particles.max);
        for (let i = 0; i < particleCount; i++) {
            ParticleFactory.create(this.svg, this.divider, color, i);
        }

        if (!isProduction) {
            console.log(`  🌊 Divider ${this.index}: ${starCount} stars, ${particleCount} particles`);
        }
    }

    createGlowFilter() {
        // Check if filter already exists
        if (this.svg.querySelector('#dividerGlow')) return;

        const filter = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
        filter.setAttribute('id', 'dividerGlow');
        filter.setAttribute('x', '-100%');
        filter.setAttribute('y', '-100%');
        filter.setAttribute('width', '300%');
        filter.setAttribute('height', '300%');

        const blur = document.createElementNS('http://www.w3.org/2000/svg', 'feGaussianBlur');
        blur.setAttribute('stdDeviation', '2');
        blur.setAttribute('result', 'coloredBlur');

        const merge = document.createElementNS('http://www.w3.org/2000/svg', 'feMerge');
        const mergeNode1 = document.createElementNS('http://www.w3.org/2000/svg', 'feMergeNode');
        mergeNode1.setAttribute('in', 'coloredBlur');
        const mergeNode2 = document.createElementNS('http://www.w3.org/2000/svg', 'feMergeNode');
        mergeNode2.setAttribute('in', 'SourceGraphic');

        merge.appendChild(mergeNode1);
        merge.appendChild(mergeNode2);
        filter.appendChild(blur);
        filter.appendChild(merge);

        this.defs.appendChild(filter);
    }

    extractColor() {
        // Try to extract color from gradient or paths
        const gradient = this.svg.querySelector('linearGradient stop');
        if (gradient) {
            const stopColor = gradient.getAttribute('style');
            const match = stopColor.match(/stop-color:(#[0-9A-Fa-f]{6})/);
            if (match) {
                return this.lightenColor(match[1], 0.3); // Lighter version
            }
        }

        // Fallback to white
        return '#ffffff';
    }

    lightenColor(hex, amount) {
        // Convert hex to RGB
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);

        // Lighten
        const newR = Math.min(255, Math.floor(r + (255 - r) * amount));
        const newG = Math.min(255, Math.floor(g + (255 - g) * amount));
        const newB = Math.min(255, Math.floor(b + (255 - b) * amount));

        // Convert back to hex
        return `#${newR.toString(16).padStart(2, '0')}${newG.toString(16).padStart(2, '0')}${newB.toString(16).padStart(2, '0')}`;
    }
}

// ==================== MAIN INITIALIZATION ====================

export function initDividerEffects() {
    // Wait for separator animations to move dividers first
    setTimeout(() => {
        const dividers = document.querySelectorAll('.wave-divider');

        if (dividers.length === 0) {
            if (!isProduction) console.log('🌊 No wave dividers found');
            return;
        }

        if (!isProduction) console.log(`🌊 Initializing Divider Effects (${dividers.length} dividers)`);

        dividers.forEach((divider, index) => {
            new DividerEffectsSystem(divider, index);
        });

        if (!isProduction) {
            console.log(`✨ Divider effects initialized`);
        }
    }, 100); // Short delay to ensure dividers are moved by separator-animations.js
}

// Auto-initialize
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDividerEffects);
} else {
    initDividerEffects();
}
