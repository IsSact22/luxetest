import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import i18n from "laravel-vue-i18n/vite";
import { visualizer } from "rollup-plugin-visualizer";

export default defineConfig({
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
        "@": "/public/storage",
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes("node_modules")) {
                        // Crea un chunk para dependencias específicas
                        const parts = id.split("node_modules/");
                        if (parts.length > 1) {
                            const packageName = parts[1].split("/")[0];
                            // Agrupar dependencias por nombre de paquete
                            return `vendor-${packageName}`;
                        }
                        return "vendor"; // Agrupa el resto en un único chunk
                    }
                    if (id.includes("resources/js/components")) {
                        return "components";
                    }
                    // Puedes agregar más condiciones según sea necesario
                },
            },
        },
        chunkSizeWarningLimit: 1000,
    },
});
