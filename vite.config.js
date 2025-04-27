import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import i18n from "laravel-vue-i18n/vite";
import { visualizer } from "rollup-plugin-visualizer";

export default defineConfig({
    build: {
        // Configuración de salida y optimización
        sourcemap: false,
        manifest: true,
        outDir: "public/build",
        chunkSizeWarningLimit: 1000,
        rollupOptions: {
            input: "resources/js/app.js",
            output: {
                manualChunks(id) {
                    if (id.includes("node_modules")) {
                        const parts = id.split("node_modules/");
                        if (parts.length > 1) {
                            const packageName = parts[1].split("/")[0];
                            return `vendor-${packageName}`;
                        }
                        return "vendor";
                    }
                    if (id.includes("resources/js/components")) {
                        return "components";
                    }
                }
            }
        },
        // Optimizaciones de producción
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true
            }
        }
    },
    resolve: {
        alias: {
            '@': '/resources/js'
        }
    },
    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
        hmr: {
            host: '127.0.0.1'
        }
    },
    plugins: [
        laravel({
            input: "resources/js/app.js",
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
        i18n(),
        visualizer({ open: true }),
    ]
});
