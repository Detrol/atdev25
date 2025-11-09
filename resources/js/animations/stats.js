/**
 * Stats Section Animations
 *
 * Features:
 * - Animated counters with GSAP (replaces LazyLoadManager counters)
 * - ScrollTrigger activation when stats come into view
 * - Smooth number increments with easing
 */

import { gsap, ScrollTrigger } from './gsap-config.js';

export function initStatsAnimations() {
    const statsSection = document.querySelector('.stats-grid');
    if (!statsSection) return;

    const statCards = statsSection.querySelectorAll(':scope > div');
    if (statCards.length === 0) return;

    // === SET INITIAL STATES (BEFORE ScrollTrigger) ===
    gsap.set(statCards, { y: 40, opacity: 0 });

    // Card reveal animation
    ScrollTrigger.create({
        trigger: '.stats-grid',
        start: 'top 80%',
        onEnter: () => {
            // Animate cards
            gsap.to(statCards, {
                y: 0,
                opacity: 1,
                stagger: 0.15,
                duration: 0.8,
                ease: 'power3.out'
            });

            // Animate counters
            const counterElements = document.querySelectorAll('[data-counter-target]');
            counterElements.forEach(card => {
                const targetValue = parseInt(card.getAttribute('data-counter-target'), 10);
                const suffix = card.getAttribute('data-counter-suffix') || '';

                gsap.to(card, {
                    textContent: targetValue,
                    duration: 3,
                    ease: 'power1.out',
                    snap: { textContent: 1 },
                    onUpdate: function() {
                        const currentValue = Math.round(gsap.getProperty(card, 'textContent'));
                        card.textContent = currentValue + suffix;
                    }
                });
            });
        },
        once: true
    });
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initStatsAnimations);
} else {
    initStatsAnimations();
}
