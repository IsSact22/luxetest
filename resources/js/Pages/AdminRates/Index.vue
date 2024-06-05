<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import Paginator from "@/Components/Paginator.vue";
import DashboardButton from "@/Components/DashboardButton.vue";
import AdminRateForm from "@/Pages/AdminRates/Partials/AdminRateForm.vue";
import { ref } from "vue";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
const showForm = ref(false);
const selectedRate = ref(null);
const setSelectedRate = (item) => {
    console.log("selectedRate", item);
    selectedRate.value = item;
    console.log("selectedRate", selectedRate.value);
    toggleShowForm();
};
const toggleShowForm = () => {
    if (selectedRate.value && showForm.value === true) {
        selectedRate.value = null;
    }
    showForm.value = !showForm.value;
};
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
                            class="px-2 py-1 rounded-md border-gray-300"
                            name="search"
                            placeholder="search"
                            type="text"
                        />
                    </div>
                    <button
                        class="btn-goto"
                        type="button"
                        @click="toggleShowForm"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M12 4.5v15m7.5-7.5h-15"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
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
                                    class="btn-goto"
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
