<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import _ from 'lodash';
import Modal from "@/Components/Modal.vue";
import {ref, computed, onMounted, watch} from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import Paginator2 from "@/Components/Paginator2.vue";
import useFormatCurrency from '@/Composables/formatCurrency';

const { formatCurrency } = useFormatCurrency();

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({})
    }
})
const camoId = ref(props.resource.data.id);
const activities = ref(null)
const currentPage = ref(null);
const lastPage = ref(null)
const search = ref('')
const handlePageChange = (page) => {
    console.log(page)
    currentPage.value = page
}
const getActivities = async () => {
    try {
        const response = await axios.get(route('camos.activities'), {
            params: {
                camo_id: camoId.value,
                page: currentPage.value,
                search: search.value,
            }
        })
        activities.value = response.data
        currentPage.value = response.data.current_page
        lastPage.value = response.data.last_page
    } catch (e) {
        console.error(e);
    }
}
onMounted(getActivities);
const fireSearch = _.throttle(function () {
    getActivities()
}, 200);

watch([currentPage], getActivities);

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
const statusSelect = ref(false);
const handleStatusSelect = () => {
    statusSelect.value = !statusSelect.value
}
const statusList = ref([
    {value: 'pending', label: 'Pending'},
    {value: 'in_progress', label: 'in Progress'},
    {value: 'completed', label: 'Completed'},
])
const form = useForm({

})
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
                        <span><b>Customer:</b> {{ resource.data.customer }}</span>
                        <span><b>Contract:</b> {{ resource.data.contract }}</span>
                        <span><b>Project Manager:</b> {{ resource.data.cam }}</span>
                        <span><b>Location:</b> {{ resource.data.location }}</span>
                    </div>
                    <div class="flex flex-col px-4">
                        <span>Project</span>
                        <span><b>Aircraft:</b> {{ resource.data.aircraft }}</span>
                        <span><b>Description:</b> {{ resource.data.description }}</span>
                        <span><b>Start Date:</b> {{ resource.data.start_date }}</span>
                        <span><b>Finish Date:</b> {{ resource.data.finish_date }}</span>
                        <span><b>Labor Curr:</b> USD</span>
                        <span><b>Material Curr:</b> USD</span>
                    </div>
                </div>
                <hr class="my-2 h-0.5 bg-neutral-200"/>
                <form class="my-2 flex flex-row justify-items-center items-center space-x-7">
                    <input
                        id="search"
                        v-model="search"
                        class="px-4 py-2 rounded-md border-gray-300 w-1/5"
                        name="search"
                        placeholder="type for search and clear to reset"
                        type="text"
                        @keyup="fireSearch"
                    >
                </form>
                <div>
                    <Modal :closeable="true" :show="showModal" @close="showModal = false">
                        <div class="p-4">
                            <div class="flex flex-row justify-items-start items-center space-x-7">
                                <div><b>Activity:</b> {{ data.name }}</div>
                                <div><b>Date:</b> {{ data.date }}</div>
                            </div>
                            <div>
                                <p class="font-semibold">Description:</p>
                                <textarea
                                    id="comments"
                                    class="rounded-md border-gray-300 w-full"
                                    cols="30"
                                    name="comments"
                                    rows="3"
                                >{{ data.description }}</textarea>
                            </div>
                            <hr class="my-2">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="font-semibold">Comments:</p>
                                    <div>
                                        <textarea
                                            id="comments"
                                            class="rounded-md border-gray-300"
                                            cols="30"
                                            name="comments"
                                            rows="5"
                                        >{{ data.comments }}</textarea>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="my-1">
                                        <InputLabel class="text-neutral-900 font-semibold" for="labor_mount">Labor
                                            Mount:
                                        </InputLabel>
                                        <input id="labor_mount" v-model="data.labor_mount"
                                               class="text-right rounded-md border-gray-400" name="labor_mount" type="number">
                                    </div>
                                    <div class="my-1">
                                        <InputLabel class="text-gray-900 font-semibold" for="material_mount">Material
                                            Mount:
                                        </InputLabel>
                                        <input id="labor_mount" v-model="data.material_mount"
                                               class="text-right rounded-md border-gray-400" name="labor_mount" type="number">
                                    </div>
                                </div>
                                <div>
                                    <InputLabel class="text-gray-900 font-semibold" for="material_mount">AWR:
                                    </InputLabel>
                                    <input id="awr" v-model="data.awr" class="rounded-md border-gray-400 w-full" name="awr"
                                           type="text">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 my-2">
                                <div>
                                    <div class="font-semibold">Status:</div>
                                    <div class="flex flex-row justify-items-center items-center">
                                        <button @click="handleStatusSelect">
                                            <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                        </button>
                                        <div v-if="statusSelect">
                                            <select
                                                name="status"
                                                id="status"
                                                class="py-1 rounded-md"
                                                v-model="data.status"
                                            >
                                                <option v-for="(status, idx) in statusList" :key="idx" :value="status.value">{{status.label}}</option>
                                            </select>
                                        </div>
                                        <div v-else>
                                            <span v-if="data.status === 'completed'"
                                                  class="bg-green-100 text-green-800 text-xl font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            {{ data.status }}
                                        </span>
                                            <span v-else-if="data.status === 'in_progress'"
                                                  class="bg-indigo-100 text-indigo-800 text-xl font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            {{ data.status }}
                                        </span>
                                            <span v-else
                                                  class="bg-amber-100 text-amber-800 text-xl font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            {{ data.status }}
                                        </span>
                                        </div>

                                    </div>
                                </div>
                                <div>
                                    <div class="font-semibold">Approval Status:</div>
                                    <div>
                                        <span v-if="data.approval_status === 'approved'"
                                              class="bg-green-100 text-green-800 text-xl font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            {{ data.approval_status }}
                                        </span>
                                        <span v-else
                                              class="bg-amber-100 text-amber-800 text-xl font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            {{ data.approval_status }}
                                        </span>
                                    </div>
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
                        <tr v-for="(act, idx) in activities && activities.resource" :key="idx" class="cursor-pointer"
                            @click="handleClickTr(act)">
                            <td>{{ act.id }}</td>
                            <td>{{ act.date }}</td>
                            <td>{{ act.name }}</td>
                            <td class="line-clamp-2">{{ act.description }}</td>
                            <td>
                                <span v-if="act.status === 'pending'" class="badge-pending">{{ act.status }}</span>
                                <span v-else-if="act.status === 'in_progress'" class="badge-progress">{{ act.status }}</span>
                                <span v-else class="badge-completed">{{ act.status }}</span>
                            </td>
                            <td class="line-clamp-2">{{ act.comments }}</td>
                            <td class="text-right">{{ formatCurrency(act.labor_mount) }}</td>
                            <td class="text-right">{{ formatCurrency(act.material_mount) }}</td>
                            <td>{{ act.awr }}</td>
                            <td>
                                <span v-if="act.approval_status === 'pending'"
                                      class="badge-pending">{{ act.approval_status }}</span>
                                <span v-else class="badge-approval">{{ act.approval_status }}</span>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="10">
                                <!-- Paginator -->
                                <Paginator2
                                    class="float-right"
                                    :current-page="currentPage"
                                    :last-page="lastPage"
                                    @page-change="handlePageChange"
                                />
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

