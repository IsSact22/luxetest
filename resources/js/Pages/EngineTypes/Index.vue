<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
</script>
<template>
    <Head title="Engine Types" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Engine Types</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center">
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
                    <Link class="btn-goto">New Engine Type</Link>
                </form>
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, idx) in resource.data" :key="idx">
                            <td>{{ item.id }}</td>
                            <td>{{ item.name }}</td>
                            <td class="col-actions">
                                <Link
                                    :href="route('engine-types.edit', item.id)"
                                    class="btn-goto"
                                >
                                    Edit
                                </Link>
                                <Link class="btn-delete">Delete</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
