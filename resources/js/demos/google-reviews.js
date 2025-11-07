/**
 * Google Reviews Demo - Alpine.js Component
 * Handles interactive search, filtering, and display of Google reviews
 */

window.googleReviewsDemo = function() {
    return {
        // State
        loading: false,
        error: null,
        searchQuery: '',
        searchResults: [],
        currentPlace: null,
        reviews: [],
        cacheInfo: null,

        // Filtering & Pagination
        ratingFilter: null,
        currentPage: 1,
        reviewsPerPage: 6,

        // Computed Properties
        get filteredReviews() {
            let filtered = this.reviews;

            // Filter by rating if set
            if (this.ratingFilter !== null) {
                filtered = filtered.filter(r => r.rating === this.ratingFilter);
            }

            return filtered;
        },

        get displayedReviews() {
            const start = (this.currentPage - 1) * this.reviewsPerPage;
            const end = start + this.reviewsPerPage;
            return this.filteredReviews.slice(start, end);
        },

        get totalPages() {
            return Math.ceil(this.filteredReviews.length / this.reviewsPerPage);
        },

        // Initialization
        async init() {
            // Load default place (Puts i Karlstad) on page load
            await this.loadDefaultPlace();
        },

        // Load default place (Puts i Karlstad)
        async loadDefaultPlace() {
            this.loading = true;
            this.error = null;
            this.searchResults = [];
            this.searchQuery = '';

            try {
                const response = await fetch('/api/demos/google-reviews/default', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.currentPlace = data.data.place;
                    this.reviews = data.data.reviews || [];
                    this.cacheInfo = data.data.meta.cached_at;
                    this.currentPage = 1;
                    this.ratingFilter = null;
                } else {
                    this.error = data.error || 'Kunde inte hämta standardexempel';
                }
            } catch (err) {
                console.error('Error loading default place:', err);
                this.error = 'Nätverksfel. Kunde inte hämta recensioner.';
            } finally {
                this.loading = false;
            }
        },

        // Search for places
        async searchPlace() {
            if (!this.searchQuery.trim()) {
                this.error = 'Ange ett företagsnamn för att söka';
                return;
            }

            this.loading = true;
            this.error = null;
            this.searchResults = [];
            this.currentPlace = null;

            try {
                const response = await fetch('/api/demos/google-reviews/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        query: this.searchQuery
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.searchResults = data.data.results || [];

                    // Track search action
                    if (window.GA4) {
                        window.GA4.trackReviews('search', this.searchQuery);
                    }

                    if (this.searchResults.length === 0) {
                        this.error = 'Inga resultat hittades. Försök med ett annat sökord.';
                    } else if (this.searchResults.length === 1) {
                        // Auto-select if only one result
                        await this.selectPlace(this.searchResults[0].place_id);
                    }
                } else {
                    this.error = data.error || 'Sökfel. Försök igen.';
                }
            } catch (err) {
                console.error('Error searching places:', err);
                this.error = 'Nätverksfel. Kunde inte genomföra sökning.';
            } finally {
                this.loading = false;
            }
        },

        // Select a place from search results
        async selectPlace(placeId) {
            this.loading = true;
            this.error = null;
            this.searchResults = [];

            try {
                const response = await fetch(`/api/demos/google-reviews/${placeId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.currentPlace = data.data.place;
                    this.reviews = data.data.reviews || [];
                    this.cacheInfo = data.data.meta.cached_at;
                    this.currentPage = 1;
                    this.ratingFilter = null;

                    // Track place selection
                    if (window.GA4) {
                        window.GA4.trackDemoInteraction('google-reviews', 'place-select', this.currentPlace.name);
                    }
                } else {
                    this.error = data.error || 'Kunde inte hämta platsdata';
                }
            } catch (err) {
                console.error('Error selecting place:', err);
                this.error = 'Nätverksfel. Kunde inte hämta platsdata.';
            } finally {
                this.loading = false;
            }
        },

        // Filter by rating
        filterByRating(rating) {
            this.ratingFilter = rating;
            this.currentPage = 1; // Reset to first page when filtering

            // Track filter action
            if (window.GA4) {
                window.GA4.trackReviews('filter', rating);
            }
        },

        // Pagination
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                // Scroll to top of reviews
                this.scrollToReviews();
            }
        },

        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                // Scroll to top of reviews
                this.scrollToReviews();
            }
        },

        scrollToReviews() {
            const section = document.getElementById('google-reviews');
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        },

        // Utility: Render star rating as HTML
        renderStars(rating) {
            const fullStars = Math.floor(rating);
            const hasHalfStar = (rating % 1) >= 0.5;
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

            let html = '';

            // Full stars
            for (let i = 0; i < fullStars; i++) {
                html += '<span class="text-yellow-400">★</span>';
            }

            // Half star
            if (hasHalfStar) {
                html += '<span class="text-yellow-400">★</span>';
            }

            // Empty stars
            for (let i = 0; i < emptyStars; i++) {
                html += '<span class="text-gray-300 dark:text-gray-600">★</span>';
            }

            return html;
        },

        // Utility: Get initials from name
        getInitials(name) {
            if (!name) return '?';

            const parts = name.trim().split(' ');

            if (parts.length === 1) {
                return parts[0].charAt(0).toUpperCase();
            }

            return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
        }
    };
};
