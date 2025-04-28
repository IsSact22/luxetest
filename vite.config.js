import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import i18n from "laravel-vue-i18n/vite";
import { visualizer } from "rollup-plugin-visualizer";

export default defineConfig({
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


// version 2 de produccion 


// import { defineConfig } from "vite";
// import laravel from "laravel-vite-plugin";
// import vue from "@vitejs/plugin-vue";
// import i18n from "laravel-vue-i18n/vite";
// import { visualizer } from "rollup-plugin-visualizer";

// export default defineConfig(({ mode }) => {
//     const isProduction = mode === 'production';

//     return {
//         base: isProduction ? '/build/' : '',
//         server: {
//             host: '0.0.0.0',   // <- ¡importante! Así funciona bien dentro de Docker
//             port: 5173,
//             strictPort: true,
//             hmr: {
//                 host: 'localhost', // o IP de tu máquina si es necesario
//             },
//         },
//         plugins: [
//             laravel({
//                 input: ["resources/js/app.js"],
//                 refresh: true,
//             }),
//             vue({
//                 template: {
//                     transformAssetUrls: {
//                         base: null,
//                         includeAbsolute: false,
//                     },
//                 },
//             }),
//             i18n(),
//             visualizer({ open: false }), // Visualizador OFF por defecto
//         ],
//         resolve: {
//             alias: {
//                 "@": "/resources/js",
//             },
//         },
//         build: {
//             outDir: 'public/build',
//             manifest: true,
//             rollupOptions: {
//                 output: {
//                     manualChunks(id) {
//                         if (id.includes("node_modules")) {
//                             const parts = id.split("node_modules/");
//                             if (parts.length > 1) {
//                                 const packageName = parts[1].split("/")[0];
//                                 return `vendor-${packageName}`;
//                             }
//                             return "vendor";
//                         }
//                         if (id.includes("resources/js/components")) {
//                             return "components";
//                         }
//                     },
//                 },
//             },
//             chunkSizeWarningLimit: 1000,
//         },
//     };
// });
