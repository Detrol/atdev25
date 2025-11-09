/**
 * Hero Section Animations
 *
 * Features:
 * - Staggered intro timeline (badges → avatar → h1 → subtitle → CTAs)
 * - Scroll parallax on gradient background
 * - Mouse-driven parallax (desktop only)
 */

import { gsap, ScrollTrigger } from './gsap-config.js';

export function initHeroAnimations() {
    const heroSection = document.querySelector('#main-content');
    if (!heroSection) return;

    // === INTRO TIMELINE ===
    const heroTL = gsap.timeline({
        defaults: {
            ease: 'power3.out'
        }
    });

    heroTL
        // Badges fade in from top
        .from('.hero-badge', {
            y: -30,
            opacity: 0,
            duration: 0.8,
            stagger: 0.15
        });

    // Avatar scales in (only if it exists)
    if (document.querySelector('.hero-avatar')) {
        heroTL.from('.hero-avatar', {
            scale: 0.8,
            opacity: 0,
            duration: 0.8
        }, '-=0.4'); // Overlap with badges
    }

    heroTL
        // H1 title slides up
        .from('h1', {
            y: 50,
            opacity: 0,
            duration: 1
        }, '-=0.6')
        // Subtitle follows
        .from('.hero-subtitle', {
            y: 30,
            opacity: 0,
            duration: 0.8
        }, '-=0.5')
        // Experience badge
        .from('.hero-experience', {
            scale: 0.9,
            opacity: 0,
            duration: 0.6
        }, '-=0.4')
        // CTA buttons stagger in
        .from('.hero-cta', {
            y: 20,
            opacity: 0,
            stagger: 0.15,
            duration: 0.6
        }, '-=0.4');

    // === SCROLL PARALLAX (Decorative background elements) ===
    initBackgroundParallax();

    // === MOUSE-DRIVEN PARALLAX (Desktop Only, Increased Strength) ===
    const isDesktop = window.matchMedia('(min-width: 768px)').matches;
    if (isDesktop) {
        initMouseParallax();
    }
}

/**
 * Background parallax effect for decorative elements
 * Different speeds create depth perception
 */
function initBackgroundParallax() {
    // Apply scroll parallax to wrappers (CSS animations run on inner elements)
    // This allows both microbe motion + scroll parallax to work together
    const wrappers = document.querySelectorAll('.parallax-wrapper');

    wrappers.forEach((wrapper, index) => {
        // Alternate speeds for depth variation
        const speeds = [30, -25, 35, -40, 28, -32];
        const speed = speeds[index] || 30;

        gsap.to(wrapper, {
            yPercent: speed,
            ease: 'none',
            force3D: true, // GPU acceleration
            scrollTrigger: {
                trigger: '#main-content',
                start: 'top top',
                end: 'bottom top',
                scrub: 1.5 // Smooth parallax
            }
        });
    });
}

/**
 * Mouse-driven parallax effect
 * Elements follow mouse position subtly for depth
 */
function initMouseParallax() {
    const parallaxElements = [
        { selector: '.hero-badge', strength: 8 },
        { selector: '.hero-avatar', strength: 12 },
        { selector: 'h1', strength: 5 },
        { selector: '.hero-experience', strength: 7 }
    ];

    let mouseX = 0;
    let mouseY = 0;
    let currentX = 0;
    let currentY = 0;

    // Track mouse position
    document.addEventListener('mousemove', (e) => {
        const { clientX, clientY } = e;
        const { innerWidth, innerHeight } = window;

        // Normalize to -1 to 1 range
        mouseX = (clientX / innerWidth - 0.5) * 2;
        mouseY = (clientY / innerHeight - 0.5) * 2;
    });

    // Smooth animation loop
    function animate() {
        // Lerp for smooth following
        currentX += (mouseX - currentX) * 0.1;
        currentY += (mouseY - currentY) * 0.1;

        parallaxElements.forEach(({ selector, strength }) => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(el => {
                const x = currentX * strength;
                const y = currentY * strength;
                gsap.to(el, {
                    x,
                    y,
                    duration: 0.3,
                    ease: 'power2.out'
                });
            });
        });

        requestAnimationFrame(animate);
    }

    animate();
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeroAnimations);
} else {
    initHeroAnimations();
}
