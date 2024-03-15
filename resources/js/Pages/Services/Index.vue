<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({})
    }
})
</script>
<template>
    <Head title="Services" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Services</h2>
        </template>
        <div class="flex flex-col mx-auto px-4 mt-4">
            <table class="w-full">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Estimate Time</th>
                    <th>has Material</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(service,idx) in resource.data" :key="idx">
                    <td>{{service.id}}</td>
                    <td>{{service.name}}</td>
                    <td>{{service.estimate_time}}</td>
                    <td>{{service.has_material}}</td>
                    <td class="col-actions">
                        <Link class="button-actions text-sky-900 bg-sky-300">Profile</Link>
                        <Link class="button-actions">Role</Link>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <Paginator :data="resource" />
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
