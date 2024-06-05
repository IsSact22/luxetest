<script setup>
import { useForm } from "laravel-precognition-vue-inertia";
import { onMounted, ref } from "vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    modelAircraft: {
        type: Object,
    },
});
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
const submit = async () => {
    form.submit({
        preserveScroll: true,
        onSuccess: () => form.reset(),
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
            <label class="block" for="brand_aircraft_id">Brand</label>
            <select
                id="brand_aircraft_id"
                v-model="form.brand_aircraft_id"
                class="w-full mt-1 block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="brand_aircraft_id"
                required
            >
                <option :value="null">Select</option>
                <option
                    v-for="(item, idx) in brandOptions"
                    :key="idx"
                    :value="item.value"
                    class="uppercase"
                    v-html="item.label"
                ></option>
            </select>
        </div>
        <div>
            <label class="block" for="engine_type_id">Engine Type</label>
            <select
                id="engine_type_id"
                v-model="form.engine_type_id"
                class="w-full mt-1 block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="engine_type_id"
                required
            >
                <option :value="null">Select</option>
                <option
                    v-for="(item, idx) in engineTypeOptions"
                    :key="idx"
                    :value="item.value"
                    class="uppercase"
                    v-html="item.label"
                ></option>
            </select>
        </div>
        <div>
            <label class="block" for="name">Name</label>
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
                >Save</PrimaryButton
            >
            <SecondaryButton @click="cancel">Cancel</SecondaryButton>
        </div>
    </form>
</template>
