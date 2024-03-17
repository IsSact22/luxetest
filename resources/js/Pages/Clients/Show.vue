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
    <Head title="Clients" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Clients</h2>
        </template>
        <div class="flex flex-col px-4">
            <div class="flex flex-row justify-items-center items-center mx-auto space-x-7 px-4 py-4 my-4 rounded-md border">
                <div class="flex flex-col">
                    <span class="font-bold text-neutral-500">Id</span>
                    <span>{{resource.data.id}}</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-neutral-500">Name</span>
                    <span>{{resource.data.user.name}}</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-neutral-500">Customer Name</span>
                    <span>{{resource.data.customer_name}}</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-neutral-500">Email</span>
                    <span>{{resource.data.user.email}}</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-neutral-500">Phone</span>
                    <span>{{resource.data.phone}}</span>
                </div>
            </div>
            <div>
                <table>
                    <caption>Project List</caption>
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Aircraft</th>
                        <th>Start Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(project, idx) in resource.data.projects" :key="idx">
                        <td>{{project.id}}</td>
                        <td>{{project.date}}</td>
                        <td>{{project.name}}</td>
                        <td>{{project.aircraft.name}}</td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
