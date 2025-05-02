<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { route } from "ziggy-js";
import _ from "lodash";
import { useToast } from "vue-toastification";
import { ref } from "vue";
import ConfirmDialog from "@/Components/ConfirmDialog.vue";

const confirmDialog = ref(null);
const selectedId = ref(null);

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
const toast = useToast();
const form = useForm({
    search: "",
});
const fireSearch = _.throttle(function () {
    form.get(route("users.index"), { preserveState: true });
}, 200);

const handleAction = () => {
    if (selectedId.value) {
        form.delete(route("users.destroy", selectedId.value), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                selectedId.value = null;
                toast.success("Usuario eliminado!");
            },
        });
    }
};

const showConfirmation = (id) => {
    selectedId.value = id;
    confirmDialog.value.show();
};

const destroy = (id) => {
    showConfirmation(id);
};
const restore = (id) => {
    router.post(
        route("users.restore", id),
        {},
        {
            preserveState: true,
            onSuccess: () => {
                toast.success("Usuario restaurado");
            },
        },
    );
};
</script>
<template>
    <Head title="Usuarios" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Usuarios</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center py-2">
            <div class="mt-4 px-4 py-4 bg-white">
                <form
                    class="mt-2 mb-7 flex flex-row justify-items-center items-center space-x-7"
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
                    <Link :href="route('users.create')" class="btn-primary">
                        {{ $t("New User") }}
                    </Link>
                    <Link :href="route('roles.index')" class="btn-primary">
                        {{ $t("Roles") }}
                    </Link>
                    <Link
                        :href="route('permissions.index')"
                        class="btn-primary"
                    >
                        {{ $t("Permissions") }}
                    </Link>
                </form>
                <table class="table-fixed">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Avatar</th>
                            <th>{{ $t("Name") }}</th>
                            <th>{{ $t("Email") }}</th>
                            <th>{{ $t("Role") }}</th>
                            <th>{{ $t("Member since") }}</th>
                            <th>{{ $t("Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(user, idx) in resource.data"
                            :key="idx"
                            :class="{ 'text-gray-400': user.deleted_at }"
                        >
                            <td>{{ user.id }}</td>
                            <td>
                                <div
                                    class="w-12 h-12 rounded-full overflow-hidden"
                                >
                                    <img
                                        v-if="user.avatar"
                                        :src="user.avatar"
                                        alt="Avatar"
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                            </td>
                            <td>{{ user.name }}</td>
                            <td class="px-2">{{ user.email }}</td>
                            <td>{{ $t(`${user.role}`) }}</td>
                            <td class="text-center">{{ user.created_at }}</td>
                            <td class="col-actions">
                                <Link
                                    v-if="user.id !== 1 && !user.deleted_at"
                                    :href="route('users.show', user.id)"
                                    class="btn-edit"
                                    ><span>
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
                                </Link>
                                <Link
                                    v-if="user.id !== 1 && !user.deleted_at"
                                    class="btn-delete"
                                    @click="destroy(user.id)"
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
                                </Link>
                                <Link
                                    v-if="user.id !== 1 && user.deleted_at"
                                    class="btn-edit"
                                    @click="restore(user.id)"
                                >
                                    <span>
                                        <svg
                                            class="size-5 stroke-green-700"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </span>
                                </Link>
                                <span
                                    v-if="user.id === 1"
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
        <!-- Componente de Confirmación -->
        <ConfirmDialog
            ref="confirmDialog"
            :onConfirm="handleAction"
            button-confirm-style="text-red-800 font-semibold bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600"
            cancelText="No, cancelar"
            confirmText="Sí, eliminar"
            message="¿Estás seguro de que deseas eliminar este usuario?"
            title="Confirma tu acción"
        />
    </AuthenticatedLayout>
</template>
