<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import LaborRateForm from "@/Pages/LaborRates/Partials/LaborRateForm.vue";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
</script>
<template>
    <Head title="Camo Rates" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Camo Rates</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="w-3/12 my-4 border rounded-md p-4">
                <LaborRateForm :labor-rate="props.resource.data" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
