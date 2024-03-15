<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { Link } from '@inertiajs/vue3';
import SubMenuUser from "@/Components/SubMenuUser.vue";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({})
    }
})
</script>
<template>
    <Head title="Users" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Users</h2>
        </template>
        <div class="flex flex-col mx-auto px-4 mt-4">
            <SubMenuUser />
            <table class="w-full">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Register</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user,idx) in resource.data" :key="idx">
                    <td>{{user.id}}</td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.role}}</td>
                    <td class="text-center">{{user.created_at}}</td>
                    <td class="col-actions">
                        <Link v-if="user.has_profile" class="button-actions text-sky-900 bg-sky-300">Profile</Link>
                        <Link class="button-actions">Role</Link>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="6">
                        <Paginator :data="resource" />
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
