<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { Link } from '@inertiajs/vue3';
import {route} from "ziggy-js";
import _ from 'lodash';
import { useToast } from "vue-toastification";
import Swal from 'sweetalert2'

const toast = useToast();

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
    form.get(route('services.index'), {
        preserveState: true
    })
});
const destroy = (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('services.destroy', id), {
                preserveState: true,
                onSuccess: (res) => {
                    toast.success('deleted register', { timeout: 2000 });
                    form.reset();
                },
                onError: (err) => {
                    toast.error('An error occurred while deleting the record.', { timeout: 2000 });
                }
            })
        }
    });
}
</script>
<template>
    <Head title="Services" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Services</h2>
        </template>
        <div class="flex flex-col mx-auto px-4 mt-4">
            <div class="flex flex-row-reverse py-2">
                <div class="basis-1/6">
                    <label class="sr-only" for="table-search">Search</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        v-model="form.search"
                        autocomplete="off"
                        @keyup="fireSearch"
                        placeholder="search">
                </div>
            </div>
            <table class="w-full">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Estimate Time</th>
                    <th>has Material</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(service,idx) in resource.data" :key="idx">
                    <td>{{service.id}}</td>
                    <td>{{service.name}}</td>
                    <td>{{service.estimate_time}}</td>
                    <td>{{service.has_material}}</td>
                    <td class="col-actions">
                        <Link class="b-show">Show</Link>
                        <Link class="b-edit">Edit</Link>
                        <Link class="b-delete" @click.prevent="destroy(service.id)">Delete</Link>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <Paginator :data="resource" />
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
