/**
 * Section Transitions - GSAP Animation System
 *
 * Del 1: Unik Hero → About transition (3D Layer Deconstruction)
 * Del 2: Återanvändbart system för alla andra sektioner
 */

import { gsap, ScrollTrigger } from './gsap-config.js';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(SplitText);

/**
 * ==========================================================================
 * DEL 1: HERO → ABOUT TRANSITION (3D Layer Deconstruction)
 * ==========================================================================
 */
export function initHeroTransition() {
    const heroContainer = document.querySelector('.hero-container');
    const heroTitle = document.querySelector('.hero-title');
    const heroSubtitle = document.querySelector('.hero-subtitle');
    const heroCtas = document.querySelectorAll('.hero-cta');
    const heroBackground = document.querySelector('.hero-background');

    console.log('🔍 DEBUG Hero elements:', {
        heroContainer: !!heroContainer,
        heroTitle: !!heroTitle,
        heroSubtitle: !!heroSubtitle,
        heroCtas: heroCtas.length,
        heroBackground: !!heroBackground
    });

    if (!heroContainer) {
        console.warn('❌ Hero container not found - skipping hero transition');
        return;
    }

    console.log('🎬 Initializing Deep Dive Hero transition');

    // Split text into characters for dramatic explosion effect
    let splitTitle, splitSubtitle;
    if (heroTitle) {
        splitTitle = new SplitText(heroTitle, { type: "chars" });
        console.log('📝 Split title into', splitTitle.chars.length, 'characters');
    }
    if (heroSubtitle) {
        splitSubtitle = new SplitText(heroSubtitle, { type: "words" });
        console.log('📝 Split subtitle into', splitSubtitle.words.length, 'words');
    }

    const heroTL = gsap.timeline({
        scrollTrigger: {
            trigger: heroContainer,
            start: 'top top',
            end: '+=150%', // Extend the animation 1.5x the viewport height (longer transition)
            scrub: 1,
            id: 'deep-dive',
        }
    });

    // 1. HERO CONTAINER - Just fade, no movement (stays in place)
    heroTL.to(heroContainer, {
        opacity: 0,
        ease: 'power2.inOut',
        duration: 1,
    }, 0);

    // 2. TITLE - Explode into fragments (toward camera)
    if (splitTitle && splitTitle.chars) {
        heroTL.to(splitTitle.chars, {
            x: () => gsap.utils.random(-300, 300),
            y: () => gsap.utils.random(-300, 300),
            z: () => gsap.utils.random(200, 1000),
            rotation: () => gsap.utils.random(-360, 360),
            opacity: 0,
            scale: () => gsap.utils.random(0.2, 2),
            ease: 'power2.out',
            stagger: {
                amount: 0.15,
                from: 'center'
            },
        }, 0.1);
    }

    // 3. SUBTITLE - Fragment explosion (slightly delayed)
    if (splitSubtitle && splitSubtitle.words) {
        heroTL.to(splitSubtitle.words, {
            x: () => gsap.utils.random(-250, 250),
            y: () => gsap.utils.random(-250, 250),
            z: () => gsap.utils.random(300, 800),
            rotation: () => gsap.utils.random(-180, 180),
            opacity: 0,
            scale: 0,
            ease: 'power2.out',
            stagger: 0.03,
        }, 0.2);
    }

    // 4. CTAs - Fly away
    if (heroCtas.length > 0) {
        heroTL.to(heroCtas, {
            z: 600,
            opacity: 0,
            scale: 0.5,
            ease: 'power2.in',
            stagger: 0.05,
        }, 0.25);
    }

    // 5. BACKGROUND - Enhanced parallax (moves faster than container)
    if (heroBackground) {
        heroTL.to(heroBackground, {
            y: -300, // Moves more than container for parallax depth
            x: -80,
            opacity: 0,
            ease: 'power2.out',
            duration: 1,
        }, 0);
    }

    console.log('✅ Deep Dive transition initialized');
}

/**
 * ==========================================================================
 * DEL 2: SECTION SYSTEM (Återanvändbart för alla sektioner)
 * ==========================================================================
 */
export function initSectionAnimations() {
    const sections = document.querySelectorAll('.animated-section');

    if (sections.length === 0) {
        console.warn('⚠️ No animated sections found');
        return [];
    }

    console.log(`🎬 Auto-discovering ${sections.length} animated sections...`);

    const transitionContexts = [];
    let zIndexBase = 100;

    sections.forEach((section, index) => {
        const scrollMode = section.dataset.scrollMode || 'normal';
        const isLayered = scrollMode === 'layered' || scrollMode === 'layered-scroll';
        const isLayeredScroll = scrollMode === 'layered-scroll';

        if (isLayered) {
            // LAYERED MODE: Fixed positioning
            let viewSpacer = null;

            // Skapa view spacer ENDAST för "layered" (inte "layered-scroll")
            // Layered-scroll får sitt scroll space från transition spacer
            if (!isLayeredScroll) {
                viewSpacer = document.createElement('div');
                viewSpacer.className = 'section-view-spacer';
                viewSpacer.style.height = '100vh';
                section.parentNode.insertBefore(viewSpacer, section);
            }

            // För layered-scroll: Skapa content spacer som håller nästa section nedskjuten
            let contentSpacer = null;
            if (isLayeredScroll) {
                // Räkna ut sektionens faktiska höjd (innan vi gör den fixed)
                const sectionHeight = section.scrollHeight;

                contentSpacer = document.createElement('div');
                contentSpacer.className = 'section-content-spacer';
                contentSpacer.style.height = `${sectionHeight}px`;

                // Sätt spacer EFTER section i DOM (håller nästa section nedskjuten)
                if (section.nextElementSibling) {
                    section.parentNode.insertBefore(contentSpacer, section.nextElementSibling);
                } else {
                    section.parentNode.appendChild(contentSpacer);
                }

                console.log(`  📦 Created content spacer (${sectionHeight}px) for ${section.id}`);
            }

            gsap.set(section, {
                position: 'fixed',
                top: 0,
                left: 0,
                width: '100%',
                minHeight: '100vh',
                zIndex: zIndexBase,
            });

            const content = section.querySelector('.animated-section-content');
            if (index > 0 && content) {
                gsap.set(content, { opacity: 0 });
                console.log(`  ✓ ${section.id}: ${scrollMode.toUpperCase()} (fixed, z-index=${zIndexBase}, content hidden${isLayeredScroll ? ', no view spacer' : ''})`);
            } else {
                console.log(`  ✓ ${section.id}: ${scrollMode.toUpperCase()} (fixed, z-index=${zIndexBase}, visible)`);
            }

            // Transition till nästa sektion
            if (index < sections.length - 1) {
                const nextSection = sections[index + 1];
                const nextScrollMode = nextSection.dataset.scrollMode || 'normal';

                if (nextScrollMode === 'layered' || nextScrollMode === 'layered-scroll') {
                    // Layered → Layered/LayeredScroll transition
                    const context = createSectionTransition(section, nextSection, zIndexBase);
                    if (context) {
                        transitionContexts.push({
                            current: section,
                            next: nextSection,
                            viewSpacer,
                            contentSpacer,
                            ...context
                        });
                    }
                } else {
                    // Layered → Normal transition
                    const context = createLayeredToNormalTransition(section, nextSection, zIndexBase);
                    if (context) {
                        transitionContexts.push({
                            current: section,
                            next: nextSection,
                            viewSpacer,
                            contentSpacer,
                            ...context
                        });
                    }
                }
            }

            zIndexBase -= 10;
        } else {
            // NORMAL MODE: Vanlig sektion i document flow
            console.log(`  ✓ ${section.id}: NORMAL (relative, document flow)`);
            gsap.set(section, {
                position: 'relative',
            });
        }

        // Parallax för denna sektion
        initSectionParallax(section);
    });

    console.log(`✅ Created ${transitionContexts.length} layered section transitions`);
    return transitionContexts;
}

/**
 * Initialisera parallax för en sektion
 * Om sektion är fixed, använd viewSpacer som trigger
 */
function initSectionParallax(section) {
    const parallaxLayers = section.querySelectorAll('[data-speed]');

    if (parallaxLayers.length === 0) return;

    // Mobil-optimering: Reducera parallax-intensitet
    const isMobile = window.innerWidth < 768;

    // Hitta rätt trigger baserat på sektionstyp
    const scrollMode = section.dataset.scrollMode || 'normal';
    const isLayeredScroll = scrollMode === 'layered-scroll';

    let trigger = section; // Default: använd section själv

    if (isLayeredScroll) {
        // För layered-scroll: Hitta transition spacer (ligger före section)
        const transitionSpacer = section.previousElementSibling;
        if (transitionSpacer && transitionSpacer.classList.contains('section-transition-spacer')) {
            trigger = transitionSpacer;
        }
    } else {
        // För vanlig layered: Använd view spacer (ligger före section)
        const viewSpacer = section.previousElementSibling;
        if (viewSpacer && viewSpacer.classList.contains('section-view-spacer')) {
            trigger = viewSpacer;
        }
    }

    parallaxLayers.forEach(layer => {
        const speed = parseInt(layer.dataset.speed) || 50;
        const adjustedSpeed = isMobile ? speed * 0.5 : speed;

        gsap.to(layer, {
            y: -adjustedSpeed,
            ease: 'none',
            scrollTrigger: {
                trigger: trigger,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true,
                invalidateOnRefresh: true,
            },
        });
    });
}

/**
 * ==========================================================================
 * GENERISK SECTION TRANSITION SYSTEM
 * ==========================================================================
 */

/**
 * Transition från layered section till normal section
 * Fade ut layered, release till normal flow, låt normal scrolla fram
 */
function createLayeredToNormalTransition(currentSection, nextSection, zIndexBase) {
    console.log(`🎨 Creating layered→normal transition: ${currentSection.id} → ${nextSection.id}`);

    // Skapa transition spacer
    const transitionSpacer = document.createElement('div');
    transitionSpacer.className = 'section-transition-spacer';
    transitionSpacer.style.height = '100vh';
    nextSection.parentNode.insertBefore(transitionSpacer, nextSection);

    const transitionTL = gsap.timeline({
        scrollTrigger: {
            trigger: transitionSpacer,
            start: 'top top',
            end: 'bottom top',
            scrub: 1,
            markers: true, // DEBUG
            onLeave: () => {
                // Release current från fixed, dölj content
                const currentContent = currentSection.querySelector('.animated-section-content');
                if (currentContent) {
                    gsap.set(currentContent, { opacity: 0 });
                }
                // Cleara alla GSAP properties för naturlig höjd/position
                gsap.set(currentSection, {
                    clearProps: 'position,top,left,width,minHeight,zIndex',
                });
                console.log(`  ✓ Released to normal flow: ${currentSection.id}`);
            },
            onEnterBack: () => {
                // Re-fix när scrollar tillbaka
                gsap.set(currentSection, {
                    position: 'fixed',
                    top: 0,
                    left: 0,
                    width: '100%',
                    minHeight: '100vh',
                    zIndex: zIndexBase,
                });
                const currentContent = currentSection.querySelector('.animated-section-content');
                if (currentContent) {
                    gsap.set(currentContent, { opacity: 1 });
                }
                console.log(`  ✓ Re-fixed: ${currentSection.id}`);
            },
        },
    });

    const currentContent = currentSection.querySelector('.animated-section-content');

    // Fade current content ut
    if (currentContent) {
        transitionTL.to(currentContent, {
            opacity: 0,
            y: -50,
            ease: 'power2.inOut',
            duration: 1,
        }, 0);
    }

    // Next section scrollar fram naturligt (den är redan i flow)

    return {
        timeline: transitionTL,
        transitionSpacer,
    };
}

/**
 * Skapa seamless transition mellan två layered sektioner
 *
 * Layering: Current section ligger FRAMFÖR next section
 * När current fadar ut blir next section synlig (den låg redan där bakom)
 */
function createSectionTransition(currentSection, nextSection, zIndexBase) {
    const nextTheme = currentSection.dataset.nextTheme;
    if (!nextTheme) {
        console.warn('⚠️ No next-theme defined for section:', currentSection.id);
        return null;
    }

    const nextColors = getThemeColors(nextTheme);
    const nextScrollMode = nextSection.dataset.scrollMode || 'normal';
    const isNextLayeredScroll = nextScrollMode === 'layered-scroll';

    console.log(`🎨 Creating transition: ${currentSection.id} → ${nextSection.id}`);

    // Skapa TRANSITION SPACER (trigger för transition animation)
    const transitionSpacer = document.createElement('div');
    transitionSpacer.className = 'section-transition-spacer';
    transitionSpacer.style.height = '100vh'; // 100vh scroll för transition
    nextSection.parentNode.insertBefore(transitionSpacer, nextSection);

    // Sektioner är redan fixed från initSectionAnimations
    // Current: z-index 100, visible
    // Next: z-index 90, content hidden (opacity: 0)

    const transitionTL = gsap.timeline({
        scrollTrigger: {
            trigger: transitionSpacer,
            start: 'top top',
            end: 'bottom top',
            scrub: 1,
            markers: true, // DEBUG
            onLeave: () => {
                // Transition klar: release current, dölj content
                const currentContent = currentSection.querySelector('.animated-section-content');
                if (currentContent) {
                    gsap.set(currentContent, { opacity: 0 });
                }
                // VIKTIGT: Cleara alla GSAP properties så sectionen får naturlig höjd/position
                gsap.set(currentSection, {
                    clearProps: 'position,top,left,width,minHeight,zIndex',
                });
                console.log(`  ✓ Released & hidden: ${currentSection.id}`);

                // Om next är "layered-scroll", release den också till normal flow
                if (isNextLayeredScroll) {
                    // VIKTIGT: Cleara alla GSAP properties så sectionen får sin naturliga höjd
                    gsap.set(nextSection, {
                        clearProps: 'position,top,left,width,minHeight,zIndex',
                    });

                    // DÖLJ transition spacer - detta eliminerar blank space
                    if (transitionSpacer) {
                        gsap.set(transitionSpacer, { height: 0 });
                        console.log(`  👻 Hidden transition spacer (height: 0)`);
                    }

                    // TA BORT content spacer - sectionen tar nu sin egen plats i flow
                    const contentSpacer = document.querySelector('.section-content-spacer');
                    if (contentSpacer && contentSpacer.parentNode) {
                        contentSpacer.remove();
                        console.log(`  🗑️ Removed content spacer for ${nextSection.id}`);
                    }

                    console.log(`  ✓ Released to scroll: ${nextSection.id} (cleared all props for natural height)`);

                    // Refresh ScrollTrigger så animationer i released section fungerar
                    setTimeout(() => {
                        ScrollTrigger.refresh();
                        console.log(`  🔄 Refreshed ScrollTrigger for ${nextSection.id} animations`);
                    }, 100);
                }
            },
            onEnterBack: () => {
                // Re-fix currentSection
                gsap.set(currentSection, {
                    position: 'fixed',
                    top: 0,
                    left: 0,
                    width: '100%',
                    minHeight: '100vh',
                    zIndex: zIndexBase,
                });
                // Låt timeline hantera opacity automatiskt
                console.log(`  ✓ Re-fixed: ${currentSection.id}`);

                // Om next är "layered-scroll", re-fix den också
                if (isNextLayeredScroll) {
                    gsap.set(nextSection, {
                        position: 'fixed',
                        top: 0,
                        left: 0,
                        width: '100%',
                        minHeight: '100vh',
                        zIndex: zIndexBase - 10,
                    });
                    // Låt timeline hantera opacity automatiskt

                    // VISA transition spacer igen
                    if (transitionSpacer) {
                        gsap.set(transitionSpacer, { height: '100vh' });
                        console.log(`  👁️ Restored transition spacer (height: 100vh)`);
                    }

                    // Återskapa content spacer för att hålla Timeline nedskjuten
                    const sectionHeight = nextSection.scrollHeight;
                    const newContentSpacer = document.createElement('div');
                    newContentSpacer.className = 'section-content-spacer';
                    newContentSpacer.style.height = `${sectionHeight}px`;

                    if (nextSection.nextElementSibling) {
                        nextSection.parentNode.insertBefore(newContentSpacer, nextSection.nextElementSibling);
                    } else {
                        nextSection.parentNode.appendChild(newContentSpacer);
                    }
                    console.log(`  📦 Recreated content spacer (${sectionHeight}px)`);
                    console.log(`  ✓ Re-fixed: ${nextSection.id}`);

                    // Refresh ScrollTrigger när sectionen re-fixes
                    setTimeout(() => {
                        ScrollTrigger.refresh();
                    }, 100);
                }
            },
        },
    });

    const currentContent = currentSection.querySelector('.animated-section-content');
    const nextContent = nextSection.querySelector('.animated-section-content');

    // Fade current content ut
    if (currentContent) {
        transitionTL.to(currentContent, {
            opacity: 0,
            y: -50,
            ease: 'power2.inOut',
            duration: 1,
        }, 0);
    }

    // Fade next content in (från opacity: 0)
    if (nextContent) {
        transitionTL.to(nextContent, {
            opacity: 1,
            y: 0,
            ease: 'power2.inOut',
            duration: 1,
        }, 0.3); // 0.3s overlap för seamless
    }

    // Color morphing - SYNKAT med content fade (samma timing & easing)
    transitionTL.to(':root', {
        '--color-primary': nextColors.primary,
        '--color-secondary': nextColors.secondary,
        '--color-accent': nextColors.accent,
        '--color-bg-overlay': nextColors.bgOverlay,
        ease: 'power2.inOut', // Samma som content fade för perfekt synk
        duration: 1,
    }, 0);

    return {
        timeline: transitionTL,
        transitionSpacer,
    };
}

/**
 * Hämta CSS custom properties för ett tema
 */
function getThemeColors(themeName) {
    const tempDiv = document.createElement('div');
    tempDiv.style.display = 'none';
    tempDiv.dataset.theme = themeName;
    document.body.appendChild(tempDiv);

    const computed = getComputedStyle(tempDiv);
    const colors = {
        primary: computed.getPropertyValue('--theme-primary').trim(),
        secondary: computed.getPropertyValue('--theme-secondary').trim(),
        accent: computed.getPropertyValue('--theme-accent').trim(),
        bgOverlay: computed.getPropertyValue('--theme-bg-overlay').trim(),
    };

    document.body.removeChild(tempDiv);

    return colors;
}

/**
 * ==========================================================================
 * MOBILE OPTIMIZATIONS
 * ==========================================================================
 */
ScrollTrigger.config({
    limitCallbacks: true, // Performance boost
    syncInterval: 150, // Balance mellan smoothness och performance
});

/**
 * Refresh ScrollTrigger vid resize (debounced)
 */
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        ScrollTrigger.refresh();
    }, 250);
});

/**
 * ==========================================================================
 * INITIALIZATION
 * ==========================================================================
 */

// Store transition contexts globally for cleanup
let sectionTransitionContexts = [];

function initializeTransitions() {
    // Kolla för prefers-reduced-motion (men inte på lokal dev)
    const isLocalDev = window.location.hostname === 'atdev.test';
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!isLocalDev && prefersReducedMotion) {
        console.log('⚠️ Reduced motion preferred - disabling GSAP animations');
        return;
    }

    console.log('🚀 Starting section transitions initialization...');

    // 1. Hero transition (isolated, special Deep Dive effect)
    initHeroTransition();

    // 2. Section transition system (auto-discovery, återanvändbart)
    sectionTransitionContexts = initSectionAnimations();

    console.log('🎉 Section transitions initialized successfully!');
    console.log(`   - Hero transition: Deep Dive effect`);
    console.log(`   - Section transitions: ${sectionTransitionContexts.length} auto-discovered`);
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    sectionTransitionContexts.forEach(ctx => {
        if (ctx.timeline) ctx.timeline.kill();
        if (ctx.viewSpacer) ctx.viewSpacer.remove();
        if (ctx.contentSpacer) ctx.contentSpacer.remove();
        if (ctx.transitionSpacer) ctx.transitionSpacer.remove();
    });
});

// Initialize immediately if DOM is ready, otherwise wait
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeTransitions);
} else {
    // DOM already loaded (Vite loads modules after DOM ready)
    initializeTransitions();
}
