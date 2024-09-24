<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import EngineTypeForm from "@/Pages/EngineTypes/Partials/EngineTypeForm.vue";
</script>
<template>
    <Head title="Tipo de Motores" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Tipo de Motor</h2>
            <div class="flex flex-row-reverse">
                <Link :href="route('engine-types.index')" class="btn-goto"
                    >Back
                </Link>
            </div>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <EngineTypeForm />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
