/**
 * Wave Divider System - Unified & Optimized
 *
 * Combines separator parallax + nebula effects for wave dividers.
 * Mobile-optimized with merged ScrollTriggers and reduced particle count.
 */

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

const isProduction = window.location.hostname === 'atdev.me';
const isMobile = window.innerWidth < 768;

// ==================== CONFIG ====================

const CONFIG = {
    // Nebula effects (stars + particles inside waves)
    stars: { min: isMobile ? 1 : 2, max: isMobile ? 2 : 3 },
    particles: { min: isMobile ? 1 : 3, max: isMobile ? 1 : 4 },
    starSize: { min: 0.3, max: 0.8 },
    particleSize: { min: 0.15, max: 0.4 },

    // Parallax settings
    parallax: {
        scrub: isMobile ? 1.5 : 0.5, // Higher scrub on mobile for smoother performance
        backLayer: 20,
        midLayer: 10,
        frontLayer: 5
    }
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
        const x = random(50, 1150);
        const y = random(20, 100);

        const starPath = this.createSparkle(size);

        const star = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        star.setAttribute('d', starPath);
        star.setAttribute('fill', color);
        star.setAttribute('opacity', '0.6');
        star.classList.add('divider-star');

        const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        group.setAttribute('transform', `translate(${x}, ${y})`);
        group.style.willChange = 'transform, opacity';
        group.appendChild(star);
        svg.appendChild(group);

        // Twinkle animation
        const timeline = gsap.timeline({ repeat: -1, yoyo: true });
        timeline.to(star, {
            opacity: random(0.1, 0.3),
            duration: random(2, 4),
            ease: 'sine.inOut',
            delay: random(0, 3)
        });

        // Parallax effect
        const starParallax = random(8, 18);
        const parallaxAnim = gsap.to(group, {
            y: starParallax,
            ease: 'none',
            scrollTrigger: {
                trigger: dividerElement,
                start: 'top bottom',
                end: 'bottom top',
                scrub: CONFIG.parallax.scrub
            }
        });

        if (!isProduction && index === 0) {
            console.log(`  ⭐ Star created at (${x.toFixed(0)}, ${y.toFixed(0)})`);
        }

        return { twinkle: timeline, parallax: parallaxAnim };
    }

    static createSparkle(size) {
        const outer = size * 10;
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
        const x = random(50, 1150);
        const y = random(20, 100);

        const particle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        particle.setAttribute('cx', x);
        particle.setAttribute('cy', y);
        particle.setAttribute('r', size * 10);
        particle.setAttribute('fill', color);
        particle.setAttribute('opacity', '0.4');
        particle.classList.add('divider-particle');

        // Disable blur filter on mobile (GPU-intensive)
        if (!isMobile) {
            particle.style.filter = 'url(#dividerGlow)';
        }
        particle.style.willChange = 'transform, opacity';

        svg.appendChild(particle);

        // Disable float animation on mobile (CPU-intensive SVG attribute animations)
        let floatAnim = null;
        if (!isMobile) {
            const floatDistance = random(15, 30);
            const floatAngle = random(0, Math.PI * 2);

            floatAnim = gsap.to(particle, {
                attr: {
                    cx: `+=${Math.cos(floatAngle) * floatDistance}`,
                    cy: `+=${Math.sin(floatAngle) * floatDistance}`
                },
                duration: random(20, 35),
                ease: 'sine.inOut',
                repeat: -1,
                yoyo: true
            });
        }

        // Opacity pulse
        const pulseAnim = gsap.to(particle, {
            opacity: random(0.1, 0.2),
            duration: random(3, 6),
            ease: 'sine.inOut',
            repeat: -1,
            yoyo: true,
            delay: random(0, 2)
        });

        // Parallax effect
        const parallaxDistance = random(15, 25);
        const parallaxAnim = gsap.to(particle, {
            y: parallaxDistance,
            ease: 'none',
            scrollTrigger: {
                trigger: dividerElement,
                start: 'top bottom',
                end: 'bottom top',
                scrub: CONFIG.parallax.scrub
            }
        });

        if (!isProduction && index === 0) {
            console.log(`  ✨ Particle created at (${x.toFixed(0)}, ${y.toFixed(0)})`);
        }

        const anims = { pulse: pulseAnim, parallax: parallaxAnim };
        if (floatAnim) anims.float = floatAnim;
        return anims;
    }
}

// ==================== WAVE DIVIDER SYSTEM ====================

class WaveDividerSystem {
    constructor(divider, index) {
        this.divider = divider;
        this.index = index;
        this.svg = divider.querySelector('svg');
        this.defs = null;
        this.animations = [];
        this.isVisible = false;

        if (!this.svg) {
            console.warn('No SVG found in divider', divider);
            return;
        }

        this.init();
        this.setupIntersectionObserver();
        this.setupParallax();
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

        // Extract color from gradient
        const color = this.extractColor();

        // Create stars
        const starCount = randomInt(CONFIG.stars.min, CONFIG.stars.max);
        for (let i = 0; i < starCount; i++) {
            const starAnims = StarFactory.create(this.svg, this.divider, color, i);
            this.animations.push(...Object.values(starAnims));
        }

        // Create particles
        const particleCount = randomInt(CONFIG.particles.min, CONFIG.particles.max);
        for (let i = 0; i < particleCount; i++) {
            const particleAnims = ParticleFactory.create(this.svg, this.divider, color, i);
            this.animations.push(...Object.values(particleAnims));
        }

        if (!isProduction) {
            console.log(`  🌊 Divider ${this.index}: ${starCount} stars, ${particleCount} particles`);
        }
    }

    createGlowFilter() {
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
        const gradient = this.svg.querySelector('linearGradient stop');
        if (gradient) {
            const stopColor = gradient.getAttribute('style');
            const match = stopColor.match(/stop-color:(#[0-9A-Fa-f]{6})/);
            if (match) {
                return this.lightenColor(match[1], 0.3);
            }
        }
        return '#ffffff';
    }

    lightenColor(hex, amount) {
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);

        const newR = Math.min(255, Math.floor(r + (255 - r) * amount));
        const newG = Math.min(255, Math.floor(g + (255 - g) * amount));
        const newB = Math.min(255, Math.floor(b + (255 - b) * amount));

        return `#${newR.toString(16).padStart(2, '0')}${newG.toString(16).padStart(2, '0')}${newB.toString(16).padStart(2, '0')}`;
    }

    setupParallax() {
        // OPTIMIZED: Merge all 3 layers into ONE ScrollTrigger timeline
        const backLayer = this.divider.querySelector('.wave-layer-back');
        const midLayer = this.divider.querySelector('.wave-layer-mid');
        const frontLayer = this.divider.querySelector('.wave-layer-front');

        if (!backLayer || !midLayer || !frontLayer) return;

        // Single timeline for all 3 layers (3x fewer ScrollTriggers!)
        const timeline = gsap.timeline({
            scrollTrigger: {
                trigger: this.divider,
                start: 'top bottom',
                end: 'bottom top',
                scrub: CONFIG.parallax.scrub
            }
        });

        timeline
            .to(backLayer, { y: CONFIG.parallax.backLayer }, 0)
            .to(midLayer, { y: CONFIG.parallax.midLayer }, 0)
            .to(frontLayer, { y: CONFIG.parallax.frontLayer }, 0);

        this.animations.push(timeline);
    }

    setupIntersectionObserver() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !this.isVisible) {
                    this.resumeAnimations();
                    this.isVisible = true;
                } else if (!entry.isIntersecting && this.isVisible) {
                    this.pauseAnimations();
                    this.isVisible = false;
                }
            });
        }, {
            rootMargin: '100px'
        });

        observer.observe(this.divider);
    }

    pauseAnimations() {
        this.animations.forEach(anim => {
            if (anim && typeof anim.pause === 'function') {
                anim.pause();
            }
        });

        if (!isProduction) {
            console.log(`  ⏸️  Divider ${this.index} animations paused`);
        }
    }

    resumeAnimations() {
        this.animations.forEach(anim => {
            if (anim && typeof anim.resume === 'function') {
                anim.resume();
            }
        });

        if (!isProduction) {
            console.log(`  ▶️  Divider ${this.index} animations resumed`);
        }
    }
}

// ==================== DOM MANIPULATION ====================

function moveDividersOutsideContent() {
    // Move wave dividers OUTSIDE .animated-section-content to prevent z-index conflicts
    const sections = document.querySelectorAll('.animated-section');
    let movedCount = 0;

    sections.forEach(section => {
        const content = section.querySelector('.animated-section-content');
        if (!content) return;

        const wavesInContent = content.querySelectorAll('.wave-divider');

        wavesInContent.forEach(wave => {
            // Deep clone to preserve SVG <defs> references
            const waveClone = wave.cloneNode(true);

            // Update gradient ID to avoid collisions
            const svg = waveClone.querySelector('svg');
            if (svg) {
                const gradient = svg.querySelector('linearGradient[id^="wave-gradient-"]');
                if (gradient) {
                    const oldId = gradient.id;
                    const newId = `wave-gradient-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;

                    gradient.id = newId;

                    const midLayer = svg.querySelector('.wave-layer-mid path');
                    if (midLayer) {
                        const fill = midLayer.getAttribute('fill');
                        if (fill && fill.includes(oldId)) {
                            midLayer.setAttribute('fill', `url(#${newId})`);
                        }
                    }
                }
            }

            section.appendChild(waveClone);
            wave.remove();
            movedCount++;
        });
    });

    if (!isProduction) {
        console.log(`  ✅ Moved ${movedCount} wave dividers outside content layer`);
    }
}

// ==================== MAIN INITIALIZATION ====================

export function initWaveDividerSystem() {
    if (!isProduction) console.log('🌊 Initializing Wave Divider System (unified)');

    // Step 1: Move dividers in DOM tree
    moveDividersOutsideContent();

    // Step 2: Initialize effects on all wave dividers
    const dividers = document.querySelectorAll('.wave-divider');

    if (dividers.length === 0) {
        if (!isProduction) console.log('  ⚠️  No wave dividers found');
        return;
    }

    dividers.forEach((divider, index) => {
        new WaveDividerSystem(divider, index);
    });

    if (!isProduction) {
        console.log(`✨ Wave Divider System initialized (${dividers.length} dividers)`);
        console.log(`   - Mobile optimizations: ${isMobile ? 'ENABLED' : 'DISABLED'}`);
        console.log(`   - Parallax scrub: ${CONFIG.parallax.scrub}`);
        console.log(`   - Particles per divider: ${isMobile ? '1' : '3-4'}`);
    }
}

// Auto-initialize
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWaveDividerSystem);
} else {
    initWaveDividerSystem();
}
