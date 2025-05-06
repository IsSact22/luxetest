<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { useToast } from "vue-toastification";
import CamoActivityForm from "@/Pages/CamoActivities/Partials/CamoActivityForm.vue";

const toast = useToast();

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
    camo: {
        type: Object,
    },
});

const handleImages = (success) => {
    if (success) {
        // Recargar la página actual
        router.reload({ only: ['resource'] });
    }
};

const goBack = () => {
    const camoId = props.resource.data.camo_id;
    router.get(route("camos.show", camoId), {}, { replace: false });
};
</script>

<template>
    <Head title="Edit Activity" />
    <AuthenticatedLayout>
        <template #header>
            <h2>
                Edit Activity <small>{{ props.resource.data.id }}</small>
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mt-6">
            <CamoActivityForm
                :cam-id="usePage().props.auth.user.id"
                :camo="props.camo.data"
                :camo-activity="props.resource.data"
                :user="usePage().props.auth.user"
            />
        </div>
    </AuthenticatedLayout>
</template>
