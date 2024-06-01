<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import BrandAircraftForm from "@/Pages/BrandAircrafts/Partials/BrandAircraftForm.vue";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
</script>
<template>
    <Head title="Engine Type" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Engine Type</h2>
            <div class="flex flex-row-reverse">
                <Link :href="route('brand-aircrafts.index')" class="btn-goto"
                    >Back
                </Link>
            </div>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <BrandAircraftForm :brand-aircraft="props.resource.data" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
