import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Public landing page — built as its own CSS + JS entries so the
                // marketing page never shares a bundle with the authenticated app.
                // The Blade file itself is not an entry: Vite 8 parses inputs as
                // JS/CSS, and landing.blade.php only needs `asset()` + `@vite`.
                'resources/css/landing.css',
                'resources/js/landing.js',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
