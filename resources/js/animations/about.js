/**
 * About Section Animations
 *
 * Features:
 * - Title reveal with gradient shimmer
 * - Staggered text paragraphs
 * - Image reveal with subtle tilt
 * - Parallax on image
 */

import { gsap, ScrollTrigger } from './gsap-config.js';

export function initAboutAnimations() {
    const aboutSection = document.querySelector('#om-mig');
    if (!aboutSection) return;

    // === MAIN REVEAL TIMELINE ===
    ScrollTrigger.create({
        trigger: '#om-mig',
        start: 'top 75%',
        onEnter: () => {
            animateAboutContent();
        },
        once: true
    });

    // === IMAGE PARALLAX ===
    initImageParallax();
}

/**
 * Main content animation sequence
 */
function animateAboutContent() {
    const timeline = gsap.timeline({
        defaults: {
            ease: 'power3.out'
        }
    });

    timeline
        // Title reveals with scale
        .from('.about-title', {
            x: -50,
            opacity: 0,
            scale: 0.95,
            duration: 1
        })
        // Paragraphs stagger in
        .from('.about-paragraph', {
            y: 30,
            opacity: 0,
            stagger: 0.2,
            duration: 0.8
        }, '-=0.5')
        // Image reveals from right with tilt
        .from('.about-image', {
            x: 60,
            opacity: 0,
            rotation: 3,
            scale: 0.95,
            duration: 1,
            ease: 'power2.out'
        }, '-=0.8');
}

/**
 * Parallax effect on about image
 */
function initImageParallax() {
    const image = document.querySelector('.about-image');
    if (!image) return;

    gsap.to(image, {
        y: -40,
        ease: 'none',
        scrollTrigger: {
            trigger: '#om-mig',
            start: 'top bottom',
            end: 'bottom top',
            scrub: 2
        }
    });
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAboutAnimations);
} else {
    initAboutAnimations();
}
