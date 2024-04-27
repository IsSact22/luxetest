<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Link} from '@inertiajs/vue3';
import {route} from "ziggy-js";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    }
})
</script>
<template>
    <Head title="Camo Rates"/>
    <AuthenticatedLayout>
        <template #header>
            <h2>Camo Rates</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center">
            <div class="my-4 border rounded-md p-4">
                <form class="my-2 flex flex-row justify-items-center items-center space-x-7">
                    <div>
                        <input
                            type="text"
                            class="px-2 py-1 rounded-md border-gray-300"
                            placeholder="search"
                            name="search"
                            id="search"
                        >
                    </div>
                    <Link class="btn-goto">New Rate</Link>
                </form>
                <table class="table-auto">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Code</th>
                        <th>Engine</th>
                        <th>Name</th>
                        <th>Mount</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, idx) in resource.data" :key="idx">
                        <td>{{item.id}}</td>
                        <td>{{item.code}}</td>
                        <td class="uppercase">{{item.engine_type.name}}</td>
                        <td>{{item.name}}</td>
                        <td>{{item.mount}}</td>
                        <td class="col-actions">
                            <Link
                                :href="route('camo-rates.edit', item.id)"
                                class="btn-goto">
                                Edit
                            </Link>
                            <Link class="btn-goto">Show</Link>
                            <Link class="btn-delete">Delete</Link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
