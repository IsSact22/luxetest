<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import { useToast } from "vue-toastification";
import { onMounted, ref } from "vue";
import { route } from "ziggy-js";
import { useForm } from "laravel-precognition-vue-inertia";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import { router } from "@inertiajs/vue3";

const toast = useToast();
const props = defineProps({
    camo: {
        type: Object,
    },
    camId: {
        type: Number,
        required: true,
    },
});
const cams = ref(null);
const owners = ref(null);
const aircrafts = ref(null);
const getCams = async () => {
    try {
        const response = await axios.get(route("cams.select"));
        cams.value = response.data.cams;
    } catch (e) {
        console.error(e);
    }
};
const getOwners = async () => {
    try {
        const response = await axios.get(route("owners.select"));
        owners.value = response.data.owners;
    } catch (e) {
        console.error(e);
    }
};
const getAircrafts = async () => {
    try {
        const response = await axios.get(route("aircrafts.select"));
        aircrafts.value = response.data;
    } catch (e) {
        console.error(e);
    }
};
onMounted(getCams);
onMounted(getOwners);
onMounted(getAircrafts);
const method = props.camo ? "put" : "post";
const url = props.camo ? `/camos/${props.camo.id}` : "/camos";
const form = useForm(method, url, {
    customer: props.camo?.customer ?? "",
    owner_id: props.camo?.owner_id ?? null,
    contract: props.camo?.contract ?? "",
    cam_id: props.camId ?? null,
    aircraft_id: props.camo?.aircraft ?? null,
    description: props.camo?.description ?? "",
    start_date: props.camo?.start_date ?? null,
    estimate_finish_date: props.camo?.estimate_finish_date ?? null,
    finish_date: props.camo?.finish_date ?? null,
    location: props.camo?.location ?? "OMZ",
});

const submit = async () => {
    console.log("click");
    form.submit();
};
const cancel = () => {
    form.clearErrors();
    form.reset();
    router.get(route("camos.index"));
};
</script>
<template>
    <div class="py-12 bg-white rounded-md p-4">
        <form @submit.prevent="submit">
            <div
                class="flex flex-row justify-items-center items-center space-x-7 mb-4"
            >
                <div>
                    <InputLabel :value="`Jefe de Proyecto`" for="cam_id" />
                    <select
                        id="cam_id"
                        v-model="form.cam_id"
                        aria-required="true"
                        class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="cam_id"
                        required
                    >
                        <option :value="null" disabled>Select</option>
                        <option
                            v-for="(cam, idx) in cams"
                            :key="idx"
                            :value="cam.id"
                        >
                            {{ cam.name }}
                        </option>
                    </select>
                    <InputError :message="form.errors.cam_id" class="mt-2" />
                </div>
                <div>
                    <InputLabel :value="`Dueño`" for="owner_id" />
                    <select
                        id="owner_id"
                        v-model="form.owner_id"
                        aria-required="true"
                        class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="owner_id"
                        required
                    >
                        <option :value="null" disabled>Select</option>
                        <option
                            v-for="(own, idx) in owners"
                            :key="idx"
                            :value="own.id"
                        >
                            {{ own.name }}
                        </option>
                    </select>
                    <InputError :message="form.errors.owner_id" class="mt-2" />
                </div>
                <div>
                    <InputLabel :value="`Ubicación`" for="location" />
                    <input
                        id="location"
                        v-model="form.location"
                        aria-required="true"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm uppercase placeholder-custom"
                        name="location"
                        placeholder="Location Code"
                        required
                        type="text"
                    />
                    <InputError :message="form.errors.location" class="mt-2" />
                </div>
            </div>

            <div class="mb-4">
                <InputLabel :value="`Avión`" for="aircraft_id" />
                <select
                    id="aircraft_id"
                    v-model="form.aircraft_id"
                    aria-required="true"
                    class="mt-1 block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    name="aircraft_id"
                    required
                >
                    <option :value="null" disabled>Select</option>
                    <option
                        v-for="(item, idx) in aircrafts"
                        :key="idx"
                        :value="item.id"
                    >
                        {{ item.model_aircraft.name }}
                        {{ item.register }}
                    </option>
                </select>
                <InputError :message="form.errors.aircraft_id" class="mt-2" />
            </div>

            <div
                class="flex flex-row justify-items-center items-center space-x-7 mb-4"
            >
                <div>
                    <InputLabel :value="`Cliente`" for="customer" />
                    <input
                        id="customer"
                        v-model="form.customer"
                        aria-required="true"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm capitalize placeholder-custom"
                        name="customer"
                        placeholder="It must be a unique name"
                        required
                        type="text"
                    />
                    <InputError :message="form.errors.customer" class="mt-2" />
                </div>

                <div>
                    <InputLabel :value="`Contrato`" for="contract" />
                    <input
                        id="contract"
                        v-model="form.contract"
                        aria-required="true"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm capitalize placeholder-custom"
                        name="contract"
                        placeholder="Quote or Contract number"
                        required
                        type="text"
                        @change="form.validate('contract')"
                    />
                    <InputError :message="form.errors.contract" class="mt-2" />
                </div>
            </div>

            <div class="mb-4">
                <InputLabel :value="`Notas (CAMO)`" for="description" />
                <textarea
                    id="description"
                    v-model="form.description"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm placeholder-custom"
                    cols="50"
                    name="description"
                    placeholder="General description"
                    rows="3"
                ></textarea>
                <InputError :message="form.errors.description" class="mt-2" />
            </div>

            <div
                class="flex flex-row justify-items-center items-center space-x-7 mb-4"
            >
                <div>
                    <InputLabel :value="$t(`Start Date`)" for="start_date" />
                    <input
                        id="start_date"
                        v-model="form.start_date"
                        aria-required="true"
                        class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="start_date"
                        required
                        type="date"
                    />
                    <InputError
                        :message="form.errors.start_date"
                        class="mt-2"
                    />
                </div>
                <div>
                    <InputLabel
                        :value="$t(`Estimate Finish Date`)"
                        for="estimate_finish_date"
                    />
                    <input
                        id="estimate_finish_date"
                        v-model="form.estimate_finish_date"
                        aria-required="true"
                        class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="estimate_finish_date"
                        required
                        type="date"
                    />
                    <InputError
                        :message="form.errors.estimate_finish_date"
                        class="mt-2"
                    />
                </div>
                <div v-if="props.camo">
                    <InputLabel :value="$t(`Finish Date`)" for="finish_date" />
                    <input
                        id="finish_date"
                        v-model="form.finish_date"
                        aria-required="false"
                        class="block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="finish_date"
                        type="date"
                    />
                    <InputError
                        :message="form.errors.finish_date"
                        class="mt-2"
                    />
                </div>
            </div>
            <div
                class="flex flex-row justify-items-center items-center space-x-7 my-2"
            >
                <PrimaryButton
                    v-if="form.isDirty"
                    :disable="form.processing"
                    type="submit"
                    >Guardar
                </PrimaryButton>
                <SecondaryButton @click="cancel">Cancelar</SecondaryButton>
            </div>
        </form>
    </div>
</template>
