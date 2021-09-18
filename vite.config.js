import path from 'path'
import vue from '@vitejs/plugin-vue';

export default ({ command }) => ({
    plugins: [vue()],
    base: command === 'serve' ? '' : '/dist/',
    publicDir: 'fake_dir_so_nothing_gets_copied',
    server: {
        strictPort: true,
        host: '0.0.0.0',
        port: 3000
    },
    build: {
        manifest: true,
        outDir: 'public/dist',
        rollupOptions: {
            input: 'resources/js/app.js',
        },
    },
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
        }
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
