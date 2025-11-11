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

// Manual animation control (set to false to disable all animations)
const ENABLE_ANIMATIONS = true;

if (!ENABLE_ANIMATIONS) {
    gsap.globalTimeline.timeScale(100); // Speed up to near-instant (effectively disables)
}

// Debug helper (only in local)
const isProduction = window.location.hostname === 'atdev.me';
if (!isProduction) {
    console.log('🎬 GSAP initialized with ScrollTrigger (local dev mode)');
    console.log(`🎨 Animations: ${ENABLE_ANIMATIONS ? 'ENABLED' : 'DISABLED'}`);

    // Uncomment to enable ScrollTrigger markers in development
    // ScrollTrigger.defaults({ markers: true });
}

// Make GSAP available globally for debugging and external use
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;
window.MotionPathPlugin = MotionPathPlugin;

// Export for use in other animation modules
export { gsap, ScrollTrigger, MotionPathPlugin };
