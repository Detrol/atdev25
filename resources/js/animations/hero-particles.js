/**
 * Hero Particles - Particle Burst Effect
 *
 * Skapar en energisk particle burst när hero-sektionen laddas
 */

import { gsap } from './gsap-config.js';

const isProduction = window.location.hostname === 'atdev.me';

export function initHeroParticles() {
    // Skip floating particles on mobile and with reduced motion (continuous animation)
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (isMobile || prefersReducedMotion) {
        if (!isProduction) console.log('✋ Hero Particles disabled (mobile or reduced motion)');
        return;
    }

    if (!isProduction) console.log('✨ Initializing Hero Particles');

    const hero = document.querySelector('.hero-container');
    if (!hero) {
        if (!isProduction) console.warn('Hero container not found');
        return;
    }

    // Create particle container
    const particleContainer = document.createElement('div');
    particleContainer.className = 'hero-particles';
    particleContainer.style.cssText = `
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 1;
        overflow: hidden;
    `;
    hero.appendChild(particleContainer);

    // Create particles
    const particleCount = 30;
    const particles = [];

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';

        // Random size
        const size = Math.random() * 4 + 2;

        particle.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            background: currentColor;
            border-radius: 50%;
            color: var(--color-accent, #E066FF);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            will-change: transform, opacity;
        `;

        particleContainer.appendChild(particle);
        particles.push(particle);
    }

    // Animate particles burst on load
    particles.forEach((particle, index) => {
        const angle = (index / particleCount) * Math.PI * 2;
        const distance = 200 + Math.random() * 100;
        const x = Math.cos(angle) * distance;
        const y = Math.sin(angle) * distance;

        gsap.fromTo(particle,
            {
                x: 0,
                y: 0,
                opacity: 1,
                scale: 1
            },
            {
                x: x,
                y: y,
                opacity: 0,
                scale: 0,
                duration: 1.5 + Math.random() * 0.5,
                delay: index * 0.02,
                ease: 'power3.out'
            }
        );
    });

    // Floating particles (continuous animation)
    const floatingParticles = particles.slice(0, 10);
    floatingParticles.forEach((particle, index) => {
        gsap.to(particle, {
            x: `+=${Math.random() * 100 - 50}`,
            y: `+=${Math.random() * 100 - 50}`,
            duration: 3 + Math.random() * 2,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
            delay: index * 0.2
        });

        gsap.to(particle, {
            opacity: 0.3 + Math.random() * 0.4,
            duration: 2 + Math.random(),
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
            delay: index * 0.1
        });
    });

    if (!isProduction) console.log(`✅ Hero Particles initialized (${particleCount} particles)`);
}

// Auto-init
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeroParticles);
} else {
    initHeroParticles();
}
