import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', // Escucha en todas las interfaces del contenedor
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost', // ESTO elimina el [::] y lo cambia por localhost
        },
        watch: {
            usePolling: true, // Para que Windows detecte cambios en Docker
        },
    },
});