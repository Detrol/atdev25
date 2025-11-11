/**
 * Pinned Sections - GSAP ScrollTrigger Curtain Reveal med Scrollbara Sektioner
 *
 * Alla sektioner är fixed och stackade MED overflow-y: auto.
 * Man scrollar INNE i varje sektion.
 * När man når slutet av sektionens innehåll, trigga transition till nästa.
 */

import { gsap, ScrollTrigger } from './gsap-config.js';

const isMobile = window.innerWidth < 768;
let currentSectionIndex = 0;
let isTransitioning = false;

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
 * Skapa curtain reveal transition
 */
function createTransition(fromSection, toSection, fromIndex, toIndex, sections) {
    return new Promise((resolve) => {
        isTransitioning = true;

        const toTheme = toSection.dataset.theme;
        const toColors = toTheme ? getThemeColors(toTheme) : null;

        console.log(`🎬 Transitioning: ${fromSection.id} → ${toSection.id}`);

        // Trigger animations at 30% progress
        let animationTriggered = false;

        // Create transition timeline
        const tl = gsap.timeline({
            onComplete: () => {
                currentSectionIndex = toIndex;
                isTransitioning = false;
                console.log(`  ✓ Transition complete, now at ${toSection.id}`);

                // Instant color change on mobile (no smooth morphing for performance)
                if (isMobile && toColors) {
                    const root = document.documentElement;
                    root.style.setProperty('--color-primary', toColors.primary);
                    root.style.setProperty('--color-secondary', toColors.secondary);
                    root.style.setProperty('--color-accent', toColors.accent);
                    root.style.setProperty('--color-bg-overlay', toColors.bgOverlay);
                }

                resolve();
            }
        });

        // Fade out FROM section entirely (curtain reveal)
        tl.to(fromSection, {
            opacity: 0,
            duration: 1,
            ease: 'power2.inOut'
        }, 0);

        // Fade in TO section
        tl.to(toSection, {
            opacity: 1,
            duration: 1,
            ease: 'power2.inOut',
            onUpdate: function() {
                const progress = this.progress();

                // Trigger animations at 30%
                if (progress >= 0.3 && !animationTriggered) {
                    const event = new CustomEvent('section:reveal', {
                        detail: { sectionId: toSection.id }
                    });
                    document.dispatchEvent(event);
                    animationTriggered = true;
                    console.log(`  ✓ Triggered animations for ${toSection.id}`);
                }

                // Color morphing (DISABLED on mobile for performance)
                // getComputedStyle() forces reflow - extremely expensive on mobile
                if (toColors && !isMobile) {
                    const root = document.documentElement;
                    gsap.set(root, {
                        '--color-primary': gsap.utils.interpolate(
                            getComputedStyle(root).getPropertyValue('--color-primary').trim(),
                            toColors.primary,
                            progress
                        ),
                        '--color-secondary': gsap.utils.interpolate(
                            getComputedStyle(root).getPropertyValue('--color-secondary').trim(),
                            toColors.secondary,
                            progress
                        ),
                        '--color-accent': gsap.utils.interpolate(
                            getComputedStyle(root).getPropertyValue('--color-accent').trim(),
                            toColors.accent,
                            progress
                        ),
                        '--color-bg-overlay': gsap.utils.interpolate(
                            getComputedStyle(root).getPropertyValue('--color-bg-overlay').trim(),
                            toColors.bgOverlay,
                            progress
                        )
                    });
                }
            }
        }, 0);
    });
}

/**
 * Setup scroll listener på en sektion
 */
function setupSectionScrollListener(section, index, sections) {
    let scrollTimeout;

    section.addEventListener('scroll', () => {
        if (isTransitioning) return;

        // Debounce för att inte trigga för ofta
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            const scrollTop = section.scrollTop;
            const scrollHeight = section.scrollHeight;
            const clientHeight = section.clientHeight;

            // Är vi i botten av sektionen?
            const atBottom = scrollTop + clientHeight >= scrollHeight - 10; // 10px threshold

            if (atBottom && index < sections.length - 1) {
                console.log(`  📍 Reached bottom of ${section.id}`);
                const nextSection = sections[index + 1].element;
                createTransition(section, nextSection, index, index + 1, sections);
            }

            // Är vi i toppen av sektionen? (för scroll up)
            const atTop = scrollTop <= 10; // 10px threshold

            if (atTop && index > 0) {
                console.log(`  📍 Reached top of ${section.id}`);
                const prevSection = sections[index - 1].element;
                createTransition(section, prevSection, index, index - 1, sections);
            }
        }, 100);
    });
}

/**
 * Initialisera alla pinned sections
 */
export function initPinnedSections() {
    console.log('🚀 Starting Stackable Scrollable Sections...');

    const sections = [
        { id: 'main-content', zIndex: 100 },
        { id: 'om-mig', zIndex: 90 },
        { id: 'hur-jag-jobbar', zIndex: 80 },
        { id: 'expertis', zIndex: 70 },
        { id: 'services', zIndex: 60 },
        { id: 'projects', zIndex: 50 },
        { id: 'demos-cta', zIndex: 40 }
    ];

    const sectionElements = sections
        .map(config => {
            const element = document.querySelector(`#${config.id}`);
            if (element) {
                return { element, zIndex: config.zIndex };
            }
            return null;
        })
        .filter(Boolean);

    if (sectionElements.length < 2) {
        console.warn('⚠️ Need at least 2 sections');
        return;
    }

    console.log(`📊 Found ${sectionElements.length} sections`);

    // Setup varje sektion
    sectionElements.forEach((config, index) => {
        const section = config.element;

        // Stack alla sektioner fixed med overflow
        // Använd setProperty med !important för att overrida Tailwind classes
        section.style.setProperty('position', 'fixed', 'important');
        section.style.setProperty('top', '0', 'important');
        section.style.setProperty('left', '0', 'important');
        section.style.setProperty('width', '100%', 'important');
        section.style.setProperty('height', '100vh', 'important');
        section.style.setProperty('overflow-y', 'auto', 'important');
        section.style.setProperty('overflow-x', 'hidden', 'important');
        section.style.setProperty('z-index', config.zIndex, 'important');

        // Alla utom första börjar dolda (opacity 0)
        if (index > 0) {
            section.style.setProperty('opacity', '0', 'important');
        } else {
            section.style.setProperty('opacity', '1', 'important');
        }

        // Setup scroll listener
        setupSectionScrollListener(section, index, sectionElements);

        console.log(`  📌 Stacked ${section.id} (z-index: ${config.zIndex})`);
    });

    console.log('✅ Stackable Scrollable Sections initialized!');
}

// Auto-initialisera
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPinnedSections);
} else {
    initPinnedSections();
}
