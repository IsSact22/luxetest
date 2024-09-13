<script setup>
import { useForm } from "laravel-precognition-vue-inertia";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { onMounted, ref } from "vue";
import { useToast } from "vue-toastification";

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
const submit = () => {
    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.success("Aircraft created");
        },
    });
};
const cancel = () => {
    form.clearErrors();
    form.reset();
};
</script>
<template>
    <form @submit.prevent="submit">
        <div>
            <label class="block" for="model_aircraft_id">Model Aircraft</label>
            <select
                id="model_aircraft_id"
                v-model="form.model_aircraft_id"
                class="w-full mt-1 block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="model_aircraft_id"
            >
                <option :value="null">Select</option>
                <option
                    v-for="(item, idx) in modelAircraftOptions"
                    :key="idx"
                    :value="item.value"
                    v-html="item.label"
                ></option>
            </select>
        </div>
        <div>
            <label class="block" for="register">Register</label>
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
        <div>
            <label class="block" for="serial">Serial</label>
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
        <div
            class="flex flex-row justify-items-center items-center space-x-7 my-2"
        >
            <PrimaryButton v-if="form.isDirty" :disable="form.processing"
                >Save
            </PrimaryButton>
            <SecondaryButton @click="cancel">Cancel</SecondaryButton>
        </div>
    </form>
</template>
