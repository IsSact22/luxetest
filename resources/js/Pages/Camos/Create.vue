<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { route } from "ziggy-js";
import { onMounted, ref } from "vue";
import { useCamoStore } from "@/Stores/useCamoStore.js";
import { storeToRefs } from "pinia";
import { useToast } from "vue-toastification";
import CamoForm from "@/Pages/Camos/Partials/CamoForm.vue";

const toast = useToast();
const storeCamo = useCamoStore();
const {
    duration,
    currentStep,
    nextStep,
    previousStep,
    camId,
    camoForm,
    activity,
    camoActivities,
    customer,
    editId,
    editEnable,
} = storeToRefs(storeCamo);

onMounted(() => {
    const camId = usePage().props.auth.user.id ?? null;
    storeCamo.setCamId(camId);
});

const hasPicture = ref(false);
const cams = ref(null);
const getCams = async () => {
    try {
        const response = await axios.get(route("cams.select"));
        cams.value = response.data.cams;
    } catch (e) {
        console.error(e);
    }
};
const owners = ref(null);
const getOwners = async () => {
    try {
        const response = await axios.get(route("owners.select"));
        owners.value = response.data.owners;
    } catch (e) {
        console.error(e);
    }
};

const aircrafts = ref(null);
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

const handlePrimarySubmit = () => {
    storeCamo.nextStep();
};
const handlePreviousStep = () => {
    storeCamo.previousStep();
};
const statusList = ref([
    { value: null, label: "Select" },
    { value: "pending", label: "Pending" },
    { value: "in_progress", label: "in Progress" },
    { value: "completed", label: "completed" },
]);
const handleAddActivity = (data) => {
    storeCamo.addActivity(data);
    storeCamo.activity.reset();
};
</script>
<template>
    <Head title="Camos" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Camos</h2>
        </template>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 my-2">
            <CamoForm :cam-id="usePage().props.auth.user.id" :mode="1" />
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
