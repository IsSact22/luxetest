<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import Paginator from "@/Components/Paginator.vue";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
</script>
<template>
    <Head title="Model Aircrafts" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Model Aircrafts</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center py-12">
            <div class="my-4 border rounded-md p-4">
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
                    <Link
                        :href="route('model-aircrafts.create')"
                        class="btn-goto"
                        >New Model Aircraft
                    </Link>
                </form>
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Brand</th>
                            <th>Engine</th>
                            <th>Model Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, idx) in resource.data" :key="idx">
                            <td>{{ item.id }}</td>
                            <td>{{ item.brand_aircraft.name }}</td>
                            <td>{{ item.engine_type.name }}</td>
                            <td>{{ item.name }}</td>
                            <td class="col-actions">
                                <Link
                                    :href="
                                        route('model-aircrafts.edit', item.id)
                                    "
                                    class="btn-goto"
                                >
                                    Edit
                                </Link>
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
