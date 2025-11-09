/**
 * Projects Section Animations
 *
 * Features:
 * - Batch reveal (cards "river in" together)
 * - Subtle rotation on reveal
 * - GSAP-enhanced hover effects (smoother than CSS)
 */

import { gsap, ScrollTrigger } from './gsap-config.js';

export function initProjectsAnimations() {
    const projectsSection = document.querySelector('#projects');
    if (!projectsSection) return;

    const projectCards = document.querySelectorAll('.project-card');
    if (projectCards.length === 0) return;

    // === SET INITIAL STATES (BEFORE ScrollTrigger) ===
    gsap.set(projectCards, { opacity: 0, y: 60, rotation: -2 });

    // === BATCH REVEAL (Fixed - using ScrollTrigger.create instead) ===
    ScrollTrigger.create({
        trigger: '#projects',
        start: 'top 70%',
        onEnter: () => {
            gsap.to(projectCards, {
                opacity: 1,
                y: 0,
                rotation: 0,
                stagger: 0.15,
                duration: 0.8,
                ease: 'power3.out'
            });
        },
        once: true
    });

    // === ENHANCED HOVER ===
    initProjectHover(projectCards);
}

/**
 * Enhanced hover effects with GSAP
 * Smoother than CSS transitions
 */
function initProjectHover(cards) {
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            gsap.to(card, {
                y: -12,
                scale: 1.03,
                duration: 0.4,
                ease: 'power2.out'
            });
        });

        card.addEventListener('mouseleave', () => {
            gsap.to(card, {
                y: 0,
                scale: 1,
                duration: 0.4,
                ease: 'power2.inOut'
            });
        });
    });
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initProjectsAnimations);
} else {
    initProjectsAnimations();
}
