<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { route } from "ziggy-js";
import { onMounted, ref } from "vue";
import { useToast } from "vue-toastification";
import _ from "lodash";
import Modal from "@/Components/Modal.vue";
import AircraftForm from "@/Pages/Aircrafts/Partials/AircraftForm.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const toast = useToast();
const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
const owners = ref(null);
const getOwners = async () => {
    try {
        const response = await axios.get(route("owners.select"));
        owners.value = response.data.owners;
    } catch (e) {
        console.error(e);
    }
};
onMounted(getOwners);
const showModalOwner = ref(false);

const ownerForm = useForm({
    owner_id: null,
    aircraft_id: null,
});
const openModalOwner = (id) => {
    showModalOwner.value = true;
    ownerForm.aircraft_id = id;
};
const closeModalOwner = () => {
    showModalOwner.value = false;
    ownerForm.reset();
};

const submit = async () => {
    try {
        const response = await axios.post(
            route("set-owner-aircraft"),
            ownerForm,
        );
        if (response.status === 201) {
            toast.success(response.data.message);
            closeModalOwner();
            router.get(route("aircrafts.index"));
        }
    } catch (e) {
        toast.error(e.response.data.message);
        console.error(e);
    }
};
const form = useForm({
    search: "",
});
const fireSearch = _.throttle(function () {
    form.get(route("aircrafts.index"), { preserveState: true });
}, 200);

const destroy = (id) => {
    if (confirm("Seguro desea eliminar el registro")) {
        form.delete(route("aircrafts.destroy", id), {
            preserveState: true,
        });
    }
};

const showModal = ref(false);
const openModal = () => {
    showModal.value = true;
};

const selected = ref(null);
const handleSelected = (object) => {
    selected.value = object;
    openModal();
};

const closeModal = () => {
    selected.value = null;
    showModal.value = false;
};
</script>
<template>
    <Head title="Avión" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Avión</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="my-4 p-4">
                <form
                    class="my-2 flex flex-row justify-items-center items-center space-x-7"
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
                    <button
                        class="btn-primary"
                        type="button"
                        @click="openModal"
                    >
                        <span class="ml-1">{{ $t("New Plane") }}</span>
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
                            <!--                            <button
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
                                                        </button>-->
                        </div>
                        <div class="p-4">
                            <div
                                v-if="!selected"
                                class="w-full flex flex-col justify-items-center items-center"
                            >
                                <svg
                                    class="fill-yellow-500"
                                    fill="none"
                                    height="72"
                                    viewBox="0 0 61 72"
                                    width="61"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <g id="Iconos/ creacion de nuevo aircraft">
                                        <path
                                            id="Vector"
                                            d="M42.875 48C42.875 48.7957 42.5589 49.5587 41.9963 50.1213C41.4337 50.684 40.6706 51 39.875 51H33.875V57C33.875 57.7957 33.5589 58.5587 32.9963 59.1213C32.4337 59.6839 31.6706 60 30.875 60C30.0793 60 29.3163 59.6839 28.7537 59.1213C28.191 58.5587 27.875 57.7957 27.875 57V51H21.875C21.0793 51 20.3163 50.684 19.7537 50.1213C19.1911 49.5587 18.875 48.7957 18.875 48C18.875 47.2044 19.1911 46.4413 19.7537 45.8787C20.3163 45.3161 21.0793 45 21.875 45H27.875V39C27.875 38.2044 28.191 37.4413 28.7537 36.8787C29.3163 36.3161 30.0793 36 30.875 36C31.6706 36 32.4337 36.3161 32.9963 36.8787C33.5589 37.4413 33.875 38.2044 33.875 39V45H39.875C40.6706 45 41.4337 45.3161 41.9963 45.8787C42.5589 46.4413 42.875 47.2044 42.875 48ZM60.8749 31.455V57C60.8702 60.9768 59.2883 64.7893 56.4763 67.6013C53.6643 70.4134 49.8517 71.9952 45.875 72H15.875C11.8982 71.9952 8.08567 70.4134 5.27366 67.6013C2.46164 64.7893 0.879764 60.9768 0.875 57V15.0001C0.879764 11.0233 2.46164 7.21074 5.27366 4.39872C8.08567 1.58671 11.8982 0.00483271 15.875 6.9146e-05H29.42C32.1788 -0.00703162 34.9117 0.532832 37.4606 1.58845C40.0096 2.64406 42.3239 4.19448 44.27 6.15006L54.7219 16.6081C56.6787 18.5528 58.23 20.8664 59.2862 23.415C60.3424 25.9635 60.8824 28.6963 60.8749 31.455ZM40.028 10.3921C39.0838 9.47754 38.0238 8.69083 36.875 8.05206V21C36.875 21.7957 37.191 22.5588 37.7536 23.1214C38.3163 23.684 39.0793 24 39.875 24H52.823C52.1838 22.8516 51.396 21.7925 50.48 20.85L40.028 10.3921ZM54.8749 31.455C54.8749 30.96 54.7789 30.486 54.7339 30H39.875C37.488 30 35.1988 29.0518 33.511 27.364C31.8232 25.6762 30.875 23.387 30.875 21V6.14106C30.389 6.09606 29.912 6.00006 29.42 6.00006H15.875C13.488 6.00006 11.1989 6.94827 9.51103 8.6361C7.8232 10.3239 6.87499 12.6131 6.87499 15.0001V57C6.87499 59.387 7.8232 61.6761 9.51103 63.364C11.1989 65.0518 13.488 66 15.875 66H45.875C48.2619 66 50.5511 65.0518 52.2389 63.364C53.9267 61.6761 54.8749 59.387 54.8749 57V31.455Z"
                                            opacity="0.72"
                                        />
                                    </g>
                                </svg>
                            </div>

                            <h2
                                class="text-2xl font-bold w-full text-center mt-12"
                            >
                                <span v-if="selected">Editar Avión</span>
                                <span v-else>Nuevo Avión</span>
                            </h2>
                            <AircraftForm :aircraft="selected" />
                        </div>
                    </template>
                </Modal>
                <!-- Modal -->

                <table class="table-fixed">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>{{ $t("Aircraft Model") }}</th>
                            <th>{{ $t("Registration") }}</th>
                            <th>{{ $t("Owner") }}</th>
                            <th>{{ $t("Engine Type") }}</th>
                            <th>{{ $t("Serial") }}</th>
                            <th>{{ $t("Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, idx) in resource.data" :key="idx">
                            <td>{{ item.id }}</td>
                            <td class="uppercase">
                                {{ item.model_aircraft.name }}
                            </td>
                            <td class="uppercase">{{ item.register }}</td>
                            <td>
                                <span v-if="item.owner.length > 0">{{
                                    item.owner[0].name
                                }}</span>
                            </td>
                            <td class="uppercase">
                                {{ item.engine_type.name }}
                            </td>
                            <td class="uppercase">{{ item.serial }}</td>
                            <td class="col-actions">
                                <button
                                    class="btn-edit"
                                    type="button"
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
                                    v-if="item.owner.length === 0"
                                    class="btn-edit"
                                    title="Set Owner"
                                    @click="openModalOwner(item.id)"
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
                                                d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </span>
                                </button>

                                <Link
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
                                </Link>
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
            <!-- Modal -->
            <div
                v-if="showModalOwner"
                class="fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full"
            >
                <div
                    class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md py-7"
                >
                    <!--                    <div class="flex justify-end p-2">
                                                                    <button
                                                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                                                        type="button"
                                                                        @click="closeModalOwner"
                                                                    >
                                                                        <svg
                                                                            class="w-5 h-5"
                                                                            fill="currentColor"
                                                                            viewBox="0 0 20 20"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                        >
                                                                            <path
                                                                                clip-rule="evenodd"
                                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                                fill-rule="evenodd"
                                                                            ></path>
                                                                        </svg>
                                                                    </button>
                                        </div>-->

                    <div class="px-4 py-7 pt-0 text-center">
                        <svg
                            class="w-20 h-20 stroke-yellow-500 mx-auto"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                            ></path>
                        </svg>
                        <div class="flex flex-col">
                            <h3
                                class="text-2xl text-gray-800 font-medium mt-5 mb-6"
                            >
                                Asignar propietario.
                            </h3>
                            <form @submit.prevent="submit">
                                <div
                                    class="flex flex-col justify-items-center items-center space-x-1"
                                >
                                    <div class="w-80 mb-12">
                                        <label
                                            class="block leading-loose text-left"
                                            for="owner_id"
                                            >Seleccionar Cliente</label
                                        >
                                        <select
                                            id="owner_id"
                                            v-model="ownerForm.owner_id"
                                            class="w-full rounded-md border-gray-300"
                                            name="owner_id"
                                        >
                                            <option :value="null">
                                                Selección
                                            </option>
                                            <option
                                                v-for="(item, idx) in owners"
                                                :key="idx"
                                                :value="item.id"
                                            >
                                                {{ item.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="flex justify-around">
                            <SecondaryButton
                                class="btn-secondary"
                                type="button"
                                @click="closeModalOwner"
                            >
                                Cancelar
                            </SecondaryButton>
                            <PrimaryButton
                                class="btn-primary"
                                type="button"
                                @click="submit"
                            >
                                Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
        </div>
    </AuthenticatedLayout>
</template>
