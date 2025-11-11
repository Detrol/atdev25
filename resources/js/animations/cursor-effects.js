/**
 * Cursor Effects - Custom Cursor & Magnetic Elements
 *
 * - Custom cursor med trailing particles
 * - Magnetic effect på CTA-knappar
 * - Hover states och interaktivitet
 */

import { gsap } from './gsap-config.js';

const isProduction = window.location.hostname === 'atdev.me';

let customCursor = null;
let cursorTrails = [];
const MAX_TRAILS = 5;

/**
 * Initialize custom cursor effects
 */
export function initCursorEffects() {
    // Skip on touch devices
    if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
        if (!isProduction) console.log('⏭️  Skipping cursor effects (touch device)');
        return;
    }

    if (!isProduction) console.log('🖱️  Initializing Cursor Effects');

    createCustomCursor();
    initMagneticElements();
    initHoverEffects();

    if (!isProduction) console.log('✅ Cursor Effects initialized');
}

/**
 * Create custom cursor with trailing effect
 */
function createCustomCursor() {
    // Main cursor
    customCursor = document.createElement('div');
    customCursor.className = 'custom-cursor';
    customCursor.style.cssText = `
        position: fixed;
        width: 20px;
        height: 20px;
        border: 2px solid currentColor;
        border-radius: 50%;
        pointer-events: none;
        z-index: 9999;
        color: var(--color-primary, #8A2BE2);
        mix-blend-mode: difference;
        transform: translate(-50%, -50%);
    `;
    document.body.appendChild(customCursor);

    // Create trail particles
    for (let i = 0; i < MAX_TRAILS; i++) {
        const trail = document.createElement('div');
        trail.className = 'cursor-trail';
        trail.style.cssText = `
            position: fixed;
            width: ${12 - i * 2}px;
            height: ${12 - i * 2}px;
            background: currentColor;
            border-radius: 50%;
            pointer-events: none;
            z-index: 9998;
            color: var(--color-accent, #E066FF);
            opacity: ${0.5 - i * 0.08};
            transform: translate(-50%, -50%);
        `;
        document.body.appendChild(trail);
        cursorTrails.push({
            element: trail,
            x: 0,
            y: 0
        });
    }

    // Track mouse movement
    let mouseX = 0;
    let mouseY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    // Animate cursor with GSAP
    gsap.ticker.add(() => {
        // Main cursor follows immediately
        if (customCursor) {
            gsap.set(customCursor, {
                x: mouseX,
                y: mouseY
            });
        }

        // Trails follow with delay
        cursorTrails.forEach((trail, index) => {
            const delay = (index + 1) * 0.05;

            gsap.to(trail, {
                x: mouseX,
                y: mouseY,
                duration: 0.3 + delay,
                ease: 'power2.out',
                overwrite: 'auto'
            });
        });
    });

    // Hide default cursor
    document.body.style.cursor = 'none';

    // Expand on click
    document.addEventListener('mousedown', () => {
        gsap.to(customCursor, {
            scale: 0.8,
            duration: 0.2,
            ease: 'back.out(2)'
        });
    });

    document.addEventListener('mouseup', () => {
        gsap.to(customCursor, {
            scale: 1,
            duration: 0.3,
            ease: 'back.out(1.5)'
        });
    });
}

/**
 * Magnetic effect on CTA buttons
 */
function initMagneticElements() {
    const magneticElements = document.querySelectorAll('a[href="#contact"], .hero-cta, button[type="submit"], .project-card');

    magneticElements.forEach(element => {
        const strength = element.classList.contains('hero-cta') ? 0.4 : 0.3;

        element.addEventListener('mouseenter', () => {
            if (customCursor) {
                gsap.to(customCursor, {
                    scale: 1.5,
                    duration: 0.3,
                    ease: 'back.out(2)'
                });
            }
        });

        element.addEventListener('mouseleave', () => {
            gsap.to(element, {
                x: 0,
                y: 0,
                duration: 0.5,
                ease: 'elastic.out(1, 0.5)'
            });

            if (customCursor) {
                gsap.to(customCursor, {
                    scale: 1,
                    duration: 0.3,
                    ease: 'back.out(1.5)'
                });
            }
        });

        element.addEventListener('mousemove', (e) => {
            const rect = element.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            gsap.to(element, {
                x: x * strength,
                y: y * strength,
                duration: 0.3,
                ease: 'power2.out'
            });
        });
    });
}

/**
 * Enhanced hover effects
 */
function initHoverEffects() {
    // Links
    const links = document.querySelectorAll('a:not(.hero-cta)');
    links.forEach(link => {
        link.addEventListener('mouseenter', () => {
            if (customCursor) {
                gsap.to(customCursor, {
                    scale: 1.2,
                    borderWidth: 3,
                    duration: 0.3
                });
            }
        });

        link.addEventListener('mouseleave', () => {
            if (customCursor) {
                gsap.to(customCursor, {
                    scale: 1,
                    borderWidth: 2,
                    duration: 0.3
                });
            }
        });
    });

    // Input fields
    const inputs = document.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('mouseenter', () => {
            if (customCursor) {
                gsap.to(customCursor, {
                    scale: 0.5,
                    opacity: 0.5,
                    duration: 0.3
                });
            }
        });

        input.addEventListener('mouseleave', () => {
            if (customCursor) {
                gsap.to(customCursor, {
                    scale: 1,
                    opacity: 1,
                    duration: 0.3
                });
            }
        });
    });
}

/**
 * Cleanup cursor effects
 */
export function destroyCursorEffects() {
    if (customCursor) {
        customCursor.remove();
        customCursor = null;
    }

    cursorTrails.forEach(trail => trail.element.remove());
    cursorTrails = [];

    document.body.style.cursor = '';
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCursorEffects);
} else {
    initCursorEffects();
}
