import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import i18n from "laravel-vue-i18n/vite";
import { visualizer } from "rollup-plugin-visualizer";

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost'
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
    ],
    resolve: {
        "@": "/resources/js",
    },
    build: {
        rollupOptions: {
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
                },
            },
        },
        chunkSizeWarningLimit: 1000,
    },
});
