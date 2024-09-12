<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { ref } from "vue";
import { route } from "ziggy-js";
import { useToast } from "vue-toastification";
import Swal from "sweetalert2";

const toast = useToast();

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
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
    <Head title="Permissions" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Permissions</h2>
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
                        <h2 class="my-2">Edit Permission</h2>
                        <!-- Formulario -->
                        <form class="px-4" @submit.prevent="submit">
                            <!-- Contenido del formulario -->
                            <div class="sm:col-span-4">
                                <label
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    for="name"
                                    >Name</label
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
                                    >Guard-Name</label
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
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
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
                        v-model="form.search"
                        class="px-2 py-1 rounded-md border-gray-300"
                        name="search"
                        placeholder="search"
                        type="text"
                        @keyup="fireSearch"
                    />
                </div>
                <button class="btn-primary" type="button">
                    New Permission
                </button>
                <Link :href="route('users.index')" class="btn-primary">
                    Users
                </Link>
                <Link :href="route('roles.index')" class="btn-primary">
                    Roles
                </Link>
            </form>

            <table class="table-auto">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Guard Name</th>
                        <th>Created at</th>
                        <th>Actions</th>
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
                                Edit
                            </button>
                            <button
                                class="btn-delete"
                                @click="handleDelete(permission.id)"
                            >
                                Delete
                            </button>
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
