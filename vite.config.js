import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; // Импортируйте плагин Vue
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/main.css',
                'resources/js/app.js',
                'resources/js/searchForm/jQformJS.js',
                'resources/js/searchForm/formJS.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
});
