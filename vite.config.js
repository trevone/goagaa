import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    css: {
        preprocessorOptions: {
          scss: {
            api: 'modern-compiler' // or "modern"
          }
        }
    },
      
    plugins: [
        laravel({
            input: ['resources/js/app.js','resources/scss/app.scss',],
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
