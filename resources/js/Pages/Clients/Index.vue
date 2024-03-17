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
    <Head title="Clients" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Clients</h2>
        </template>
        <div class="flex flex-col mx-auto px-4 mt-4">
            <table class="w-full">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Register</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(client,idx) in resource.data" :key="idx">
                    <td>{{client.id}}</td>
                    <td>{{client.user.name}}</td>
                    <td>{{client.customer_name}}</td>
                    <td>{{client.phone}}</td>
                    <td>{{client.user.email}}</td>
                    <td class="text-center">{{client.user.created_at}}</td>
                    <td class="col-actions">
                        <Link :href="route('clients.show', client.id)" class="b-show">Show</Link>
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
