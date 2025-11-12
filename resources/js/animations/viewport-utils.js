/**
 * Viewport Detection Utility
 *
 * Provides dynamic viewport detection with resize listeners.
 * Replaces static `const isMobile = window.innerWidth < 768` pattern
 * with reactive detection that updates on resize/rotation.
 *
 * Usage:
 * import { viewport } from './viewport-utils.js';
 *
 * if (viewport.isMobile) { ... }
 * viewport.onResize(() => { console.log('Viewport changed!'); });
 */

export class ViewportDetector {
    constructor() {
        this.breakpoints = {
            mobile: 768,
            tablet: 1024,
            desktop: 1400
        };

        this.resizeCallbacks = [];
        this.wasLastMobile = null;

        this.update();
        this.setupListeners();
    }

    /**
     * Update current viewport state
     */
    update() {
        this.width = window.innerWidth;
        this.height = window.innerHeight;
        this.isMobile = this.width < this.breakpoints.mobile;
        this.isTablet = this.width >= this.breakpoints.mobile && this.width < this.breakpoints.tablet;
        this.isDesktop = this.width >= this.breakpoints.desktop;
        this.isLandscape = this.width > this.height;
        this.isPortrait = this.height >= this.width;

        // Aspect ratio categories
        this.aspectRatio = this.width / this.height;
        this.isWidescreen = this.aspectRatio > 1.6; // > 16:10
        this.isSquarish = this.aspectRatio >= 0.9 && this.aspectRatio <= 1.1;
    }

    /**
     * Setup resize listener with debouncing
     */
    setupListeners() {
        let resizeTimeout;

        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);

            resizeTimeout = setTimeout(() => {
                const previousWidth = this.width;
                const previousIsMobile = this.isMobile;

                this.update();

                // Only trigger callbacks if viewport actually changed significantly
                const widthChanged = Math.abs(previousWidth - this.width) > 10;
                const categoryChanged = previousIsMobile !== this.isMobile;

                if (widthChanged || categoryChanged) {
                    this.triggerCallbacks();
                }
            }, 150); // 150ms debounce
        });
    }

    /**
     * Register callback to be called on resize
     * @param {Function} callback - Function to call on viewport change
     */
    onResize(callback) {
        if (typeof callback === 'function') {
            this.resizeCallbacks.push(callback);
        }
    }

    /**
     * Trigger all registered callbacks
     */
    triggerCallbacks() {
        this.resizeCallbacks.forEach(callback => {
            try {
                callback(this);
            } catch (error) {
                console.error('Viewport resize callback error:', error);
            }
        });
    }

    /**
     * Check if device category changed (mobile ↔ desktop)
     * Useful for knowing when to regenerate animations
     */
    didCategoryChange() {
        const changed = this.wasLastMobile !== null && this.wasLastMobile !== this.isMobile;
        this.wasLastMobile = this.isMobile;
        return changed;
    }

    /**
     * Get safe positioning ranges based on viewport
     * Returns tighter bounds on mobile to prevent objects from going off-screen
     */
    getSafeRanges(type = 'default') {
        const ranges = {
            mobile: {
                asteroids: { x: [15, 85], y: [15, 85] },
                stars: { x: [10, 90], y: [10, 90] },
                planets: { x: [20, 80], y: [20, 80] },
                nebulae: { x: [25, 75], y: [25, 75] },
                default: { x: [15, 85], y: [15, 85] }
            },
            desktop: {
                asteroids: { x: [10, 90], y: [10, 90] },
                stars: { x: [5, 95], y: [5, 95] },
                planets: { x: [10, 90], y: [10, 90] },
                nebulae: { x: [20, 80], y: [20, 80] },
                default: { x: [10, 90], y: [10, 90] }
            }
        };

        const category = this.isMobile ? 'mobile' : 'desktop';
        return ranges[category][type] || ranges[category].default;
    }

    /**
     * Get optimal SVG viewBox based on viewport aspect ratio
     * Returns viewBox string that matches screen proportions
     *
     * Examples:
     * - Portrait mobile (375×667): "0 0 100 178" (taller)
     * - Square tablet (768×1024): "0 0 100 133"
     * - Landscape desktop (1920×1080): "0 0 100 56" (wider)
     */
    getViewBox() {
        // Base width is always 100
        const baseWidth = 100;

        // Calculate height based on aspect ratio to maintain proportions
        // aspectRatio = width / height
        // So: viewBoxHeight = baseWidth / aspectRatio
        const viewBoxHeight = Math.round(baseWidth / this.aspectRatio);

        return `0 0 ${baseWidth} ${viewBoxHeight}`;
    }

    /**
     * Get edge ranges for thread system
     */
    getEdgeRanges() {
        if (this.isMobile) {
            return {
                top: { x: [15, 85], y: [5, 20] },
                right: { x: [75, 95], y: [15, 85] },
                bottom: { x: [15, 85], y: [80, 95] },
                left: { x: [5, 25], y: [15, 85] }
            };
        }

        return {
            top: { x: [5, 95], y: [0, 15] },
            right: { x: [85, 100], y: [5, 95] },
            bottom: { x: [5, 95], y: [85, 100] },
            left: { x: [0, 15], y: [5, 95] }
        };
    }

    /**
     * Debug helper: Draw safe zones on SVG (localhost only)
     */
    drawSafeZones(svg, type = 'default') {
        if (window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
            return;
        }

        const ranges = this.getSafeRanges(type);
        const NS = 'http://www.w3.org/2000/svg';

        const safeZone = document.createElementNS(NS, 'rect');
        safeZone.setAttribute('x', ranges.x[0]);
        safeZone.setAttribute('y', ranges.y[0]);
        safeZone.setAttribute('width', ranges.x[1] - ranges.x[0]);
        safeZone.setAttribute('height', ranges.y[1] - ranges.y[0]);
        safeZone.setAttribute('fill', 'none');
        safeZone.setAttribute('stroke', 'red');
        safeZone.setAttribute('stroke-width', '0.5');
        safeZone.setAttribute('stroke-dasharray', '2 2');
        safeZone.setAttribute('opacity', '0.5');

        svg.appendChild(safeZone);
    }
}

// Export singleton instance
export const viewport = new ViewportDetector();

// Optional: Make available globally for debugging
if (typeof window !== 'undefined') {
    window.viewportDetector = viewport;
}
