<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import {Link} from '@inertiajs/vue3';
import {route} from "ziggy-js";
import _ from 'lodash';

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({})
    }
})

const form = useForm({
    search: ''
})
const fireSearch = _.throttle(function () {
    form.get(route('users.index'), {preserveState: true})
}, 200);

const destroy = (id) => {
    if (confirm("Seguro desea eliminar el Usuario")) {
        form.delete(route('users.destroy', id), {preserveState: true});
    }
}

</script>
<template>
    <Head title="Users"/>
    <AuthenticatedLayout>
        <template #header>
            <h2>Users</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center">
            <div class="my-4 border rounded-md px-4 py-4">
                <form class="my-2 flex flex-row justify-items-center items-center space-x-7">
                    <div>
                        <input
                            type="text"
                            class="px-2 py-1 rounded-md border-gray-300"
                            name="search"
                            id="search"
                            placeholder="search"
                            @keyup="fireSearch"
                            v-model="form.search"
                        >
                    </div>
                    <Link :href="route('users.create')"
                        class="b-goto"
                    >
                        New User
                    </Link>
                    <Link :href="route('roles.index')"
                        class="b-goto"
                    >
                        Roles
                    </Link>
                    <Link
                        class="b-goto"
                    >
                        Permissions
                    </Link>
                </form>
                <table class="table-auto">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Avatar</th>
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
                        <td>
                            <div class="w-12 h-12 rounded-full overflow-hidden">
                                <img v-if="user.avatar" :src="user.avatar" alt="Avatar" class="w-full h-full object-cover" />
                            </div>
                        </td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.role }}</td>
                        <td class="text-center">{{ user.created_at }}</td>
                        <td class="col-actions">
                            <Link :href="route('users.show', user.id)" class="b-edit">Edit</Link>
                            <Link v-if="user.id !== 1" @click="destroy(user.id)" class="b-delete">Delete</Link>
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
