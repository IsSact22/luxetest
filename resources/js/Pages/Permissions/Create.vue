<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({})
    }
})
</script>
<template>
    <Head title="Users"/>
    <AuthenticatedLayout>
        <template #header>
            <h2>Users</h2>
        </template>
        <div class="flex flex-col mx-auto px-4 mt-4">
            <div class="flex flex-row justify-items-center items-center space-x-4 px-4 py-2">
                <Link class="b-goto">Roles</Link>
                <Link class="b-goto">Permissions</Link>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
