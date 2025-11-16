import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
    root: path.resolve(__dirname, 'resources/scripts'),
    build: {
        target: 'esnext',
        outDir: path.resolve(__dirname, 'public'),
        emptyOutDir: false,
        manifest: true,
        rollupOptions: {
            input: {
                main: path.resolve(__dirname, 'resources/scripts/main.js')
            }
        }
    },
});