/**
 * Hero Section Animations
 *
 * Features:
 * - Staggered intro timeline (badges → avatar → h1 → subtitle → CTAs)
 * - Scroll parallax on gradient background
 * - Mouse-driven parallax (desktop only)
 */

import { gsap, ScrollTrigger } from './gsap-config.js';

const isProduction = window.location.hostname === 'atdev.me';

export function initHeroAnimations() {
    const heroSection = document.querySelector('#main-content');
    if (!heroSection) return;

    // === INTRO TIMELINE ===
    // Set initial states FIRST to prevent flash
    gsap.set('.hero-badge', { opacity: 0, y: -30 });
    if (document.querySelector('.hero-avatar')) {
        gsap.set('.hero-avatar', { opacity: 0, scale: 0.8 });
    }
    gsap.set('h1', { opacity: 0, y: 50 });
    gsap.set('.hero-subtitle', { opacity: 0, y: 30 });
    gsap.set('.hero-experience', { opacity: 0, scale: 0.9 });
    gsap.set('.hero-cta', { opacity: 0, y: 20 });

    const heroTL = gsap.timeline({
        defaults: {
            ease: 'power3.out'
        },
        delay: 0.3
    });

    heroTL
        // Badges fade in from top
        .to('.hero-badge', {
            y: 0,
            opacity: 1,
            duration: 0.8,
            stagger: 0.15
        });

    // Avatar scales in (only if it exists)
    if (document.querySelector('.hero-avatar')) {
        heroTL.to('.hero-avatar', {
            scale: 1,
            opacity: 1,
            duration: 0.8
        }, '-=0.4');
    }

    heroTL
        // H1 title slides up
        .to('h1', {
            y: 0,
            opacity: 1,
            duration: 1
        }, '-=0.6')
        // Subtitle follows
        .to('.hero-subtitle', {
            y: 0,
            opacity: 1,
            duration: 0.8
        }, '-=0.5')
        // Experience badge
        .to('.hero-experience', {
            scale: 1,
            opacity: 1,
            duration: 0.6
        }, '-=0.4')
        // CTA buttons stagger in
        .to('.hero-cta', {
            y: 0,
            opacity: 1,
            stagger: 0.15,
            duration: 0.6
        }, '-=0.4');

    if (!isProduction) console.log('✅ Hero intro timeline created with', heroTL.duration().toFixed(2), 'seconds duration');

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
