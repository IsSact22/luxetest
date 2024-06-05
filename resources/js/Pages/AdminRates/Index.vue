<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Paginator from "@/Components/Paginator.vue";
import DashboardButton from "@/Components/DashboardButton.vue";
import AdminRateForm from "@/Pages/AdminRates/Partials/AdminRateForm.vue";
import { ref } from "vue";
import _ from "lodash";
import { route } from "ziggy-js";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
const showForm = ref(false);
const selectedRate = ref(null);
const setSelectedRate = (item) => {
    selectedRate.value = item;
    toggleShowForm();
};
const toggleShowForm = () => {
    if (selectedRate.value && showForm.value === true) {
        selectedRate.value = null;
    }
    showForm.value = !showForm.value;
};
const form = useForm({
    search: "",
});
const fireSearch = _.throttle(function () {
    form.get(route("admin-rates.index"), { preserveState: true });
}, 200);
</script>

<template>
    <Head title="Admin Rates" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Admin Rates</h2>
            <DashboardButton />
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <AdminRateForm
                v-if="selectedRate"
                :admin-rate="selectedRate"
                @show-form="toggleShowForm"
            />

            <AdminRateForm
                v-if="showForm && !selectedRate"
                :admin-rate="selectedRate"
                @show-form="toggleShowForm"
            />

            <div v-if="!showForm" class="my-4 border rounded-md p-4">
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
                    <button
                        class="btn-goto"
                        type="button"
                        @click="toggleShowForm"
                    >
                        New Rate
                    </button>
                </form>
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, idx) in resource.data" :key="idx">
                            <td>{{ item.id }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.description }}</td>
                            <td class="col-actions">
                                <button
                                    class="btn-edit"
                                    type="button"
                                    @click="setSelectedRate(item)"
                                >
                                    Edit
                                </button>
                                <Link class="btn-delete">Delete</Link>
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

<style scoped></style>
