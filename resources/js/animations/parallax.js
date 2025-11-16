/**
 * Global Parallax Effects
 *
 * Features:
 * - Multi-layer scroll parallax across sections
 * - Different speeds create depth perception
 * - Performance optimized with GSAP's scrub
 */

import { gsap, ScrollTrigger } from './gsap-config.js';

const isProduction = window.location.hostname === 'atdev.me';

export function initGlobalParallax() {
    // Skip all parallax effects if animations are disabled
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (isMobile || prefersReducedMotion) {
        if (!isProduction) console.log('✋ Global Parallax Effects disabled (mobile or reduced motion)');
        return;
    }

    if (!isProduction) console.log('🎨 Initializing Global Parallax Effects');

    // === PATTERN BACKGROUNDS (Geometric shapes in sections) ===
    // Advanced parallax with multi-directional movement for depth
    const bgLayers = document.querySelectorAll('.parallax-layer-bg');
    const midLayers = document.querySelectorAll('.parallax-layer-mid');
    const fgLayers = document.querySelectorAll('.parallax-layer-fg');

    // Background layers - move UP (opposite direction for depth)
    bgLayers.forEach(layer => {
        const speed = parseInt(layer.getAttribute('data-speed') || '30', 10);
        const distance = (speed / 100) * -40; // Negative for upward movement

        gsap.to(layer, {
            y: distance,
            ease: 'none',
            scrollTrigger: {
                trigger: layer.closest('section') || layer.closest('.animated-section'),
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1.5
            }
        });
    });

    // Mid layers - move DOWN (normal scroll direction)
    midLayers.forEach(layer => {
        const speed = parseInt(layer.getAttribute('data-speed') || '60', 10);
        const distance = (speed / 100) * 50; // Positive for downward movement

        gsap.to(layer, {
            y: distance,
            ease: 'none',
            scrollTrigger: {
                trigger: layer.closest('section') || layer.closest('.animated-section'),
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1.5
            }
        });
    });

    // Foreground layers - move UP faster (creates depth illusion)
    fgLayers.forEach(layer => {
        const speed = parseInt(layer.getAttribute('data-speed') || '100', 10);
        const distance = (speed / 100) * -60; // Negative + faster for dramatic effect

        gsap.to(layer, {
            y: distance,
            ease: 'none',
            scrollTrigger: {
                trigger: layer.closest('section') || layer.closest('.animated-section'),
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1.5
            }
        });
    });

    if (!isProduction) console.log(`✅ Advanced parallax initialized: ${bgLayers.length} bg, ${midLayers.length} mid, ${fgLayers.length} fg layers`);

    // === HERO GRADIENT MESH (already handled in hero.js) ===
    // Keeping this centralized for future global parallax elements

    // === ABOUT SECTION IMAGES ===
    const aboutImages = document.querySelectorAll('#about img');
    aboutImages.forEach(img => {
        gsap.to(img, {
            y: '15%',
            ease: 'none',
            scrollTrigger: {
                trigger: img.closest('section'),
                start: 'top bottom',
                end: 'bottom top',
                scrub: 2
            }
        });
    });

    // === SERVICES SECTION CARDS === (DISABLED - No parallax on service cards)
    // const serviceCards = document.querySelectorAll('.service-card');
    // serviceCards.forEach((card, index) => {
    //     // Alternate parallax direction for visual interest
    //     const direction = index % 2 === 0 ? 10 : -10;

    //     gsap.to(card, {
    //         y: `${direction}%`,
    //         ease: 'none',
    //         scrollTrigger: {
    //             trigger: card,
    //             start: 'top bottom',
    //             end: 'bottom top',
    //             scrub: 1.5
    //         }
    //     });
    // });

    // === FAQ SECTION ITEMS ===
    const faqItems = document.querySelectorAll('.faq-item');
    if (faqItems.length > 0) {
        gsap.from(faqItems, {
            opacity: 0,
            y: 40,
            stagger: 0.1,
            duration: 0.8,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: '.faq-container',
                start: 'top 75%',
                once: true
            }
        });
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGlobalParallax);
} else {
    initGlobalParallax();
}
