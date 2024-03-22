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
    form.get(route('roles.index'), {preserveState: true})
}, 200);

const destroy = (id) => {
    if (confirm("Seguro desea eliminar el Usuario")) {
        form.delete(route('roles.destroy', id), {preserveState: true});
    }
}

</script>
<template>
    <Head title="Roles"/>
    <AuthenticatedLayout>
        <template #header>
            <h2>Roles</h2>
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
                    <Link :href="route('users.index')"
                        class="px-2 py-1 rounded-md border border-neutral-300 hover:bg-neutral-300"
                    >
                        Users
                    </Link>
                    <Link
                        class="px-2 py-1 rounded-md border border-neutral-300 hover:bg-neutral-300"
                    >
                        Permissions
                    </Link>
                </form>
                <div>

                </div>
                <table class="table-auto">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Guard name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(role,idx) in resource.data" :key="idx">
                        <td>{{ role.id }}</td>
                        <td>{{ role.name }}</td>
                        <td class="text-center">{{ role.guard_name }}</td>
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

