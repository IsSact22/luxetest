<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import {Link} from '@inertiajs/vue3';
import SubMenuUser from "@/Components/SubMenuUser.vue";

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
        <div class="flex flex-col justify-items-center items-center">
            <div class="my-4 border rounded-md px-4 py-4">
                <div class="my-2 flex flex-row justify-items-center items-center space-x-7">
                    <div>
                        <select
                            name="filter_by"
                            id="filter_by"
                            class="py-1 rounded-md border-gray-300"
                        >
                            <option :value="null">Filter-by</option>
                        </select>
                    </div>
                    <div>
                        <input
                            type="text"
                            class="px-2 py-1 rounded-md border-gray-300"
                            name="search"
                            id="search"
                            placeholder="search"
                        >
                    </div>
                </div>
                <table class="table-auto">
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
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.role }}</td>
                        <td class="text-center">{{ user.created_at }}</td>
                        <td class="col-actions">
                            <Link class="b-show">Show</Link>
                            <Link class="b-edit">Edit</Link>
                            <Link class="b-delete">Delete</Link>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6">
                            <Paginator :data="resource"/>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
