<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { route } from "ziggy-js";
import { onMounted, ref } from "vue";
import { useToast } from "vue-toastification";
import _ from "lodash";

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
const showModal = ref(false);

const ownerForm = useForm({
    owner_id: null,
    aircraft_id: null,
});
const openModal = (id) => {
    showModal.value = true;
    ownerForm.aircraft_id = id;
};
const closeModal = () => {
    showModal.value = false;
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
            closeModal();
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
</script>
<template>
    <Head title="Aircrafts" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Aircraft</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="my-4 border rounded-md p-4">
                <form
                    class="my-2 flex flex-row justify-items-center items-center space-x-7"
                >
                    <div>
                        <input
                            id="search"
                            v-model="form.search"
                            class="px-2 py-1 rounded-md border-gray-300 uppercase"
                            name="search"
                            placeholder="search"
                            type="text"
                            @keyup="fireSearch"
                        />
                    </div>
                    <Link :href="route('aircrafts.create')" class="btn-primary">
                        <span class="ml-1">New Aircraft</span>
                    </Link>
                </form>
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Aircraft Model</th>
                            <th>Register</th>
                            <th>Owner</th>
                            <th>Engine Type</th>
                            <th>Serial</th>
                            <th>Actions</th>
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
                                <Link
                                    :href="route('aircrafts.edit', item.id)"
                                    class="btn-edit"
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
                                </Link>
                                <button
                                    v-if="item.owner.length === 0"
                                    class="btn-edit"
                                    title="Set Owner"
                                    @click="openModal(item.id)"
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

                                <Link class="btn-delete">
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
                v-if="showModal"
                class="fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4"
            >
                <div
                    class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md"
                >
                    <div class="flex justify-end p-2">
                        <button
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                            type="button"
                            @click="closeModal"
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
                    </div>

                    <div class="p-6 pt-0 text-center">
                        <svg
                            class="w-20 h-20 text-red-600 mx-auto"
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
                        <div class="flex flex-col mb-4">
                            <h3
                                class="text-xl font-normal text-gray-500 mt-5 mb-6"
                            >
                                Set Aircraft Owner.
                            </h3>
                            <form @submit.prevent="submit">
                                <div
                                    class="flex flex-col justify-items-center items-center space-x-1"
                                >
                                    <label class="block" for="owner_id"
                                        >Owner</label
                                    >
                                    <select
                                        id="owner_id"
                                        v-model="ownerForm.owner_id"
                                        class="rounded-md border-gray-300"
                                        name="owner_id"
                                    >
                                        <option :value="null">Select</option>
                                        <option
                                            v-for="(item, idx) in owners"
                                            :key="idx"
                                            :value="item.id"
                                        >
                                            {{ item.name }}
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <button class="btn-submit mx-4" @click="submit">
                            Save
                        </button>
                        <button class="btn-cancel mx-4" @click="closeModal">
                            No, cancel
                        </button>
                    </div>
                </div>
            </div>
            <!-- Modal -->
        </div>
    </AuthenticatedLayout>
</template>
