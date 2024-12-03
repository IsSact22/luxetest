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

const formattedStartDate = ref(
    moment(props.camo.start_date).format("YYYY-MM-DDTHH:mm"),
);

// Computed property to format startDate as datetime-local (YYYY-MM-DDTHH:mm)
/*watch(
    () => startDate.value,
    (newValue) => {
        const momentDate = moment(newValue.value);
        momentDate.set({ hour: 8, minute: 0 });
        return momentDate.format("YYYY-MM-DDTHH:mm");
    }
)*/

/*const formattedStartDate = computed(() => {
    // Asegúrate de que startDate no sea nulo antes de intentar formatearlo
    if (startDate.value) {
        // Convertir startDate a un objeto moment
        const momentDate = moment(startDate.value);
        // Establecer la hora a 08:00
        momentDate.set({ hour: 8, minute: 0 });
        // Retornar el valor formateado para datetime-local
        return momentDate.format("YYYY-MM-DDTHH:mm");
    }
    return moment().format("YYYY-MM-DDTHH:mm");
});*/

const form = useForm(method, url, {
    camo_id: props.camo?.id ?? null,
    labor_rate_id: props.camoActivity?.labor_rate_id ?? null,
    required: props.camoActivity?.required ?? false,
    date: now.value,
    name: props.camoActivity?.name ?? null,
    description: props.camoActivity?.description ?? null,
    estimate_time: props.camoActivity?.estimate_time ?? null,
    started_at: props.camoActivity?.started_at
        ? props.camoActivity.started_at
        : formattedStartDate,
    completed_at: props.camoActivity?.completed_at ?? null,
    status: props.camoActivity?.status ?? "pending",
    comments: props.camoActivity?.comments ?? null,
    special_rate: props.camoActivity?.special_rate?.amount ?? null,
    labor_mount: props.camoActivity?.labor_mount ?? null,
    material_mount: props.camoActivity?.material_mount ?? null,
    material_information: props.camoActivity?.material_information ?? null,
    awr: props.camoActivity?.awr ?? null,
    approval_status: props.camoActivity?.approval_status ?? "pending",
    priority: props.camoActivity?.priority ?? 3,
});

onMounted(() => {
    if (props.camoActivity && props.camoActivity.started_at) {
        form.started_at = props.camoActivity.started_at;
    } else {
        form.started_at = formattedStartDate.value; // Usa el valor formateado
    }
});

// Validación para que started_at no sea menor que startDate a las 8:00
watch(
    () => form.started_at,
    (newValue) => {
        if (
            moment(newValue).isBefore(
                moment(formattedStartDate.value).set({ hour: 8, minute: 0 }),
            )
        ) {
            form.errors.started_at =
                "La fecha de inicio no puede ser anterior a la fecha de inicio del proyecto a las 8:00.";
        } else {
            delete form.errors.started_at; // Elimina el error si la validación es exitosa
        }
    },
);

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
        // Asegúrate de que se esté usando el formato correcto al parsear
        const startDate = moment(form.started_at); // Cambié el formato a solo form.started_at
        const estimateHours = form.estimate_time;

        // Calculamos los días estimados correctamente
        let estimatedDays = Math.ceil(estimateHours / dailyWorkHours);

        // Inicializa la fecha estimada de finalización
        let estimatedFinishDate = startDate.clone();

        // Avanza los días estimados, saltando los fines de semana
        while (estimatedDays > 0) {
            // Avanza un día
            estimatedFinishDate = estimatedFinishDate.add(1, "day");

            // Si es fin de semana, salta al siguiente día laborable
            if (
                estimatedFinishDate.day() === 0 ||
                estimatedFinishDate.day() === 6
            ) {
                estimatedFinishDate = estimatedFinishDate.add(1, "day");
            }

            estimatedDays -= 1;
        }

        return estimatedFinishDate.toDate(); // Devuelve la fecha como objeto Date
    }
    return null; // Devuelve un valor nulo si no se cumplen las condiciones
});

/*watch(
    () => form.started_at,
    (newValue) => {
        if (form.status === "pending" && newValue !== null) {
            form.status = "in_progress";
        }
    },
);*/

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
    { value: "pending", label: "Pendiente", selected: false },
    { value: "in_progress", label: "en Progreso", selected: false },
    { value: "completed", label: "completado", selected: false },
]);
const statusApproval = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get(route("approval-status"));
        statusApproval.value = response.data;
    } catch (e) {
        console.error(e);
    }
});

watch(
    () => form.special_rate,
    (newValue) => {
        if (newValue === "") {
            form.special_rate = null;
        }
    },
);

watch(
    () => [
        laborRates,
        form.labor_rate_id,
        form.estimate_time,
        form.special_rate,
    ],
    ([newLaborRates, newLaborRateId, newEstimateTime, newSpecialRate]) => {
        let mount;

        // Verifica si hay una tasa especial válida
        if (newSpecialRate !== null && newSpecialRate > 0) {
            mount = newSpecialRate;
        } else if (newLaborRates && newLaborRateId) {
            const selected = newLaborRates.value.filter(
                (item) => item.id === newLaborRateId,
            );
            mount = selected[0]?.amount; // Usar el monto de la tasa laboral seleccionada
        }

        // Si se obtuvo un monto (ya sea de la tasa especial o de la tasa laboral), calcula el monto laboral
        if (mount && newEstimateTime) {
            const result = newEstimateTime * mount;
            form.labor_mount = parseFloat(result).toFixed(2);
        } else {
            form.labor_mount = null; // Si no hay monto, se puede establecer como nulo
        }
    },
);

// Habilitar o deshabilitar el campo based on status
const enableCompletedAt = computed(() => {
    return form.status !== "completed"; // 'true' si el estado es "completed", 'false' en caso contrario
});

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
const enableSpecialRate = computed(() => props.user.is_admin);
</script>
<template>
    <div class="py-12 bg-white rounded-md p-4">
        <h3 class="text-right">
            Duración estimada: {{ estimateDuration }} dia(s)
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
                        <span class="font-bold">Actividad Obligatoria</span>
                        <Checkbox
                            v-model="form.required"
                            :checked="form.required"
                            value="true"
                        />
                    </label>
                </div>

                <div>
                    <InputLabel
                        :value="`Estatus de Aprobación`"
                        for="approval_status"
                    />
                    <select
                        id="approval_status"
                        v-model="form.approval_status"
                        :class="{
                            'border-2 border-orange-400':
                                props.user.is_owner &&
                                form.approval_status === `pending`,
                        }"
                        :disabled="!props.user.is_owner"
                        class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="approval_status"
                    >
                        <option :value="null" disabled>
                            {{ $t("Select") }}
                        </option>
                        <option
                            v-for="(item, idx) in statusApproval"
                            :key="idx"
                            :value="item.value"
                        >
                            {{ $t(item.label) }}
                        </option>
                    </select>
                    <InputError
                        :message="form.errors.approval_status"
                        class="mt-2"
                    />
                </div>

                <!--                <div>
                                    <InputLabel :value="`Prioridad`" for="priority" />
                                    <select
                                        id="priority"
                                        v-model="form.priority"
                                        class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        name="priority"
                                    >
                                        <option :value="null" disabled>Select</option>
                                        <option :value="1" class="bg-red-500">
                                            {{ $t("High Priority") }}
                                        </option>
                                        <option :value="2" class="bg-amber-300">
                                            {{ $t("Medium Priority") }}
                                        </option>
                                        <option :value="3" class="bg-blue-200">
                                            {{ $t("Low Priority") }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.priority" class="mt-2" />
                                </div>-->
            </div>

            <hr class="mb-4" />
            <div
                class="flex flex-row justify-items-center items-center space-x-5 mb-4"
            >
                <!--                <div>
                                    <InputLabel :value="`Fecha`" for="date" />
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
                                </div>-->

                <div>
                    <InputLabel :value="`Tipo`" for="labor_rate_id" />
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
                        <option :value="null" disabled>
                            {{ $t("Select") }}
                        </option>
                        <option
                            v-for="(item, idx) in laborRates"
                            :key="idx"
                            :value="item.id"
                        >
                            {{ item.label }}
                        </option>
                    </select>
                    <InputError
                        :message="form.errors.labor_rate_id"
                        class="mt-2"
                    />
                </div>

                <!-- tarifa especial -->
                <div v-if="props.user.is_admin">
                    <InputLabel :value="`Tarifa Especial`" for="special_rate" />
                    <input
                        id="special_rate"
                        v-model="form.special_rate"
                        :aria-readonly="!enableSpecialRate"
                        :class="{ 'input-not-allowed': !enableSpecialRate }"
                        :readonly="!enableSpecialRate"
                        class="w-32 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-right"
                        name="special_rate"
                        step="0.1"
                        type="number"
                    />
                </div>
            </div>

            <div
                class="flex flex-row justify-items-center items-center space-x-5 mb-4"
            >
                <div>
                    <InputLabel :value="`Nombre`" for="name" />
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
                        placeholder="Nombre de la actividad"
                        type="text"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <div>
                    <InputLabel
                        :value="`Tiempo Estimado Hrs/Hom`"
                        for="estimate_time"
                    />
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
                    <InputLabel :value="`Descripción`" for="description" />
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
                        placeholder="Descripción de la actividad"
                        rows="3"
                    ></textarea>
                    <InputError
                        :message="form.errors.description"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel :value="`Comentarios`" for="comments" />
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
                        placeholder="Comentarios"
                        rows="3"
                    ></textarea>
                    <InputError :message="form.errors.comments" class="mt-2" />
                </div>

                <div>
                    <InputLabel
                        :value="`Información de Materiales`"
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
                        placeholder="Información sobre materiales"
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
                <div v-if="form.started_at">
                    <InputLabel :value="`Fecha de Inicio`" for="started_at" />
                    <input
                        id="started_at"
                        v-model="form.started_at"
                        :aria-readonly="props.user.is_owner"
                        :class="{
                            'input-not-allowed': props.user.is_owner,
                        }"
                        :min="formattedStartDate"
                        :readonly="props.user.is_owner"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="started_at"
                        type="datetime-local"
                    />
                    <InputError
                        :message="form.errors.started_at"
                        class="mt-2"
                    />
                </div>

                <div v-if="props.user.is_admin">
                    <InputLabel :value="`Hrs/Hom Monto`" for="labor_mount" />
                    <input
                        id="labor_mount"
                        v-model="form.labor_mount"
                        :aria-readonly="true"
                        :readonly="true"
                        class="w-32 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-right input-not-allowed"
                        name="labor_mount"
                        step="0.1"
                        type="number"
                    />
                    <InputError
                        :message="form.errors.labor_mount"
                        class="mt-2"
                    />
                </div>

                <div v-if="props.user.is_admin">
                    <InputLabel
                        :value="`Materiales Monto`"
                        for="material_mount"
                    />
                    <input
                        id="material_mount"
                        v-model="form.material_mount"
                        :aria-readonly="true"
                        :readonly="true"
                        class="w-32 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-right input-not-allowed"
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

                <div>
                    <InputLabel :value="`Estatus/Actividad`" for="status" />
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
                        <option :value="null" disabled>
                            {{ $t("Select") }}
                        </option>
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
                    <InputLabel
                        :value="`Fecha de finalización`"
                        for="completed_date"
                    />
                    <input
                        id="completed_date"
                        v-model="form.completed_at"
                        :aria-readonly="props.user.is_owner"
                        :class="{
                            'input-not-allowed': props.user.is_owner,
                        }"
                        :disabled="enableCompletedAt"
                        :min="formattedStartDate"
                        :readonly="props.user.is_owner"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="completed_date"
                        type="datetime-local"
                    />
                    <InputError
                        :message="form.errors.completed_at"
                        class="mt-2"
                    />
                </div>
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
                <SecondaryButton @click="handleCancel"
                    >Cancelar
                </SecondaryButton>
            </div>
        </form>
        <h3 v-if="form.started_at && estimatedFinishDate" class="text-right">
            <small>Fecha estimada de finalización</small> <br />
            <small>{{ estimatedFinishDate }}</small>
        </h3>
    </div>
</template>
