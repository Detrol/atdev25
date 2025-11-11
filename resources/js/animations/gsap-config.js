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

// Detect mobile devices and reduced motion preference (for per-animation checks)
const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const shouldDisableAnimations = isMobile || prefersReducedMotion;

// Note: Individual animation files handle motion checks appropriately:
// - Continuous animations (floating particles) are disabled completely
// - Parallax effects are disabled (can cause motion sickness)
// - Entrance animations run normally (provide visual feedback)
// - Scroll-based animations run normally (user-controlled motion)

// Debug helper (only in local)
const isProduction = window.location.hostname === 'atdev.me';
if (!isProduction) {
    console.log('🎬 GSAP initialized with ScrollTrigger (local dev mode)');
    console.log(`📱 Mobile device: ${isMobile}`);
    console.log(`♿ Reduced motion: ${prefersReducedMotion}`);
    console.log(`✨ Animations: ${shouldDisableAnimations ? 'DISABLED' : 'ENABLED'}`);

    // Uncomment to enable ScrollTrigger markers in development
    // ScrollTrigger.defaults({ markers: true });
}

// Make GSAP available globally for debugging and external use
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;
window.MotionPathPlugin = MotionPathPlugin;

// Export for use in other animation modules
export { gsap, ScrollTrigger, MotionPathPlugin, shouldDisableAnimations };
