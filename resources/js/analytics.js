/**
 * Google Analytics 4 Event Tracking Helper
 *
 * Skickar endast events om användaren godkänt analytics cookies
 * och gtag är laddat.
 */

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
            console.debug('GA4 not available, skipping event:', eventName);
            return;
        }

        window.gtag('event', eventName, params);
        console.log('GA4 event →', eventName, params);
    },

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
    trackContactFormSubmit(formType = 'contact') {
        this.event('generate_lead', {
            form_type: formType,
            value: 1.0,
            currency: 'SEK'
        });
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

    /**
     * Track scroll depth (25%, 50%, 75%, 100%)
     */
    trackScrollDepth(percentage) {
        this.event('scroll', {
            percent_scrolled: percentage
        });
    }
};

// Export som global
window.GA4 = GA4;
