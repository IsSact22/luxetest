// version 2 de produccion 


import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import i18n from "laravel-vue-i18n/vite";
import { visualizer } from "rollup-plugin-visualizer";
import { createHtmlPlugin } from "vite-plugin-html";
import vueDevTools from "vite-plugin-vue-devtools";
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig(({ mode }) => {
    const isProduction = mode === 'production';

    return {
        base: isProduction ? '/build/' : '',
        server: {
            host: '0.0.0.0',   // <- ¡importante! Así funciona bien dentro de Docker
            port: 5173,
            strictPort: true,
            hmr: {
                host: 'localhost', // o IP de tu máquina si es necesario
            },
        },
        plugins: [
            laravel({
                input: ["resources/js/app.js"],
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
            visualizer({ open: false }), // Visualizador OFF por defecto,
            VitePWA({
                registerType: 'autoUpdate',
                includeAssets: ['icons/*.png'],
                manifest: false,
                devOptions: {
                    enabled: true
                },
                workbox: {
                    globPatterns: ['**/*.{js,css,html,ico,png,svg,woff2}'],
                    runtimeCaching: [
                        {
                            urlPattern: /^https:\/\/api\..*/i,
                            handler: 'NetworkFirst',
                            options: {
                                cacheName: 'api-cache',
                                networkTimeoutSeconds: 10,
                                cacheableResponse: {
                                    statuses: [0, 200]
                                }
                            }
                        }
                    ]
                },
                workbox: {
                    globPatterns: ['**/*.{js,css,html,ico,png,svg,woff2}'],
                    runtimeCaching: [
                        {
                            urlPattern: /^https:\/\/api\.*/i,
                            handler: 'NetworkFirst',
                            options: {
                                cacheName: 'api-cache',
                                networkTimeoutSeconds: 10,
                                cacheableResponse: {
                                    statuses: [0, 200]
                                }
                            }
                        }
                    ]
                }
            })
        ],
        resolve: {
            alias: {
                "@": "/resources/js",
            },
        },
        build: {
            outDir: 'public/build',
            manifest: true,
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
    };
});
