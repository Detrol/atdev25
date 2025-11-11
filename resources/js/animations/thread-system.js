/**
 * Thread System - Storytelling Visual Thread
 *
 * En animerad linje/path som följer användarens scroll och
 * visuellt kopplar ihop alla sektioner som en sammanhängande berättelse.
 */

import { gsap } from './gsap-config.js';
import { ScrollTrigger } from './gsap-config.js';

const isProduction = window.location.hostname === 'atdev.me';

export function initThreadSystem() {
    // Only show thread on home page
    const isHomePage = window.location.pathname === '/' || window.location.pathname === '/home';
    if (!isHomePage) {
        if (!isProduction) console.log('🧵 Thread System: Skipping (not home page)');
        return;
    }

    // Check if thread already exists
    if (document.querySelector('.story-thread-container')) {
        return;
    }

    if (!isProduction) console.log('🧵 Initializing Story Thread System');

    let threadContainer = null;
    let pathElement = null;
    let glowCircle = null;

    const createThreadSystem = () => {
        // Create thread container - fixed to viewport
        threadContainer = document.createElement('div');
        threadContainer.className = 'story-thread-container';
        threadContainer.style.cssText = `
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 1400px;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            overflow: visible;
        `;

        // Create SVG for the thread path
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('width', '100%');
        svg.setAttribute('height', '100%');
        svg.setAttribute('viewBox', `0 0 100 100`);
        svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');
        svg.style.cssText = 'position: absolute; top: 0; left: 0; width: 100%; height: 100%;';

        // Define gradient for the thread
        const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
        const gradient = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
        gradient.setAttribute('id', 'threadGradient');
        gradient.setAttribute('x1', '0%');
        gradient.setAttribute('y1', '0%');
        gradient.setAttribute('x2', '0%');
        gradient.setAttribute('y2', '100%');

        // Gradient stops - matching theme progression
        const stops = [
            { offset: '0%', color: '#8A2BE2' },      // Purple (hero/about)
            { offset: '20%', color: '#4169E1' },     // Blue (how-i-work)
            { offset: '35%', color: '#FF69B4' },     // Pink (timeline)
            { offset: '50%', color: '#FF8C00' },     // Orange (services)
            { offset: '65%', color: '#FFBF00' },     // Amber (projects)
            { offset: '80%', color: '#32CD32' },     // Green (faq)
            { offset: '100%', color: '#20B2AA' }     // Teal (contact)
        ];

        stops.forEach(stop => {
            const stopEl = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
            stopEl.setAttribute('offset', stop.offset);
            stopEl.setAttribute('style', `stop-color:${stop.color};stop-opacity:1`);
            gradient.appendChild(stopEl);
        });

        defs.appendChild(gradient);

        // Add glow filter
        const glowFilter = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
        glowFilter.setAttribute('id', 'glow');
        glowFilter.setAttribute('x', '-200%');
        glowFilter.setAttribute('y', '-200%');
        glowFilter.setAttribute('width', '500%');
        glowFilter.setAttribute('height', '500%');
        glowFilter.setAttribute('filterUnits', 'userSpaceOnUse');

        const feGaussianBlur = document.createElementNS('http://www.w3.org/2000/svg', 'feGaussianBlur');
        feGaussianBlur.setAttribute('stdDeviation', '3');
        feGaussianBlur.setAttribute('result', 'coloredBlur');

        const feMerge = document.createElementNS('http://www.w3.org/2000/svg', 'feMerge');
        const feMergeNode1 = document.createElementNS('http://www.w3.org/2000/svg', 'feMergeNode');
        feMergeNode1.setAttribute('in', 'coloredBlur');
        const feMergeNode2 = document.createElementNS('http://www.w3.org/2000/svg', 'feMergeNode');
        feMergeNode2.setAttribute('in', 'SourceGraphic');

        feMerge.appendChild(feMergeNode1);
        feMerge.appendChild(feMergeNode2);
        glowFilter.appendChild(feGaussianBlur);
        glowFilter.appendChild(feMerge);
        defs.appendChild(glowFilter);

        svg.appendChild(defs);

        // Create the path - smooth flowing line within viewport
        pathElement = document.createElementNS('http://www.w3.org/2000/svg', 'path');

        // S-curve path that flows vertically through viewport (viewBox 0-100)
        const pathData = `
            M 50 10
            Q 70 25, 50 40
            Q 30 55, 50 70
            Q 70 85, 50 100
        `;

        pathElement.setAttribute('d', pathData);
        pathElement.setAttribute('fill', 'none');
        pathElement.setAttribute('stroke', 'url(#threadGradient)');
        pathElement.setAttribute('stroke-width', '0.8');
        pathElement.setAttribute('stroke-linecap', 'round');
        pathElement.setAttribute('opacity', '0.6');
        pathElement.classList.add('story-thread');

        svg.appendChild(pathElement);

        // Add glow circle
        glowCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        glowCircle.setAttribute('r', '1.5');
        glowCircle.setAttribute('fill', 'currentColor');
        glowCircle.setAttribute('filter', 'url(#glow)');
        glowCircle.classList.add('thread-glow');
        glowCircle.style.color = '#8A2BE2';

        svg.appendChild(glowCircle);

        threadContainer.appendChild(svg);
        document.body.appendChild(threadContainer);

        // Hide on mobile
        if (window.innerWidth < 768) {
            threadContainer.style.display = 'none';
        }
    };

    const animateThread = () => {
        if (!pathElement || !glowCircle) return;

        // Get total path length for animation
        const pathLength = pathElement.getTotalLength();

        // Set initial state - path not drawn
        gsap.set(pathElement, {
            strokeDasharray: pathLength,
            strokeDashoffset: pathLength
        });

        // Animate path drawing on scroll
        gsap.to(pathElement, {
            strokeDashoffset: 0,
            ease: 'none',
            scrollTrigger: {
                trigger: 'body',
                start: 'top top',
                end: 'bottom bottom',
                scrub: 1,
                onUpdate: (self) => {
                    // Update opacity based on scroll progress
                    const opacity = 0.3 + (self.progress * 0.4); // 0.3 to 0.7
                    pathElement.setAttribute('opacity', opacity);
                }
            }
        });

        // Animate glow circle along path
        gsap.to(glowCircle, {
            motionPath: {
                path: pathElement,
                align: pathElement,
                alignOrigin: [0.5, 0.5]
            },
            ease: 'none',
            scrollTrigger: {
                trigger: 'body',
                start: 'top top',
                end: 'bottom bottom',
                scrub: 1,
                onUpdate: (self) => {
                    // Update glow color based on progress
                    const colors = ['#8A2BE2', '#4169E1', '#FF69B4', '#FF8C00', '#FFBF00', '#32CD32', '#20B2AA'];
                    const colorIndex = Math.min(Math.floor(self.progress * colors.length), colors.length - 1);
                    glowCircle.style.color = colors[colorIndex];
                }
            }
        });
    };

    // Initialize thread system
    createThreadSystem();
    animateThread();

    // Handle resize - debounced with smart refresh
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            if (window.innerWidth < 768) {
                // Hide on mobile
                if (threadContainer) threadContainer.style.display = 'none';
            } else {
                // Show on desktop
                if (threadContainer) {
                    threadContainer.style.display = '';
                    // Refresh ScrollTriggers for thread only
                    ScrollTrigger.getAll().forEach(trigger => {
                        if (trigger.vars.trigger === 'body') {
                            trigger.refresh();
                        }
                    });
                }
            }
        }, 250);
    });

    if (!isProduction) console.log('✅ Story Thread System initialized');
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initThreadSystem);
} else {
    initThreadSystem();
}
