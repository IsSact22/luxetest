<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import AircraftForm from "@/Pages/Aircrafts/Partials/AircraftForm.vue";
import { route } from "ziggy-js";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
</script>
<template>
    <Head title="Aircrafts" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Aircraft</h2>
            <div class="flex flex-row-reverse">
                <Link :href="route('aircrafts.index')" class="btn-goto"
                    >Back
                </Link>
            </div>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <AircraftForm :aircraft="props.resource.data" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
