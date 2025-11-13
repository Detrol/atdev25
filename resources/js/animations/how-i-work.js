/**
 * How I Work Section Animations
 *
 * Features:
 * - Progress line drawing between steps
 * - Step cards cascade with rotation
 * - Step number pulse
 * - Substep animations on expand
 * - Values cards 3D flip
 * - Services checkmark pop-in
 * - Parallax scrolling
 */

import { gsap, ScrollTrigger, shouldDisableAnimations } from './gsap-config.js';
import { viewport } from './viewport-utils.js';

const isProduction = window.location.hostname === 'atdev.me';

export function initHowIWorkAnimations() {
    const howIWorkSection = document.querySelector('#hur-jag-jobbar');
    if (!howIWorkSection) {
        if (!isProduction) console.log('⚠️ How I Work section not found');
        return;
    }
    if (!isProduction) console.log('🎯 Initializing How I Work animations');

    // === SET INITIAL STATES (BEFORE ScrollTrigger) ===
    gsap.set('.hiw-title', { y: -30, opacity: 0, scale: 0.95 });
    gsap.set('.hiw-subtitle', { y: 20, opacity: 0 });
    gsap.set('.progress-line', { scaleY: 0, transformOrigin: 'top' });
    gsap.set('.process-step', { x: -80, opacity: 0, rotation: -3 });
    gsap.set('.step-number', { scale: 0, rotation: -180 });
    gsap.set('.value-card', { rotationY: -90, opacity: 0 });
    gsap.set('.service-card', { y: 60, opacity: 0, scale: 0.95, rotation: 2 });
    gsap.set('.service-checkmark', { scale: 0, rotation: -180 });

    // === MAIN SECTION REVEAL ===
    ScrollTrigger.create({
        trigger: '#hur-jag-jobbar',
        start: 'top 75%',
        onEnter: () => {
            animateSection();
        },
        once: true
    });

    // === SUBSTEP EXPANSION OBSERVER ===
    observeStepExpansion();

    // === PARALLAX EFFECTS ===
    initParallaxEffects();
}

/**
 * Main section animation sequence
 */
function animateSection() {
    const timeline = gsap.timeline({
        defaults: {
            ease: 'power3.out'
        }
    });

    timeline
        // Section titles
        .to('.hiw-title', {
            y: 0,
            opacity: 1,
            scale: 1,
            duration: 0.8
        })
        .to('.hiw-subtitle', {
            y: 0,
            opacity: 1,
            duration: 0.6
        }, '-=0.4')

        // Progress line draws between steps
        .to('.progress-line', {
            scaleY: 1,
            duration: 1.2,
            ease: 'power2.inOut'
        }, '-=0.2')

        // Step cards cascade in
        .to('.process-step', {
            x: 0,
            opacity: 1,
            rotation: 0,
            stagger: 0.2,
            duration: 0.8,
            ease: 'back.out(1.2)'
        }, '-=0.8')

        // Step numbers pulse
        .to('.step-number', {
            scale: 1,
            rotation: 0,
            stagger: 0.2,
            duration: 0.6,
            ease: 'back.out(1.7)'
        }, '-=0.6');

    // Values section
    animateValues();

    // Services section
    animateServices();
}

/**
 * Animate values cards with 3D flip
 */
function animateValues() {
    const valueCards = document.querySelectorAll('.value-card');
    if (valueCards.length === 0) return;

    const valuesGrid = document.querySelector('.values-grid');
    if (!valuesGrid) return;

    if (viewport.isMobile) {
        // Mobile: IntersectionObserver
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    gsap.to(valueCards, {
                        opacity: 1,
                        stagger: 0.1,
                        duration: 0.5,
                        ease: 'power2.out'
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        observer.observe(valuesGrid);
    } else {
        // Desktop: 3D flip animation with ScrollTrigger
        gsap.to(valueCards, {
            rotationY: 0,
            opacity: 1,
            stagger: {
                each: 0.15,
                from: 'start'
            },
            duration: 0.8,
            ease: 'back.out(1.4)',
            scrollTrigger: {
                trigger: '.values-grid',
                start: 'top 80%',
                once: true
            }
        });
    }
}

/**
 * Animate services cards with rise effect
 */
function animateServices() {
    const serviceCards = document.querySelectorAll('.service-card');
    if (serviceCards.length === 0) return;

    const servicesGrid = document.querySelector('.services-grid');
    if (!servicesGrid) return;

    if (viewport.isMobile) {
        // Mobile: IntersectionObserver
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    gsap.to(serviceCards, {
                        y: 0,
                        opacity: 1,
                        scale: 1,
                        rotation: 0,
                        duration: 0.6,
                        ease: 'power2.out'
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        observer.observe(servicesGrid);
    } else {
        // Desktop: ScrollTrigger with stagger
        gsap.to(serviceCards, {
            y: 0,
            opacity: 1,
            scale: 1,
            rotation: 0,
            stagger: 0.2,
            duration: 0.8,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: '.services-grid',
                start: 'top 80%',
                once: true
            }
        });

        // Animate checkmarks separately
        const checkmarks = document.querySelectorAll('.service-checkmark');
        if (checkmarks.length > 0) {
            checkmarks.forEach((check, index) => {
                gsap.to(check, {
                    scale: 1,
                    rotation: 0,
                    duration: 0.5,
                    ease: 'back.out(1.7)',
                    scrollTrigger: {
                        trigger: check.closest('.service-card'),
                        start: 'top 75%',
                        once: true
                    },
                    delay: index * 0.05
                });
            });
        }
    }
}

/**
 * Parallax effects for cards
 */
function initParallaxEffects() {
    // Skip parallax effects if animations are disabled
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!isProduction) console.log('🔍 Parallax check - Mobile:', isMobile, 'Reduced motion:', prefersReducedMotion);
    if (isMobile || prefersReducedMotion) {
        if (!isProduction) console.log('✋ Parallax effects disabled');
        return;
    }
    if (!isProduction) console.log('✅ Parallax effects enabled');

    // Values cards parallax
    const valueCards = document.querySelectorAll('.value-card');
    valueCards.forEach((card, index) => {
        const speed = [15, -12, 18, -15][index] || 15;

        gsap.to(card, {
            yPercent: speed,
            ease: 'none',
            scrollTrigger: {
                trigger: '.values-grid',
                start: 'top bottom',
                end: 'bottom top',
                scrub: 2
            }
        });
    });

    // Services cards parallax
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach((card, index) => {
        const speed = [20, -15, 18][index] || 15;

        gsap.to(card, {
            yPercent: speed,
            ease: 'none',
            scrollTrigger: {
                trigger: '.services-grid',
                start: 'top bottom',
                end: 'bottom top',
                scrub: 2
            }
        });
    });
}

/**
 * Observe when steps are expanded and animate substeps
 */
function observeStepExpansion() {
    const steps = document.querySelectorAll('[x-collapse]');

    steps.forEach((collapseContainer) => {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'style') {
                    const isHidden = collapseContainer.style.display === 'none';

                    if (!isHidden) {
                        animateSubsteps(collapseContainer);
                    }
                }
            });
        });

        observer.observe(collapseContainer, {
            attributes: true,
            attributeFilter: ['style']
        });
    });
}

/**
 * Enhanced substep animations
 */
function animateSubsteps(container) {
    const substeps = container.querySelectorAll('.substep');
    if (substeps.length === 0) return;

    // Set initial states
    gsap.set(substeps, { opacity: 0, y: 30, scale: 0.9 });

    // Icons initial state
    substeps.forEach((substep) => {
        const icon = substep.querySelector('.substep-icon');
        if (icon) {
            gsap.set(icon, { scale: 0, rotation: -360 });
        }
    });

    // Box pops in first
    gsap.to(substeps, {
        opacity: 1,
        y: 0,
        scale: 1,
        stagger: 0.1,
        duration: 0.5,
        ease: 'back.out(1.5)',
        clearProps: 'all'
    });

    // Icons pop in after with delay
    substeps.forEach((substep, index) => {
        const icon = substep.querySelector('.substep-icon');
        if (icon) {
            gsap.to(icon, {
                scale: 1,
                rotation: 0,
                duration: 0.6,
                ease: 'back.out(2)',
                delay: index * 0.1 + 0.15
            });
        }
    });
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHowIWorkAnimations);
} else {
    initHowIWorkAnimations();
}
