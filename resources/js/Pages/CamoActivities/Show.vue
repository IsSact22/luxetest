<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { useToast } from "vue-toastification";
import useFormatCurrency from "@/Composables/formatCurrency.js";

const toast = useToast();
const { formatCurrency } = useFormatCurrency();

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});

const goBack = () => {
    const camoId = props.resource.data.camo_id;
    router.get(route("camos.show", camoId), {}, { replace: false });
};
</script>

<template>
    <Head title="Actividad" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Actividad</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mt-6">
            <div class="inline-flex space-x-5">
                <Link
                    :href="route('camos.show', props.resource.data.camo_id)"
                    class="btn-primary"
                    >Regresar
                </Link>
            </div>
            <p>
                <strong style="color: #b58a00 !important">Avión/Modelo:</strong>
                {{ props.resource.data.camo.aircraft.model_aircraft.name }} -
                <strong style="color: #b58a00 !important">Matricula</strong>
                {{ props.resource.data.camo.aircraft.register }} <br />
                <strong style="color: #b58a00 !important"
                    >Nombre de la Actividad</strong
                >
                {{ props.resource.data.name }}
            </p>
            <hr class="my-3" />
            <p class="text-lg font-semibold">Información de la Actividad</p>
            <div
                class="flex flex-col justify-items-center items-start space-y-3"
            >
                <div
                    class="flex flex-row justify-items-center items-start space-x-7 w-full px-4 py-2 border rounded-md shadow-lg"
                >
                    <p class="w-1/3">
                        <strong style="color: #b58a00 !important">Fecha</strong>
                        <br />
                        {{ resource.data.date }}
                    </p>
                    <p class="w-1/3">
                        <strong style="color: #b58a00 !important"
                            >Tarifa/Monto</strong
                        >
                        <br />
                        {{ resource.data.labor_mount }}
                    </p>
                    <!--                    <p class="w-1/4">
                                            <strong style="color: #b58a00 !important"
                                                >Nombre</strong
                                            >
                                            <br />
                                            {{ resource.data.name }}
                                        </p>-->
                    <p class="w-1/3">
                        <strong style="color: #b58a00 !important"
                            >Tiempo Estimado Hrs/Hom</strong
                        >
                        <br />
                        {{ resource.data.estimate_time }}
                    </p>
                </div>
                <div
                    class="flex flex-row justify-items-center items-start space-x-7 w-full px-4 py-2 border rounded-md shadow-lg shadow-lg"
                >
                    <p class="w-1/3">
                        <strong style="color: #b58a00 !important"
                            >Estatus de Aprobación</strong
                        >
                        <br />
                        {{ $t(resource.data.approval_status) }}
                    </p>
                    <p class="w-1/3">
                        <strong style="color: #b58a00 !important"
                            >Fecha de Inicio</strong
                        >
                        <br />
                        {{ resource.data.started_at }}
                    </p>
                    <p class="w-1/3">
                        <strong style="color: #b58a00 !important"
                            >Fecha de Finalización</strong
                        >
                        <br />
                        {{ resource.data.complete_at }}
                    </p>
                </div>
                <div
                    class="flex flex-row justify-items-center items-start space-x-7 w-full px-4 py-2 border rounded-md shadow-lg"
                >
                    <p class="w-1/3">
                        <strong style="color: #b58a00 !important"
                            >Estatus</strong
                        >
                        <br />
                        {{ $t(resource.data.status) }}
                    </p>
                    <p class="w-1/3">
                        <strong style="color: #b58a00 !important"
                            >Hrs/Hom Monto</strong
                        >
                        <br />
                        {{ formatCurrency(resource.data.labor_mount) }}
                    </p>
                    <p class="w-1/3">
                        <strong style="color: #b58a00 !important"
                            >Materiales Monto</strong
                        >
                        <br />
                        {{ formatCurrency(resource.data.material_mount) }}
                    </p>
                </div>
                <div
                    class="flex flex-row justify-items-center items-start space-x-7 w-full px-4 py-2 border rounded-md shadow-lg"
                >
                    <p class="w-1/3">
                        <strong style="color: #b58a00 !important"
                            >Descripción</strong
                        >
                        <br />
                        {{ resource.data.description }}
                    </p>
                    <p class="w-1/3">
                        <strong style="color: #b58a00 !important"
                            >Comentarios</strong
                        >
                        <br />
                        {{ resource.data.comments }}
                    </p>
                    <p class="w-1/3">
                        <strong style="color: #b58a00 !important"
                            >Información de Materiales</strong
                        >
                        <br />
                        {{ resource.data.material_information }}
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
