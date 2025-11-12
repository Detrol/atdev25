/**
 * reCAPTCHA Lazy Loader
 *
 * Loads Google reCAPTCHA v3 only when user scrolls near a form.
 * This saves ~690KB and 575ms execution time during initial page load.
 *
 * Strategy:
 * 1. Monitor scroll position
 * 2. Load reCAPTCHA when user is within 500px of contact form or price calculator
 * 3. Cache loaded state to avoid duplicate loading
 */

const isProduction = window.location.hostname === 'atdev.me';

class RecaptchaLoader {
    constructor() {
        this.loaded = false;
        this.loading = false;
        this.formSelectors = ['#contact', '#price-calculator', '#audit-form'];
        this.scrollThreshold = 500; // pixels before form
        this.observer = null;
    }

    /**
     * Initialize intersection observer for form sections
     */
    init() {
        // Check if reCAPTCHA is enabled
        if (!window.recaptchaConfig || !window.recaptchaConfig.enabled) {
            if (!isProduction) {
                console.log('🔒 reCAPTCHA disabled (dev mode)');
            }
            return;
        }

        // Check if forms exist on page
        const forms = this.formSelectors
            .map(selector => document.querySelector(selector))
            .filter(Boolean);

        if (forms.length === 0) {
            if (!isProduction) {
                console.log('🔒 reCAPTCHA loader: No forms found on page');
            }
            return;
        }

        if (!isProduction) {
            console.log(`🔒 reCAPTCHA Lazy Loader initialized for ${forms.length} form(s)`);
        }

        // Use Intersection Observer for better performance than scroll listener
        this.observer = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !this.loaded && !this.loading) {
                        if (!isProduction) {
                            console.log('📜 User approaching form - loading reCAPTCHA...');
                        }
                        this.loadRecaptcha();
                    }
                });
            },
            {
                root: null,
                rootMargin: `${this.scrollThreshold}px`, // Trigger 500px before form
                threshold: 0
            }
        );

        // Observe all forms
        forms.forEach(form => {
            this.observer.observe(form);
        });
    }

    /**
     * Dynamically load reCAPTCHA script
     */
    async loadRecaptcha() {
        if (this.loaded || this.loading) return;

        this.loading = true;

        try {
            // Create script element
            const script = document.createElement('script');
            script.src = `https://www.google.com/recaptcha/api.js?render=${window.recaptchaConfig.siteKey}`;
            script.async = true;
            script.defer = true;

            // Wait for script to load
            await new Promise((resolve, reject) => {
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });

            this.loaded = true;
            this.loading = false;

            if (!isProduction) {
                console.log('✅ reCAPTCHA loaded successfully');
            }

            // Disconnect observer after loading
            if (this.observer) {
                this.observer.disconnect();
            }

        } catch (error) {
            console.error('❌ Failed to load reCAPTCHA:', error);
            this.loading = false;
        }
    }

    /**
     * Manual trigger (for programmatic form display)
     */
    trigger() {
        if (!this.loaded && !this.loading) {
            this.loadRecaptcha();
        }
    }
}

// Initialize and expose globally
const recaptchaLoader = new RecaptchaLoader();

// Auto-initialize
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => recaptchaLoader.init());
} else {
    recaptchaLoader.init();
}

// Expose for manual triggering if needed
window.recaptchaLoader = recaptchaLoader;

if (!isProduction) {
    console.log('🔒 reCAPTCHA Lazy Loader module loaded');
}
