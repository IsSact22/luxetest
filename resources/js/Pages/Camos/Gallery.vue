<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import MediaComponent from "@/Components/MediaComponent.vue";
import { route } from "ziggy-js";
import { computed } from "vue";

const props = defineProps({
    resource: {
        type: Object,
    },
});
const camoId = computed(() => props.resource[0].camo_id);
</script>

<template>
    <Head title="Camos" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Camos Gallery</h2>
        </template>
        <div class="flex flex-row-reverse">
            <Link :href="route('camos.show', camoId)" class="btn-primary"
                >Back
            </Link>
        </div>
        <div class="flex flex-col justify-items-center items-center py-12">
            <MediaComponent :images="resource" class="my-2" />
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
