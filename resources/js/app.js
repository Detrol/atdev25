// Import Alpine.js and plugins
import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import persist from '@alpinejs/persist';
import collapse from '@alpinejs/collapse';

// Import D3.js for Tech Stack Visualizer
import * as d3 from 'd3';
window.d3 = d3;

// Import demo components before Alpine starts
import './demos/product-viewer.js';
import './demos/before-after-slider.js';
import './demos/smart-menu.js';
import './demos/google-reviews.js';

// Import modal scripts
import { initTechStackModal } from './tech-stack-modal.js';
import { initProjectModal } from './project-modal.js';

// Import Google Analytics 4 helper
import { GA4 } from './analytics.js';
window.GA4 = GA4;

// Import darkmode store
import darkModeStore from './darkmode-store.js';

// Import GSAP animations
import './animations/gsap-config.js';
import './animations/hero.js';
import './animations/timeline.js';
import './animations/stats.js';
// import './animations/projects.js'; // DISABLED - conflicts with section-transitions.js
import './animations/how-i-work.js';
import './animations/parallax.js';
import './animations/about.js';

// Import new animation systems
import './animations/thread-system.js';
import './animations/section-transitions.js';
import './animations/cursor-effects.js';
import './animations/service-cards.js';
// import './animations/projects-gallery.js'; // DISABLED - conflicts with section-transitions.js
import './animations/hero-particles.js';
import './animations/separator-animations.js';

// Register Alpine.js plugins
Alpine.plugin(intersect);
Alpine.plugin(persist);
Alpine.plugin(collapse);

// Register darkmode store
Alpine.store('darkMode', darkModeStore());

// Register Cookie Consent component
Alpine.data('cookieConsent', () => ({
    showBanner: false,
    showDetails: false,
    preferences: {
        essential: true,
        functional: false,
        analytics: true, // Berättigat Intresse (GDPR Art. 6.1.f)
        marketing: false
    },

    init() {
        this.checkConsentStatus();
        window.addEventListener('open-cookie-banner', () => {
            this.showBanner = true;
        });

        // Track cookie banner view
        if (window.GA4) {
            GA4.trackCookieBanner('banner_view');
        }
    },

    async checkConsentStatus() {
        try {
            const response = await fetch('/api/consent');
            const data = await response.json();

            if (!data.has_choice_made) {
                this.showBanner = true;
            } else {
                this.preferences = data.preferences;
                this.applyConsent();
            }
        } catch (error) {
            console.error('Failed to check consent status:', error);
            this.showBanner = true;
        }
    },

    async saveChoices() {
        try {
            const response = await fetch('/api/consent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(this.preferences)
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccessMessage('Cookie-inställningar sparade!');
                this.showBanner = false;
                this.applyConsent();

                // Track custom cookie choices
                if (window.GA4) {
                    GA4.trackCookieBanner('customize', this.preferences);
                }
            }
        } catch (error) {
            console.error('Failed to save consent:', error);
            this.showErrorMessage('Kunde inte spara inställningar');
        }
    },

    async acceptAll() {
        try {
            const response = await fetch('/api/consent/accept-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            const data = await response.json();

            if (data.success) {
                this.preferences = data.preferences;
                this.showSuccessMessage('Alla cookies accepterade!');
                this.showBanner = false;
                this.applyConsent();

                // Track accept all
                if (window.GA4) {
                    GA4.trackCookieBanner('accept_all', data.preferences);
                }
            }
        } catch (error) {
            console.error('Failed to accept all:', error);
            this.showErrorMessage('Kunde inte acceptera cookies');
        }
    },

    async rejectAll() {
        try {
            const response = await fetch('/api/consent/reject-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            const data = await response.json();

            if (data.success) {
                this.preferences = data.preferences;
                this.showSuccessMessage('Endast nödvändiga cookies aktiverade');
                this.showBanner = false;
                this.applyConsent();

                // Track reject all
                if (window.GA4) {
                    GA4.trackCookieBanner('reject_all', data.preferences);
                }
            }
        } catch (error) {
            console.error('Failed to reject cookies:', error);
            this.showErrorMessage('Kunde inte avvisa cookies');
        }
    },

    closeBanner() {
        if (!this.hasChoiceMade()) {
            this.rejectAll();
        } else {
            this.showBanner = false;
        }
    },

    async hasChoiceMade() {
        try {
            const response = await fetch('/api/consent');
            const data = await response.json();
            return data.has_choice_made;
        } catch {
            return false;
        }
    },

    applyConsent() {
        if (!this.preferences.functional) {
            localStorage.removeItem('darkMode');
            localStorage.removeItem('chat_session_id');
        }

        if (this.preferences.analytics) {
            this.loadAnalytics();
        }

        if (this.preferences.marketing) {
            this.loadMarketingScripts();
        }

        window.dispatchEvent(new CustomEvent('consent-updated', {
            detail: { preferences: this.preferences }
        }));
    },

    loadAnalytics() {
        // Analytics loaded
    },

    loadMarketingScripts() {
        // Marketing scripts loaded
    },

    showSuccessMessage(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg';
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    },

    showErrorMessage(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg';
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
}));

// Register Chat Widget component
Alpine.data('chatWidget', () => ({
    isOpen: false,
    sessionId: null,
    messages: [],
    inputMessage: '',
    isLoading: false,
    error: null,
    hasLoadedHistory: false,

    async init() {
        this.sessionId = await this.getOrCreateSessionId();
    },

    async getOrCreateSessionId() {
        const hasFunctionalConsent = await this.checkFunctionalConsent();
        let sessionId = null;

        if (hasFunctionalConsent) {
            sessionId = localStorage.getItem('chat_session_id');
            if (!sessionId) {
                sessionId = this.generateUUID();
                localStorage.setItem('chat_session_id', sessionId);
            }
        } else {
            if (!window._tempChatSessionId) {
                window._tempChatSessionId = this.generateUUID();
            }
            sessionId = window._tempChatSessionId;
        }

        return sessionId;
    },

    async checkFunctionalConsent() {
        try {
            const response = await fetch('/api/consent/check/functional');
            const data = await response.json();
            return data.has_consent;
        } catch {
            return false;
        }
    },

    generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    },

    toggleChat() {
        this.isOpen = !this.isOpen;

        if (this.isOpen && !this.hasLoadedHistory) {
            this.loadHistory();
        }

        if (this.isOpen) {
            this.$nextTick(() => {
                this.$refs.messageInput?.focus();
            });
        }
    },

    async loadHistory() {
        try {
            const response = await fetch(`/api/chat/history?session_id=${this.sessionId}`);
            const data = await response.json();

            if (data.success && data.history.length > 0) {
                this.messages = data.history.map(chat => ([
                    { role: 'user', content: chat.question },
                    { role: 'assistant', content: chat.answer }
                ])).flat();

                this.hasLoadedHistory = true;
                this.scrollToBottom();
            }
        } catch (error) {
            console.error('Failed to load chat history:', error);
        }
    },

    async sendMessage() {
        if (!this.inputMessage.trim() || this.isLoading) {
            return;
        }

        const userMessage = this.inputMessage.trim();
        this.inputMessage = '';
        this.error = null;

        this.messages.push({
            role: 'user',
            content: userMessage
        });

        // Track chatbot conversation start (first message)
        if (this.messages.length === 1 && window.GA4) {
            GA4.trackChatbotStart(this.sessionId);
        }

        this.scrollToBottom();
        this.isLoading = true;

        try {
            const response = await fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    message: userMessage,
                    session_id: this.sessionId
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Ett fel uppstod');
            }

            this.messages.push({
                role: 'assistant',
                content: data.response
            });

            // Track chatbot interaction
            if (window.GA4) {
                const messageCount = Math.ceil(this.messages.length / 2);
                GA4.trackChatbotMessage(this.sessionId, messageCount);
            }

            this.scrollToBottom();

        } catch (error) {
            console.error('Chat error:', error);
            this.error = error.message || 'Kunde inte skicka meddelandet. Försök igen.';
            this.messages.pop();
        } finally {
            this.isLoading = false;
        }
    },

    handleKeydown(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            this.sendMessage();
        }
    },

    scrollToBottom() {
        this.$nextTick(() => {
            const messagesContainer = this.$refs.messagesContainer;
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });
    },

    clearError() {
        this.error = null;
    },

    selectSuggestedQuestion(question) {
        this.inputMessage = question;
        this.$nextTick(() => {
            this.$refs.messageInput?.focus();
            this.sendMessage();
        });
    },

    getFormattedTime(timestamp) {
        if (!timestamp) return '';
        const date = new Date(timestamp);
        return date.toLocaleTimeString('sv-SE', { hour: '2-digit', minute: '2-digit' });
    }
}));

// Helper function for checking consent
window.hasConsent = async function(category) {
    try {
        const response = await fetch(`/api/consent/check/${category}`);
        const data = await response.json();
        return data.has_consent;
    } catch {
        return category === 'essential';
    }
};

/**
 * LazyLoadManager - Modern Intersection Observer-based lazy loading
 * Replaces Alpine.js x-intersect for better performance and mobile support
 */
class LazyLoadManager {
    constructor() {
        this.observers = new Map();
        this.prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.isMobile = window.innerWidth < 768;
        this.isTouch = 'ontouchstart' in window;

        this.init();
    }

    init() {
        // Create observers for different animation types
        this.createObserver('fade-in', this.handleFadeIn.bind(this));
        this.createObserver('slide-up', this.handleSlideUp.bind(this));
        // NOTE: 'counter' observer removed - GSAP stats.js handles counters
        this.createObserver('skeleton', this.handleSkeleton.bind(this));

        // Observe all elements with data-lazy attribute
        this.observeElements();
    }

    createObserver(type, callback) {
        const options = {
            root: null,
            rootMargin: this.isMobile ? '50px' : '100px',
            threshold: this.isMobile ? 0.1 : 0.2
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    callback(entry.target, entry);
                    observer.unobserve(entry.target);
                }
            });
        }, options);

        this.observers.set(type, observer);
    }

    observeElements() {
        document.querySelectorAll('[data-lazy]').forEach(el => {
            const type = el.dataset.lazy;
            const observer = this.observers.get(type);

            if (observer) {
                // Add initial state class
                el.classList.add('lazy-hidden');
                observer.observe(el);
            }
        });
    }

    handleFadeIn(element) {
        const duration = this.prefersReducedMotion ? 0 : (this.isMobile ? 300 : 600);
        const delay = parseInt(element.dataset.delay || 0);

        setTimeout(() => {
            element.classList.remove('lazy-hidden');
            element.classList.add('lazy-visible', 'animate-fade-in');
        }, delay);
    }

    handleSlideUp(element) {
        const duration = this.prefersReducedMotion ? 0 : (this.isMobile ? 300 : 600);
        const delay = parseInt(element.dataset.delay || 0);

        setTimeout(() => {
            element.classList.remove('lazy-hidden');
            element.classList.add('lazy-visible', 'animate-slide-up');
        }, delay);
    }

    // handleCounter() REMOVED - GSAP stats.js handles all counter animations
    // with better performance and smoother easing

    handleSkeleton(element) {
        const duration = this.prefersReducedMotion ? 0 : 300;

        // Find skeleton placeholder
        const skeleton = element.querySelector('.skeleton-placeholder');
        const content = element.querySelector('.skeleton-content');

        if (skeleton && content) {
            setTimeout(() => {
                skeleton.style.opacity = '0';
                setTimeout(() => {
                    skeleton.style.display = 'none';
                    content.style.display = 'block';
                    setTimeout(() => {
                        content.style.opacity = '1';
                    }, 10);
                }, duration);
            }, 100);
        }
    }

    // Public method to manually trigger observation (for dynamic content)
    observe(element, type) {
        const observer = this.observers.get(type);
        if (observer) {
            element.dataset.lazy = type;
            element.classList.add('lazy-hidden');
            observer.observe(element);
        }
    }

    // Cleanup method
    destroy() {
        this.observers.forEach(observer => observer.disconnect());
        this.observers.clear();
    }
}

// Register Price Calculator component
Alpine.data('priceCalculator', () => ({
    description: '',
    serviceCategory: '',
    websiteUrl: '',
    loading: false,
    result: null,
    error: null,
    estimationId: null,
    placeholder: 'Välj en tjänstekategori ovan för att få relevanta exempel...',

    placeholders: {
        'web_development': 'T.ex: Jag behöver en webbshop där kunder kan köpa produkter, lägga i kundvagn, betala via Stripe, och jag vill ha en admin-panel för att hantera produkter, lager och ordrar. Viktigt med mobilanpassning och SEO.',
        'mobile_app': 'T.ex: Jag vill ha en iOS och Android-app för [beskrivning]. Appen ska ha [funktioner], integration med [API/system], push-notifikationer, och offline-funktionalitet.',
        'bug_fixes': 'T.ex: Min webbplats/app har ett problem med [specifik funktion]. Felet uppstår när [scenario]. Jag behöver snabb åtgärd och rotorsaksanalys.',
        'performance': 'T.ex: Min webbplats laddar långsamt (nuvarande laddningstid: X sekunder). Jag vill optimera Core Web Vitals, databasfrågor, och implementera caching för bättre prestanda.',
        'api_integration': 'T.ex: Jag behöver integration med [Stripe/Klarna/Mailgun/etc]. API:et ska hantera [funktionalitet] och jag vill ha dokumentation och säker autentisering.',
        'security': 'T.ex: Jag behöver säkerhetsanalys av min webbplats/app. Viktigast är [GDPR/penetrationstestning/SSL/2FA]. Vi hanterar [typ av data] och behöver [compliance-krav].',
        'maintenance': 'T.ex: Jag behöver kontinuerligt underhåll av [webbplats/app]. Det inkluderar säkerhetsuppdateringar, övervakning, backups, och [X timmar/månad] support för ändringar.',
        'modernization': 'T.ex: Min webbplats är byggd med [gammal teknologi] och behöver uppgraderas till [ny teknologi]. Viktigt att behålla [data/funktionalitet] och minimera driftavbrott.'
    },

    updatePlaceholder() {
        if (this.serviceCategory && this.placeholders[this.serviceCategory]) {
            this.placeholder = this.placeholders[this.serviceCategory];
        } else {
            this.placeholder = 'Välj en tjänstekategori ovan för att få relevanta exempel...';
        }

        // Clear website URL if switching to category that doesn't need it
        if (!this.shouldShowWebsiteField()) {
            this.websiteUrl = '';
        }
    },

    shouldShowWebsiteField() {
        // Show website field for categories that typically work with existing websites
        const categoriesNeedingWebsite = [
            'modernization',    // Modernizing existing site
            'maintenance',      // Maintaining existing site
            'performance',      // Optimizing existing site
            'bug_fixes',        // Fixing existing site
            'security',         // Auditing existing site
            'web_development'   // Can be rebuild/redesign
        ];

        return categoriesNeedingWebsite.includes(this.serviceCategory);
    },

    websiteFieldLabel() {
        const labels = {
            'modernization': 'Befintlig webbplats att modernisera',
            'maintenance': 'Webbplats att underhålla',
            'performance': 'Webbplats att optimera',
            'bug_fixes': 'Webbplats att fixa',
            'security': 'Webbplats att analysera',
            'web_development': 'Har du en befintlig webbplats?'
        };

        return labels[this.serviceCategory] || 'Befintlig webbplats';
    },

    websiteFieldDescription() {
        const descriptions = {
            'modernization': 'Ange URL:en så analyserar AI:n nuvarande teknologier och ger konkreta moderniseringsförslag',
            'maintenance': 'Ange URL:en så kan AI:n analysera webbplatsen och ge bättre underhållsuppskattning',
            'performance': 'Ange URL:en så kan AI:n identifiera prestandaflaskhalsar och optimeringsmöjligheter',
            'bug_fixes': 'Ange URL:en där felet uppstår för bättre felsökningsanalys',
            'security': 'Ange URL:en så kan AI:n göra en preliminär säkerhetsanalys',
            'web_development': 'Om du vill bygga om/designa en befintlig webbplats, klistra in URL:en här'
        };

        return descriptions[this.serviceCategory] || 'Klistra in URL:en så analyserar AI:n din webbplats för en mer exakt estimering';
    },

    async estimate() {
        if (!this.serviceCategory) {
            this.error = 'Vänligen välj en tjänstekategori.';
            return;
        }

        if (this.description.length < 20 || this.description.length > 2000) {
            this.error = 'Beskrivningen måste vara mellan 20 och 2000 tecken.';
            return;
        }

        // Get Turnstile token
        const turnstileToken = document.querySelector('[name="cf-turnstile-response"]')?.value;
        if (!turnstileToken) {
            this.error = 'Vänligen slutför säkerhetsverifieringen.';
            return;
        }

        // Track calculator submit
        if (window.GA4) {
            window.GA4.trackCalculatorSubmit({
                service_category: this.serviceCategory,
                description_length: this.description.length
            });
        }

        this.loading = true;
        this.error = null;
        this.result = null;
        this.estimationId = null;

        try {
            const response = await fetch('/api/price-estimate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    description: this.description,
                    service_category: this.serviceCategory,
                    website_url: this.websiteUrl || null,
                    'cf-turnstile-response': turnstileToken
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                this.result = data.estimation;
                this.estimationId = data.estimation_id;

                // Track calculator result
                if (window.GA4 && this.result) {
                    window.GA4.trackCalculatorResult(this.result.estimated_price);
                }

                // Scroll to results section
                setTimeout(() => {
                    const resultsSection = document.querySelector('#price-results');
                    if (resultsSection) {
                        resultsSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }, 200); // Slight delay to allow for transition animation
            } else {
                this.error = data.error || 'Ett fel uppstod vid estimering.';
            }

        } catch (error) {
            console.error('Price estimation error:', error);
            this.error = 'Kunde inte kontakta servern. Vänligen försök igen.';
        } finally {
            this.loading = false;
        }
    },

    bookConsultation() {
        // Track CTA click
        if (window.GA4) {
            window.GA4.trackCalculatorCTA('contact');
        }

        // Dispatch custom event with estimation data
        if (this.estimationId && this.result) {
            window.dispatchEvent(new CustomEvent('estimation-ready', {
                detail: {
                    id: this.estimationId,
                    data: this.result
                }
            }));
        }

        // Scroll to contact form
        setTimeout(() => {
            const contactSection = document.querySelector('#contact');
            if (contactSection) {
                contactSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }, 100);
    },

    formatCurrency(amount) {
        if (!amount) return '0 kr';
        return new Intl.NumberFormat('sv-SE').format(amount) + ' kr';
    }
}));

// Start Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Initialize LazyLoadManager and GA4 tracking after Alpine starts
document.addEventListener('DOMContentLoaded', () => {
    window.lazyLoadManager = new LazyLoadManager();

    // Initialize darkmode store
    if (Alpine.store('darkMode')) {
        Alpine.store('darkMode').init();
    }

    // Initialize GA4 tracking (scroll depth, time on page)
    if (window.GA4) {
        GA4.init();
    }
});

// Form validation and interaction enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Initialize modals
    initTechStackModal();

    // Initialize project modal if projects data exists
    if (window.projectModalData) {
        initProjectModal(window.projectModalData);
    }

    // Auto-hide flash messages after 5 seconds
    const flashMessages = document.querySelectorAll('[x-data]');
    
    // Custom smooth scroll function with easing
    function smoothScrollTo(targetPosition, duration = 800) {
        const startPosition = window.pageYOffset;
        const distance = targetPosition - startPosition;
        let startTime = null;

        // Easing function (easeInOutCubic)
        function easeInOutCubic(t) {
            return t < 0.5
                ? 4 * t * t * t
                : 1 - Math.pow(-2 * t + 2, 3) / 2;
        }

        function animation(currentTime) {
            if (startTime === null) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const progress = Math.min(timeElapsed / duration, 1);
            const ease = easeInOutCubic(progress);

            window.scrollTo(0, startPosition + (distance * ease));

            if (timeElapsed < duration) {
                requestAnimationFrame(animation);
            }
        }

        requestAnimationFrame(animation);
    }

    // Removed: preRenderIntersectSections() - replaced by LazyLoadManager

    // Smooth scroll to anchors without hash in URL
    // Handles both #section and /#section links
    document.querySelectorAll('a[href^="#"], a[href^="/#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');

            // Skip empty hash
            if (href === '#' || href === '/#') return;

            // Extract hash from href (handles both #section and /#section)
            const hash = href.includes('#') ? href.substring(href.indexOf('#')) : null;
            if (!hash) return;

            // Check if we're navigating to current page
            const targetPath = href.startsWith('/#') ? '/' : window.location.pathname;
            const isCurrentPage = window.location.pathname === targetPath;

            // Only smooth scroll if we're on the same page
            if (isCurrentPage) {
                e.preventDefault();
                const target = document.querySelector(hash);
                if (target) {
                    // Calculate position with small offset
                    const targetPosition = target.offsetTop - 20;

                    // Use custom smooth scroll
                    smoothScrollTo(targetPosition, 800);

                    // Show navigation after scroll completes (800ms duration + small buffer)
                    setTimeout(() => {
                        const nav = document.querySelector('nav');
                        if (nav && typeof Alpine !== 'undefined') {
                            const alpineData = Alpine.$data(nav);
                            if (alpineData && 'showNav' in alpineData) {
                                alpineData.showNav = true;
                            }
                        }
                    }, 850);

                    // Remove hash from URL without adding to browser history
                    setTimeout(() => {
                        history.replaceState(null, '', window.location.pathname);
                    }, 10);
                }
            }
            // If different page, let browser navigate normally (will load page then scroll to hash)
        });
    });

    // Handle hash on page load (when navigating from another page with /#section)
    window.addEventListener('load', function() {
        if (window.location.hash) {
            const hash = window.location.hash;
            const target = document.querySelector(hash);
            if (target) {
                // Small delay to ensure page is fully rendered and LazyLoadManager is initialized
                setTimeout(() => {
                    // Calculate position with small offset
                    const targetPosition = target.offsetTop - 20;
                    smoothScrollTo(targetPosition, 800);

                    // Show navigation after scroll completes
                    setTimeout(() => {
                        const nav = document.querySelector('nav');
                        if (nav && typeof Alpine !== 'undefined') {
                            const alpineData = Alpine.$data(nav);
                            if (alpineData && 'showNav' in alpineData) {
                                alpineData.showNav = true;
                            }
                        }
                    }, 850);

                    // Remove hash from URL
                    setTimeout(() => {
                        history.replaceState(null, '', window.location.pathname);
                    }, 10);
                }, 100);
            }
        }
    });

    // Auto-generate slug from title (for admin project forms)
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    if (titleInput && slugInput && !slugInput.value) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.dataset.manuallyEdited) {
                const slug = this.value
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '') // Remove diacritics
                    .replace(/å/g, 'a')
                    .replace(/ä/g, 'a')
                    .replace(/ö/g, 'o')
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.value = slug;
            }
        });
        
        slugInput.addEventListener('input', function() {
            this.dataset.manuallyEdited = 'true';
        });
    }

    // Confirm before leaving form with unsaved changes
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        let formChanged = false;
        
        form.addEventListener('input', function() {
            formChanged = true;
        });
        
        form.addEventListener('submit', function() {
            formChanged = false;
        });
        
        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    });

    // Google reCAPTCHA v3 integration
    // Handle form submission with reCAPTCHA token
    const recaptchaForms = document.querySelectorAll('form:has(input.g-recaptcha-response)');

    recaptchaForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const recaptchaInput = form.querySelector('input.g-recaptcha-response');

            // If reCAPTCHA is disabled (dev mode) or token already exists, allow submission
            if (!recaptchaInput || recaptchaInput.value) {
                return;
            }

            // Prevent form submission until we get the token
            e.preventDefault();

            // Check if reCAPTCHA is loaded
            if (typeof grecaptcha === 'undefined') {
                console.error('reCAPTCHA not loaded');
                // Submit anyway to show server-side error
                form.submit();
                return;
            }

            // Execute reCAPTCHA and get token
            grecaptcha.ready(function() {
                const siteKey = document.querySelector('script[src*="recaptcha"]')?.src.match(/render=([^&]+)/)?.[1];

                if (!siteKey) {
                    console.error('reCAPTCHA site key not found');
                    form.submit();
                    return;
                }

                grecaptcha.execute(siteKey, { action: 'submit' }).then(function(token) {
                    // Add token to hidden field
                    recaptchaInput.value = token;
                    // Submit form
                    form.submit();
                }).catch(function(error) {
                    console.error('reCAPTCHA error:', error);
                    // Submit anyway to show server-side error
                    form.submit();
                });
            });
        });
    });
});