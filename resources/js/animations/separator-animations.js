/**
 * Separator Animations
 *
 * Parallax effekter för wave separators
 */

import { gsap } from './gsap-config.js';
import { ScrollTrigger } from './gsap-config.js';

const isProduction = window.location.hostname === 'atdev.me';

export function initSeparatorAnimations() {
    if (!isProduction) console.log('🌊 Initializing Wave Separator Animations');

    // FÖRSTA: Flytta wave dividers UTANFÖR .animated-section-content
    // Använd cloneNode för att bevara SVG <defs> referenser
    const sections = document.querySelectorAll('.animated-section');
    sections.forEach(section => {
        const content = section.querySelector('.animated-section-content');
        if (!content) return;

        // Hitta alla wave dividers INUTI content
        const wavesInContent = content.querySelectorAll('.wave-divider');

        wavesInContent.forEach(wave => {
            // Skapa en djup klon (inkluderar <defs> och alla SVG-element)
            const waveClone = wave.cloneNode(true);

            // VIKTIGT: Uppdatera gradient-ID för att undvika kollisioner
            const svg = waveClone.querySelector('svg');
            if (svg) {
                // Hitta gradient i <defs>
                const gradient = svg.querySelector('linearGradient[id^="wave-gradient-"]');
                if (gradient) {
                    const oldId = gradient.id;
                    const newId = `wave-gradient-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;

                    // Uppdatera gradient ID
                    gradient.id = newId;

                    // Uppdatera alla referenser till gradient
                    const midLayer = svg.querySelector('.wave-layer-mid path');
                    if (midLayer) {
                        const fill = midLayer.getAttribute('fill');
                        if (fill && fill.includes(oldId)) {
                            midLayer.setAttribute('fill', `url(#${newId})`);
                        }
                    }
                }
            }

            // Lägg till klonen på section-nivå (UTANFÖR content)
            section.appendChild(waveClone);

            // Ta bort originalet från content
            wave.remove();
        });
    });

    if (!isProduction) console.log(`✅ Moved ${document.querySelectorAll('.wave-divider').length} wave dividers outside content layer`);

    // Wave Separators med parallax effekt
    const waveSeparators = document.querySelectorAll('[data-wave-separator]');

    waveSeparators.forEach(separator => {
        const backLayer = separator.querySelector('.wave-layer-back');
        const midLayer = separator.querySelector('.wave-layer-mid');
        const frontLayer = separator.querySelector('.wave-layer-front');

        if (!backLayer || !midLayer || !frontLayer) return;

        // VIKTIGT: Sätt INTE opacity här - SVG har redan opacity-värden (0.15, 0.5, 0.2)
        // Wave-containern har opacity: 1, vilket är tillräckligt för synlighet

        // Parallax effekt baserat på scroll - smooth och synkroniserad
        gsap.to(backLayer, {
            y: 20,
            ease: 'none',
            scrollTrigger: {
                trigger: separator,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 0.5  // Smoothare scrub
            }
        });

        gsap.to(midLayer, {
            y: 10,
            ease: 'none',
            scrollTrigger: {
                trigger: separator,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 0.5
            }
        });

        gsap.to(frontLayer, {
            y: 5,
            ease: 'none',
            scrollTrigger: {
                trigger: separator,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 0.5
            }
        });
    });

    if (!isProduction) console.log(`✅ Wave Separator Animations initialized (${waveSeparators.length} separators)`);
}

// Auto-init
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSeparatorAnimations);
} else {
    initSeparatorAnimations();
}
