<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import _ from "lodash";

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
                    @submit.prevent
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
                    <Link
                        :href="route('engine-types.create')"
                        class="btn-primary"
                        >New Engine Type
                    </Link>
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
                            <td class="uppercase">{{ item.name }}</td>
                            <td class="col-actions">
                                <Link
                                    :href="route('engine-types.edit', item.id)"
                                    class="btn-edit"
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
