import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    build: {
        chunkSizeWarningLimit: 1000,
        rollupOptions: {
            output: {
                manualChunks: {
                    apexcharts: ['apexcharts', 'vue3-apexcharts'],
                    vendor: ['vue', 'pinia', '@inertiajs/vue3', 'laravel-echo', 'pusher-js'],
                },
            },
        },
    },
});