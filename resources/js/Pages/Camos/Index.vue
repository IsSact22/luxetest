<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import {Link} from '@inertiajs/vue3';
import {route} from "ziggy-js";
import _ from 'lodash';

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({})
    }
})

const form = useForm({
    search: ''
})
const fireSearch = _.throttle(function () {
    form.get(route('camos.index'), {preserveState: true})
}, 200);

const destroy = (id) => {
    if (confirm("Seguro desea eliminar el Usuario")) {
        form.delete(route('camos.destroy', id), {preserveState: true});
    }
}

</script>
<template>
    <Head title="Camos"/>
    <AuthenticatedLayout>
        <template #header>
            <h2>Camos</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center">
            <div class="my-4 border rounded-md px-4 py-4">
                <form class="my-2 flex flex-row justify-items-center items-center space-x-7">
                    <div>
                        <input
                            type="text"
                            class="px-2 py-1 rounded-md border-gray-300"
                            name="search"
                            id="search"
                            placeholder="search"
                            @keyup="fireSearch"
                            v-model="form.search"
                        >
                    </div>
                    <Link class="b-goto">New CAMO</Link>
                </form>
                <div>

                </div>
                <table class="table-auto">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Customer</th>
                        <th>Owner</th>
                        <th>Contract</th>
                        <th>Cam</th>
                        <th>Aircraft</th>
                        <th>Activities</th>
                        <th>Start Date</th>
                        <th>Finish Date</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(camo,idx) in resource.data" :key="idx">
                        <td>{{ camo.id }}</td>
                        <td>{{ camo.customer }}</td>
                        <td>{{ camo.owner }}</td>
                        <td>{{camo.contract}}</td>
                        <td>{{camo.cam}}</td>
                        <td>{{camo.aircraft}}</td>
                        <td class="text-center">{{camo.activities}}</td>
                        <td>{{camo.start_date}}</td>
                        <td>{{camo.finish_date}}</td>
                        <td>{{camo.location}}</td>
                        <td class="col-actions">
                            <Link :href="route('camos.show', camo.id)" class="b-show">Show</Link>
                            <Link class="b-edit">Edit</Link>
                            <Link class="b-delete">Delete</Link>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6">
                            <Paginator :data="resource"/>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

