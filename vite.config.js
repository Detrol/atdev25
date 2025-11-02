import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/chat-widget.css',
                'resources/js/app.js',
                'resources/js/chat-widget.js',
                'resources/js/cookie-consent.js',
                'resources/js/demos/product-viewer.js',
                'resources/js/demos/before-after-slider.js',
                'resources/js/demos/smart-menu.js',
                'resources/js/demos/google-reviews.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
    },
});