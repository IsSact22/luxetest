<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ModelAircraftFrom from "@/Pages/ModelAircrafts/Partials/ModelAircraftForm.vue";
import { Head, Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
</script>
<template>
    <Head title="Modelo de Aviones" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Modelo de Aviones</h2>
            <div class="flex flex-row-reverse">
                <Link :href="route('model-aircrafts.index')" class="btn-goto"
                    >Back
                </Link>
            </div>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <ModelAircraftFrom />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
