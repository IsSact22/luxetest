<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import EngineTypeForm from "@/Pages/EngineTypes/Partials/EngineTypeForm.vue";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
</script>
<template>
    <Head title="Tipo de Motores" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Tipo de Motor</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <EngineTypeForm :engine-type="props.resource.data" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
