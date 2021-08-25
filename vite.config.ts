import { defineConfig } from 'laravel-vite'
import vue from '@vitejs/plugin-vue'
// @ts-ignore
import tailwind from 'tailwindcss'
import autoprefixer from 'autoprefixer'

export default defineConfig()
	.withPlugin(vue)
    .withPostCSS([
        tailwind,
        autoprefixer,
    ])
    .merge({
        server: {
            strictPort: true,
            host: '0.0.0.0',
            port: 3000
        },
        optimizeDeps: {
            include: [
                'vue',
                '@inertiajs/inertia',
                '@inertiajs/inertia-vue3',
                '@inertiajs/progress',
                'axios'
            ]
        }
    });
