<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import Paginator from "@/Components/Paginator.vue";
import DashboardButton from "@/Components/DashboardButton.vue";
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
    form.get(route("labor-rates.index"), { preserveState: true });
}, 200);
</script>
<template>
    <Head title="Labor Rates" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Labor Rates</h2>
            <DashboardButton />
        </template>
        <div class="flex flex-col justify-items-center items-center">
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
                    <Link :href="route('labor-rates.create')" class="btn-goto"
                        >New Rate
                    </Link>
                </form>
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Code</th>
                            <th>RateableS</th>
                            <th>Name</th>
                            <th>Mount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, idx) in resource.data" :key="idx">
                            <td>{{ item.id }}</td>
                            <td>{{ item.code }}</td>
                            <td class="uppercase">{{ item.rateable.name }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.mount }}</td>
                            <td class="col-actions">
                                <Link
                                    :href="route('labor-rates.edit', item.id)"
                                    class="btn-edit"
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
