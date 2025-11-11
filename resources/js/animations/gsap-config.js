/**
 * GSAP Configuration & Setup
 *
 * Initializes GSAP with ScrollTrigger plugin and sets global defaults.
 * Respects user's prefers-reduced-motion preference for accessibility.
 */

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { MotionPathPlugin } from 'gsap/MotionPathPlugin';

// Register plugins
gsap.registerPlugin(ScrollTrigger, MotionPathPlugin);

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

// Respect prefers-reduced-motion for accessibility (ONLY in production)
const isProduction = window.location.hostname === 'atdev.me';
if (isProduction) {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) {
        gsap.globalTimeline.timeScale(100); // Speed up to near-instant
    }
}

// Debug helper (only in local)
if (!isProduction) {
    console.log('🎬 GSAP initialized with ScrollTrigger (local dev mode)');

    // Uncomment to enable ScrollTrigger markers in development
    // ScrollTrigger.defaults({ markers: true });
}

// Make GSAP available globally for debugging and external use
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;
window.MotionPathPlugin = MotionPathPlugin;

// Export for use in other animation modules
export { gsap, ScrollTrigger, MotionPathPlugin };
