import {defineConfig} from 'vite';

export default defineConfig({
    build: {
        outDir: 'resources/dist',
        emptyOutDir: true,
        lib: {
            entry: 'resources/js/kanban-board.js',
            name: 'FilamentKanbanBoard',
            formats: ['iife'],
            fileName: () => 'kanban-board.js'
        },
        rollupOptions: {
            external: ['alpinejs'],
            output: {
                globals: {
                    'alpinejs': 'Alpine'
                },
                assetFileNames: 'kanban-board.css'
            }
        },
        cssCodeSplit: false
    }
});