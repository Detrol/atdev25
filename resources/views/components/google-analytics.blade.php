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
            loadGoogleAnalytics();
        } else if (!preferences.analytics && window.gtag) {
            // Användaren har avaktiverat analytics - uppdatera consent
            window.gtag('consent', 'update', {
                'analytics_storage': 'denied'
            });
        }
    });

    // Kolla befintligt consent vid sidladdning
    async function checkInitialConsent() {
        try {
            const response = await fetch('/api/consent');
            const data = await response.json();

            if (data.has_choice_made && data.preferences.analytics) {
                loadGoogleAnalytics();
            }
        } catch (error) {
            console.error('Failed to check analytics consent:', error);
        }
    }

    // Ladda Google Analytics 4
    function loadGoogleAnalytics() {
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
        gtag('config', GA4_ID, {
            'anonymize_ip': true,  // GDPR: Anonymisera IP-adresser
            'cookie_flags': 'SameSite=Lax;Secure',
            'send_page_view': true
        });

        console.log('✓ Google Analytics 4 loaded and initialized');
    }

    // Kolla consent när sidan laddas
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkInitialConsent);
    } else {
        checkInitialConsent();
    }
</script>
@endif
