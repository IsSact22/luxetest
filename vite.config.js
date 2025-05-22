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
            cors: true,
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
                manifest: {
                    name: 'Luxeplus',
                    short_name: 'Luxeplus',
                    description: 'Luxeplus Application',
                    theme_color: '#ffffff',
                    background_color: '#ffffff',
                    display: 'standalone',
                    orientation: 'portrait',
                    start_url: '/',
                    id: '/',
                    launch_handler: {
                        client_mode: ['navigate-existing', 'auto']
                    },
                    icons: [
                        {
                            src: 'icons/icon-192x192.png',
                            sizes: '192x192',
                            type: 'image/png',
                            purpose: 'any maskable'
                        },
                        {
                            src: 'icons/icon-512x512.png',
                            sizes: '512x512',
                            type: 'image/png',
                            purpose: 'any maskable'
                        }
                    ],
                    screenshots: [
                        {
                            src: 'screenshots/desktop.png',
                            sizes: '1920x1080',
                            type: 'image/png',
                            form_factor: 'wide'
                        }
                    ]
                },
                devOptions: {
                    enabled: true,
                    type: 'module'
                },
                strategies: 'generateSW',
                filename: 'sw.js',
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
