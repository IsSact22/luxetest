<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import {Link} from '@inertiajs/vue3';
import {route} from "ziggy-js";
import _ from 'lodash';
import Modal from "@/Components/Modal.vue";
import {ref} from "vue";

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
const showModal = ref(false)
const data = ref(null)
const handleClickTr = (obj) => {
    data.value = obj
    showModal.value = true
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
                <div class="flex flex-row justify-start items-start space-x-7">
                    <div class="flex flex-col px-4">
                        <span><b>Customer:</b> {{resource.data.customer}}</span>
                        <span><b>Contract:</b> {{resource.data.contract}}</span>
                        <span><b>Project Manager:</b> {{resource.data.cam}}</span>
                        <span><b>Location:</b> {{resource.data.location}}</span>
                    </div>
                    <div class="flex flex-col px-4">
                        <span>Project</span>
                        <span><b>Aircraft:</b> {{resource.data.aircraft}}</span>
                        <span><b>Description:</b> {{resource.data.description}}</span>
                        <span><b>Start Date:</b> {{resource.data.start_date}}</span>
                        <span><b>Finish Date:</b> {{resource.data.finish_date}}</span>
                        <span><b>Labor Curr:</b> USD</span>
                        <span><b>Material Curr:</b> USD</span>
                    </div>
                </div>
                <hr class="my-2 h-0.5 bg-neutral-200" />
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
                </form>
                <div>
                    <Modal :show="showModal" :closeable="true" @close="showModal = false">
                        <div class="p-4">
                            <div>
                                <h1 class="text-2xl font-bold mb-2"><small>Activity</small> {{ data.name }}</h1>
                            </div>
                            <div>
                                <p class="font-semibold">Date:</p>
                                <p class="text-gray-500 text-sm mb-2">{{ data.date }}</p>
                            </div>
                            <div>
                                <p class="font-semibold">Description:</p>
                                <p class="mb-4">{{ data.description }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="font-semibold">Status:</p>
                                    <p>{{ data.status }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Comments:</p>
                                    <p>{{ data.comments }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Labor Mount:</p>
                                    <p>{{ data.labor_mount }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Material Mount:</p>
                                    <p>{{ data.material_mount }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold">AWR:</p>
                                    <p>{{ data.awr }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Approval Status:</p>
                                    <p>{{ data.approval_status }}</p>
                                </div>
                            </div>
                        </div>
                    </Modal>
                    <table class="table-auto">
                        <thead>
                        <tr>
                            <th>Required</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Comments</th>
                            <th>Labor/Mount</th>
                            <th>Material/Mount</th>
                            <th>AWR</th>
                            <th>Approval/Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(act, idx) in resource.data.activities" :key="idx">
                            <td>
                                <span class="cursor-pointer" @click="handleClickTr(act)">{{act.id}}</span>
                            </td>
                            <td>{{act.date}}</td>
                            <td>{{act.name}}</td>
                            <td>{{act.description}}</td>
                            <td>
                                <span v-if="act.status === 'pending'" class="badge-pending">{{act.status}}</span>
                                <span v-else class="badge-completed">{{act.status}}</span>
                            </td>
                            <td>{{act.comments}}</td>
                            <td>{{act.labor_mount}}</td>
                            <td>{{act.material_mount}}</td>
                            <td>{{act.awr}}</td>
                            <td>
                                <span v-if="act.approval_status === 'pending'" class="badge-pending">{{act.approval_status}}</span>
                                <span v-else class="badge-approval">{{act.approval_status}}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

