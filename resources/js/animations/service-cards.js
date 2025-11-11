/**
 * Service Cards - Enhanced Interactions
 *
 * 3D card flips, magnetic layout, staggered entrances
 */

import { gsap } from './gsap-config.js';

const isProduction = window.location.hostname === 'atdev.me';

export function initServiceCards() {
    if (!isProduction) console.log('💼 Initializing Service Cards');

    const cards = document.querySelectorAll('#services .group');

    cards.forEach((card, index) => {
        // 3D hover tilt
        card.addEventListener('mouseenter', (e) => {
            gsap.to(card, {
                scale: 1.05,
                rotateY: 2,
                rotateX: -2,
                duration: 0.4,
                ease: 'power2.out'
            });
        });

        card.addEventListener('mouseleave', () => {
            gsap.to(card, {
                scale: 1,
                rotateY: 0,
                rotateX: 0,
                duration: 0.4,
                ease: 'power2.out'
            });
        });

        // Parallax on mouse move
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;

            gsap.to(card, {
                rotateY: x * 10,
                rotateX: -y * 10,
                duration: 0.3,
                ease: 'power2.out'
            });
        });
    });

    if (!isProduction) console.log(`✅ Service Cards initialized (${cards.length} cards)`);
}

// Auto-init
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initServiceCards);
} else {
    initServiceCards();
}
