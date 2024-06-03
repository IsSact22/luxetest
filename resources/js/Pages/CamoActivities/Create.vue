<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CamoActivityForm from "@/Pages/CamoActivities/Partials/CamoActivityForm.vue";

const props = defineProps({
    camo: {
        type: Object,
    },
});
</script>
<template>
    <Head title="Camo Activity" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Camo Activity</h2>
            <div class="flex flex-row-reverse">
                <Link class="btn-goto">Back</Link>
            </div>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h2>Camo {{ props.camo.id }}</h2>
                <CamoActivityForm
                    :cam-id="usePage().props.auth.user.id"
                    :camo="props.camo"
                    :user="usePage().props.auth.user"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
