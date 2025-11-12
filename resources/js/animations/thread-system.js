/**
 * Multi-Thread System - Dynamic Background Storytelling
 *
 * Genererar slumpmässiga animerade threads från olika håll med
 * roterande comet-shapes och particle trails.
 *
 * MOBILE OPTIMIZATION:
 * - Dynamic viewport detection (updates on resize/rotation)
 * - Safer edge placement to prevent threads going off-screen
 * - Reduced trail count and spread on mobile
 * - Container fills full viewport width (no max-width constraint)
 */

import { gsap } from './gsap-config.js';
import { ScrollTrigger } from './gsap-config.js';
import { MotionPathPlugin } from './gsap-config.js';
import { viewport } from './viewport-utils.js';

const isProduction = window.location.hostname === 'atdev.me';

// ==================== CONFIGURATION ====================

const CONFIG = {
    threadCount: { min: 2, max: 3 },
    strokeWidth: { min: 0.5, max: 1.2 },
    opacity: { min: 0.3, max: 0.7 },

    // Comet/Teardrop settings
    cometSize: { min: 1.5, max: 2.5 },

    // Trail settings (REDUCED on mobile for performance and visibility)
    trailLength: viewport.isMobile ? 8 : 20, // Fewer particles on mobile
    trailSpacing: 2, // Frames between particle spawns (lower = more frequent)
    particleLifetime: 0.8, // Seconds before particle disappears
    trailSpread: viewport.isMobile ? 0.4 : 0.6, // SMALLER spread on mobile to keep within viewport

    staggerDelay: 0.15,
    colors: [
        '#8A2BE2', // Purple (hero/about)
        '#4169E1', // Blue (how-i-work)
        '#FF69B4', // Pink (timeline)
        '#FF8C00', // Orange (services)
        '#FFBF00', // Amber (projects)
        '#32CD32', // Green (faq)
        '#20B2AA'  // Teal (contact)
    ]
};

// Thread types for variation
const THREAD_TYPES = {
    S_CURVE: 'sCurve',
    WAVE: 'wave',
    DIAGONAL: 'diagonal',
    ZIGZAG: 'zigzag'
};

// ==================== UTILITY FUNCTIONS ====================

function random(min, max) {
    return Math.random() * (max - min) + min;
}

function randomInt(min, max) {
    return Math.floor(random(min, max + 1));
}

function randomChoice(array) {
    return array[Math.floor(Math.random() * array.length)];
}

// ==================== PATH FACTORY ====================

class PathFactory {
    /**
     * Genererar olika typer av SVG paths
     */
    static generate(type, startX, startY, endX, endY) {
        switch (type) {
            case THREAD_TYPES.S_CURVE:
                return this.generateSCurve(startX, startY, endX, endY);
            case THREAD_TYPES.WAVE:
                return this.generateWave(startX, startY, endX, endY);
            case THREAD_TYPES.DIAGONAL:
                return this.generateDiagonal(startX, startY, endX, endY);
            case THREAD_TYPES.ZIGZAG:
                return this.generateZigzag(startX, startY, endX, endY);
            default:
                return this.generateSCurve(startX, startY, endX, endY);
        }
    }

    static generateSCurve(startX, startY, endX, endY) {
        const midY = (startY + endY) / 2;
        const controlOffset = random(15, 25);

        const cp1X = startX > 50 ? startX - controlOffset : startX + controlOffset;
        const cp2X = endX > 50 ? endX + controlOffset : endX - controlOffset;
        const cp1Y = startY + (endY - startY) * 0.33;
        const cp2Y = startY + (endY - startY) * 0.67;

        return `M ${startX} ${startY} Q ${cp1X} ${cp1Y}, ${(startX + endX) / 2} ${midY} Q ${cp2X} ${cp2Y}, ${endX} ${endY}`;
    }

    static generateWave(startX, startY, endX, endY) {
        const segments = randomInt(3, 5);
        const amplitude = random(8, 15);
        let path = `M ${startX} ${startY}`;

        for (let i = 1; i <= segments; i++) {
            const t = i / segments;
            const y = startY + (endY - startY) * t;
            const x = startX + (endX - startX) * t +
                     Math.sin(t * Math.PI * 2) * amplitude * (i % 2 === 0 ? 1 : -1);
            const cpY = startY + (endY - startY) * (t - 0.5 / segments);
            const cpX = startX + (endX - startX) * (t - 0.5 / segments) +
                       Math.sin((t - 0.5 / segments) * Math.PI * 2) * amplitude * (i % 2 === 0 ? 1 : -1);
            path += ` Q ${cpX} ${cpY}, ${x} ${y}`;
        }

        return path;
    }

    static generateDiagonal(startX, startY, endX, endY) {
        const midX = (startX + endX) / 2 + random(-5, 5);
        const midY = (startY + endY) / 2 + random(-5, 5);
        return `M ${startX} ${startY} Q ${midX} ${midY}, ${endX} ${endY}`;
    }

    static generateZigzag(startX, startY, endX, endY) {
        const segments = randomInt(4, 6);
        let path = `M ${startX} ${startY}`;

        for (let i = 1; i <= segments; i++) {
            const t = i / segments;
            const y = startY + (endY - startY) * t;
            const offset = random(10, 20) * (i % 2 === 0 ? 1 : -1);
            const x = startX + (endX - startX) * t + offset;
            path += ` L ${x} ${y}`;
        }

        return path;
    }
}

// ==================== THREAD GENERATOR ====================

class ThreadGenerator {
    static generateThreads() {
        const count = randomInt(CONFIG.threadCount.min, CONFIG.threadCount.max);
        const threads = [];

        if (!isProduction) console.log(`🧵 Generating ${count} threads`);

        for (let i = 0; i < count; i++) {
            threads.push(this.generateSingleThread(i));
        }

        return threads;
    }

    static generateSingleThread(index) {
        const { startX, startY, endX, endY } = this.generateRandomPoints();

        // Weighted type selection: prefer flowing curves over zigzag
        const typeWeights = [
            THREAD_TYPES.S_CURVE,
            THREAD_TYPES.S_CURVE,  // Double weight
            THREAD_TYPES.WAVE,
            THREAD_TYPES.WAVE,      // Double weight
            THREAD_TYPES.DIAGONAL,
            THREAD_TYPES.ZIGZAG     // Single weight
        ];
        const type = randomChoice(typeWeights);

        const strokeWidth = random(CONFIG.strokeWidth.min, CONFIG.strokeWidth.max);
        const opacity = random(CONFIG.opacity.min, CONFIG.opacity.max);
        const cometSize = random(CONFIG.cometSize.min, CONFIG.cometSize.max);

        return {
            id: `thread-${index}`,
            gradientId: `gradient-${index}`,
            type,
            startX,
            startY,
            endX,
            endY,
            strokeWidth,
            opacity,
            cometSize,
            path: PathFactory.generate(type, startX, startY, endX, endY)
        };
    }

    static generateRandomPoints() {
        // Randomly choose which edges to start/end from (top, right, bottom, left)
        const edges = ['top', 'right', 'bottom', 'left'];
        const startEdge = randomChoice(edges);
        let endEdge = randomChoice(edges);

        // Avoid starting and ending on same edge
        while (endEdge === startEdge) {
            endEdge = randomChoice(edges);
        }

        // Get edge ranges (tighter on mobile to prevent threads going off-screen)
        const edgeRanges = viewport.getEdgeRanges();

        const getPointOnEdge = (edge) => {
            const range = edgeRanges[edge];
            return {
                x: random(range.x[0], range.x[1]),
                y: random(range.y[0], range.y[1])
            };
        };

        const start = getPointOnEdge(startEdge);
        const end = getPointOnEdge(endEdge);

        return {
            startX: start.x,
            startY: start.y,
            endX: end.x,
            endY: end.y
        };
    }
}

// ==================== COMET SHAPE ====================

class CometShape {
    static createTeardrop(size) {
        // Teardrop/comet shape: rounded back, pointed front
        // Designed to point right by default (rotation will orient it)
        const width = size * 1.2;
        const height = size * 2;

        // Path centered at origin for clean rotation
        const path = `
            M ${height * 0.5} 0
            Q ${height * 0.3} ${width * 0.5}, 0 ${width * 0.5}
            Q ${-height * 0.4} 0, 0 ${-width * 0.5}
            Q ${height * 0.3} ${-width * 0.5}, ${height * 0.5} 0
            Z
        `;

        return path.trim();
    }
}

// ==================== TRAIL CONTROLLER ====================

class TrailController {
    constructor(svg, comet, threadId, color, path) {
        this.svg = svg;
        this.comet = comet;
        this.threadId = threadId;
        this.color = color;
        this.particles = [];
        this.frameCount = 0;
        this.particleGroup = null;
        this.path = path;
        this.pathLength = path.getTotalLength();
        this.currentProgress = 0;
        this.lastUpdateFrame = 0; // RAF throttling

        // Particle pooling for better performance
        this.particlePool = [];
        this.maxPoolSize = CONFIG.trailLength + 5; // Pre-allocate slightly more

        this.initParticleGroup();
        this.initParticlePool();
    }

    initParticleGroup() {
        this.particleGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        this.particleGroup.setAttribute('class', 'trail-particles');
        this.particleGroup.setAttribute('data-for', this.threadId);
        this.svg.appendChild(this.particleGroup);

        if (!isProduction) {
            console.log(`🎨 Trail controller initialized for ${this.threadId}`);
        }
    }

    initParticlePool() {
        // Pre-create particle elements to avoid DOM creation during animation
        for (let i = 0; i < this.maxPoolSize; i++) {
            const particle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            particle.setAttribute('cx', 0);
            particle.setAttribute('cy', 0);
            particle.style.willChange = 'transform, opacity';
            particle.style.filter = 'url(#glow)';
            particle.style.display = 'none'; // Hidden by default
            this.particleGroup.appendChild(particle);
            this.particlePool.push(particle);
        }

        if (!isProduction) {
            console.log(`  🔄 Particle pool created: ${this.maxPoolSize} particles`);
        }
    }

    getParticleFromPool() {
        // Reuse particle from pool
        return this.particlePool.find(p => p.style.display === 'none');
    }

    returnParticleToPool(particle) {
        // Return particle to pool (hide it)
        particle.style.display = 'none';
        particle.style.opacity = '0';
    }

    update(progress, color) {
        this.color = color;

        // RAF Throttling: More aggressive on mobile for better performance
        // Desktop: Every 2nd frame (30fps), Mobile: Every 20th frame (3fps)
        const frameSkip = viewport.isMobile ? 20 : 2;
        const currentFrame = Math.floor(Date.now() / 16.67); // ~60fps frame counter
        if (currentFrame - this.lastUpdateFrame < frameSkip) {
            return; // Skip this frame
        }
        this.lastUpdateFrame = currentFrame;

        // Calculate progress delta
        const progressDelta = Math.abs(progress - this.currentProgress);

        // Only spawn particles if:
        // 1. Comet has moved (not stationary)
        // 2. Movement is small (not a teleport/initialization jump)
        const hasMovedEnough = progressDelta > 0.001;
        const isTeleport = progressDelta > 0.02; // More than 2% jump = teleport

        if (hasMovedEnough && !isTeleport) {
            this.frameCount++;
            this.currentProgress = progress;

            // Spawn particle every N frames
            if (this.frameCount % CONFIG.trailSpacing === 0) {
                this.spawnParticle();
            }
        } else if (isTeleport) {
            // Update position silently without spawning particles
            this.currentProgress = progress;
        }

        // Always update existing particles
        this.updateParticles();
    }

    spawnParticle() {
        // Get particle from pool instead of creating new
        const particle = this.getParticleFromPool();
        if (!particle) {
            // Pool exhausted (shouldn't happen with proper sizing)
            if (!isProduction) console.warn('Particle pool exhausted');
            return;
        }

        const size = random(0.2, 0.5);

        // Get comet's ACTUAL position from GSAP transform in viewBox coordinates
        const matrix = this.comet.getCTM();
        const svgMatrix = this.svg.getScreenCTM().inverse();
        const point = this.svg.createSVGPoint();
        point.x = matrix.e;
        point.y = matrix.f;
        const transformed = point.matrixTransform(svgMatrix);
        const currentPoint = {
            x: transformed.x,
            y: transformed.y
        };

        // Sample points on path for tangent calculation
        const currentLength = this.pathLength * this.currentProgress;
        const sampleDistance = this.pathLength * 0.01;
        const aheadLength = Math.min(this.pathLength, currentLength + sampleDistance);
        const behindLength = Math.max(0, currentLength - sampleDistance);

        const aheadPoint = this.path.getPointAtLength(aheadLength);
        const behindPoint = this.path.getPointAtLength(behindLength);

        // Calculate tangent from behind to ahead (forward direction)
        const tangentX = aheadPoint.x - behindPoint.x;
        const tangentY = aheadPoint.y - behindPoint.y;
        const magnitude = Math.sqrt(tangentX * tangentX + tangentY * tangentY);

        let offsetX = 0;
        let offsetY = 0;

        if (magnitude > 0.01) {
            // Normalize tangent (forward direction)
            const normX = tangentX / magnitude;
            const normY = tangentY / magnitude;

            // Backward direction (opposite of forward)
            const backX = -normX;
            const backY = -normY;

            // Cone-shaped fire spread: particles further to sides are also further back
            const spreadDist = random(-CONFIG.trailSpread, CONFIG.trailSpread);
            const baseBackwardDist = random(0.5, 1.2);
            const spreadPenalty = Math.abs(spreadDist) * 0.8; // More spread = more backward
            const backwardDist = baseBackwardDist + spreadPenalty;

            // Perpendicular direction for cone spread
            const perpX = -normY;
            const perpY = normX;

            // Final offset (cone shape)
            offsetX = (backX * backwardDist) + (perpX * spreadDist);
            offsetY = (backY * backwardDist) + (perpY * spreadDist);
        }

        const particleX = currentPoint.x + offsetX;
        const particleY = currentPoint.y + offsetY;

        // Reuse pooled particle - just update its properties
        particle.setAttribute('r', size);
        particle.setAttribute('fill', this.color);
        particle.style.transform = `translate(${particleX}px, ${particleY}px)`;
        particle.style.opacity = '0.9';
        particle.style.display = 'block'; // Show particle from pool

        this.particles.push({
            element: particle,
            birthTime: Date.now(),
            initialSize: size
        });

        // Debug first particle spawn
        if (!isProduction && this.particles.length === 1) {
            console.log(`   ✨ First particle spawned for ${this.threadId} at progress ${(this.currentProgress * 100).toFixed(1)}%`);
        }

        // Limit trail length - return oldest to pool
        if (this.particles.length > CONFIG.trailLength) {
            const old = this.particles.shift();
            this.returnParticleToPool(old.element);
        }
    }

    updateParticles() {
        const now = Date.now();

        this.particles.forEach(particle => {
            const age = (now - particle.birthTime) / 1000; // seconds
            const lifeProgress = age / CONFIG.particleLifetime;

            if (lifeProgress >= 1) {
                // Return dead particle to pool
                this.returnParticleToPool(particle.element);
                return;
            }

            // Fade out faster and shrink more aggressively (fire-like)
            const opacity = 0.9 * (1 - lifeProgress * lifeProgress); // Quadratic fade
            const scale = 1 - (lifeProgress * 0.7); // Shrink to 30%

            // Use style for opacity (faster than setAttribute)
            particle.element.style.opacity = opacity;
            particle.element.setAttribute('r', particle.initialSize * scale);
        });

        // Clean up dead particles from array
        this.particles = this.particles.filter(p => {
            const age = (now - p.birthTime) / 1000;
            return age < CONFIG.particleLifetime;
        });
    }

    destroy() {
        if (this.particleGroup && this.particleGroup.parentNode) {
            this.particleGroup.parentNode.removeChild(this.particleGroup);
        }
        this.particles = [];
    }
}

// ==================== SVG BUILDER ====================

class SVGBuilder {
    constructor(container) {
        this.container = container;
        this.svg = this.createSVG();
        this.defs = this.createDefs();
        this.threads = [];
    }

    createSVG() {
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('width', '100%');
        svg.setAttribute('height', '100%');
        svg.setAttribute('viewBox', '0 0 100 100');
        svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');
        svg.style.cssText = 'position: absolute; top: 0; left: 0; width: 100%; height: 100%;';
        this.container.appendChild(svg);
        return svg;
    }

    createDefs() {
        const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');

        // Glow filter (for particles)
        const glowFilter = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
        glowFilter.setAttribute('id', 'glow');
        glowFilter.setAttribute('x', '-200%');
        glowFilter.setAttribute('y', '-200%');
        glowFilter.setAttribute('width', '500%');
        glowFilter.setAttribute('height', '500%');

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

        // Strong glow filter (for comets)
        const strongGlow = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
        strongGlow.setAttribute('id', 'strongGlow');
        strongGlow.setAttribute('x', '-300%');
        strongGlow.setAttribute('y', '-300%');
        strongGlow.setAttribute('width', '700%');
        strongGlow.setAttribute('height', '700%');
        strongGlow.setAttribute('filterUnits', 'objectBoundingBox');

        const feGaussianBlur2 = document.createElementNS('http://www.w3.org/2000/svg', 'feGaussianBlur');
        feGaussianBlur2.setAttribute('stdDeviation', '5');
        feGaussianBlur2.setAttribute('result', 'coloredBlur');

        const feMerge2 = document.createElementNS('http://www.w3.org/2000/svg', 'feMerge');
        const feMergeNode2_1 = document.createElementNS('http://www.w3.org/2000/svg', 'feMergeNode');
        feMergeNode2_1.setAttribute('in', 'coloredBlur');
        const feMergeNode2_2 = document.createElementNS('http://www.w3.org/2000/svg', 'feMergeNode');
        feMergeNode2_2.setAttribute('in', 'coloredBlur');
        const feMergeNode2_3 = document.createElementNS('http://www.w3.org/2000/svg', 'feMergeNode');
        feMergeNode2_3.setAttribute('in', 'SourceGraphic');

        feMerge2.appendChild(feMergeNode2_1);
        feMerge2.appendChild(feMergeNode2_2);
        feMerge2.appendChild(feMergeNode2_3);
        strongGlow.appendChild(feGaussianBlur2);
        strongGlow.appendChild(feMerge2);
        defs.appendChild(strongGlow);

        this.svg.appendChild(defs);
        return defs;
    }

    createGradient(gradientId) {
        const gradient = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
        gradient.setAttribute('id', gradientId);
        gradient.setAttribute('x1', '0%');
        gradient.setAttribute('y1', '0%');
        gradient.setAttribute('x2', '0%');
        gradient.setAttribute('y2', '100%');

        const stops = [
            { offset: '0%', color: CONFIG.colors[0] },
            { offset: '20%', color: CONFIG.colors[1] },
            { offset: '35%', color: CONFIG.colors[2] },
            { offset: '50%', color: CONFIG.colors[3] },
            { offset: '65%', color: CONFIG.colors[4] },
            { offset: '80%', color: CONFIG.colors[5] },
            { offset: '100%', color: CONFIG.colors[6] }
        ];

        stops.forEach(stop => {
            const stopEl = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
            stopEl.setAttribute('offset', stop.offset);
            stopEl.setAttribute('style', `stop-color:${stop.color};stop-opacity:1`);
            gradient.appendChild(stopEl);
        });

        this.defs.appendChild(gradient);
    }

    buildThread(threadConfig) {
        this.createGradient(threadConfig.gradientId);

        // Create path
        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        path.setAttribute('d', threadConfig.path);
        path.setAttribute('fill', 'none');
        path.setAttribute('stroke', `url(#${threadConfig.gradientId})`);
        path.setAttribute('stroke-width', threadConfig.strokeWidth.toString());
        path.setAttribute('stroke-linecap', 'round');
        path.setAttribute('opacity', threadConfig.opacity.toString());
        path.classList.add('story-thread');
        path.setAttribute('data-thread-id', threadConfig.id);

        this.svg.appendChild(path);

        // Create comet shape
        const comet = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        const cometPath = CometShape.createTeardrop(threadConfig.cometSize);

        comet.setAttribute('d', cometPath);
        comet.setAttribute('fill', 'currentColor');
        comet.setAttribute('filter', 'url(#strongGlow)'); // Use stronger glow for comets
        comet.classList.add('thread-comet');
        comet.style.color = CONFIG.colors[0];
        comet.style.opacity = '0';
        comet.setAttribute('data-comet-for', threadConfig.id);

        this.svg.appendChild(comet);

        this.threads.push({
            config: threadConfig,
            path,
            comet
        });

        return { path, comet };
    }
}

// ==================== ANIMATION CONTROLLER ====================

class AnimationController {
    constructor(threads, svg) {
        this.threads = threads;
        this.svg = svg;
        this.trailControllers = new Map();
    }

    animate() {
        this.threads.forEach((thread, index) => {
            this.animateThread(thread, index);
        });
    }

    animateThread(thread, index) {
        const { path, comet, config } = thread;
        const pathLength = path.getTotalLength();

        // Set initial state
        gsap.set(path, {
            strokeDasharray: pathLength,
            strokeDashoffset: pathLength
        });

        const delay = index * CONFIG.staggerDelay;

        // Animate path drawing
        gsap.to(path, {
            strokeDashoffset: 0,
            ease: 'none',
            scrollTrigger: {
                trigger: 'body',
                start: 'top top',
                end: 'bottom bottom',
                scrub: viewport.isMobile ? 1.5 : 1, // Higher scrub on mobile for smoother performance
                onUpdate: (self) => {
                    const adjustedProgress = Math.max(0, Math.min(1, self.progress - (delay / 10)));
                    const currentOffset = pathLength * (1 - adjustedProgress);
                    path.setAttribute('stroke-dashoffset', currentOffset);

                    const minOpacity = config.opacity * 0.5;
                    const maxOpacity = config.opacity;
                    const opacity = minOpacity + (adjustedProgress * (maxOpacity - minOpacity));
                    path.setAttribute('opacity', opacity);
                }
            }
        });

        // Animate comet
        this.animateComet(comet, path, index, config.id);
    }

    animateComet(comet, path, index, threadId) {
        const delay = index * CONFIG.staggerDelay;

        // Create trail controller with path reference
        const trailController = new TrailController(this.svg, comet, threadId, CONFIG.colors[0], path);
        this.trailControllers.set(threadId, trailController);

        gsap.to(comet, {
            motionPath: {
                path: path,
                align: path,
                autoRotate: true, // Rotate to follow path direction!
                alignOrigin: [0.5, 0.5]
            },
            ease: 'none',
            scrollTrigger: {
                trigger: 'body',
                start: 'top top',
                end: 'bottom bottom',
                scrub: viewport.isMobile ? 1.5 : 1, // Higher scrub on mobile for smoother performance
                onUpdate: (self) => {
                    const adjustedProgress = Math.max(0, Math.min(1, self.progress - (delay / 10)));

                    // Update color
                    const colorIndex = Math.min(
                        Math.floor(adjustedProgress * CONFIG.colors.length),
                        CONFIG.colors.length - 1
                    );
                    const currentColor = CONFIG.colors[colorIndex];
                    comet.style.color = currentColor;
                    comet.style.opacity = adjustedProgress > 0 ? '1' : '0';

                    // Update trail with progress and color
                    if (adjustedProgress > 0) {
                        trailController.update(adjustedProgress, currentColor);
                    }
                }
            }
        });
    }

    destroy() {
        this.trailControllers.forEach(controller => controller.destroy());
        this.trailControllers.clear();
    }
}

// ==================== MAIN INITIALIZATION ====================

export function initThreadSystem() {
    const isHomePage = window.location.pathname === '/' || window.location.pathname === '/home';
    if (!isHomePage) {
        if (!isProduction) console.log('🧵 Thread System: Skipping (not home page)');
        return;
    }

    if (document.querySelector('.story-thread-container')) {
        return;
    }

    if (!isProduction) console.log('🧵 Initializing Multi-Thread System with Comets');

    const threadContainer = document.createElement('div');
    threadContainer.className = 'story-thread-container';

    // Mobile: Full viewport width (no max-width constraint)
    // Desktop: Centered 1400px container
    const containerStyles = viewport.isMobile
        ? `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            pointer-events: none;
            z-index: 1;
            overflow: visible;
        `
        : `
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 1400px;
            height: 100vh;
            pointer-events: none;
            z-index: 1;
            overflow: visible;
        `;

    threadContainer.style.cssText = containerStyles;

    const threadConfigs = ThreadGenerator.generateThreads();
    const svgBuilder = new SVGBuilder(threadContainer);
    const threads = threadConfigs.map(config => {
        const { path, comet } = svgBuilder.buildThread(config);
        return { config, path, comet };
    });

    document.body.appendChild(threadContainer);

    const animator = new AnimationController(threads, svgBuilder.svg);
    animator.animate();

    // Refresh ScrollTriggers on resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            ScrollTrigger.getAll().forEach(trigger => {
                if (trigger.vars.trigger === 'body') {
                    trigger.refresh();
                }
            });
        }, 250);
    });

    if (!isProduction) {
        console.log(`✅ Multi-Thread System with Comets initialized`);
        console.log(`   - ${threads.length} threads with particle trails`);
    }
}

// Auto-initialize
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initThreadSystem);
} else {
    initThreadSystem();
}
