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
import { viewport } from './viewport-utils.js';

export function initAboutAnimations() {
    const aboutSection = document.querySelector('#om-mig');
    if (!aboutSection) return;

    // === MAIN REVEAL TIMELINE ===
    if (viewport.isMobile) {
        // Set initial states BEFORE observing (mobile only)
        gsap.set('.about-title', { x: -50, opacity: 0, scale: 0.95 });
        gsap.set('.about-paragraph', { y: 30, opacity: 0 });
        gsap.set('.about-image', { x: 30, opacity: 0 });

        // Mobile: IntersectionObserver
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateAboutContent();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.25 });

        observer.observe(aboutSection);
    } else {
        // Desktop: ScrollTrigger
        ScrollTrigger.create({
            trigger: '#om-mig',
            start: 'top 75%',
            onEnter: () => {
                animateAboutContent();
            },
            once: true
        });
    }

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

    if (viewport.isMobile) {
        // Mobile: Animate TO final state (from pre-set initial states)
        timeline
            .to('.about-title', {
                x: 0,
                opacity: 1,
                scale: 1,
                duration: 0.8
            })
            .to('.about-paragraph', {
                y: 0,
                opacity: 1,
                stagger: 0.15,
                duration: 0.6
            }, '-=0.4')
            .to('.about-image', {
                x: 0,
                opacity: 1,
                duration: 0.6,
                ease: 'power2.out'
            }, '-=0.5');
    } else {
        // Desktop: Use FROM (works with ScrollTrigger)
        timeline
            .from('.about-title', {
                x: -50,
                opacity: 0,
                scale: 0.95,
                duration: 1
            })
            .from('.about-paragraph', {
                y: 30,
                opacity: 0,
                stagger: 0.2,
                duration: 0.8
            }, '-=0.5')
            .from('.about-image', {
                x: 60,
                opacity: 0,
                rotation: 3,
                scale: 0.95,
                duration: 1,
                ease: 'power2.out'
            }, '-=0.8');
    }
}

/**
 * Parallax effect on about image
 */
function initImageParallax() {
    // Disable parallax on mobile
    if (viewport.isMobile) {
        return;
    }

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
