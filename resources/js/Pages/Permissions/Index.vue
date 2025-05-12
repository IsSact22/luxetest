<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { ref } from "vue";
import { route } from "ziggy-js";
import { useToast } from "vue-toastification";
import Swal from "sweetalert2";
import _ from "lodash";

const toast = useToast();

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});

const formSearch = useForm({
    search: "",
});

const fireSearch = _.throttle(function () {
    formSearch.get(route("permissions.index"), { preserveState: true });
}, 200);

const isModalOpen = ref(false);
const openModal = () => {
    isModalOpen.value = true;
};
const closeModal = () => {
    isModalOpen.value = false;
    editId.value = null;
    form.reset();
};
const form = useForm({
    name: "",
    guard_name: "web",
});

const editId = ref(null);
const handleEdit = (data) => {
    openModal();
    editId.value = data.id;
    form.name = data.name;
    form.guard_name = data.guard_name;
};

const submit = () => {
    if (editId.value) {
        form.submit("patch", route("permissions.update", editId.value), {
            onSuccess: (response) => {
                closeModal();
                toast.success("Updated register", { timeout: 2000 });
            },
            onError: (error) => {
                toast.error("An error occurred while updating the record.");
                console.error(error);
            },
        });
    } else {
        form.post(route("permissions.store"), {
            onSuccess: (response) => {
                closeModal();
                toast.success("Created register", { timeout: 2000 });
            },
            onError: (error) => {
                toast.error("An error occurred while creating the record.");
                console.error(error);
            },
        });
    }
};
const handleDelete = (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("permissions.destroy", id), {
                onSuccess: (response) => {
                    toast.success("deleted register", { timeout: 2000 });
                    form.reset();
                },
                onError: (err) => {
                    toast.error(
                        "An error occurred while deleting the record.",
                        { timeout: 2000 },
                    );
                },
            });
        }
    });
};
</script>
<template>
    <Head title="Permisos" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Permisos</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center py-2">
            <!-- Modal -->
            <transition name="modal-transition">
                <div
                    v-if="isModalOpen"
                    class="fixed inset-0 z-50 flex items-center justify-center"
                >
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    <div class="relative bg-white rounded-lg p-8 sm:p-10">
                        <h2 class="my-2">Editar Permiso</h2>
                        <!-- Formulario -->
                        <form class="px-4" @submit.prevent="submit">
                            <!-- Contenido del formulario -->
                            <div class="sm:col-span-4">
                                <label
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    for="name"
                                    >Nombre</label
                                >
                                <input
                                    id="name"
                                    v-model="form.name"
                                    autocomplete="off"
                                    name="name"
                                    placeholder="Permission Name"
                                    type="text"
                                />
                                <div v-if="form.errors.name">
                                    {{ form.errors.name }}
                                </div>
                            </div>
                            <div class="sm:col-span-4">
                                <label
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    for="guard_name"
                                    >Nombre de Guardia</label
                                >
                                <input
                                    id="guard_name"
                                    v-model="form.guard_name"
                                    name="guard_name"
                                    placeholder="Guard Name"
                                    readonly
                                    type="text"
                                />
                                <div v-if="form.errors.guard_name">
                                    {{ form.errors.guard_name }}
                                </div>
                            </div>
                            <div class="flex justify-end mt-6">
                                <button
                                    :disabled="form.processing"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded"
                                    type="submit"
                                >
                                    Guardar
                                </button>
                                <button
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded ml-2"
                                    type="button"
                                    @click="closeModal"
                                >
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </transition>

            <form
                class="mt-2 mb-7 flex flex-row justify-items-center items-center space-x-7"
            >
                <div>
                    <input
                        id="search"
                        v-model="formSearch.search"
                        class="px-2 py-1 rounded-md border-gray-300"
                        name="search"
                        placeholder="buscar"
                        type="text"
                        @keyup="fireSearch"
                    />
                </div>
                <!--                <button class="btn-primary" type="button">Nuevo Permiso</button>-->
                <Link :href="route('users.index')" class="btn-primary">
                    Usuarios
                </Link>
                <Link :href="route('roles.index')" class="btn-primary">
                    Roles
                </Link>
            </form>

            <table class="table-fixed">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Nombre de Guardia</th>
                        <th>Creado el</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(permission, idx) in resource.data" :key="idx">
                        <td>{{ permission.id }}</td>
                        <td>{{ permission.name }}</td>
                        <td>{{ permission.guard_name }}</td>
                        <td class="text-center">{{ permission.created_at }}</td>
                        <td class="col-actions">
                            <button
                                class="btn-edit"
                                @click="handleEdit(permission)"
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
                            <!--                            <button
                                                        class="btn-delete"
                                                        @click="handleDelete(permission.id)"
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
                                                    </button>-->
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

<style>
.modal-transition-enter-active,
.modal-transition-leave-active {
    transition: opacity 0.3s;
}

.modal-transition-enter-from,
.modal-transition-leave-to {
    opacity: 0;
}
</style>
