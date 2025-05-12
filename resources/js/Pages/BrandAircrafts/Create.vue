<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import BrandAircraftForm from "@/Pages/BrandAircrafts/Partials/BrandAircraftForm.vue";
</script>
<template>
    <Head title="Marcas de Aviones" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Marcas de Aviones</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <h2>Nueva Marca</h2>
            <div class="p-4 sm:p-8 bg-white">
                <BrandAircraftForm />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
