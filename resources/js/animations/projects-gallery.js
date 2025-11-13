/**
 * Projects Gallery - Enhanced Animations
 *
 * Curtain wipe reveal, hover effects, smooth transitions
 */

import { gsap } from './gsap-config.js';
import { ScrollTrigger } from './gsap-config.js';
import { viewport } from './viewport-utils.js';

const isProduction = window.location.hostname === 'atdev.me';

export function initProjectsGallery() {
    if (!isProduction) console.log('🖼️  Initializing Projects Gallery');

    const projectCards = document.querySelectorAll('.project-card');

    projectCards.forEach((card, index) => {
        // Curtain reveal on scroll
        const img = card.querySelector('img');
        if (img) {
            if (viewport.isMobile) {
                // Mobile: IntersectionObserver for zero scroll overhead
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            gsap.from(img, {
                                opacity: 0,
                                duration: 0.5,
                                ease: 'power2.out'
                            });
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.15 });

                observer.observe(card);
            } else {
                // Desktop: Curtain reveal with clipPath
                gsap.from(img, {
                    clipPath: 'inset(0 100% 0 0)',
                    duration: 1,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 85%',
                        toggleActions: 'play none none reverse'
                    }
                });
            }
        }

        // 3D hover effect
        card.addEventListener('mouseenter', () => {
            gsap.to(card, {
                y: -10,
                scale: 1.02,
                duration: 0.3,
                ease: 'power2.out'
            });
        });

        card.addEventListener('mouseleave', () => {
            gsap.to(card, {
                y: 0,
                scale: 1,
                duration: 0.3,
                ease: 'power2.out'
            });
        });

        // Parallax on mousemove
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;

            gsap.to(card, {
                rotateY: x * 5,
                rotateX: -y * 5,
                duration: 0.3,
                ease: 'power2.out'
            });
        });
    });

    if (!isProduction) console.log(`✅ Projects Gallery initialized (${projectCards.length} projects)`);
}

// Auto-init
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initProjectsGallery);
} else {
    initProjectsGallery();
}
