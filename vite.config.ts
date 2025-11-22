import type { UserConfig } from 'vite';
import * as path from 'path';

export default {
    base: '/vite/',
    root: path.resolve(__dirname, 'resources/scripts'),
    build: {
        target: 'esnext',
        outDir: path.resolve(__dirname, 'public'),
        emptyOutDir: false,
        manifest: true,
        rollupOptions: {
            input: {
                main: path.resolve(__dirname, 'resources/scripts/main.ts'),
            },
        },
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/scripts'),
        },
    },
    server: {
        host: true,
        port: 5173,
        strictPort: true,
        allowedHosts: [
            'social.local',
        ],
        watch: {
            usePolling: true,
            interval: 100,
        },
        hmr: {
            host: 'social.local',
            port: 5173,
        },
    },
} satisfies UserConfig