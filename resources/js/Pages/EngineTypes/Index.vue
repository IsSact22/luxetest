<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import _ from "lodash";
import Paginator from "@/Components/Paginator.vue";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import EngineTypeForm from "@/Pages/EngineTypes/Partials/EngineTypeForm.vue";
import ConfirmDialog from "@/Components/ConfirmDialog.vue";
import { router } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";


const toast = useToast();
const confirmDialog = ref(null);
const selectedId = ref(null);
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
    form.get(route("engine-types.index"), { preserveState: true });
}, 200);

const showModal = ref(false);
const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    selected.value = null;
    showModal.value = false;
};
const destroy = (id) => {
    selectedId.value = id;
    confirmDialog.value.show();
};

const handleAction = async () => {
    if (selectedId.value) {
        try {
            await router.delete(route('engine-types.destroy', selectedId.value), {
                preserveScroll: true,
                preserveState: true,
                only: ['resource']
            });
            toast.success('Tipo de motor eliminado correctamente');
            selectedId.value = null;
        } catch (error) {
            toast.error('Error al eliminar el tipo de motor');
        }
    }
};
const selected = ref(null);
const handleSelected = (object) => {
    selected.value = object;
    openModal();
};
</script>
<template>
    <Head title="Tipo de Motores" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Tipo de Motores</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center">
            <div class="my-4 p-4">
                <form
                    class="mt-2 mb-7 flex flex-row justify-items-center items-center space-x-7"
                    @submit.prevent
                >
                    <div>
                        <input
                            id="search"
                            v-model="form.search"
                            class="px-2 py-1 rounded-md border-gray-300 uppercase"
                            name="search"
                            placeholder="buscar"
                            type="text"
                            @keyup="fireSearch"
                        />
                    </div>
                    <button class="btn-primary" @click="openModal">
                        Nuevo Tipo
                    </button>
                </form>
                <!-- Modal -->
                <Modal
                    :show="showModal"
                    closeable
                    maxWidth="md"
                    @close="closeModal"
                >
                    <template #default>
                        <div class="float-right">
                            <button
                                class="mt-2 mr-2 px-1 py-0.5"
                                @click="closeModal"
                            >
                                <svg
                                    class="size-6 stroke-red-700 hover:fill-red-100"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>
                        </div>
                        <div class="p-4">
                            <h2 class="text-lg font-bold">
                                <span v-if="selected"
                                    >Editar tipo de Motor</span
                                >
                                <span v-else
                                    >Registrar nuevo Tipo de Motor</span
                                >
                            </h2>
                            <EngineTypeForm :engine-type="selected" />
                        </div>
                    </template>
                </Modal>
                <!-- Modal -->
                <table class="table-fixed">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>{{ $t("Name") }}</th>
                            <th>{{ $t("Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, idx) in resource.data" :key="idx">
                            <td>{{ item.id }}</td>
                            <td class="uppercase">{{ item.name }}</td>
                            <td class="col-actions">
                                <button
                                    class="btn-edit"
                                    @click="handleSelected(item)"
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
                                <button
                                    v-if="$page.props.auth.user.is_super"
                                    class="btn-delete"
                                    @click="destroy(item.id)"
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
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">
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
            button-confirm-style="text-yellow-800 font-semibold bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600"
            cancelText="No, cancelar"
            confirmText="Sí, eliminar"
            message="¿Estás seguro de que deseas eliminar este tipo de motor?"
            title="Confirma tu acción"
        />
    </AuthenticatedLayout>
</template>
