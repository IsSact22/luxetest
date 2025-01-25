<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ModelAircraftFrom from "@/Pages/ModelAircrafts/Partials/ModelAircraftForm.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
</script>
<template>
    <Head title="Modelo de Aviones" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Modelo de Aviones</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <ModelAircraftFrom :model-aircraft="props.resource.data" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
