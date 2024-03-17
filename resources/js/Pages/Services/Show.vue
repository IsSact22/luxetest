<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
</script>
<template>
    <Head title="Services" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Services</h2>
        </template>
    </AuthenticatedLayout>
</template>
