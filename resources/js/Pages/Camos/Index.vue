<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { route } from "ziggy-js";
import _ from "lodash";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import { useToast } from "vue-toastification";

const toast = useToast();
const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});

const camoSelected = ref(null);
const url = ref("");
const formCamo = useForm({
    description: "",
});

const showModal = ref(false);
const openModal = (camo) => {
    camoSelected.value = camo;
    formCamo.description = camo.description;
    url.value = route("camos.update", camo?.id);
    showModal.value = true;
};
const closeModal = () => {
    showModal.value = false;
};

const form = useForm({
    search: "",
});
const fireSearch = _.throttle(function () {
    form.get(route("camos.index"), { preserveState: true });
}, 200);

const destroy = (id) => {
    if (confirm("Seguro desea eliminar el Usuario")) {
        form.delete(route("camos.destroy", id), { preserveState: true });
    }
};

const submitUpdate = () => {
    console.log("update");
    console.log(url.value);
    formCamo.patch(url.value, {
        onSuccess: () => {
            closeModal();
            toast.success("Camo actualizado");
        },
        onError: () => {
            toast.error("Imposible actualizar");
        },
    });
};
</script>
<template>
    <Head title="Camos" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Camos</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center">
            <div v-if="props.resource.data.length > 0" class="my-4 px-4 py-4">
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
                    <Link
                        v-if="$page.props.auth.user.is_cam"
                        :href="route('camos.create')"
                        class="btn-primary"
                        >Nuevo CAMO
                    </Link>
                </form>

                <!-- Modal -->
                <Modal
                    :show="showModal"
                    closeable
                    maxWidth="xl"
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
                            <div
                                class="w-full flex flex-col justify-items-center items-center"
                            >
                                <svg
                                    class="size-14 fill-yellow-500"
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
                                <span>Editar CAMO</span>
                            </h2>
                            <!-- form -->
                            <form @submit.prevent="submitUpdate">
                                <div class="mb-3">
                                    <label
                                        class="block text-sm font-medium text-gray-500"
                                        for="cam"
                                        >CAM</label
                                    >
                                    <p
                                        class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-md"
                                    >
                                        {{ camoSelected.cam }}
                                    </p>
                                </div>

                                <div
                                    class="flex flex-row justify-items-center items-center space-x-3"
                                >
                                    <div class="w-full mb-3">
                                        <label
                                            class="block text-sm font-medium text-gray-500"
                                            for="customer"
                                            >Cliente</label
                                        >
                                        <p
                                            class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-md"
                                        >
                                            {{ camoSelected.customer }}
                                        </p>
                                    </div>

                                    <div class="w-full mb-3">
                                        <label
                                            class="block text-sm font-medium text-gray-500"
                                            for="owner"
                                            >Propietario</label
                                        >
                                        <p
                                            class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-md"
                                        >
                                            {{ camoSelected.owner }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="flex flex-row justify-items-center items-center space-x-3"
                                >
                                    <div class="w-full mb-3">
                                        <label
                                            class="block text-sm font-medium text-gray-500"
                                            for="brand_aircraft"
                                            >Fabricante/Avión</label
                                        >
                                        <p
                                            class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-md"
                                        >
                                            {{
                                                camoSelected.aircraft
                                                    .model_aircraft
                                                    .brand_aircraft.name
                                            }}
                                        </p>
                                    </div>

                                    <div class="w-full mb-3">
                                        <label
                                            class="block text-sm font-medium text-gray-500"
                                            for="model_aircraft"
                                            >Modelo/Avión</label
                                        >
                                        <p
                                            class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-md"
                                        >
                                            {{
                                                camoSelected.aircraft
                                                    .model_aircraft.name
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label
                                        class="block text-sm font-medium text-gray-500"
                                        for="description"
                                        >Descripción</label
                                    >
                                    <textarea
                                        id="description"
                                        v-model="formCamo.description"
                                        class="w-full border border-gray-300 rounded-md"
                                        cols="30"
                                        name="description"
                                        rows="3"
                                    ></textarea>
                                </div>
                                <div
                                    class="flex flex-row-reverse justify-items-center items-center space-x-3"
                                >
                                    <button
                                        class="btn-submit"
                                        type="button"
                                        @click="submitUpdate"
                                    >
                                        Guardar
                                    </button>
                                </div>
                            </form>
                            <!-- form -->
                        </div>
                    </template>
                </Modal>
                <!-- Modal -->

                <table class="table-auto">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>{{ $t("Customer") }}</th>
                            <th>{{ $t("Owner") }}</th>
                            <th>{{ $t("Contract") }}</th>
                            <th>{{ $t("Cam") }}</th>
                            <th>{{ $t("Airplane") }}</th>
                            <th>{{ $t("Activities") }}</th>
                            <th>{{ $t("Start Date") }}</th>
                            <th>{{ $t("Estimate Finish Date") }}</th>
                            <th>{{ $t("Finish Date") }}</th>
                            <th>{{ $t("Location") }}</th>
                            <th>{{ $t("Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(camo, idx) in resource.data" :key="idx">
                            <td>{{ camo.id }}</td>
                            <td class="uppercase">{{ camo.customer }}</td>
                            <td>{{ camo.owner }}</td>
                            <td>{{ camo.contract }}</td>
                            <td>{{ camo.cam }}</td>
                            <td>
                                {{ camo.aircraft.model_aircraft.name }} /
                                {{ camo.aircraft.register }}
                            </td>
                            <td class="text-center">
                                {{ camo.activities.length }}
                            </td>
                            <td class="text-center">{{ camo.start_date }}</td>
                            <td class="text-center">
                                {{ camo.estimate_finish_date }}
                            </td>
                            <td class="text-center">{{ camo.finish_date }}</td>
                            <td class="text-center">{{ camo.location }}</td>
                            <td
                                class="flex flex-row justify-items-center items-center space-x-3"
                            >
                                <Link
                                    :href="route('camos.show', camo.id)"
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
                                    class="btn-edit"
                                    @click="openModal(camo)"
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
            <div v-else class="my-12">
                <h1 class="text-center text-2xl my-2">
                    Aun no tiene registros para listar
                </h1>
                <p class="text-center">
                    ¿Quieres crear tu primer
                    <a :href="route('camos.create')">
                        <span class="text-blue-700">CAMO?</span>
                    </a>
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
