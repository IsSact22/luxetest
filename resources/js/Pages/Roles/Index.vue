<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { route } from "ziggy-js";
import _ from "lodash";
import RoleForm from "@/Pages/Roles/Partials/RoleForm.vue";
import { ref } from "vue";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
const form = useForm({
    search: "",
});
const fireSearch = _.throttle(function () {
    form.get(route("roles.index"), { preserveState: true });
}, 200);
const destroy = (id) => {
    if (confirm("Seguro desea eliminar el Usuario")) {
        form.delete(route("roles.destroy", id), { preserveState: true });
    }
};
const newRole = ref(false);
const editRole = ref(false);
const roleToEdit = ref(null);
const setRoleToEdit = (obj) => {
    roleToEdit.value = obj;
    editRole.value = true;
};
const toggleNewRole = () => {
    if (editRole.value === true) {
        editRole.value = false;
    }
    newRole.value = !newRole.value;
};
const toggleEditRole = () => {
    if (newRole.value === true) {
        newRole.value = false;
    }
    editRole.value = !editRole.value;
};
</script>
<template>
    <Head title="Roles" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Roles</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="my-4 border rounded-md px-4 py-4">
                <form
                    class="my-2 flex flex-row justify-items-center items-center space-x-7"
                >
                    <div>
                        <input
                            id="search"
                            v-model="form.search"
                            class="px-2 py-1 rounded-md border-gray-300"
                            name="search"
                            placeholder="search"
                            type="text"
                            @keyup="fireSearch"
                        />
                    </div>
                    <button
                        class="btn-goto"
                        type="button"
                        @click="toggleNewRole"
                    >
                        New Role
                    </button>
                    <Link :href="route('users.index')" class="btn-goto">
                        Users
                    </Link>
                    <Link :href="route('permissions.index')" class="btn-goto">
                        Permissions</Link
                    >
                </form>

                <!-- new role -->
                <div v-if="newRole">
                    <RoleForm @cancel-new-role="toggleNewRole" />
                </div>
                <!-- edit role -->
                <div v-if="editRole">
                    <RoleForm
                        :role="roleToEdit"
                        @cancel-edit-role="toggleEditRole"
                    />
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
                        <tr v-for="(role, idx) in resource.data" :key="idx">
                            <td>{{ role.id }}</td>
                            <td>{{ role.name }}</td>
                            <td class="text-center">{{ role.guard_name }}</td>
                            <td class="col-actions">
                                <Link
                                    v-if="role.name !== `super-admin`"
                                    :href="route('roles.show', role.id)"
                                    class="btn-show"
                                    >Show
                                </Link>
                                <button
                                    v-if="role.name !== `super-admin`"
                                    class="btn-edit"
                                    @click="setRoleToEdit(role)"
                                >
                                    Edit
                                </button>
                                <Link
                                    v-if="role.name !== `super-admin`"
                                    class="btn-delete"
                                    >Delete
                                </Link>
                                <span
                                    v-if="role.name === `super-admin`"
                                    class="font-bold text-red-800"
                                    >Actions not allowed</span
                                >
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
        </div>
    </AuthenticatedLayout>
</template>
