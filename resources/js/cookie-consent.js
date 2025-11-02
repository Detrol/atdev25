/**
 * Cookie Consent Management
 * Alpine.js component för att hantera cookie consent
 */

window.cookieConsent = function() {
    return {
        showBanner: false,
        preferences: {
            essential: true, // Alltid true
            functional: false,
            analytics: false,
            marketing: false
        },

        init() {
            // Check om användaren redan gjort ett val
            this.checkConsentStatus();

            // Lyssna på event för att öppna banner
            window.addEventListener('open-cookie-banner', () => {
                this.showBanner = true;
            });
        },

        async checkConsentStatus() {
            try {
                const response = await fetch('/api/consent');
                const data = await response.json();

                if (!data.has_choice_made) {
                    // Visa banner om inget val gjorts
                    this.showBanner = true;
                } else {
                    // Ladda sparade preferences
                    this.preferences = data.preferences;
                    this.applyConsent();
                }
            } catch (error) {
                console.error('Failed to check consent status:', error);
                // Visa banner vid fel
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
                }
            } catch (error) {
                console.error('Failed to reject cookies:', error);
                this.showErrorMessage('Kunde inte avvisa cookies');
            }
        },

        closeBanner() {
            // Om användaren stänger utan att välja, defaulta till endast essential
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
            // Tillämpa consent-beslut
            // Detta körs efter att consent sparats

            // Functional cookies (localStorage)
            if (!this.preferences.functional) {
                // Ta bort functional localStorage items
                localStorage.removeItem('darkMode');
                localStorage.removeItem('chat_session_id');
            }

            // Analytics (Google Analytics etc.)
            if (this.preferences.analytics) {
                this.loadAnalytics();
            }

            // Marketing (Social pixels etc.)
            if (this.preferences.marketing) {
                this.loadMarketingScripts();
            }

            // Dispatch event för andra komponenter
            window.dispatchEvent(new CustomEvent('consent-updated', {
                detail: { preferences: this.preferences }
            }));
        },

        loadAnalytics() {
            // TODO: Ladda Google Analytics eller annan analytics
            console.log('Analytics enabled');
        },

        loadMarketingScripts() {
            // TODO: Ladda marketing pixels (Facebook, LinkedIn, etc.)
            console.log('Marketing scripts enabled');
        },

        showSuccessMessage(message) {
            // Simple success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg';
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        },

        showErrorMessage(message) {
            // Simple error notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg';
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }
    };
};

/**
 * Helper function: Check om consent finns för specifik kategori
 */
window.hasConsent = async function(category) {
    try {
        const response = await fetch(`/api/consent/check/${category}`);
        const data = await response.json();
        return data.has_consent;
    } catch {
        return category === 'essential'; // Default till endast essential
    }
};
