import type { UserConfig } from 'vite';
import * as path from 'path';

export default {
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
} satisfies UserConfig