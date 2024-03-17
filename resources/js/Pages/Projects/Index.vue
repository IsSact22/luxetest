<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { Link } from '@inertiajs/vue3';
import {route} from "ziggy-js";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({})
    }
})
</script>
<template>
    <Head title="Project" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Projects</h2>
        </template>
        <div class="flex flex-col mx-auto px-4 mt-4">
            <table class="w-full">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Aircraft</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(project,idx) in resource.data" :key="idx">
                    <td>{{project.id}}</td>
                    <td>{{project.name}}</td>
                    <td>{{project.date}}</td>
                    <td>{{project.client.customer_name}}</td>
                    <td>{{project.aircraft.name}}</td>
                    <td class="col-actions">
                        <Link :href="route('projects.show', project.id)" class="b-show">Show</Link>
                        <Link class="b-edit">Edit</Link>
                        <Link class="b-delete">Delete</Link>
                    </td>
                </tr>
                </tbody>
            </table>
            <Paginator :data="resource" />
        </div>
    </AuthenticatedLayout>
</template>
