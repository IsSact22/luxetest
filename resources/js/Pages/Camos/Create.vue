<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Link} from '@inertiajs/vue3';
import {route} from "ziggy-js";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import {onMounted, ref, computed, watch} from "vue";
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {useCamoStore} from "@/Stores/useCamoStore.js";
import {storeToRefs} from "pinia";
import {useToast} from "vue-toastification";
import {trans} from 'laravel-vue-i18n';
import TimelineComponent from "@/Components/TimelineComponent.vue";

const toast = useToast();
const storeCamo = useCamoStore();
const {
    duration,
    currentStep,
    nextStep,
    previousStep,
    camId,
    camoForm,
    activity,
    camoActivities,
    customer,
    editId,
    editEnable,
} = storeToRefs(storeCamo);

onMounted(() => {
    const camId = usePage().props.auth.user.id ?? null
    storeCamo.setCamId(camId)
})

const hasPicture = ref(false);
const cams = ref(null);
const getCams = async () => {
    try {
        const response = await axios.get(route('cams.select'));
        cams.value = response.data.cams
    } catch (e) {
        console.error(e)
    }
}
const owners = ref(null);
const getOwners = async () => {
    try {
        const response = await axios.get(route('owners.select'));
        owners.value = response.data.owners
    } catch (e) {
        console.error(e)
    }
}
onMounted(getCams);
onMounted(getOwners)

const handlePrimarySubmit = () => {
    storeCamo.nextStep()
}
const handlePreviousStep = () => {
    storeCamo.previousStep()
}
const statusList = ref([
    {value: null, label: 'Select'},
    {value: 'pending', label: 'Pending'},
    {value: 'in_progress', label: 'in Progress'},
    {value: 'completed', label: 'completed'},
])
const handleAddActivity = (data) => {
    storeCamo.addActivity(data);
    storeCamo.activity.reset();
}
</script>
<template>
    <Head title="Camos"/>
    <AuthenticatedLayout>
        <template #header>
            <h2>Camos</h2>
        </template>

        <Transition>
            <!-- primary form -->
            <div v-if="currentStep === 1" class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 my-2">
                <Link :href="route('camos.index')" class="b-goto">back to CAMO</Link>
                <form class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" @submit.prevent="handlePrimarySubmit" @keydown.enter.prevent>
                    <div class="my-2">
                        <h2>New CAMO</h2>
                        <small class="text-neutral-600">All fields are required </small>
                    </div>
                    <div class="space-y-3">
                        <div class="flex flex-row space-x-7">
                            <div>
                                <InputLabel for="cam_id" value="Project Manager/CAM"></InputLabel>
                                <select
                                    id="cam_id"
                                    v-model="camoForm.cam_id"
                                    class="mt-1 block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    name="cam_id"
                                    required
                                >
                                    <option :selected="camoForm.cam_id === null" :value="null" disabled>Select</option>
                                    <option v-for="(cam, idx) in cams"
                                            :key="idx"
                                            :value="cam.id"
                                    >
                                        {{ cam.name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <InputLabel for="owner_id" value="Owner"></InputLabel>
                                <select
                                    id="owner_id"
                                    v-model="camoForm.owner_id"
                                    class="mt-1 block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    name="owner_id"
                                    required
                                >
                                    <option :selected="camoForm.owner_id === null" :value="null" disabled>Select
                                    </option>
                                    <option v-for="(own, idx) in owners" :key="idx" :value="own.id">{{ own.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <InputLabel for="location" value="Location/Station"></InputLabel>
                            <TextInput
                                id="location"
                                v-model="camoForm.location"
                                autocomplete="off"
                                class="mt-1 block placeholder-custom uppercase"
                                placeholder="Location Code"
                                required
                                type="text"
                            />
                        </div>
                        <div class="flex flex-row space-x-7">
                            <div>
                                <InputLabel for="customer" value="Customer"/>
                                <TextInput
                                    id="customer"
                                    v-model="camoForm.customer"
                                    autocomplete="off"
                                    class="mt-1 block placeholder-custom capitalize"
                                    placeholder="It must be a unique name"
                                    required
                                    type="text"
                                    @change="camoForm.validate('customer')"
                                />
                                <InputError :message="camoForm.errors.customer" class="mt-2"/>
                            </div>
                            <div>
                                <InputLabel for="contract" value="Contract"></InputLabel>
                                <TextInput
                                    id="contract"
                                    v-model="camoForm.contract"
                                    autocomplete="off"
                                    class="mt-1 block placeholder-custom capitalize"
                                    placeholder="Quote or Contract number"
                                    required
                                    type="text"
                                    @change="camoForm.validate('contract')"
                                />
                                <InputError :message="camoForm.errors.contract" class="mt-2"/>
                            </div>
                        </div>
                        <div>
                            <InputLabel for="aircraft" value="Aircraft"></InputLabel>
                            <TextInput
                                id="aircraft"
                                v-model="camoForm.aircraft"
                                autocomplete="off"
                                class="mt-1 block placeholder-custom capitalize"
                                placeholder="airplane model and registration"
                                required
                                type="text"
                                @change="camoForm.validate('aircraft')"
                            />
                            <InputError :message="camoForm.errors.aircraft" class="mt-2"/>
                        </div>
                        <div>
                            <InputLabel for="description" value="Description (CAMO)"></InputLabel>
                            <textarea
                                id="description"
                                v-model="camoForm.description"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm placeholder-custom"
                                cols="50"
                                name="description"
                                placeholder="General description"
                                required
                                rows="3"></textarea>
                        </div>
                        <div class="flex flex-row space-x-7">
                            <div>
                                <InputLabel for="start_date" value="Start Date"></InputLabel>
                                <input
                                    id="start_date"
                                    v-model="camoForm.start_date"
                                    class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    name="start_dat"
                                    type="date"
                                    @change="camoForm.validate('start_date')">
                                <InputError :message="camoForm.errors.start_date" class="mt-2"/>
                            </div>
                            <div>
                                <InputLabel for="finish_date" value="Finish Date"></InputLabel>
                                <input
                                    id="finish_date"
                                    v-model="camoForm.finish_date"
                                    class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    name="finish_dat"
                                    type="date"
                                    @change="camoForm.validate('finish_date')">
                                <InputError :message="camoForm.errors.finish_date" class="mt-2"/>
                            </div>
                        </div>
                    </div>
                    <div class="my-7 space-x-7">
                        <PrimaryButton>Step Two</PrimaryButton>
                        <SecondaryButton>Cancel</SecondaryButton>
                    </div>
                </form>
            </div>
        </Transition>

        <Transition>
            <!-- Add Activity -->
            <div v-if="currentStep === 2" class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 my-2">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div>
                        <div class="float-right">
                            <p class="font-semibold text-right">Execution period {{ storeCamo.duration }} days</p>
                            <p class="font-semibold text-right">Activities could begin from: {{ storeCamo.afterTo }}</p>
                        </div>
                    </div>
                    <div>
                        <SecondaryButton @click="handlePreviousStep">Step One</SecondaryButton>
                        <h2 class="my-2">CAMO: {{ storeCamo.customer }} <br><small>Activity <span class="badge-info">{{storeCamo.camoActivities.length}}</span></small></h2>
                    </div>
                    <div>
                        <small class="text-neutral-600">at least one activity is required and all fields are
                            required</small>
                    </div>
                    <hr class="my-1">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="flex flex-col space-y-2">
                            <div>
                                <label class="block text-sm text-slate-500 font-semibold" for="status">Status</label>
                                <select id="status" v-model="activity.status" class="inline-block rounded-md border-gray-300"
                                        name="status">
                                    <option v-for="(st, idx) in statusList"
                                            :key="idx" :disabled="st.value === null"
                                            :selected="st.value === activity.status"
                                            :value="st.value"
                                    >
                                        {{ st.label }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="activity.status !== 'pending'">
                                <label class="block text-sm text-slate-500 font-semibold" for="date">Date</label>
                                <input id="date" v-model="activity.date" class="inline-block rounded-md border-gray-300" name="date"
                                       type="date">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 font-semibold" for="Name">Activity
                                    Name</label>
                                <input id="name" v-model="activity.name"
                                       class="inline-block rounded-md border-gray-300 placeholder-custom" name="name" placeholder="Activity name" type="text">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 font-semibold"
                                       for="description">Description</label>
                                <textarea id="description"
                                          v-model="activity.description" class="inline-block rounded-md border-gray-300 placeholder-custom" cols="30" name="description"
                                          placeholder="Short description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <div>
                                <label class="block text-sm text-slate-500 font-semibold"
                                       for="comments">Comments</label>
                                <textarea id="comments"
                                          v-model="activity.comments" class="inline-block rounded-md border-gray-300 placeholder-custom" cols="30" name="comments" placeholder="About activity"
                                          rows="3"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 font-semibold" for="material_information">Material
                                    Information</label>
                                <textarea id="material_information"
                                          v-model="activity.material_information" class="inline-block rounded-md border-gray-300 placeholder-custom"
                                          cols="30" name="material_information" placeholder="About materials"
                                          rows="3"></textarea>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <div>
                                <label class="block text-sm text-slate-500 font-semibold" for="labor_mount">Labor
                                    Mount</label>
                                <input id="labor_mount" v-model="activity.labor_mount"
                                       class="inline-block rounded-md border-gray-300 text-right" name="labor_mount" placeholder="0.00"
                                       type="number">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 font-semibold" for="material_mount">Material
                                    Mount</label>
                                <input id="material_mount" v-model="activity.material_mount"
                                       class="inline-block rounded-md border-gray-300 text-right" name="material_mount" placeholder="0.00"
                                       type="number">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-500 font-semibold" for="awr">AWR</label>
                                <input id="awr" v-model="activity.awr"
                                       class="inline-block rounded-md border-gray-300 placeholder-custom" name="awr" placeholder="certifications" type="text">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="flex flex-row justify-items-center items-center space-x-3" for="required">
                            <span class="font-bold">Activity has required</span>
                            <Checkbox v-model="activity.required" :checked="activity.required" value="true"/>
                        </label>
                    </div>
                    <div class="mt-4">
                        <div class="flex flex-row place-content-around">
                            <div class="flex-1">
                                <PrimaryButton v-if="editEnable" @click="storeCamo.handleEdit(activity.data())">Save</PrimaryButton>
                                <PrimaryButton v-else @click="handleAddActivity(activity.data())">ADD</PrimaryButton>
                            </div>
                            <div class="space-x-7">
                                <SecondaryButton @click="storeCamo.nextStep()">View Activities</SecondaryButton>
                                <PrimaryButton>Finish CAMO</PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

        <transition>
            <!-- activities -->
            <div v-if="currentStep === 3"
                 class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mt-4 mb-2">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <TimelineComponent :title="storeCamo.customer" :subtitle="`${storeCamo.camoActivities.length}`" :data="storeCamo.camoActivities" />
                    <hr class="h-1">
                    <div class="flex flex-row justify-around items-center mt-4">
                        <PrimaryButton @click="handlePreviousStep">add activity</PrimaryButton>
                        <PrimaryButton @click="storeCamo.finish()" class="bg-emerald-400 hover:bg-emerald-500">Finish CAMO</PrimaryButton>
                    </div>
                </div>
            </div>
        </transition>

    </AuthenticatedLayout>
</template>

<style scoped>
.fade-item {
    transition: opacity 0.5s;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>

