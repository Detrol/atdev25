/**
 * Unified Background System - SIMPLIFIED
 *
 * Features:
 * - Lightweight CSS-only animations (no GSAP parallax)
 * - 4 simple orbs with microbe motion
 * - Minimal performance impact
 */

export function initBackgroundSystem() {
    const backgroundContainer = document.querySelector('#unified-background');
    if (!backgroundContainer) return;

    const orbs = backgroundContainer.querySelectorAll('.bg-orb');
    if (orbs.length === 0) return;

    // Hide orbs during hero section (optional - can be removed if not needed)
    const heroSection = document.querySelector('#main-content');
    if (heroSection) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Hero visible - hide orbs
                    backgroundContainer.style.opacity = '0';
                } else {
                    // Hero not visible - show orbs
                    backgroundContainer.style.opacity = '1';
                }
            });
        }, { threshold: 0.1 });

        observer.observe(heroSection);
    }

    // That's it! CSS handles all animations via animate-microbe-* classes
    // No GSAP, no mouse parallax, no scroll triggers = great performance
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initBackgroundSystem);
} else {
    initBackgroundSystem();
}
