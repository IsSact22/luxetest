<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import LaborRateForm from "@/Pages/LaborRates/Partials/LaborRateForm.vue";
</script>
<template>
    <Head title="Tarifas laborales" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Tarifas laborales</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <LaborRateForm />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
