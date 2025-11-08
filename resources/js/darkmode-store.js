/**
 * Darkmode Store för Alpine.js
 *
 * Tre-läges system: 'light', 'dark', 'system'
 * - light: Tvingar ljust tema (ignorerar prefers-color-scheme)
 * - dark: Tvingar mörkt tema (ignorerar prefers-color-scheme)
 * - system: Följer prefers-color-scheme automatiskt
 */

export default function darkModeStore() {
    return {
        // Nuvarande läge: 'light', 'dark', eller 'system'
        mode: localStorage.getItem('darkMode') || 'system',

        // Media query för system-preferens
        mediaQuery: window.matchMedia('(prefers-color-scheme: dark)'),

        /**
         * Initialisera store
         */
        init() {
            // Lyssna på ändringar i system-preferens
            this.mediaQuery.addEventListener('change', () => {
                if (this.mode === 'system') {
                    this.applyTheme();
                }
            });

            // Applicera initialt tema
            this.applyTheme();
        },

        /**
         * Hämta det effektiva läget (resolve 'system' till light/dark)
         * @returns {string} 'light' eller 'dark'
         */
        getEffectiveMode() {
            if (this.mode === 'system') {
                return this.mediaQuery.matches ? 'dark' : 'light';
            }
            return this.mode;
        },

        /**
         * Sätt nytt läge
         * @param {string} newMode - 'light', 'dark', eller 'system'
         */
        setMode(newMode) {
            if (!['light', 'dark', 'system'].includes(newMode)) {
                console.warn(`Invalid darkmode mode: ${newMode}`);
                return;
            }

            const oldMode = this.mode;
            this.mode = newMode;

            // Spara till localStorage
            localStorage.setItem('darkMode', newMode);

            // Applicera tema
            this.applyTheme();

            // GA4 tracking om tillgängligt
            if (window.GA4 && typeof window.GA4.trackDarkMode === 'function') {
                window.GA4.trackDarkMode(this.getEffectiveMode() === 'dark', newMode);
            }

            // Logga i dev-mode
            if (import.meta.env.DEV) {
                console.log(`Darkmode changed: ${oldMode} → ${newMode} (effective: ${this.getEffectiveMode()})`);
            }
        },

        /**
         * Applicera tema på document
         */
        applyTheme() {
            const effectiveMode = this.getEffectiveMode();
            const isDark = effectiveMode === 'dark';

            document.documentElement.classList.toggle('dark', isDark);
        },

        /**
         * Växla genom lägena: light → dark → system → light
         */
        toggle() {
            const modes = ['light', 'dark', 'system'];
            const currentIndex = modes.indexOf(this.mode);
            const nextMode = modes[(currentIndex + 1) % modes.length];
            this.setMode(nextMode);
        },

        /**
         * Kontrollera om specifikt läge är aktivt
         * @param {string} checkMode - Läge att kontrollera
         * @returns {boolean}
         */
        isMode(checkMode) {
            return this.mode === checkMode;
        },

        /**
         * Kontrollera om effektivt tema är mörkt
         * @returns {boolean}
         */
        isDark() {
            return this.getEffectiveMode() === 'dark';
        }
    };
}
