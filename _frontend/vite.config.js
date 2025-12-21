import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
    plugins: [react()],
    build: {
        outDir: '../content/themes/admin-default/assets/js',
        emptyOutDir: false,
        rollupOptions: {
            input: 'src/main.jsx',
            output: {
                entryFileNames: 'editor.bundle.js',
                format: 'iife',
                name: 'ZeroEditor'
            }
        }
    },
    define: {
        'process.env': {}
    }
})
