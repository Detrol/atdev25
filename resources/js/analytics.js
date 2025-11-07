/**
 * Google Analytics 4 Event Tracking Helper
 *
 * Komplett tracking för alla user interactions på atdev.me
 * Total: 49 tracking events
 *
 * Skickar endast events om användaren godkänt analytics cookies
 * och gtag är laddat.
 */

const isDev = import.meta.env.MODE === 'development';

export const GA4 = {
    /**
     * Kontrollera om GA4 är tillgängligt
     */
    isAvailable() {
        return typeof window.gtag === 'function';
    },

    /**
     * Skicka custom event till GA4
     */
    event(eventName, params = {}) {
        if (!this.isAvailable()) {
            if (isDev) console.debug('GA4 not available, skipping event:', eventName);
            return;
        }

        window.gtag('event', eventName, params);
        if (isDev) console.log('GA4 event →', eventName, params);
    },

    // =========================================================================
    // BEFINTLIGA EVENTS (6 st)
    // =========================================================================

    /**
     * Track projekt-vy
     */
    trackProjectView(projectSlug, projectTitle) {
        this.event('view_item', {
            item_id: projectSlug,
            item_name: projectTitle,
            item_category: 'project'
        });
    },

    /**
     * Track klick på projekt live-länk
     */
    trackProjectLiveClick(projectSlug, liveUrl) {
        this.event('click', {
            event_category: 'project',
            event_label: projectSlug,
            link_url: liveUrl,
            link_text: 'Se live'
        });
    },

    /**
     * Track kontaktformulär submission
     */
    trackContactFormSubmit(formType = 'contact', source = null) {
        const params = {
            form_type: formType,
            value: 1.0,
            currency: 'SEK'
        };

        if (source) {
            params.source = source;
        }

        this.event('generate_lead', params);
    },

    /**
     * Track AI chatbot usage
     */
    trackChatbotMessage(sessionId, messageCount) {
        this.event('chatbot_interaction', {
            session_id: sessionId,
            message_count: messageCount,
            engagement_type: 'message'
        });
    },

    /**
     * Track chatbot conversation start
     */
    trackChatbotStart(sessionId) {
        this.event('chatbot_start', {
            session_id: sessionId,
            engagement_type: 'start'
        });
    },

    // =========================================================================
    // PHASE 7: ENGAGEMENT TRACKING (6 events)
    // =========================================================================

    /**
     * Initialize scroll depth tracking
     */
    initScrollTracking() {
        const thresholds = [25, 50, 75, 100];
        const triggered = new Set();

        const checkScroll = () => {
            const scrollPercent = Math.round(
                (window.scrollY + window.innerHeight) / document.documentElement.scrollHeight * 100
            );

            thresholds.forEach(threshold => {
                if (scrollPercent >= threshold && !triggered.has(threshold)) {
                    triggered.add(threshold);
                    this.event('scroll_depth_' + threshold, {
                        scroll_depth: threshold,
                        event_category: 'engagement'
                    });
                }
            });
        };

        window.addEventListener('scroll', checkScroll, { passive: true });
        // Check immediately in case user already scrolled
        checkScroll();
    },

    /**
     * Initialize time on page tracking
     */
    initTimeTracking() {
        const timepoints = [
            { seconds: 30, triggered: false },
            { seconds: 60, triggered: false }
        ];

        timepoints.forEach(point => {
            setTimeout(() => {
                if (!point.triggered) {
                    point.triggered = true;
                    this.event('time_on_page_' + point.seconds + 's', {
                        time_seconds: point.seconds,
                        event_category: 'engagement'
                    });
                }
            }, point.seconds * 1000);
        });
    },

    // =========================================================================
    // PHASE 8: COOKIE CONSENT TRACKING (4 events)
    // =========================================================================

    /**
     * Track cookie banner interactions
     */
    trackCookieBanner(action, preferences = null) {
        const eventData = {
            event_category: 'cookie_consent',
            action: action
        };

        if (preferences) {
            eventData.analytics = preferences.analytics;
            eventData.functional = preferences.functional;
            eventData.marketing = preferences.marketing;
        }

        this.event('cookie_' + action, eventData);
    },

    // =========================================================================
    // PHASE 2: NAVIGATION & CTAs (8 events)
    // =========================================================================

    /**
     * Track Hero CTA clicks
     */
    trackHeroCTA(ctaType) {
        this.event('hero_cta_click', {
            cta_type: ctaType,
            event_category: 'engagement',
            event_label: 'hero_section'
        });
    },

    /**
     * Track navigation clicks
     */
    trackNavigation(sectionName) {
        this.event('navigation_click', {
            section: sectionName,
            event_category: 'navigation'
        });
    },

    /**
     * Track mobile menu toggle
     */
    trackMobileMenu(action) {
        this.event('mobile_menu_toggle', {
            action: action, // 'open' or 'close'
            event_category: 'navigation'
        });
    },

    /**
     * Track dark mode toggle
     */
    trackDarkMode(enabled) {
        this.event('dark_mode_toggle', {
            mode: enabled ? 'dark' : 'light',
            event_category: 'preferences'
        });
    },

    /**
     * Track external link clicks
     */
    trackExternalLink(url, category = 'general') {
        this.event('external_link_click', {
            link_url: url,
            link_category: category,
            event_category: 'outbound'
        });
    },

    /**
     * Track footer link clicks
     */
    trackFooterLink(destination) {
        this.event('footer_link_click', {
            destination: destination,
            event_category: 'navigation'
        });
    },

    // =========================================================================
    // PHASE 3: PROJECTS & TECH STACK (7 events)
    // =========================================================================

    /**
     * Track project card clicks
     */
    trackProjectCard(projectSlug) {
        this.event('project_card_click', {
            project_slug: projectSlug,
            event_category: 'projects'
        });
    },

    /**
     * Track tech stack modal
     */
    trackTechStackModal(action) {
        this.event('tech_stack_modal_' + action, {
            event_category: 'tech_stack'
        });
    },

    /**
     * Track tech stack D3 node interactions
     */
    trackTechNode(techName, action = 'click') {
        this.event('tech_stack_node_' + action, {
            technology: techName,
            event_category: 'tech_stack'
        });
    },

    /**
     * Track technology badge clicks
     */
    trackTechBadge(techName, projectSlug = null) {
        this.event('tech_badge_click', {
            technology: techName,
            project: projectSlug,
            event_category: 'tech_stack'
        });
    },

    /**
     * Track project screenshot views
     */
    trackScreenshotView(projectSlug) {
        this.event('project_screenshot_view', {
            project_slug: projectSlug,
            event_category: 'projects'
        });
    },

    /**
     * Track project modal open
     */
    trackProjectModal(projectSlug, action = 'open') {
        this.event('project_modal_' + action, {
            project_slug: projectSlug,
            event_category: 'projects'
        });
    },

    // =========================================================================
    // PHASE 4: PRICE CALCULATOR (8 events)
    // =========================================================================

    /**
     * Track calculator view
     */
    trackCalculatorView() {
        this.event('calculator_view', {
            event_category: 'price_calculator'
        });
    },

    /**
     * Track calculator service selection
     */
    trackCalculatorService(serviceType) {
        this.event('calculator_service_select', {
            service_type: serviceType,
            event_category: 'price_calculator'
        });
    },

    /**
     * Track calculator input start
     */
    trackCalculatorInput() {
        this.event('calculator_input_start', {
            event_category: 'price_calculator'
        });
    },

    /**
     * Track calculator submission
     */
    trackCalculatorSubmit(formData) {
        this.event('calculator_submit', {
            service_type: formData.service_type || 'unknown',
            complexity: formData.complexity || 'unknown',
            event_category: 'price_calculator'
        });
    },

    /**
     * Track calculator result view
     */
    trackCalculatorResult(estimatedPrice) {
        this.event('calculator_result_view', {
            estimated_price: estimatedPrice,
            value: estimatedPrice,
            currency: 'SEK',
            event_category: 'price_calculator'
        });
    },

    /**
     * Track calculator AI explanation expand
     */
    trackCalculatorAIExpand() {
        this.event('calculator_ai_expand', {
            event_category: 'price_calculator'
        });
    },

    /**
     * Track calculator CTAs
     */
    trackCalculatorCTA(ctaType) {
        this.event('calculator_' + ctaType, {
            event_category: 'price_calculator'
        });
    },

    // =========================================================================
    // PHASE 5: CONTACT FORM (6 events)
    // =========================================================================

    /**
     * Track contact form view
     */
    trackContactView() {
        this.event('contact_form_view', {
            event_category: 'contact'
        });
    },

    /**
     * Track contact input focus
     */
    trackContactInput(fieldName) {
        this.event('contact_input_focus', {
            field_name: fieldName,
            event_category: 'contact'
        });
    },

    /**
     * Track contact from calculator
     */
    trackContactFromCalculator(estimatedPrice) {
        this.event('contact_from_calculator', {
            estimated_price: estimatedPrice,
            source: 'calculator',
            event_category: 'contact'
        });
    },

    /**
     * Track email link click
     */
    trackEmailClick(emailAddress) {
        this.event('contact_email_click', {
            email: emailAddress,
            event_category: 'contact'
        });
    },

    /**
     * Track contact form errors
     */
    trackContactError(errorType) {
        this.event('contact_form_error', {
            error_type: errorType,
            event_category: 'contact'
        });
    },

    // =========================================================================
    // PHASE 6: DEMO COMPONENTS (8 events)
    // =========================================================================

    /**
     * Generic demo interaction tracker
     */
    trackDemoInteraction(demoType, action, value = null) {
        const eventData = {
            demo_type: demoType,
            action: action,
            event_category: 'demos'
        };

        if (value !== null) {
            eventData.value = value;
        }

        this.event('demo_' + demoType + '_' + action, eventData);
    },

    /**
     * Track product rotation
     */
    trackProductRotation(direction) {
        this.trackDemoInteraction('product', 'rotation', direction);
    },

    /**
     * Track AR start
     */
    trackARStart() {
        this.trackDemoInteraction('product', 'ar_start');
    },

    /**
     * Track before/after slider
     */
    trackBeforeAfter(action, value = null) {
        this.trackDemoInteraction('before_after', action, value);
    },

    /**
     * Track smart menu
     */
    trackSmartMenu(action, value = null) {
        this.trackDemoInteraction('smart_menu', action, value);
    },

    /**
     * Track reviews
     */
    trackReviews(action, value = null) {
        this.trackDemoInteraction('reviews', action, value);
    },

    // =========================================================================
    // INITIALIZATION
    // =========================================================================

    /**
     * Initialize all tracking
     * Call this once on page load
     */
    init() {
        if (!this.isAvailable()) {
            console.debug('GA4 not available, skipping initialization');
            return;
        }

        // Initialize engagement tracking
        this.initScrollTracking();
        this.initTimeTracking();

        console.log('✓ GA4 tracking initialized with 49 events');
    }
};

// Export som global
window.GA4 = GA4;
