{{-- Google Analytics 4 - GDPR-compliant (endast med cookie consent) --}}
@if(config('services.google.analytics_measurement_id'))
<script>
    // GA4 Measurement ID
    const GA4_ID = '{{ config('services.google.analytics_measurement_id') }}';

    // Vänta på consent-uppdatering från cookie-systemet
    window.addEventListener('consent-updated', (event) => {
        const { preferences } = event.detail;

        if (preferences.analytics && !window.gtag) {
            // Användaren har godkänt analytics - ladda GA4
            loadGoogleAnalytics(preferences);
        } else if (!preferences.analytics && window.gtag) {
            // Användaren har avaktiverat analytics - uppdatera consent
            window.gtag('consent', 'update', {
                'analytics_storage': 'denied',
                'ad_storage': 'denied',
                'ad_user_data': 'denied',
                'ad_personalization': 'denied'
            });
        } else if (window.gtag && preferences.analytics) {
            // Analytics är redan laddat, uppdatera Google Signals baserat på marketing consent
            updateGoogleSignalsConsent(preferences);
        }
    });

    // Kolla befintligt consent vid sidladdning
    // Med Berättigat Intresse (GDPR Art. 6.1.f) är analytics aktiverat som standard
    async function checkInitialConsent() {
        try {
            const response = await fetch('/api/consent');
            const data = await response.json();

            // Ladda GA4 om analytics är aktiverat (antingen genom Berättigat Intresse eller explicit godkännande)
            if (data.preferences.analytics) {
                loadGoogleAnalytics(data.preferences);
            }
        } catch (error) {
            console.error('Failed to check analytics consent:', error);
        }
    }

    // Ladda Google Analytics 4
    function loadGoogleAnalytics(preferences) {
        if (window.gtag) {
            console.debug('GA4 already loaded');
            return;
        }

        // Ladda gtag.js script
        const script = document.createElement('script');
        script.async = true;
        script.src = `https://www.googletagmanager.com/gtag/js?id=${GA4_ID}`;
        document.head.appendChild(script);

        // Initiera gtag
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        window.gtag = gtag;

        gtag('js', new Date());

        // Google Signals aktiveras endast om BÅDE analytics OCH marketing godkänts
        const allowGoogleSignals = preferences.analytics && preferences.marketing;

        gtag('config', GA4_ID, {
            'anonymize_ip': true,  // GDPR: Anonymisera IP-adresser
            'cookie_flags': 'SameSite=Lax;Secure',
            'send_page_view': true,
            'allow_google_signals': allowGoogleSignals,
            'allow_ad_personalization_signals': allowGoogleSignals
        });

        // Sätt consent mode för advertising
        gtag('consent', 'default', {
            'ad_storage': preferences.marketing ? 'granted' : 'denied',
            'ad_user_data': preferences.marketing ? 'granted' : 'denied',
            'ad_personalization': preferences.marketing ? 'granted' : 'denied',
            'analytics_storage': 'granted'
        });

        console.log('✓ Google Analytics 4 loaded and initialized');
        console.log('  Google Signals:', allowGoogleSignals ? 'ENABLED' : 'DISABLED (requires marketing consent)');
    }

    // Uppdatera Google Signals consent när användaren ändrar marketing preferences
    function updateGoogleSignalsConsent(preferences) {
        if (!window.gtag) return;

        const allowGoogleSignals = preferences.analytics && preferences.marketing;

        window.gtag('consent', 'update', {
            'ad_storage': preferences.marketing ? 'granted' : 'denied',
            'ad_user_data': preferences.marketing ? 'granted' : 'denied',
            'ad_personalization': preferences.marketing ? 'granted' : 'denied',
            'analytics_storage': preferences.analytics ? 'granted' : 'denied'
        });

        console.log('Google Signals consent updated:', allowGoogleSignals ? 'ENABLED' : 'DISABLED');
    }

    // Kolla consent när sidan laddas
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkInitialConsent);
    } else {
        checkInitialConsent();
    }
</script>
@endif
