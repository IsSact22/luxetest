<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import BrandAircraftForm from "@/Pages/BrandAircrafts/Partials/BrandAircraftForm.vue";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
</script>
<template>
    <Head title="Marcas de Aviones" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Marcas de Aviones</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <BrandAircraftForm :brand-aircraft="props.resource.data" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
