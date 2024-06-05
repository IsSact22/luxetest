<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { route } from "ziggy-js";
import { onMounted, ref } from "vue";
import { useToast } from "vue-toastification";
import DashboardButton from "@/Components/DashboardButton.vue";
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
            <DashboardButton />
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
                    <Link :href="route('aircrafts.create')" class="btn-goto">
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
                                    Edit
                                </Link>
                                <button
                                    v-if="item.owner.length === 0"
                                    class="btn-edit"
                                    @click="openModal(item.id)"
                                >
                                    set Owner
                                </button>

                                <Link class="btn-delete"> Delete</Link>
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
