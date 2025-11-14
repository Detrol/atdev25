/**
 * Section Transitions - Unique Entrance Animations
 *
 * Varje sektion får en unik entrance-animation när den scrollar in i viewporten.
 * Skapar en seamless upplevelse mellan sektionerna.
 */

import { gsap } from './gsap-config.js';
import { ScrollTrigger } from './gsap-config.js';
import { viewport } from './viewport-utils.js';

const isProduction = window.location.hostname === 'atdev.me';

/**
 * Initialize section entrance animations
 */
export function initSectionTransitions() {
    if (!isProduction) console.log('🎬 Initializing Section Transitions');

    const sections = document.querySelectorAll('.animated-section');

    sections.forEach((section, index) => {
        const theme = section.dataset.theme;
        const content = section.querySelector('.animated-section-content');

        if (!content) return;

        // Get section ID for specific animations
        const sectionId = section.id;

        // Apply entrance animation based on section
        switch (sectionId) {
            case 'om-mig': // About
                initAboutEntrance(section, content);
                break;

            case 'hur-jag-jobbar': // How I Work
                initHowIWorkEntrance(section, content);
                break;

            case 'expertis': // Timeline
                initTimelineEntrance(section, content);
                break;

            case 'services': // Services
                initServicesEntrance(section, content);
                break;

            case 'projects': // Projects
                initProjectsEntrance(section, content);
                break;

            case 'faq': // FAQ
                initFAQEntrance(section, content);
                break;

            case 'price-calculator': // Price Calculator
                initPriceCalculatorEntrance(section, content);
                break;

            case 'contact': // Contact
                initContactEntrance(section, content);
                break;

            default:
                // Default fade-in
                initDefaultEntrance(section, content);
        }

        // Color theme transition
        initThemeTransition(section, theme);
    });

    if (!isProduction) console.log(`✅ Section Transitions initialized for ${sections.length} sections`);
}

/**
 * About Section - Slide in from right with scale
 */
function initAboutEntrance(section, content) {
    if (!isProduction) {
        console.log('About entrance - isMobile:', viewport.isMobile, 'width:', viewport.width);
    }

    if (viewport.isMobile) {
        // Set initial state BEFORE observing
        gsap.set(content, { y: 20, opacity: 0 });

        // Mobile: IntersectionObserver (zero scroll overhead)
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    gsap.to(content, {
                        y: 0,
                        opacity: 1,
                        duration: 0.8,
                        ease: 'power2.out'
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.05,
            rootMargin: '50px'
        });

        observer.observe(section);
    } else {
        // Desktop: ScrollTrigger with full animation
        gsap.from(content, {
            x: 100,
            opacity: 0,
            scale: 0.95,
            duration: 1.2,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: section,
                start: 'top 80%',
                end: 'top 50%',
                toggleActions: 'play none none reverse'
            }
        });
    }
}

/**
 * How I Work - Conveyor belt entrance (slide from left)
 */
function initHowIWorkEntrance(section, content) {
    if (viewport.isMobile) {
        // Set initial state BEFORE observing
        gsap.set(content, { y: 20, opacity: 0 });

        // Mobile: IntersectionObserver (zero scroll overhead)
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    gsap.to(content, {
                        y: 0,
                        opacity: 1,
                        duration: 0.8,
                        ease: 'power2.out'
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.05,
            rootMargin: '50px'
        });

        observer.observe(section);
    } else {
        // Desktop: Full animation with stagger
        gsap.from(content, {
            x: -100,
            opacity: 0,
            duration: 1,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: section,
                start: 'top 80%',
                toggleActions: 'play none none reverse'
            }
        });

        // Stagger children (process steps)
        const steps = content.querySelectorAll('.step-card, .hiw-step');
        if (steps.length) {
            gsap.from(steps, {
                y: 50,
                opacity: 0,
                stagger: 0.15,
                duration: 0.8,
                ease: 'back.out(1.2)',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 60%',
                    toggleActions: 'play none none reverse'
                }
            });
        }
    }
}

/**
 * Timeline - Fade in with subtle slide (NO scale to prevent text blur)
 */
function initTimelineEntrance(section, content) {
    if (viewport.isMobile) {
        // Set initial state BEFORE observing
        gsap.set(content, { y: 20, opacity: 0 });

        // Mobile: IntersectionObserver
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    gsap.to(content, {
                        y: 0,
                        opacity: 1,
                        duration: 0.8,
                        ease: 'power2.out'
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.05,
            rootMargin: '50px'
        });

        observer.observe(section);
    } else {
        // Desktop: ScrollTrigger
        gsap.from(content, {
            y: 50,
            opacity: 0,
            duration: 1,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: section,
                start: 'top 70%',
                toggleActions: 'play none none reverse'
            }
        });
    }
}

/**
 * Services - Simple fade up (SIMPLIFIED FOR MOBILE)
 */
function initServicesEntrance(section, content) {
    if (viewport.isMobile) {
        // Mobile: Ultra-simple fade up with very low threshold
        gsap.set(content, { y: 20, opacity: 0 });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    gsap.to(content, {
                        y: 0,
                        opacity: 1,
                        duration: 0.8,
                        ease: 'power2.out'
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.05,  // Trigger when just 5% visible
            rootMargin: '50px'  // Start 50px before entering viewport
        });

        observer.observe(section);
    } else {
        // Desktop: Full animation with scale
        gsap.from(content, {
            y: 50,
            opacity: 0,
            scale: 0.95,
            duration: 1.2,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: section,
                start: 'top 80%',
                end: 'top 50%',
                toggleActions: 'play none none reverse'
            }
        });

        // Cascade service cards
        const cards = content.querySelectorAll('.service-card, .main-service-card');
        if (cards.length) {
            gsap.from(cards, {
                y: 80,
                opacity: 0,
                stagger: 0.1,
                duration: 0.6,
                ease: 'back.out(1.4)',
                immediateRender: false,
                scrollTrigger: {
                    trigger: section,
                    start: 'top 60%',
                    toggleActions: 'play none none none'
                }
            });
        }
    }
}

/**
 * Projects - Masonry assemble (staggered grid)
 */
function initProjectsEntrance(section, content) {
    const projectCards = content.querySelectorAll('.project-card');

    if (projectCards.length) {
        if (viewport.isMobile) {
            // Set initial state BEFORE observing
            gsap.set(projectCards, { y: 20, opacity: 0 });

            // Mobile: IntersectionObserver
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        gsap.to(projectCards, {
                            y: 0,
                            opacity: 1,
                            duration: 0.8,
                            ease: 'power2.out'
                        });
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.05,
                rootMargin: '50px'
            });

            observer.observe(section);
        } else {
            // Desktop: Full masonry assembly
            gsap.from(projectCards, {
                scale: 0.8,
                opacity: 0,
                stagger: {
                    amount: 0.8,
                    grid: 'auto',
                    from: 'start'
                },
                duration: 0.6,
                ease: 'back.out(1.2)',
                immediateRender: false,
                scrollTrigger: {
                    trigger: section,
                    start: 'top 70%',
                    toggleActions: 'play none none none'
                }
            });
        }
    }
}

/**
 * FAQ - Chat bubbles pop
 */
function initFAQEntrance(section, content) {
    if (viewport.isMobile) {
        // Set initial state BEFORE observing
        gsap.set(content, { y: 20, opacity: 0 });

        // Mobile: IntersectionObserver
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    gsap.to(content, {
                        y: 0,
                        opacity: 1,
                        duration: 0.8,
                        ease: 'power2.out'
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.05,
            rootMargin: '50px'
        });

        observer.observe(section);
    } else {
        // Desktop: Full bubble pop animation
        gsap.from(content, {
            scale: 0.9,
            opacity: 0,
            duration: 0.8,
            ease: 'back.out(1.4)',
            scrollTrigger: {
                trigger: section,
                start: 'top 80%',
                toggleActions: 'play none none reverse'
            }
        });

        // Pop individual FAQ items
        const faqItems = content.querySelectorAll('[x-data]');
        if (faqItems.length) {
            gsap.from(faqItems, {
                scale: 0.95,
                opacity: 0,
                stagger: 0.08,
                duration: 0.5,
                ease: 'back.out(1.3)',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 60%',
                    toggleActions: 'play none none reverse'
                }
            });
        }
    }
}

/**
 * Price Calculator - Slide up with grid assembly
 */
function initPriceCalculatorEntrance(section, content) {
    if (viewport.isMobile) {
        // Set initial state BEFORE observing
        gsap.set(content, { y: 20, opacity: 0 });

        // Mobile: IntersectionObserver
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    gsap.to(content, {
                        y: 0,
                        opacity: 1,
                        duration: 0.8,
                        ease: 'power2.out'
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.05,
            rootMargin: '50px'
        });

        observer.observe(section);
    } else {
        // Desktop: Full animation with scale
        gsap.from(content, {
            y: 60,
            opacity: 0,
            scale: 0.95,
            duration: 1,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: section,
                start: 'top 75%',
                toggleActions: 'play none none reverse'
            }
        });
    }
}

/**
 * Contact - Door swing open
 */
function initContactEntrance(section, content) {
    if (viewport.isMobile) {
        // Set initial state BEFORE observing
        gsap.set(content, { y: 20, opacity: 0 });

        // Mobile: IntersectionObserver
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    gsap.to(content, {
                        y: 0,
                        opacity: 1,
                        duration: 0.8,
                        ease: 'power2.out'
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.05,
            rootMargin: '50px'
        });

        observer.observe(section);
    } else {
        // Desktop: 3D door swing
        gsap.from(content, {
            rotateY: -15,
            transformOrigin: 'left center',
            opacity: 0,
            duration: 1.2,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: section,
                start: 'top 80%',
                toggleActions: 'play none none reverse'
            }
        });
    }
}

/**
 * Default entrance - Simple fade in
 */
function initDefaultEntrance(section, content) {
    gsap.from(content, {
        y: 30,
        opacity: 0,
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: section,
            start: 'top 80%',
            toggleActions: 'play none none reverse'
        }
    });
}

/**
 * Color theme transition
 */
function initThemeTransition(section, theme) {
    if (!theme) return;

    // More gradual theme transition with longer overlap
    ScrollTrigger.create({
        trigger: section,
        start: 'top 75%',  // Start earlier for smoother transition
        end: 'bottom 25%',  // End later
        onEnter: () => updateThemeColors(theme, true),  // Smooth transition
        onEnterBack: () => updateThemeColors(theme, true)
    });
}

/**
 * Update CSS custom properties for smooth color transitions
 */
function updateThemeColors(theme, smooth = false) {
    const root = document.documentElement;
    const themeData = getThemeColors(theme);

    if (!themeData) return;

    // Longer, smoother transitions
    gsap.to(root, {
        '--color-primary': themeData.primary,
        '--color-secondary': themeData.secondary,
        '--color-accent': themeData.accent,
        duration: smooth ? 1.5 : 0.8,  // Longer for smooth mode
        ease: 'power1.inOut'  // Gentler easing
    });
}

/**
 * Get theme color values
 */
function getThemeColors(theme) {
    const themes = {
        'purple-blue': { primary: '#8A2BE2', secondary: '#4169E1', accent: '#E066FF' },
        'blue-pink': { primary: '#4169E1', secondary: '#FF69B4', accent: '#87CEEB' },
        'pink-orange': { primary: '#FF69B4', secondary: '#FF8C00', accent: '#FFB6C1' },
        'orange-amber': { primary: '#FF8C00', secondary: '#FFBF00', accent: '#FFD700' },
        'amber-green': { primary: '#FFBF00', secondary: '#32CD32', accent: '#FFD700' },
        'green-teal': { primary: '#2E8B57', secondary: '#20B2AA', accent: '#3CB371' },
        'purple-pink': { primary: '#8B5CF6', secondary: '#EC4899', accent: '#A78BFA' },
        'teal-blue': { primary: '#20B2AA', secondary: '#4169E1', accent: '#00CED1' }
    };

    return themes[theme] || null;
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSectionTransitions);
} else {
    initSectionTransitions();
}
