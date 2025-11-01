/**
 * 3D/AR Product Viewer Demo
 *
 * Uses Google Model-Viewer web component for 3D rendering and AR support.
 * Alpine.js for state management and interactivity.
 */

// Product data will be passed from backend, but here's the structure for reference
const demoProducts = [
    {
        id: 1,
        name: 'Modern Fåtölj',
        description: 'Skandinavisk design med mjuka kurvor. Perfekt för moderna hem och kontor.',
        category: 'Möbler',
        model: '/models/armchair.glb',
        poster: '/images/products/armchair.jpg',
        useCases: ['Möbelbutiker', 'Inredningsdesigners', 'Homestyling'],
        dimensions: '80cm × 85cm × 90cm',
        arScale: '1.0'
    },
    {
        id: 2,
        name: 'Bordslampa',
        description: 'Minimalistisk bordslampa i mässing och glas. Ger varm, ambient belysning.',
        category: 'Belysning',
        model: '/models/lamp.glb',
        poster: '/images/products/lamp.jpg',
        useCases: ['Belysningsbutiker', 'Heminredning', 'Designbutiker'],
        dimensions: '25cm × 25cm × 45cm',
        arScale: '0.8'
    },
    {
        id: 3,
        name: 'Dekorativ Vas',
        description: 'Handgjord keramikvas med organiska former. En unik konversationsstartare.',
        category: 'Heminredning',
        model: '/models/vase.glb',
        poster: '/images/products/vase.jpg',
        useCases: ['Heminredning', 'Presentbutiker', 'Konstgallerier'],
        dimensions: '20cm × 20cm × 35cm',
        arScale: '0.6'
    },
    {
        id: 4,
        name: 'Abstrakt Skulptur',
        description: 'Modernistisk skulptur i polerad metall. Statement-piece för moderna rum.',
        category: 'Konst',
        model: '/models/sculpture.glb',
        poster: '/images/products/sculpture.jpg',
        useCases: ['Konstgallerier', 'Lyxbutiker', 'Företagskonst'],
        dimensions: '30cm × 30cm × 50cm',
        arScale: '0.7'
    }
];

/**
 * Alpine.js Product Viewer Component
 */
window.productViewerData = function() {
    return {
        // State
        selectedProductIndex: 0,
        products: demoProducts,
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
