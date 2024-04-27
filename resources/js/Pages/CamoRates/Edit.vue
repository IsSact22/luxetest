<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Link} from '@inertiajs/vue3';
import {route} from "ziggy-js";
import CamoRateForm from "@/Pages/CamoRates/Partials/CamoRateForm.vue";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    }
})
</script>
<template>
    <Head title="Camo Rates"/>
    <AuthenticatedLayout>
        <template #header>
            <h2>Camo Rates</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center">
            <div class="w-3/12 my-4 border rounded-md p-4">
                <CamoRateForm :method="'update'" :data="resource.data" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
