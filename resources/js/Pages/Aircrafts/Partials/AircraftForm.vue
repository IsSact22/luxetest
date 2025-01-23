<script setup>
import { useForm } from "laravel-precognition-vue-inertia";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { onMounted, ref } from "vue";
import { useToast } from "vue-toastification";
import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const toast = useToast();
const props = defineProps({
    aircraft: {
        type: Object,
    },
});
const modelAircraftOptions = ref([]);
const getModelAircraftOptions = async () => {
    try {
        const response = await axios.get(route("model-aircrafts.select"));
        modelAircraftOptions.value = response.data;
    } catch (e) {
        console.error(e);
    }
};
onMounted(getModelAircraftOptions);
const method = props.aircraft ? "put" : "post";
const url = props.aircraft ? `/aircrafts/${props.aircraft.id}` : `/aircrafts`;
const form = useForm(method, url, {
    model_aircraft_id: props.aircraft?.model_aircraft.id ?? null,
    register: props.aircraft?.register ?? "",
    serial: props.aircraft?.serial ?? "",
});

const emit = defineEmits(["close"]);
const submit = () => {
    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit("close", true);
            toast.success("Avión creado");
        },
    });
};
const cancel = () => {
    form.clearErrors();
    form.reset();
    router.get(route("aircrafts.index"));
};
</script>
<template>
    <form
        class="justify-items-center items-center px-7 py-3 space-y-5"
        @submit.prevent="submit"
    >
        <div class="my-2">
            <label class="block" for="model_aircraft_id">{{
                $t("Model")
            }}</label>
            <select
                id="model_aircraft_id"
                v-model="form.model_aircraft_id"
                class="w-full mt-1 block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="model_aircraft_id"
            >
                <option :value="null">{{ $t("Select") }}</option>
                <option
                    v-for="(item, idx) in modelAircraftOptions"
                    :key="idx"
                    :value="item.value"
                    v-html="item.label"
                ></option>
            </select>
        </div>
        <div class="my-2">
            <label class="block" for="register"
                >{{ $t("Registration") }}/{{ $t("Enrollment") }}</label
            >
            <input
                id="register"
                v-model="form.register"
                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm uppercase"
                name="register"
                type="text"
                @change="form.validate('register')"
            />
            <InputError :message="form.errors.register" class="mt-2" />
        </div>
        <div class="my-2">
            <label class="block" for="serial">{{ $t("Serial") }}</label>
            <input
                id="serial"
                v-model="form.serial"
                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm uppercase"
                name="serial"
                type="text"
                @change="form.validate('serial')"
            />
            <InputError :message="form.errors.serial" class="mt-2" />
        </div>
        <div class="flex flex-row justify-around items-center space-x-7 my-2">
            <SecondaryButton @click="cancel">{{
                $t("Cancel")
            }}</SecondaryButton>
            <PrimaryButton :disable="form.processing">{{ $t("Save") }}</PrimaryButton>
        </div>
    </form>
</template>
