import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
    plugins: [react()],
    // Use relative paths for assets - works in any installation folder
    base: './',
    build: {
        outDir: '../content/themes/admin-default/assets/js',
        emptyOutDir: false,
        rollupOptions: {
            input: 'src/main.jsx',
            output: {
                entryFileNames: 'editor.bundle.js',
                // Keep assets in same folder as bundle
                assetFileNames: 'assets/[name][extname]',
            }
        }
    },
    define: {
        'process.env': {}
    }
})
