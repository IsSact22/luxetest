import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "ziggy-js";
import Toast, { useToast } from "vue-toastification";
import "vue-toastification/dist/index.css";
import { createPinia } from "pinia";
import piniaPluginPersistedState from "pinia-plugin-persistedstate";
import storeReset from "./Stores/plugins/storeReset.js";
import { i18nVue } from "laravel-vue-i18n";
import VueSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { vTooltip } from "floating-vue";
import "floating-vue/dist/style.css";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

const pinia = createPinia();
pinia.use(storeReset);
pinia.use(piniaPluginPersistedState);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue"),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(pinia)
            .use(Toast, {
                position: 'top-right',
                timeout: 3000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: true,
                closeButton: 'button',
                icon: true,
                rtl: false
            })
            .use(i18nVue, {
                resolve: async (lang) => {
                    const langs = import.meta.glob("../../lang/*.json");
                    return await langs[`../../lang/${lang}.json`]();
                },
            })
            .directive('tooltip', vTooltip)
            .component('v-select', VueSelect)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
