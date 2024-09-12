<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import AircraftForm from "@/Pages/Aircrafts/Partials/AircraftForm.vue";
</script>
<template>
    <Head title="Aircrafts" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Aircraft</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="p-4 sm:p-8 bg-white">
                <AircraftForm />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
