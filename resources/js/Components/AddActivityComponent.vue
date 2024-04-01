<template>
    <div class="max-w-7xl p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <form id="addActivity" ref="addActivity" @submit.prevent="submit">
            <div>
                <small class="text-neutral-600">at least one activity is required and all fields are
                    required</small>
            </div>
            <hr class="my-1">
            <div class="grid grid-cols-3 gap-4">
                <div class="flex flex-col space-y-2">
                    <div>
                        <label class="block text-sm text-slate-500 font-semibold" for="status">Status</label>
                        <select id="status" v-model="form.status" class="inline-block rounded-md border-gray-300"
                                name="status">
                            <option v-for="(st, idx) in statusList"
                                    :key="idx" :disabled="st.value === null"
                                    :selected="st.value === form.status"
                                    :value="st.value"
                            >
                                {{ st.label }}
                            </option>
                        </select>
                    </div>
                    <div v-if="form.status !== 'pending'">
                        <label class="block text-sm text-slate-500 font-semibold" for="date">Date</label>
                        <input id="date" v-model="form.date" class="inline-block rounded-md border-gray-300" name="date"
                               type="date">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-500 font-semibold" for="Name">Activity
                            Name</label>
                        <input id="name" v-model="form.name"
                               class="inline-block rounded-md border-gray-300 placeholder-custom" name="name" placeholder="Activity name" type="text">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-500 font-semibold"
                               for="description">Description</label>
                        <textarea id="description"
                                  v-model="form.description" class="inline-block rounded-md border-gray-300 placeholder-custom" cols="30" name="description"
                                  placeholder="Short description" rows="3"></textarea>
                    </div>
                </div>
                <div class="flex flex-col space-y-2">
                    <div>
                        <label class="block text-sm text-slate-500 font-semibold"
                               for="comments">Comments</label>
                        <textarea id="comments"
                                  v-model="form.comments" class="inline-block rounded-md border-gray-300 placeholder-custom" cols="30" name="comments" placeholder="About activity"
                                  rows="3"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-500 font-semibold" for="material_information">Material
                            Information</label>
                        <textarea id="material_information"
                                  v-model="form.material_information" class="inline-block rounded-md border-gray-300 placeholder-custom"
                                  cols="30" name="material_information" placeholder="About materials"
                                  rows="3"></textarea>
                    </div>
                </div>
                <div class="flex flex-col space-y-2">
                    <div>
                        <label class="block text-sm text-slate-500 font-semibold" for="labor_mount">Labor
                            Mount</label>
                        <input id="labor_mount" v-model="form.labor_mount"
                               class="inline-block rounded-md border-gray-300 text-right" name="labor_mount" placeholder="0.00"
                               type="number">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-500 font-semibold" for="material_mount">Material
                            Mount</label>
                        <input id="material_mount" v-model="form.material_mount"
                               class="inline-block rounded-md border-gray-300 text-right" name="material_mount" placeholder="0.00"
                               type="number">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-500 font-semibold" for="awr">AWR</label>
                        <input id="awr" v-model="form.awr"
                               class="inline-block rounded-md border-gray-300 placeholder-custom" name="awr" placeholder="certifications" type="text">
                    </div>
                </div>
            </div>
            <div>
                <label class="flex flex-row justify-items-center items-center space-x-3" for="required">
                    <span class="font-bold">Activity has required</span>
                    <Checkbox v-model="form.required" :checked="form.required" value="true"/>
                </label>
            </div>
            <div class="mt-4">
                <div class="flex flex-row place-content-around">
                    <div class="flex-1 space-x-7">
                        <PrimaryButton type="submit" :disabled="form.processing" @click="submitActivity">ADD</PrimaryButton>
                        <SecondaryButton @click="closeComponent"> Close </SecondaryButton>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {useForm} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import {route} from "ziggy-js";
import {useToast} from "vue-toastification";

const toast = useToast();
const props = defineProps({
    camoId: Number
})
const form = useForm({
    camo_id: props.camoId,
    required: false,
    date: '',
    name: '',
    description: '',
    status: 'pending',
    comments: '',
    labor_mount: '',
    material_mount: '',
    material_information: '',
    awr: '',
    approval_status: ''
})

const statusList = ref([
    {value: null, label: 'Select'},
    {value: 'pending', label: 'Pending'},
    {value: 'in_progress', label: 'in Progress'},
    {value: 'completed', label: 'completed'},
])

const emit = defineEmits(['event-close', 'sent-activity']);

const submitActivity = async () => {
    try {
        const response = await axios.post(route('camo_activities.add'), form.data());
        emit('sent-activity');
        toast.success('added Activity')
        closeComponent();
    } catch (e) {
        toast.error('error adding activity')
        console.error(e)
    }

}
const closeComponent = () => {
    emit('event-close');
}
</script>
