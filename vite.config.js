import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/website/landing-page.js',
                'resources/js/bootstrap.js',
                'resources/js/subscription.js',
                'resources/js/admin.js',
                'resources/js/admin_transactions.js',
                'resources/js/admin_statistics.js',
                'resources/js/admin_newstatistics.js',
                'resources/css/admin-layout.css',
                'resources/css/terms.css',
                'resources/css/navbar-style.css',
                'resources/css/landing-page-style.css',
                'resources/css/footer-style.css',
                'resources/css/faq-style.css',
                'resources/css/data-admin.css',
                'resources/css/contact.css'
            ],
            refresh: true,
        }),
    ],
});
