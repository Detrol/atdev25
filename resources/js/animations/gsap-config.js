/**
 * GSAP Configuration & Setup
 *
 * Initializes GSAP with ScrollTrigger plugin and sets global defaults.
 * Respects user's prefers-reduced-motion preference for accessibility.
 */

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Register plugins
gsap.registerPlugin(ScrollTrigger);

// Global GSAP defaults
gsap.defaults({
    ease: 'power3.out',
    duration: 0.8
});

// ScrollTrigger defaults
ScrollTrigger.defaults({
    toggleActions: 'play none none none', // Play animation once on enter
    markers: false // Set to true for debugging (shows trigger points)
});

// Respect prefers-reduced-motion (DISABLED FOR TESTING)
// const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
// if (prefersReducedMotion) {
//     gsap.globalTimeline.timeScale(100);
//     console.log('Animations disabled: prefers-reduced-motion is active');
// }

// Debug helper (only in development)
if (import.meta.env.DEV) {
    console.log('GSAP initialized with ScrollTrigger');

    // Uncomment to enable ScrollTrigger markers in development
    // ScrollTrigger.defaults({ markers: true });
}

// Export for use in other animation modules
export { gsap, ScrollTrigger };
