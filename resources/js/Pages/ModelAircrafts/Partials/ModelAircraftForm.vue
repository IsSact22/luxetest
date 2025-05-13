<script setup>
import { useForm } from "laravel-precognition-vue-inertia";
import { onMounted, ref } from "vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { route } from "ziggy-js";
import { useToast } from "vue-toastification";
import { router } from "@inertiajs/vue3";

const toast = useToast();
const props = defineProps({
    modelAircraft: {
        type: Object,
    },
});
const emit = defineEmits(["close"]);
const method = props.modelAircraft ? "put" : "post";
const url = props.modelAircraft
    ? `/model-aircrafts/${props.modelAircraft.id}`
    : "/model-aircrafts";
const form = useForm(method, url, {
    name: props.modelAircraft?.name ?? "",
    brand_aircraft_id: props.modelAircraft?.brand_aircraft.id ?? null,
    engine_type_id: props.modelAircraft?.engine_type.id ?? null,
});
const brandOptions = ref(null);
const engineTypeOptions = ref(null);
const getBrandOptions = async () => {
    try {
        const response = await axios.get(route("brand-aircrafts.select"));
        brandOptions.value = response.data;
    } catch (e) {
        console.error(e);
    }
};
const getEngineTypeOptions = async () => {
    try {
        const response = await axios.get(route("engine-types.select"));
        engineTypeOptions.value = response.data;
    } catch (e) {
        console.error(e);
    }
};
onMounted(getBrandOptions);
onMounted(getEngineTypeOptions);
const submit = () => {
    console.log(form.data());
    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            if (method === "put") {
                toast.success("Modelo actualizado exitosamente.");
            } else {
                toast.success("Modelo creado exitosamente.");
            }
            emit("close", true);
        },
    });
};
const cancel = () => {
    form.clearErrors();
    form.reset();
    emit('close');
};
</script>
<template>
    <form class="px-4 py-2" @submit.prevent="submit">
        <div>
            <label class="block" for="brand_aircraft_id">Marca</label>
            <select
                id="brand_aircraft_id"
                v-model="form.brand_aircraft_id"
                class="w-full mt-1 block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="brand_aircraft_id"
                required
            >
                <option :value="null">{{ $t("Select") }}</option>
                <option
                    v-for="(item, idx) in brandOptions"
                    :key="idx"
                    :value="item.value"
                    class="uppercase"
                    v-html="item.label"
                ></option>
            </select>
            <InputError
                v-if="form.errors.brand_aircraft_id"
                :message="form.errors.brand_aircraft_id"
            />
        </div>
        <div>
            <label class="block" for="engine_type_id">Tipo de Motor(es)</label>
            <select
                id="engine_type_id"
                v-model="form.engine_type_id"
                class="w-full mt-1 block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="engine_type_id"
                required
            >
                <option :value="null">{{ $t("Select") }}</option>
                <option
                    v-for="(item, idx) in engineTypeOptions"
                    :key="idx"
                    :value="item.value"
                    class="uppercase"
                    v-html="item.label"
                ></option>
            </select>
            <InputError
                v-if="form.errors.engine_type_id"
                :message="form.errors.engine_type_id"
            />
        </div>
        <div>
            <label class="block" for="name">Nombre del Modelo</label>
            <input
                id="name"
                v-model="form.name"
                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm uppercase"
                name="name"
                required
                type="text"
                @change="form.validate('name')"
            />
            <InputError :message="form.errors.name" class="mt-2" />
        </div>
        <div
            class="flex flex-row justify-items-center items-center space-x-7 my-2"
        >
            <PrimaryButton v-if="form.isDirty" :disable="form.processing"
                >Guardar
            </PrimaryButton>
            <SecondaryButton @click="cancel">Cancelar</SecondaryButton>
        </div>
    </form>
</template>
