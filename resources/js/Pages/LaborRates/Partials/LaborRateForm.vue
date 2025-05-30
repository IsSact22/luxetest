<script setup>
import { useForm } from "laravel-precognition-vue-inertia";
import { computed, onMounted, ref, watch } from "vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { route } from "ziggy-js";
import { router } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

const toast = useToast();
const props = defineProps({
    laborRate: {
        type: Object,
    },
});

const rates = ref([]);
const adminRate = ref([]);

const method = props.laborRate ? "put" : "post";
const url = props.laborRate
    ? `/labor-rates/${props.laborRate.id}`
    : "/labor-rates";
const form = useForm(method, url, {
    rateable_id: props.laborRate?.rateable.id ?? null,
    rateable_type: props.laborRate?.rateable_type ?? null,
    code: props.laborRate?.code ?? "",
    name: props.laborRate?.name ?? "",
    amount: props.laborRate?.amount ?? null,
});
const isLoading = ref(false);
const fetchRates = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get(route("admin-rates.select"));
        rates.value = response.data.map((rate) => ({
            rateable_id: rate.rateable_id,
            rateable_type: rate.rateable_type,
            label: rate.label,
        }));
    } catch (e) {
        console.error(e);
    } finally {
        isLoading.value = false;
    }
};

// Observador para actualizar rateable_type basado en rateable_id
watch(
    () => form.rateable_id,
    (newValue) => {
        const selectedRate = rates.value.find(
            (rate) => rate.rateable_id === newValue,
        );
        form.rateable_type = selectedRate ? selectedRate.rateable_type : null; // Asigna el tipo correspondiente
        // console.log(form.rateable_type);
    },
);

// Efecto al montar
onMounted(async () => {
    await fetchRates();
    // await fetchAdminRates();
});
const submitForm = () => {
    if (!form.rateable_id || !form.rateable_type) {
        toast.error('Por favor seleccione una tarifa');
        return;
    }
    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            const message = props.laborRate ? "Tarifa laboral actualizada" : "Tarifa laboral creada";
            toast.success(message);
            form.reset();
            router.visit(route("labor-rates.index"));
        },
        onError: (errors) => { 
            console.log(errors);
            toast.error('Por favor verificar los datos ingresados');
        }
    });
};

// Validaciones y computados
const hasErrors = computed(() => !!Object.keys(form.errors).length);
const resetForm = () => {
    form.clearErrors();
    form.reset();
};

const cancel = () => {
    resetForm();
    router.get(route("labor-rates.index"));
};
</script>

<template>
    <form class="flex flex-col space-y-3" @submit.prevent="submitForm">
        <div>
            <InputLabel for="rateable_id">Tarifas</InputLabel>
            <select
                id="rateable_id"
                v-model="form.rateable_id"
                class="w-full mt-1 border-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                required
                @change="form.validate('rateable_id')"
            >
                <option :value="null">{{ $t("Select") }}</option>
                <option
                    v-for="(item, idx) in rates"
                    :key="idx"
                    :value="item.rateable_id"
                >
                    {{ item.label }}
                </option>
            </select>
            <InputError
                v-if="form.errors.rateable_id"
                :message="form.errors.rateable_id"
            />
            <input
                id="rateable_type"
                v-model="form.rateable_type"
                name="rateable_type"
                type="hidden"
            />
            <InputError
                v-if="form.errors.rateable_type"
                :message="form.errors.rateable_type"
            />
        </div>

        <div>
            <InputLabel for="code">{{ $t("Code") }}</InputLabel>
            <input
                id="code"
                v-model="form.code"
                class="w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                required
                type="text"
                @change="form.validate('code')"
            />
            <InputError v-if="form.errors.code" :message="form.errors.code" />
        </div>

        <div>
            <InputLabel for="name">{{ $t("Name") }}</InputLabel>
            <input
                id="name"
                v-model="form.name"
                class="w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                required
                type="text"
                @change="form.validate('name')"
            />
            <InputError v-if="form.errors.name" :message="form.errors.name" />
        </div>

        <div>
            <InputLabel for="amount">Monto</InputLabel>
            <input
                id="amount"
                v-model.number="form.amount"
                class="w-full text-right border-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                required
                type="number"
                step="0.01"
                min="0"
                @change="form.validate('amount')"
            />
            <InputError v-if="form.errors.amount" :message="form.errors.amount" />
        </div>

        <div class="flex space-x-7 my-2">
            <SecondaryButton @click="cancel">Cancelar</SecondaryButton>
            <PrimaryButton :disabled="isLoading" type="submit"
                >Guardar
            </PrimaryButton>
        </div>
    </form>
</template>
