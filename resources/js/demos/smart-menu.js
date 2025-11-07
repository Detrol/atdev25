/**
 * Smart Menu Alpine.js Component
 *
 * Hanterar AI-driven allergenanalys för maträtter
 */

window.smartMenuData = function(dishesData) {
    return {
        // State
        dishes: dishesData || [],
        selectedDishIndex: 0,
        analyzing: false,
        analysisResult: null,
        error: null,
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),

        // Computed
        get selectedDish() {
            return this.dishes[this.selectedDishIndex] || this.dishes[0];
        },

        get hasAnalysis() {
            return this.analysisResult !== null;
        },

        get allergenCount() {
            return this.analysisResult?.allergens?.length || 0;
        },

        /**
         * Initialize component
         */
        init() {
            console.log('Smart Menu initialized with', this.dishes.length, 'dishes');

            // Auto-analyze first dish on load (demo mode)
            // setTimeout(() => this.analyzeDish(), 500);
        },

        /**
         * Select a dish from the list
         */
        selectDish(index) {
            if (index === this.selectedDishIndex) return;

            this.selectedDishIndex = index;
            this.analysisResult = null;
            this.error = null;

            // Track dish selection
            if (window.GA4) {
                window.GA4.trackSmartMenu('dish-select', this.selectedDish.name);
            }

            console.log('Selected dish:', this.selectedDish.name);
        },

        /**
         * Analyze selected dish for allergens using AI
         */
        async analyzeDish() {
            if (this.analyzing) return;

            this.analyzing = true;
            this.error = null;

            const dishDescription = `${this.selectedDish.name} - ${this.selectedDish.description}`;

            console.log('Analyzing dish:', dishDescription);

            try {
                const response = await fetch('/api/menu/analyze-allergens', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({
                        dish_description: dishDescription
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Något gick fel vid analysen');
                }

                if (data.success) {
                    this.analysisResult = data.analysis;

                    // Track successful allergen analysis
                    if (window.GA4) {
                        window.GA4.trackSmartMenu('allergen-analyze', this.selectedDish.name);
                    }

                    console.log('Analysis complete:', this.analysisResult);
                } else {
                    throw new Error(data.error || 'Analysen misslyckades');
                }

            } catch (error) {
                console.error('Analysis error:', error);
                this.error = error.message;
            } finally {
                this.analyzing = false;
            }
        },

        /**
         * Reset analysis
         */
        resetAnalysis() {
            this.analysisResult = null;
            this.error = null;

            // Track reset action
            if (window.GA4) {
                window.GA4.trackDemoInteraction('smart-menu', 'reset');
            }
        },

        /**
         * Get allergen icon
         */
        getAllergenIcon(allergenKey) {
            const icons = {
                'gluten': '🌾',
                'lactose': '🥛',
                'eggs': '🥚',
                'fish': '🐟',
                'crustaceans': '🦐',
                'mollusks': '🦪',
                'nuts': '🥜',
                'peanuts': '🥜',
                'soy': '🫘',
                'celery': '🥬',
                'mustard': '🌱',
                'sesame': '🌾',
                'sulfites': '🍷',
                'lupin': '🌸'
            };
            return icons[allergenKey] || '⚠️';
        },

        /**
         * Get confidence color class
         */
        getConfidenceColor(confidence) {
            const colors = {
                'high': 'bg-red-100 text-red-800 border-red-300',
                'medium': 'bg-yellow-100 text-yellow-800 border-yellow-300',
                'low': 'bg-blue-100 text-blue-800 border-blue-300'
            };
            return colors[confidence] || colors['medium'];
        },

        /**
         * Get confidence label
         */
        getConfidenceLabel(confidence) {
            const labels = {
                'high': 'Hög säkerhet',
                'medium': 'Medel säkerhet',
                'low': 'Låg säkerhet'
            };
            return labels[confidence] || 'Okänd';
        },

        /**
         * Get category color
         */
        getCategoryColor(category) {
            const colors = {
                'Husmanskost': 'bg-amber-100 text-amber-800',
                'Sallader': 'bg-green-100 text-green-800',
                'Pastarätter': 'bg-yellow-100 text-yellow-800',
                'Vegetariskt': 'bg-lime-100 text-lime-800',
                'Asiatiskt': 'bg-red-100 text-red-800',
                'Pizza': 'bg-orange-100 text-orange-800',
                'Förrätter': 'bg-purple-100 text-purple-800',
                'Efterrätter': 'bg-pink-100 text-pink-800'
            };
            return colors[category] || 'bg-gray-100 text-gray-800';
        },

        /**
         * Format dietary info for display
         */
        getDietaryBadges() {
            if (!this.analysisResult?.dietary_info) return [];

            const badges = [];
            const info = this.analysisResult.dietary_info;

            if (info.vegan) badges.push({ label: 'Vegansk', color: 'bg-green-100 text-green-800', icon: '🌱' });
            if (info.vegetarian) badges.push({ label: 'Vegetarisk', color: 'bg-green-100 text-green-800', icon: '🥗' });
            if (info.gluten_free) badges.push({ label: 'Glutenfri', color: 'bg-blue-100 text-blue-800', icon: '🚫🌾' });
            if (info.lactose_free) badges.push({ label: 'Laktosfri', color: 'bg-blue-100 text-blue-800', icon: '🚫🥛' });

            return badges;
        },

        /**
         * Cleanup on component destroy
         */
        destroy() {
            console.log('Smart Menu destroyed');
        }
    };
};
