<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import LaborRateForm from "@/Pages/LaborRates/Partials/LaborRateForm.vue";
import { route } from "ziggy-js";
</script>
<template>
    <Head title="Labor Rates" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Labor Rates</h2>
            <div class="flex flex-row-reverse">
                <Link :href="route('labor-rates.index')" class="btn-goto"
                    >Back
                </Link>
            </div>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <LaborRateForm />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
