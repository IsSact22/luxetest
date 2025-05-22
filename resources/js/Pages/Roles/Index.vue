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
        <div class="flex flex-col justify-items-center items-center py-2">
            <div class="my-4 px-4 py-4">
                <form
                    class="mt-2 mb-7 flex flex-row justify-items-center items-center space-x-7"
                >
                    <div>
                        <input
                            id="search"
                            v-model="form.search"
                            class="px-2 py-1 rounded-md border-gray-300"
                            name="search"
                            placeholder="buscar"
                            type="text"
                            @keyup="fireSearch"
                        />
                    </div>
                    <button
                        class="btn-primary"
                        type="button"
                        @click="toggleNewRole"
                    >
                        Nuevo Rol
                    </button>
                    <Link :href="route('users.index')" class="btn-primary">
                        Usuarios
                    </Link>
                    <Link
                        :href="route('permissions.index')"
                        class="btn-primary"
                    >
                        Permisos
                    </Link>
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
                            <th>{{ $t("Name") }}</th>
                            <th>{{ $t("Guard name") }}</th>
                            <th>{{ $t("Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(role, idx) in resource.data" :key="idx">
                            <td>{{ role.id }}</td>
                            <td>
                                {{ role.name }} (<small>{{
                                    $t(`${role.name}`)
                                }}</small
                                >)
                            </td>
                            <td class="text-center">{{ role.guard_name }}</td>
                            <td class="col-actions">
                                <Link
                                    v-if="role.name !== `super-admin`"
                                    :href="route('roles.show', role.id)"
                                    class="btn-show"
                                >
                                    <span>
                                        <svg
                                            class="size-5 stroke-yellow-700"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </span>
                                </Link>
                                <button
                                    v-if="role.name !== `super-admin`"
                                    class="btn-edit"
                                    @click="setRoleToEdit(role)"
                                >
                                    <span>
                                        <svg
                                            class="size-5 stroke-yellow-700"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </span>
                                </button>
                                <!--                                <Link
                                                                v-if="role.name !== `super-admin`"
                                                                class="btn-delete"
                                                            >
                                                                <span>
                                                                    <svg
                                                                        class="size-5 stroke-red-700"
                                                                        fill="none"
                                                                        stroke="currentColor"
                                                                        stroke-width="1.5"
                                                                        viewBox="0 0 24 24"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                    >
                                                                        <path
                                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                        />
                                                                    </svg>
                                                                </span>
                                                            </Link>-->
                                <span
                                    v-if="role.name === `super-admin`"
                                    class="font-bold text-red-800"
                                    >{{ $t("Actions not allowed") }}</span
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
