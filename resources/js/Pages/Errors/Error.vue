<script setup>
import { computed } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { route } from "ziggy-js";

const props = defineProps({
    status: Number,
    message: {
        type: String,
        default: () => "",
    },
});

const title = computed(() => {
    return {
        503: "503: Servicio no disponible",
        500: "500: Error del servidor",
        400: "400: Solicitud incorrecta",
        404: "404: Página no encontrada",
        403: "403: Prohibido",
        401: "401: No autorizado",
        422: "422: Entidad no procesable (Error de validación)",
    }[props.status];
});

const description = computed(() => {
    return {
        503: "Lo sentimos, estamos realizando mantenimiento. Por favor, vuelve pronto.",
        500: "Oops, algo salió mal en nuestros servidores.",
        400: "Lo sentimos, tu solicitud no fue válida.",
        404: "Lo sentimos, la página que buscas no se pudo encontrar.",
        401: "Lo sentimos, no estás autorizado para acceder a esta página.",
        403: "Lo sentimos, está prohibido acceder a esta página.",
        422: "Lo sentimos, hubo un error al procesar tu solicitud.",
    }[props.status];
});
</script>

<template>
    <div class="grid h-screen place-content-center bg-black px-4">
        <div
            class="flex flex-col justify-items-center items-center text-center"
        >
            <ApplicationLogo />
            <h1
                class="mt-6 text-2xl font-bold tracking-tight text-yellow-600 sm:text-4xl"
            >
                {{ title }}
            </h1>

            <p class="mt-4 text-yellow-500">{{ description }}</p>
            <small class="text-white text-xl my-3">{{ message }}</small>
            <p class="my-7">
                <a :href="route('dashboard')" class="btn-primary">Dashboard</a>
            </p>
        </div>
    </div>
</template>
