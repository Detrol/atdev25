/**
 * Global Parallax Effects
 *
 * Features:
 * - Multi-layer scroll parallax across sections
 * - Different speeds create depth perception
 * - Performance optimized with GSAP's scrub
 */

import { gsap, ScrollTrigger } from './gsap-config.js';

export function initGlobalParallax() {
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

    // === SERVICES SECTION CARDS ===
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach((card, index) => {
        // Alternate parallax direction for visual interest
        const direction = index % 2 === 0 ? 10 : -10;

        gsap.to(card, {
            y: `${direction}%`,
            ease: 'none',
            scrollTrigger: {
                trigger: card,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1.5
            }
        });
    });

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
