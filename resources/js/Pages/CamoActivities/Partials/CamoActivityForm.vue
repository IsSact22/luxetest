<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import { computed, onMounted, ref, watch } from "vue";
import { route } from "ziggy-js";
import { useForm } from "laravel-precognition-vue-inertia";
import Checkbox from "@/Components/Checkbox.vue";
import moment from "moment";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const now = ref(moment().format("YYYY-MM-DD"));
const props = defineProps({
    camo: {
        type: Object,
    },
    camoActivity: {
        type: Object,
    },
    user: {
        type: Object,
        required: true,
    },
});
const method = props.camoActivity ? "put" : "post";
const url = props.camoActivity
    ? `/camo_activities/${props.camoActivity.id}`
    : "/camo_activities";
const form = useForm(method, url, {
    camo_id: props.camo?.id ?? null,
    labor_rate_id: props.camoActivity?.labor_rate_id ?? null,
    required: false,
    date: now.value,
    name: null,
    description: null,
    estimate_time: props.camoActivity?.estimate_time ?? null,
    started_at: null,
    completed_at: null,
    status: props.camoActivity?.status ?? "pending",
    comments: null,
    labor_mount: null,
    material_mount: null,
    material_information: null,
    awr: null,
    approval_status: props.camoActivity?.approval_status ?? "pending",
    priority: props.camoActivity?.priority ?? 3,
});
watch(
    () => form.required,
    (newValue) => {
        if (newValue) {
            form.approval_status = "approved";
        } else {
            form.approval_status = "pending";
        }
    },
);
const laborRates = ref(null);
const getLaborRates = async () => {
    try {
        const response = await axios.get(route("labor-rates.select"));
        laborRates.value = response.data;
    } catch (e) {
        console.error(e);
    }
};
onMounted(getLaborRates);
const statusList = ref([
    { value: "pending", label: "Pending", selected: false },
    { value: "in_progress", label: "in Progress", selected: false },
    { value: "completed", label: "completed", selected: false },
]);
const statusApproval = ref([
    { value: "pending", label: "Pending", selected: false },
    { value: "approved", label: "Approved", selected: false },
    { value: "canceled", label: "Canceled", selected: false },
]);
const selectedRate = ref(null);
const changeRate = (id) => {
    const selected = laborRates.value.filter((item) => item.id === id);
    selectedRate.value = parseFloat(selected[0].mount);
};
watch(
    () => form.estimate_time,
    (newValue) => {
        if (newValue && selectedRate.value) {
            const result = newValue * selectedRate.value;
            form.labor_mount = parseFloat(result).toFixed(2);
        }
    },
);
const laborMount = computed(() => {
    if (selectedRate.value && form.estimate_time) {
        return selectedRate.value * form.estimate_time;
    }
    return 0;
});
const emit = defineEmits(["addActivity"]);
const handleCancel = () => {
    form.reset();
    emit("addActivity", false);
};
const submit = async () => {
    console.log("click");
    form.submit({
        onSuccess: () => {
            form.reset();
            emit("addActivity", false);
        },
    });
};
</script>
<template>
    <div class="py-12 bg-white rounded-md p-4">
        <form @submit.prevent="submit">
            <input id="camo_id" name="camo_id" type="hidden" />

            <div
                class="flex flex-row justify-items-center items-center space-x-5 mb-4"
            >
                <div>
                    <label
                        class="flex flex-row justify-items-center items-center space-x-3"
                        for="required"
                    >
                        <span class="font-bold">Activity has required</span>
                        <Checkbox
                            v-model="form.required"
                            :checked="form.required"
                            value="true"
                        />
                    </label>
                </div>

                <div>
                    <InputLabel
                        :value="`Approval Status`"
                        for="approval_status"
                    />
                    <select
                        id="approval_status"
                        v-model="form.approval_status"
                        class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="approval_status"
                    >
                        <option :value="null" disabled>Select</option>
                        <option
                            v-for="(item, idx) in statusApproval"
                            :key="idx"
                            :value="item.value"
                        >
                            {{ item.label }}
                        </option>
                    </select>
                    <InputError
                        :message="form.errors.approval_status"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel :value="`Priority`" for="priority" />
                    <select
                        id="priority"
                        v-model="form.priority"
                        class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="priority"
                    >
                        <option :value="null" disabled>Select</option>
                        <option :value="1" class="bg-red-500">
                            High Priority
                        </option>
                        <option :value="2" class="bg-amber-300">
                            Medium Priority
                        </option>
                        <option :value="3" class="bg-blue-200">
                            Low Priority
                        </option>
                    </select>
                    <InputError :message="form.errors.priority" class="mt-2" />
                </div>
            </div>

            <hr class="mb-4" />
            <div
                class="flex flex-row justify-items-center items-center space-x-5 mb-4"
            >
                <div>
                    <InputLabel :value="`Date`" for="date" />
                    <input
                        id="date"
                        v-model="form.date"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="date"
                        type="date"
                    />
                    <InputError :message="form.errors.date" class="mt-2" />
                </div>

                <div>
                    <InputLabel :value="`Type`" for="labor_rate_id" />
                    <select
                        id="labor_rate_id"
                        v-model="form.labor_rate_id"
                        aria-describedby="labor_rate_id"
                        aria-required="true"
                        class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="labor_rate_id"
                        required
                        @change="changeRate(form.labor_rate_id)"
                    >
                        <option :value="null" disabled>Select</option>
                        <option
                            v-for="(item, idx) in laborRates"
                            :key="idx"
                            :value="item.id"
                        >
                            {{ item.name }}
                        </option>
                    </select>
                    <InputError
                        :message="form.errors.labor_rate_id"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel :value="`name`" for="name" />
                    <input
                        id="name"
                        v-model="form.name"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm capitalize"
                        name="name"
                        placeholder="Activity Name"
                        type="text"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <div>
                    <InputLabel :value="`Estimate Time`" for="estimate_time" />
                    <input
                        id="estimate_time"
                        v-model="form.estimate_time"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-right"
                        name="estimate_time"
                        type="number"
                    />
                    <InputError
                        :message="form.errors.estimate_time"
                        class="mt-2"
                    />
                </div>
            </div>

            <div
                class="flex flex-row justify-items-center items-center space-x-5 mb-4"
            >
                <div>
                    <InputLabel :value="`Description`" for="description" />
                    <textarea
                        id="description"
                        v-model="form.description"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        cols="30"
                        name="description"
                        placeholder="Description activity"
                        rows="3"
                    ></textarea>
                    <InputError
                        :message="form.errors.description"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel :value="`Comments`" for="comments" />
                    <textarea
                        id="comments"
                        v-model="form.comments"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        cols="30"
                        name="comments"
                        placeholder="Comments activity"
                        rows="3"
                    ></textarea>
                    <InputError :message="form.errors.comments" class="mt-2" />
                </div>

                <div>
                    <InputLabel
                        :value="`Material Information`"
                        for="material_information"
                    />
                    <textarea
                        id="material_information"
                        v-model="form.material_information"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        cols="30"
                        name="material_information"
                        placeholder="Material information"
                        rows="3"
                    ></textarea>
                    <InputError
                        :message="form.errors.material_information"
                        class="mt-2"
                    />
                </div>
            </div>

            <div
                class="flex flex-row justify-items-center items-center space-x-5 mb-4"
            >
                <div>
                    <InputLabel :value="`Start Date`" for="start_date" />
                    <input
                        id="start_date"
                        v-model="form.start_date"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="start_date"
                        type="date"
                    />
                    <InputError
                        :message="form.errors.start_date"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel :value="`Status`" for="status" />
                    <select
                        id="status"
                        v-model="form.status"
                        class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="status"
                    >
                        <option :value="null">Select</option>
                        <option
                            v-for="(item, idx) in statusList"
                            :key="idx"
                            :value="item.value"
                        >
                            {{ item.label }}
                        </option>
                    </select>
                    <InputError :message="form.errors.status" class="mt-2" />
                </div>

                <div>
                    <InputLabel :value="`Labor Mount`" for="labor_mount" />
                    <input
                        id="labor_mount"
                        v-model="form.labor_mount"
                        aria-readonly="true"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-right"
                        name="labor_mount"
                        readonly
                        type="number"
                    />
                    <InputError
                        :message="form.errors.labor_mount"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel
                        :value="`Material Mount`"
                        for="material_mount"
                    />
                    <input
                        id="material_mount"
                        v-model="form.material_mount"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-right"
                        name="material_mount"
                        placeholder="0.00"
                        type="number"
                    />
                    <InputError
                        :message="form.errors.material_mount"
                        class="mt-2"
                    />
                </div>
            </div>

            <div class="mb-4">
                <InputLabel :value="`AWR`" for="awr" />
                <input
                    id="awr"
                    v-model="form.awr"
                    class="w-1/3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-right"
                    maxlength="191"
                    name="awr"
                    type="text"
                />
                <InputError :message="form.errors.awr" class="mt-2" />
            </div>

            <div>
                <InputLabel :value="`Completed Date`" for="completed_date" />
                <input
                    id="completed_date"
                    v-model="form.completed_at"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    name="completed_date"
                    type="date"
                />
                <InputError :message="form.errors.completed_at" class="mt-2" />
            </div>
            <div
                class="flex flex-row justify-items-center items-center space-x-7 my-4"
            >
                <PrimaryButton
                    v-if="form.isDirty"
                    :disable="form.processing"
                    type="submit"
                    >Save
                </PrimaryButton>
                <SecondaryButton @click="handleCancel">Cancel</SecondaryButton>
            </div>
        </form>
    </div>
</template>
