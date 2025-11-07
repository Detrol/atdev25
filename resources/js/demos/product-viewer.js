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
        },

        selectProduct(index) {
            if (index < 0 || index >= this.products.length) return;

            this.selectedProductIndex = index;
            this.modelLoaded = false;
            this.modelLoading = true;
            this.modelError = false;

            // Track product selection
            if (window.GA4) {
                window.GA4.trackDemoInteraction('product-viewer', 'product-select', this.products[index]?.name);
            }

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
            });

            // Model loading progress
            modelViewer.addEventListener('progress', (event) => {
                const progress = event.detail.totalProgress;
                // Track loading progress if needed
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
                    // Track AR start
                    if (window.GA4) {
                        window.GA4.trackARStart(this.selectedProduct?.name);
                    }
                }
            });
        },

        toggleAutoRotate() {
            this.autoRotate = !this.autoRotate;
            const modelViewer = this.$refs.modelViewer;
            if (modelViewer) {
                modelViewer.autoRotate = this.autoRotate;
            }

            // Track rotation toggle
            if (window.GA4) {
                window.GA4.trackProductRotation(this.autoRotate ? 'enabled' : 'disabled');
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

            // Track camera reset
            if (window.GA4) {
                window.GA4.trackDemoInteraction('product-viewer', 'camera-reset');
            }
        },

        shareProduct() {
            const product = this.selectedProduct;

            // Track share action
            if (window.GA4) {
                window.GA4.trackDemoInteraction('product-viewer', 'share', product?.name);
            }

            if (navigator.share) {
                navigator.share({
                    title: product.name,
                    text: product.description,
                    url: window.location.href
                }).catch(err => {
                    // Share failed
                });
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
    // Script loaded
});
