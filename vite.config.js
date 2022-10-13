import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['public/js/script.js', 'resources/css/app.css', 'public/css/style.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
