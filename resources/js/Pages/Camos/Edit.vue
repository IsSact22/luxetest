<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { route } from "ziggy-js";
import { computed } from "vue";
import { useForm } from "laravel-precognition-vue-inertia";

const props = defineProps({
    resource: {
        type: Object,
        required: true,
    },
});
const data = computed(() => {
    if (props.resource?.data) {
        return props.resource.data;
    }
});

const form = useForm("patch", route("camos.update", props.resource.data.id), {
    description: props.resource.data.description,
});
</script>

<template>
    <Head title="Camos" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Camos</h2>
        </template>

        <div class="flex flex-col justify-items-center items-center mx-auto">
            <div class="my-4 border rounded-md px-4 py-4">
                <div class="inline-flex space-x-5">
                    <Link :href="route('camos.index')" class="btn-primary"
                        >Regresar
                    </Link>
                </div>

                <div class="my-3">
                    <div>
                        <h2>Datos del CAMOS</h2>
                        <form>
                            <div class="mb-3">
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    for="cam"
                                    >CAM</label
                                >
                                <p
                                    class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-md"
                                >
                                    {{ data.cam }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    for="customer"
                                    >Cliente</label
                                >
                                <p
                                    class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-md"
                                >
                                    {{ data.customer }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    for="owner"
                                    >Propietario</label
                                >
                                <p
                                    class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-md"
                                >
                                    {{ data.owner }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    for="brand_aircraft"
                                    >Fabricante/Avión</label
                                >
                                <p
                                    class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-md"
                                >
                                    {{
                                        data.aircraft.model_aircraft
                                            .brand_aircraft.name
                                    }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    for="model_aircraft"
                                    >Modelo/Avión</label
                                >
                                <p
                                    class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-md"
                                >
                                    {{ data.aircraft.model_aircraft.name }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label
                                    class="block text-sm font-medium text-gray-500"
                                    for="description"
                                    >Descripción</label
                                >
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    class="border border-gray-300 rounded-md"
                                    cols="30"
                                    name="description"
                                    rows="3"
                                ></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    /
</template>

<style scoped></style>
