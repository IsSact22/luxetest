<script setup>
import {Head, useForm, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { Link } from '@inertiajs/vue3';
import {ref} from "vue";
import { useToast } from "vue-toastification";
import {route} from "ziggy-js";
import SubMenuUser from "@/Components/SubMenuUser.vue";
import Swal from 'sweetalert2'

const toast = useToast();

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({})
    }
})
const isModalOpen = ref(false);
const openModal = () => {
    isModalOpen.value = true;
}
const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
}
const form = useForm({
    name: '',
    guard_name: ''
})
const editId = ref(null);
const handleEdit = (data) => {
    openModal();
    editId.value = data.id
    form.name = data.name
    form.guard_name = data.guard_name
}
const submit = () => {
    form.submit('patch', route('permissions.update', editId.value), {
        onSuccess: (response) => {
            closeModal();
            toast.success('Updated register', { timeout: 2000 });
        },
        onError: (error) => {
            toast.error('An error occurred while updating the record.')
            console.error(error);
        }
    });
}
const handleDelete = (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('permissions.destroy', id), {
                onSuccess: (response) => {
                    toast.success('deleted register', { timeout: 2000 });
                    form.reset();
                },
                onError: (err) => {
                    toast.error('An error occurred while deleting the record.', { timeout: 2000 });
                }
            });
        }
    });
}
</script>
<template>
    <Head title="Users" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Users</h2>
        </template>
        <div class="flex flex-col mx-auto px-4 mt-4">
            <SubMenuUser @new-permission="openModal" />
            <!-- Modal -->
            <transition name="modal-transition">
                <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    <div class="relative bg-white rounded-lg p-8 sm:p-10">
                        <h2 class="my-2">Edit Permission</h2>
                        <!-- Formulario -->
                        <form @submit.prevent="submit" class="px-4">
                            <!-- Contenido del formulario -->
                            <div class="sm:col-span-4">
                                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                                <input type="text" name="name" id="name" placeholder="Permission Name" v-model="form.name" autocomplete="off">
                                <div v-if="form.errors.name">{{ form.errors.name }}</div>
                            </div>
                            <div class="sm:col-span-4">
                                <label for="guard_name" class="block text-sm font-medium leading-6 text-gray-900">Guard-Name</label>
                                <input type="text" name="guard_name" id="guard_name" placeholder="Guard Name" v-model="form.guard_name">
                                <div v-if="form.errors.guard_name">{{ form.errors.guard_name }}</div>
                            </div>
                            <div class="flex justify-end mt-6">
                                <button
                                    :disabled="form.processing"
                                    type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Guardar
                                </button>
                                <button @click="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded ml-2">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </transition>

            <table class="w-full">
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
                <tr v-for="(permission,idx) in resource.data" :key="idx">
                    <td>{{permission.id}}</td>
                    <td>{{permission.name}}</td>
                    <td>{{permission.guard_name}}</td>
                    <td class="text-center">{{permission.created_at}}</td>
                    <td class="col-actions">
                        <button class="b-edit" @click="handleEdit(permission)">Edit</button>
                        <button class="b-delete" @click="handleDelete(permission.id)">Delete</button>
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

