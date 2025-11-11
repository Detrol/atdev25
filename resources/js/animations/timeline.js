/**
 * Timeline Section Animations
 *
 * Features:
 * - Smooth progress bar animation with ScrollTrigger
 * - Tech icons drop-down with elastic bounce
 * - Staggered milestone reveals
 * - Parallax on background blobs
 */

import { gsap, ScrollTrigger } from './gsap-config.js';

export function initTimelineAnimations() {
    const timelineSection = document.querySelector('#expertis');
    if (!timelineSection) return;

    // === MAIN TIMELINE SEQUENCE ===
    ScrollTrigger.create({
        trigger: '#expertis',
        start: 'top 70%',
        onEnter: () => {
            animateTimeline();
        },
        once: true
    });

    // === PARALLAX BLOBS ===
    initBlobParallax();
}

/**
 * Main timeline animation sequence
 * Matches original Alpine.js animation timing
 */
function animateTimeline() {
    const isDesktop = window.matchMedia('(min-width: 768px)').matches;

    if (isDesktop) {
        // === DESKTOP TIMELINE ===
        // Progress bar (smooth, 5 seconds like original)
        gsap.to('.timeline-progress', {
            width: '100%',
            duration: 5,
            ease: 'linear',
            delay: 0.8
        });

        // Icons appear at specific positions (matching original timing)
        const icons = document.querySelectorAll('.timeline-icon');
        if (icons[0]) gsap.from(icons[0], { y: -80, scale: 0.5, opacity: 0, duration: 0.6, ease: 'back.out(1.7)', delay: 1.425 }); // 625ms + 800ms
        if (icons[1]) gsap.from(icons[1], { y: -80, scale: 0.5, opacity: 0, duration: 0.6, ease: 'back.out(1.7)', delay: 2.675 }); // 1875ms + 800ms
        if (icons[2]) gsap.from(icons[2], { y: -80, scale: 0.5, opacity: 0, duration: 0.6, ease: 'back.out(1.7)', delay: 3.925 }); // 3125ms + 800ms
        if (icons[3]) gsap.from(icons[3], { y: -80, scale: 0.5, opacity: 0, duration: 0.6, ease: 'back.out(1.7)', delay: 5.175 }); // 4375ms + 800ms

        // Milestones
        gsap.from('.timeline-milestone', {
            y: 30,
            opacity: 0,
            stagger: 0.25,
            duration: 0.6,
            delay: 1.5
        });

    } else {
        // === MOBILE TIMELINE ===
        const milestones = document.querySelectorAll('.mobile-milestone');
        const icons = document.querySelectorAll('.mobile-milestone-icon');

        // Milestones appear in sequence (matching original)
        milestones.forEach((milestone, index) => {
            gsap.from(milestone, {
                scale: 0.9,
                opacity: 0,
                y: 20,
                duration: 0.6,
                delay: index * 1.25, // 0, 1250ms, 2500ms, 3750ms
                ease: 'power2.out'
            });
        });

        // Icons pop in after milestones
        icons.forEach((icon, index) => {
            gsap.from(icon, {
                scale: 0,
                rotation: -45,
                duration: 0.5,
                delay: index * 1.25 + 0.3,
                ease: 'back.out(1.5)'
            });
        });
    }
}

/**
 * Parallax effect on background blobs
 */
function initBlobParallax() {
    const blobs = document.querySelectorAll('#expertis .absolute.opacity-30 > div');

    blobs.forEach((blob, index) => {
        // Alternate direction for each blob
        const direction = index % 2 === 0 ? 1 : -1;

        gsap.to(blob, {
            y: `${direction * 30}%`,
            ease: 'none',
            scrollTrigger: {
                trigger: '#expertis',
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1.5
            }
        });
    });
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTimelineAnimations);
} else {
    initTimelineAnimations();
}
