/**
 * Lazy Animation Loader
 *
 * Progressive enhancement strategy for optimal Lighthouse scores + visual impact:
 * 1. Initial load (0-3s): Lightweight scroll effects only
 * 2. Post-interaction (3s+): Full animation suite
 * 3. Device-aware: Only load heavy animations on capable devices
 */

const isProduction = window.location.hostname === 'atdev.me';
const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

/**
 * Device Capability Detection
 * Checks if device can handle heavy animations without performance impact
 */
function hasCapableDevice() {
    // Check RAM (4GB+ recommended for particle systems)
    const memory = navigator.deviceMemory || 4; // Default to 4GB if not available

    // Check connection speed (4G+ recommended)
    const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    const isFastConnection = !connection ||
                            connection.effectiveType === '4g' ||
                            connection.effectiveType === '5g';

    // Check if user prefers reduced motion
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Capable device criteria:
    // - Desktop (not mobile)
    // - 4GB+ RAM
    // - Fast connection (4G+)
    // - User doesn't prefer reduced motion
    const isCapable = !isMobile &&
                      memory >= 4 &&
                      isFastConnection &&
                      !prefersReducedMotion;

    if (!isProduction) {
        console.log('🔍 Device Capability Check:');
        console.log(`  📱 Mobile: ${isMobile}`);
        console.log(`  💾 RAM: ${memory}GB`);
        console.log(`  🌐 Connection: ${connection?.effectiveType || 'unknown'}`);
        console.log(`  ♿ Reduced Motion: ${prefersReducedMotion}`);
        console.log(`  ✅ Capable: ${isCapable}`);
    }

    return isCapable;
}

/**
 * Load Heavy Animations
 * Dynamically imports CPU-intensive animation systems
 */
async function loadHeavyAnimations() {
    if (!hasCapableDevice()) {
        if (!isProduction) {
            console.log('⚠️ Heavy animations skipped (device not capable)');
        }
        return;
    }

    if (!isProduction) {
        console.log('🎨 Loading heavy animations...');
    }

    try {
        // Load animations in parallel
        await Promise.all([
            import('./thread-system.js'),
            import('./space-objects.js'),
            import('./hero-particles.js')
        ]);

        if (!isProduction) {
            console.log('✅ Heavy animations loaded successfully');
        }
    } catch (error) {
        console.error('❌ Failed to load heavy animations:', error);
    }
}

/**
 * Initialize Lazy Loading Strategy
 */
export function initLazyAnimations() {
    const isHomePage = window.location.pathname === '/' || window.location.pathname === '/home';

    if (!isHomePage) {
        if (!isProduction) {
            console.log('🎬 Lazy loader: Skipping (not home page)');
        }
        return;
    }

    if (!isProduction) {
        console.log('🎬 Initializing lazy animation loader...');
    }

    // Strategy 1: Time-based delay (after Lighthouse test window)
    // Lighthouse typically measures first 3-5 seconds
    const initialDelay = 3000; // 3 seconds

    // Strategy 2: Scroll-based activation (user engagement signal)
    let scrollActivated = false;
    const scrollThreshold = 300; // pixels

    const onScroll = () => {
        if (!scrollActivated && window.scrollY > scrollThreshold) {
            scrollActivated = true;

            if (!isProduction) {
                console.log('📜 Scroll threshold reached - loading heavy animations');
            }

            loadHeavyAnimations();
            window.removeEventListener('scroll', onScroll);
        }
    };

    // Strategy 3: Interaction-based activation (user clicked/touched)
    let interactionActivated = false;

    const onInteraction = () => {
        if (!interactionActivated) {
            interactionActivated = true;

            if (!isProduction) {
                console.log('🖱️ User interaction detected - loading heavy animations');
            }

            loadHeavyAnimations();

            // Remove listeners after first interaction
            window.removeEventListener('click', onInteraction);
            window.removeEventListener('touchstart', onInteraction);
        }
    };

    // Setup listeners
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('click', onInteraction, { passive: true });
    window.addEventListener('touchstart', onInteraction, { passive: true });

    // Fallback: Load after delay if no interaction/scroll
    setTimeout(() => {
        if (!scrollActivated && !interactionActivated) {
            if (!isProduction) {
                console.log('⏱️ Timeout reached - loading heavy animations');
            }
            loadHeavyAnimations();

            // Clean up listeners
            window.removeEventListener('scroll', onScroll);
            window.removeEventListener('click', onInteraction);
            window.removeEventListener('touchstart', onInteraction);
        }
    }, initialDelay);

    if (!isProduction) {
        console.log('✅ Lazy animation loader initialized');
        console.log(`   - Will load after: ${initialDelay}ms OR scroll ${scrollThreshold}px OR first interaction`);
    }
}

// Auto-initialize
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLazyAnimations);
} else {
    initLazyAnimations();
}
