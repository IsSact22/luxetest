<script setup>
import {Head, Link, useForm, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import _ from 'lodash';
import Modal from "@/Components/Modal.vue";
import {ref, computed, onMounted, watch, watchEffect, nextTick} from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import Paginator2 from "@/Components/Paginator2.vue";
import useFormatCurrency from '@/Composables/formatCurrency';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {useToast} from "vue-toastification";
import AddActivityComponent from "@/Components/AddActivityComponent.vue";

const toast = useToast();

const {formatCurrency} = useFormatCurrency();

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({})
    }
})

const waitingApproval = computed(() => {
    if (props.resource.data && props.resource.data.activities){
        const activities = props.resource.data.activities;
        return activities.filter(act => {
            return act.approval_status === 'pending'
        })
    } else { return [] }
})

const pendingExecution = computed(() => {
    if (props.resource.data && props.resource.data.activities){
        const activities = props.resource.data.activities;
        return activities.filter(act => {
            return act.status === 'pending'
        })
    } else { return [] }
})

const inProgress = computed(() => {
    if (props.resource.data && props.resource.data.activities){
        const activities = props.resource.data.activities;
        return activities.filter(act => {
            return act.status === 'in_progress'
        })
    } else { return [] }
})

const completed = computed(() => {
    if (props.resource.data && props.resource.data.activities){
        const activities = props.resource.data.activities;
        return activities.filter(act => {
            return act.status === 'completed'
        })
    } else { return [] }
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

const filter = ref(null);
onMounted(() => {
    if (usePage().props.auth.user.is_owner || usePage().props.auth.user.is_crew) {
        filter.value = 'approval_status.pending'
    }
})
const getActivities = async () => {
    try {
        const response = await axios.get(route('camos.activities'), {
            params: {
                camo_id: camoId.value,
                page: currentPage.value,
                search: search.value,
                filter: filter.value,
            }
        })
        activities.value = response.data
        currentPage.value = response.data.current_page
        lastPage.value = response.data.last_page
    } catch (e) {
        console.error(e);
    }
}

watch(filter, (newValue) => {
    getActivities()
})

onMounted(getActivities);
const fireSearch = _.throttle(function () {
    getActivities()
}, 200);

watch([currentPage], getActivities);
// Propiedad computada para calcular el total de labor_mount
const totalLaborMount = computed(() => {
    const total = props.resource.data.activities.reduce((total, activity) => {
        return total + Number(activity.labor_mount);
    }, 0);
    return total.toFixed(2);
});

// Propiedad computada para calcular el total de material_mount
const totalMaterialMount = computed(() => {
    const total = props.resource.data.activities.reduce((total, activity) => {
        return total + Number(activity.material_mount);
    }, 0);
    return total.toFixed(2);
});

const destroy = (id) => {
    if (confirm("Seguro desea eliminar el Usuario")) {
        form.delete(route('camos.destroy', id), {preserveState: true});
    }
}
const showModal = ref(false)
const activityId = ref(null);

const formActivity = useForm({
    name: '',
    date: '',
    description: '',
    comments: '',
    awr: '',
    labor_mount: '',
    material_mount: '',
    material_information: '',
    status: '',
})
const closeModal = () => {
    activityId.value = null;
    showModal.value = false;
}
const handleClickTr = (obj) => {
    activityId.value = obj.id
    Object.assign(formActivity, obj);
    formActivity.defaults(obj)
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

const submit = async () => {
    try {
        const response = await axios.patch(route('camo_activities.handle', activityId.value), formActivity.data());
        toast.success(response.data.message)
        await getActivities();
        closeModal();

    } catch (e) {
        console.error(e)
    }
}
const addActivity = ref(false)
const handleAddActivity = (e) => {
    addActivity.value = false
}
</script>
<template>
    <Head title="Camos"/>
    <AuthenticatedLayout>
        <template #header>
            <h2>Camos</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center max-w-7xl mx-auto">
            <div class="my-4 border rounded-md px-4 py-4">
                <div class="space-x-3 my-3">
                    <Link :href="route('camos.index')" class="b-goto">back to Camos</Link>
                    <button
                        @click="addActivity = true"
                        class="b-goto"
                    >
                        new activity for this CAMO
                    </button>
                    <button class="b-inDev" title="Under development" type="button" @click.passive.prevent>Camo to PDF
                    </button>
                    <button class="b-inDev" title="Under development" type="button" @click.passive.prevent>Archive
                        Camo
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-4 my-3">
                    <div class="flex flex-col px-4 border rounded-md bg-gray-100/50">
                        <h1 class="text-gray-700">Customer Data</h1>
                        <hr class="h-0.5 bg-neutral-400">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <p>Customer</p>
                                <p>Contract</p>
                                <p>Project Manager</p>
                                <p>Location</p>
                            </div>
                            <div>
                                <p>{{ resource.data.customer }}</p>
                                <p>{{ resource.data.contract }}</p>
                                <p>{{ resource.data.cam }}</p>
                                <p>{{ resource.data.location }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col px-4 border rounded-md bg-gray-100/50">
                        <h1 class="text-gray-700">Project</h1>
                        <hr class="h-0.5 bg-neutral-400">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <p>Aircraft</p>
                                <p>Description</p>
                                <p>Start Date</p>
                                <p>Finish Date</p>
                            </div>
                            <div>
                                <p class="text-right">{{ resource.data.aircraft }}</p>
                                <p class="text-right line-clamp-1">{{ resource.data.description }}</p>
                                <p class="text-right">{{ resource.data.start_date }}</p>
                                <p class="text-right">{{ resource.data.finish_date }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col px-4 border rounded-md bg-gray-100/50">
                        <h1 class="text-gray-700">Summary</h1>
                        <hr class="h-0.5 bg-neutral-400">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <p>Labor Mount</p>
                                <p>Material Mount</p>
                                <p>Total</p>
                                <p>Waiting Approval</p>
                                <p>Pending</p>
                                <p>In Progress</p>
                                <p>Completed</p>
                                <p>Total Activities</p>
                            </div>
                            <div>
                                <p class="text-right">{{ formatCurrency(totalLaborMount) }}</p>
                                <p class="text-right">{{ formatCurrency(totalMaterialMount) }}</p>
                                <hr class="h-0.5 bg-gray-300">
                                <p class="text-right text-green-700">
                                    {{ formatCurrency(Number(totalLaborMount) + Number(totalMaterialMount)) }}
                                </p>
                                <hr>
                                <p v-if="waitingApproval" class="text-right">
                                    <span class="badge-alert">{{waitingApproval.length}}</span>
                                </p>
                                <p v-if="pendingExecution" class="text-right">
                                    <span class="badge-pending">{{pendingExecution.length}}</span>
                                </p>
                                <p v-if="completed" class="text-right">
                                    <span class="badge-progress">{{inProgress.length}}</span>
                                </p>
                                <p v-if="completed" class="text-right">
                                    <span class="badge-completed">{{completed.length}}</span>
                                </p>
                                <p v-if="activities && activities.total" class="text-right">
                                    <span class="badge-info">{{activities.total}}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <form v-show="!addActivity" class="my-2">
                        <InputLabel for="search">Search</InputLabel>
                        <input
                            id="search"
                            v-model="search"
                            class="px-4 py-2 rounded-md border-gray-300 w-3/12"
                            name="search"
                            placeholder="type for search and clear to reset"
                            type="text"
                            @keyup="fireSearch"
                        >
                    </form>
                    <!-- add activity -->
                    <Transition name="fade" appear @after-enter="addActivity">
                        <AddActivityComponent
                            v-show="addActivity"
                            :camo-id="resource.data.id"
                            @event-close="handleAddActivity"
                            @sent-activity="getActivities"
                            ref="addActivityComponent"
                        />
                    </Transition>
                    <!-- add activity -->
                    <!-- modal -->
                    <Transition appear name="fade">
                        <Modal :closeable="false" :show="showModal" @close="showModal = false" backdrop="static">
                            <div class="px-6 py-4 space-y-3">
                                <form @submit.prevent="submit" @keydown.enter.prevent>
                                    <div class="flex flex-row justify-items-start items-center space-x-7">
                                        <div>
                                            <InputLabel for="name">Activity</InputLabel>
                                            <input id="name" v-model="formActivity.name"
                                                   class="rounded-md border-gray-100" disabled
                                                   name="name" readonly type="text">
                                        </div>
                                        <div v-if="formActivity.date">
                                            <InputLabel for="date">Date</InputLabel>
                                            <input id="date" v-model="formActivity.date"
                                                   class="rounded-md border-gray-100" disabled
                                                   name="date" readonly type="text">
                                        </div>
                                    </div>

                                    <div class="flex flex-row justify-around">
                                        <div>
                                            <InputLabel for="description">Description</InputLabel>
                                            <textarea
                                                id="description"
                                                v-model="formActivity.description"
                                                class="rounded-md border-gray-300 w-full"
                                                cols="30"
                                                name="description"
                                                rows="5"
                                            ></textarea>
                                        </div>
                                        <div>
                                            <InputLabel for="comments">Comments</InputLabel>
                                            <div>
                                        <textarea
                                            id="comments"
                                            v-model="formActivity.comments"
                                            class="rounded-md border-gray-300 w-full"
                                            cols="30"
                                            name="comments"
                                            rows="5"
                                        ></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-1/2">
                                        <InputLabel for="material_mount">AWR</InputLabel>
                                        <input id="awr" v-model="formActivity.awr"
                                               class="rounded-md border-gray-300 w-full"
                                               name="awr"
                                               type="text">
                                    </div>

                                    <div class="flex flex-row justify-items-center space-x-3">
                                        <div class="my-1">
                                            <InputLabel for="labor_mount">Labor Mount</InputLabel>
                                            <input id="labor_mount" v-model="formActivity.labor_mount" step="0.01"
                                                   class="text-right rounded-md border-gray-300" name="labor_mount"
                                                   type="number">
                                        </div>
                                        <div class="my-1">
                                            <InputLabel for="material_mount">Material Mount</InputLabel>
                                            <input id="labor_mount" v-model="formActivity.material_mount" step="0.01"
                                                   class="text-right rounded-md border-gray-300" name="labor_mount"
                                                   type="number">
                                        </div>
                                    </div>

                                    <div>
                                        <InputLabel>Material Information</InputLabel>
                                        <textarea
                                            id="description"
                                            v-model="formActivity.material_information"
                                            class="rounded-md border-gray-300 w-full"
                                            cols="30"
                                            name="description"
                                            rows="2"
                                        ></textarea>
                                    </div>

                                    <div>
                                        <InputLabel for="status">Status</InputLabel>
                                        <select
                                            id="status"
                                            v-model="formActivity.status"
                                            class="rounded-md border-gray-300"
                                            name="status"
                                        >
                                            <option v-for="(status, idx) in statusList" :key="idx"
                                                    :value="status.value">
                                                {{ status.label }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="flex justify-end space-x-4">
                                        <PrimaryButton type="submit" v-if="formActivity.isDirty">Save</PrimaryButton>
                                        <SecondaryButton @click="closeModal">Close</SecondaryButton>
                                    </div>
                                </form>
                            </div>
                        </Modal>
                    </Transition>
                    <!-- modal -->
                    <Transition name="fade" appear @after-enter="!addActivity">
                        <div v-show="!addActivity">
                            <!-- pending approval -->
                            <div class="flex items-center -mx-4 space-x-2 overflow-x-auto overflow-y-hidden sm:justify-center flex-nowrap my-4">
                                <button rel="noopener noreferrer" href="#"
                                   :class="{ 'border-b-blue-400' : filter === null}"
                                   class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                                   @click="filter = null" preserve-scroll
                                >All</button>
                                <button rel="noopener noreferrer" href="#"
                                   :class="{ 'border-b-blue-400' : filter === 'approval_status.approved'}"
                                   class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                                   @click="filter = 'approval_status.approved'" preserve-scroll
                                >Approved</button>
                                <button rel="noopener noreferrer" href="#"
                                   :class="{ 'border-b-blue-400' : filter === 'approval_status.pending'}"
                                   class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                                   @click="filter = 'approval_status.pending'" preserve-scroll
                                >Pending Approval</button>
                                <button rel="noopener noreferrer" href="#"
                                   :class="{ 'border-b-blue-400' : filter === 'status.pending'}"
                                   class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                                   @click="filter = 'status.pending'" preserve-scroll
                                >Pending</button>
                                <button rel="noopener noreferrer" href="#"
                                   :class="{ 'border-b-blue-400' : filter === 'status.in_progress'}"
                                   class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                                   @click="filter = 'status.in_progress'" preserve-scroll
                                >In Progress</button>
                                <button rel="noopener noreferrer" href="#"
                                   :class="{ 'border-b-blue-400' : filter === 'status.completed'}"
                                   class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                                   @click="filter = 'status.completed'" preserve-scroll
                                >Completed</button>
                            </div>
                            <!-- pending approval -->
                            <table class="table-auto w-full">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Status</th>
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
                                    <td class="text-xs">{{ act.date }}</td>
                                    <td>
                                        <div class="flex flex-row justify-items-center items-center space-x-2">
                                    <span>
                                    <svg class="w-4 h-4" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg"><g
                                        fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round"><circle cx="8.5" cy="8.5" r="5"/><path
                                        d="m17.571 17.5-5.571-5.5"/></g></svg>
                                </span>
                                            <span>{{ act.name }}</span>
                                        </div>
                                    </td>
                                    <td class="flex place-content-center">
                                        <span v-if="act.status === 'pending'" class="badge-pending">{{ act.status }}</span>
                                        <span v-else-if="act.status === 'in_progress'" class="badge-progress">
                                            {{ act.status}}
                                        </span>
                                        <span v-else class="badge-completed">{{ act.status }}</span>
                                    </td>
                                    <td class="text-right">{{ formatCurrency(act.labor_mount) }}</td>
                                    <td class="text-right">{{ formatCurrency(act.material_mount) }}</td>
                                    <td><span class="line-clamp-1">{{ act.awr }}</span></td>
                                    <td class="flex place-content-center">
                                <span v-if="act.approval_status === 'pending'"
                                      class="badge-pending">{{ act.approval_status }}</span>
                                        <span v-else class="badge-approval">{{ act.approval_status }}</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="flex float-right">
                                <!-- Paginator -->
                                <Paginator2
                                    v-if="activities && activities.total > 9"
                                    :current-page="currentPage"
                                    :last-page="lastPage"
                                    @page-change="handlePageChange"
                                />
                            </div>
                        </div>
                    </Transition>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
/* ---------------------------------- */
.fade-enter, .fade-leave-to {
    opacity: 0;
}

.fade-enter-active, .fade-leave-active {
    transition: 0.2s opacity ease-out;
}

.scale-enter-active,
.scale-leave-active {
    transition: transform 0.3s;
}

.scale-enter-from,
.scale-leave-to {
    transform: scaleY(0);
}

.scale-enter-to,
.scale-leave-from {
    transform: scaleY(1);
}
</style>

