<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CamoForm from "@/Pages/Camos/Partials/CamoForm.vue";
</script>
<template>
    <Head title="Camos" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Camos</h2>
        </template>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 my-2">
            <CamoForm :cam-id="usePage().props.auth.user.id" :mode="1" />
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
