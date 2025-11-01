/**
 * 3D/AR Product Viewer Demo
 *
 * Uses Google Model-Viewer web component for 3D rendering and AR support.
 * Alpine.js for state management and interactivity.
 */

/**
 * Alpine.js Product Viewer Component
 */
window.productViewerData = function(productsData) {
    return {
        // State
        selectedProductIndex: 0,
        products: productsData || [],
        modelLoaded: false,
        modelLoading: false,
        modelError: false,
        arAvailable: false,
        autoRotate: true,

        // Computed
        get selectedProduct() {
            return this.products[this.selectedProductIndex] || this.products[0];
        },

        // Methods
        init() {
            // Check AR availability
            this.checkARSupport();

            // Listen for model viewer events
            this.$nextTick(() => {
                const modelViewer = this.$refs.modelViewer;
                if (modelViewer) {
                    this.setupModelViewerEvents(modelViewer);
                }
            });

            console.log('Product Viewer initialized');
        },

        selectProduct(index) {
            if (index < 0 || index >= this.products.length) return;

            this.selectedProductIndex = index;
            this.modelLoaded = false;
            this.modelLoading = true;
            this.modelError = false;

            // Reset camera when switching products
            this.$nextTick(() => {
                const modelViewer = this.$refs.modelViewer;
                if (modelViewer && modelViewer.resetTurntableRotation) {
                    modelViewer.resetTurntableRotation();
                }
            });
        },

        checkARSupport() {
            // Model-viewer handles AR detection automatically
            // But we can check for basic WebXR support
            if ('xr' in navigator) {
                navigator.xr.isSessionSupported('immersive-ar').then((supported) => {
                    this.arAvailable = supported;
                }).catch(() => {
                    this.arAvailable = false;
                });
            } else {
                // Check for iOS AR Quick Look or Android Scene Viewer
                const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
                const isAndroid = /Android/.test(navigator.userAgent);
                this.arAvailable = isIOS || isAndroid;
            }
        },

        setupModelViewerEvents(modelViewer) {
            // Model loaded successfully
            modelViewer.addEventListener('load', () => {
                this.modelLoaded = true;
                this.modelLoading = false;
                this.modelError = false;
                console.log('Model loaded:', this.selectedProduct.name);
            });

            // Model loading progress
            modelViewer.addEventListener('progress', (event) => {
                const progress = event.detail.totalProgress;
                console.log('Loading progress:', Math.round(progress * 100) + '%');
            });

            // Model error
            modelViewer.addEventListener('error', (event) => {
                this.modelLoaded = false;
                this.modelLoading = false;
                this.modelError = true;
                console.error('Model loading error:', event);
            });

            // AR session started
            modelViewer.addEventListener('ar-status', (event) => {
                if (event.detail.status === 'session-started') {
                    console.log('AR session started');
                }
            });
        },

        toggleAutoRotate() {
            this.autoRotate = !this.autoRotate;
            const modelViewer = this.$refs.modelViewer;
            if (modelViewer) {
                modelViewer.autoRotate = this.autoRotate;
            }
        },

        resetCamera() {
            const modelViewer = this.$refs.modelViewer;
            if (modelViewer) {
                modelViewer.resetTurntableRotation();
                if (modelViewer.fieldOfView) {
                    modelViewer.fieldOfView = '45deg'; // Reset FOV
                }
            }
        },

        shareProduct() {
            const product = this.selectedProduct;
            if (navigator.share) {
                navigator.share({
                    title: product.name,
                    text: product.description,
                    url: window.location.href
                }).catch(err => console.log('Share failed:', err));
            } else {
                // Fallback: copy URL
                navigator.clipboard.writeText(window.location.href);
                alert('Länk kopierad till urklipp!');
            }
        }
    };
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    console.log('Product Viewer script loaded');
});
