/**
 * Before/After Image Slider Demo
 *
 * Interactive image comparison slider with drag support.
 * Alpine.js for state management and interactivity.
 */

/**
 * Alpine.js Before/After Slider Component
 */
window.beforeAfterSliderData = function(examplesData) {
    return {
        // State
        selectedExampleIndex: 0,
        examples: examplesData || [],
        sliderPosition: 50, // Percentage 0-100
        isDragging: false,
        containerRect: null,

        // Computed
        get selectedExample() {
            return this.examples[this.selectedExampleIndex] || this.examples[0];
        },

        // Methods
        init() {
            // Set up window listeners
            window.addEventListener('mousemove', this.onDrag.bind(this));
            window.addEventListener('mouseup', this.stopDrag.bind(this));
            window.addEventListener('touchmove', this.onDrag.bind(this), { passive: false });
            window.addEventListener('touchend', this.stopDrag.bind(this));

            // Update container rect on resize
            window.addEventListener('resize', this.updateContainerRect.bind(this));

            // Set initial container rect
            this.$nextTick(() => {
                this.updateContainerRect();
            });
        },

        selectExample(index) {
            if (index < 0 || index >= this.examples.length) return;

            this.selectedExampleIndex = index;
            this.sliderPosition = 50; // Reset to center

            // Track example selection
            if (window.GA4) {
                window.GA4.trackDemoInteraction('before-after', 'select', this.selectedExample.title);
            }
        },

        updateContainerRect() {
            const container = this.$refs.sliderContainer;
            if (container) {
                this.containerRect = container.getBoundingClientRect();
            }
        },

        startDrag(event) {
            this.isDragging = true;
            this.updateContainerRect();

            // Prevent default to avoid text selection, etc.
            event.preventDefault();

            // Set initial position
            this.onDrag(event);
        },

        onDrag(event) {
            if (!this.isDragging) return;

            event.preventDefault();

            const container = this.$refs.sliderContainer;
            if (!container || !this.containerRect) return;

            // Get X position from mouse or touch
            let clientX;
            if (event.type.startsWith('touch')) {
                if (event.touches.length === 0) return;
                clientX = event.touches[0].clientX;
            } else {
                clientX = event.clientX;
            }

            // Calculate position relative to container
            const x = clientX - this.containerRect.left;
            const percentage = (x / this.containerRect.width) * 100;

            // Clamp between 0 and 100
            this.sliderPosition = Math.max(0, Math.min(100, percentage));
        },

        stopDrag() {
            if (!this.isDragging) return;

            this.isDragging = false;

            // Track slider drag interaction
            if (window.GA4) {
                window.GA4.trackBeforeAfter('drag', Math.round(this.sliderPosition));
            }
        },

        handleKeyboard(event) {
            const step = event.shiftKey ? 10 : 2;

            switch (event.key) {
                case 'ArrowLeft':
                    event.preventDefault();
                    this.sliderPosition = Math.max(0, this.sliderPosition - step);
                    break;
                case 'ArrowRight':
                    event.preventDefault();
                    this.sliderPosition = Math.min(100, this.sliderPosition + step);
                    break;
                case 'Home':
                    event.preventDefault();
                    this.sliderPosition = 0;
                    break;
                case 'End':
                    event.preventDefault();
                    this.sliderPosition = 100;
                    break;
            }
        },

        resetPosition() {
            this.sliderPosition = 50;

            // Track reset action
            if (window.GA4) {
                window.GA4.trackDemoInteraction('before-after', 'reset');
            }
        },

        destroy() {
            // Cleanup listeners
            window.removeEventListener('mousemove', this.onDrag);
            window.removeEventListener('mouseup', this.stopDrag);
            window.removeEventListener('touchmove', this.onDrag);
            window.removeEventListener('touchend', this.stopDrag);
            window.removeEventListener('resize', this.updateContainerRect);
        }
    };
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Script loaded
});
