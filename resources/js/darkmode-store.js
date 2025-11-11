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
        // FORCED DARK MODE: Alltid mörkt läge (space theme kräver det)
        mode: 'dark',

        // Media query för system-preferens (behålls för bakåtkompatibilitet)
        mediaQuery: window.matchMedia('(prefers-color-scheme: dark)'),

        /**
         * Initialisera store
         */
        init() {
            // Applicera mörkt tema direkt
            this.applyTheme();
        },

        /**
         * Hämta det effektiva läget (alltid 'dark')
         * @returns {string} 'dark'
         */
        getEffectiveMode() {
            return 'dark';
        },

        /**
         * Sätt nytt läge (disabled - alltid dark)
         * @param {string} newMode - Ignoreras
         */
        setMode(newMode) {
            // Dark mode is forced - do nothing
            if (import.meta.env.DEV) {
                console.log('Dark mode is forced, ignoring setMode() call');
            }
        },

        /**
         * Applicera tema på document (alltid dark)
         */
        applyTheme() {
            document.documentElement.classList.add('dark');
        },

        /**
         * Toggle (disabled - dark mode är alltid aktiverat)
         */
        toggle() {
            // Dark mode is forced - do nothing
            if (import.meta.env.DEV) {
                console.log('Dark mode is forced, toggle disabled');
            }
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
