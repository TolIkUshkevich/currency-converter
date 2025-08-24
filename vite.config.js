import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/converter.css', 'resources/js/converter.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
