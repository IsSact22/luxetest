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
import { router } from "@inertiajs/vue3";
import UploadImage from "@/Components/UploadImage.vue";
import { useToast } from "vue-toastification";

const toast = useToast();
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
    ? `/camo_activities/${props.camo.id}`
    : "/camo_activities";

const form = useForm(method, url, {
    camo_id: props.camo?.id ?? null,
    labor_rate_id: props.camoActivity?.labor_rate_id ?? null,
    required: props.camoActivity?.required ?? false,
    date: now.value,
    name: props.camoActivity?.name ?? null,
    description: props.camoActivity?.description ?? null,
    estimate_time: props.camoActivity?.estimate_time ?? null,
    started_at: props.camoActivity?.started_at ?? null,
    completed_at: props.camoActivity?.completed_at ?? null,
    status: props.camoActivity?.status ?? "pending",
    comments: props.camoActivity?.comments ?? null,
    labor_mount: props.camoActivity?.labor_mount ?? null,
    material_mount: props.camoActivity?.material_mount ?? null,
    material_information: props.camoActivity?.material_information ?? null,
    awr: props.camoActivity?.awr ?? null,
    approval_status: props.camoActivity?.approval_status ?? "pending",
    priority: props.camoActivity?.priority ?? 3,
});
const estimateDuration = computed(() => {
    if (props.camo) {
        return Math.ceil(
            moment(props.camo.estimate_finish_date).diff(
                props.camo.start_date,
                "days",
            ),
        );
    } else {
        return null;
    }
});

const dailyWorkHours = Number(import.meta.env.VITE_DAILY_HOURS);
const workDaysPerWeek = Number(import.meta.env.VITE_WORK_DAYS_PER_WEEK);

const estimatedFinishDate = computed(() => {
    if (form.estimate_time && form.started_at) {
        const startDate = moment(form.started_at, "YYYY-MM-DD HH:mm");
        const estimateHours = form.estimate_time;

        // Calculamos los días estimados correctamente
        let estimatedDays = Math.ceil(estimateHours / dailyWorkHours);

        // Calculamos las semanas estimadas correctamente
        const estimatedWeeks = Math.ceil(estimatedDays / workDaysPerWeek);

        let estimatedFinishDate = startDate.clone();

        // Avanzamos los días estimados, saltando los fines de semana
        while (estimatedDays > 0) {
            // Avanza un día
            estimatedFinishDate = estimatedFinishDate.add(1, "day");

            // Si es fin de semana, salta al siguiente día laborable
            if (
                estimatedFinishDate.day() === 0 ||
                estimatedFinishDate.day() === 6
            ) {
                estimatedFinishDate = estimatedFinishDate.add(1, "day");
                while (
                    estimatedFinishDate.day() === 0 ||
                    estimatedFinishDate.day() === 6
                ) {
                    estimatedFinishDate = estimatedFinishDate.add(1, "day");
                }
            }

            estimatedDays -= 1;
        }

        return estimatedFinishDate.toDate();
    }
});

watch(
    () => form.started_at,
    (newValue) => {
        if (form.status === "pending" && newValue !== null) {
            form.status = "in_progress";
        }
    },
);

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

watch(
    () => [laborRates, form.labor_rate_id, form.estimate_time],
    ([newLaborRates, newLaborRateId, newEstimateTime]) => {
        if (newLaborRates && newLaborRateId) {
            const selected = newLaborRates.value.filter(
                (item) => item.id === newLaborRateId,
            );
            const mount = selected[0].mount;
            const result = newEstimateTime * mount;
            form.labor_mount = parseFloat(result).toFixed(2);
        }
    },
);

const emit = defineEmits(["addActivity"]);
const handleUpload = (eventData) => {
    if (eventData) {
        const toastOptions = {
            timeout: 2000,
            onClose: () => {},
        };
        toast.success(
            "The images have been uploaded successfully.",
            toastOptions,
        );
    }
};
const handleCancel = () => {
    form.reset();
    if (props.camoActivity) {
        goBack();
    } else {
        emit("addActivity", false);
    }
};
const submit = async () => {
    console.log("click");
    form.submit({
        onSuccess: () => {
            form.reset();
            emit("addActivity", false);
            router.get(route("camos.show", props.camo.id));
        },
    });
};
const goBack = () => {
    const camoId = props.camo.id;
    router.get(route("camos.show", camoId), {}, { replace: false });
};
const enableRates = computed(() => props.user.is_admin || props.user.is_super);
</script>
<template>
    <div class="py-12 bg-white rounded-md p-4">
        <h3 class="text-right">
            CAMO Estimate Duration {{ estimateDuration }} days
        </h3>
        <form @submit.prevent="submit">
            <input
                id="camo_id"
                v-model="form.camo_id"
                name="camo_id"
                type="hidden"
            />
            <InputError :message="form.errors.camo_id" class="mt-2" />
            <div
                class="flex flex-row justify-items-center items-center space-x-5 mb-4"
            >
                <div v-show="!props.user.is_owner">
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
                        :class="{
                            'border border-red-600 bg-red-200':
                                props.user.is_owner &&
                                form.approval_status === `pending`,
                        }"
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
                        :aria-readonly="props.user.is_owner"
                        :class="{
                            'input-not-allowed': props.user.is_owner,
                        }"
                        :readonly="props.user.is_owner"
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
                        :class="{
                            'input-not-allowed': props.user.is_owner,
                        }"
                        :disabled="props.user.is_owner"
                        aria-describedby="labor_rate_id"
                        aria-required="true"
                        class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="labor_rate_id"
                        required
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
                        :aria-readonly="props.user.is_owner"
                        :class="{
                            'input-not-allowed': props.user.is_owner,
                        }"
                        :readonly="props.user.is_owner"
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
                        :aria-readonly="props.user.is_owner"
                        :class="{
                            'input-not-allowed': props.user.is_owner,
                        }"
                        :readonly="props.user.is_owner"
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
                        :aria-readonly="props.user.is_owner"
                        :class="{
                            'input-not-allowed': props.user.is_owner,
                        }"
                        :readonly="props.user.is_owner"
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
                        :aria-readonly="props.user.is_owner"
                        :class="{
                            'input-not-allowed': props.user.is_owner,
                        }"
                        :readonly="props.user.is_owner"
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
                        :aria-readonly="props.user.is_owner"
                        :class="{
                            'input-not-allowed': props.user.is_owner,
                        }"
                        :readonly="props.user.is_owner"
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
                        v-model="form.started_at"
                        :aria-readonly="props.user.is_owner"
                        :class="{
                            'input-not-allowed': props.user.is_owner,
                        }"
                        :readonly="props.user.is_owner"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="start_date"
                        type="datetime-local"
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
                        :aria-disabled="props.user.is_owner"
                        :class="{
                            'input-not-allowed': props.user.is_owner,
                        }"
                        :disabled="props.user.is_owner"
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
                        :aria-readonly="!enableRates"
                        :class="{ 'input-not-allowed': !enableRates }"
                        :readonly="!enableRates"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-right"
                        name="labor_mount"
                        step="0.1"
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
                        :aria-readonly="!enableRates"
                        :class="{ 'input-not-allowed': !enableRates }"
                        :readonly="!enableRates"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-right"
                        name="material_mount"
                        placeholder="0.00"
                        step="0.1"
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
                    :aria-readonly="props.user.is_owner"
                    :class="{
                        'input-not-allowed': props.user.is_owner,
                    }"
                    :readonly="props.user.is_owner"
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
                    :aria-readonly="props.user.is_owner"
                    :class="{
                        'input-not-allowed': props.user.is_owner,
                    }"
                    :readonly="props.user.is_owner"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    name="completed_date"
                    type="date"
                />
                <InputError :message="form.errors.completed_at" class="mt-2" />
            </div>

            <UploadImage
                v-if="camoActivity && props.user.is_cam"
                :id="camoActivity.id"
                @uploaded="handleUpload"
            />

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
        <h3 v-if="form.started_at" class="text-right">
            Estimate Finish Date <br />
            {{ estimatedFinishDate }}
        </h3>
    </div>
</template>
