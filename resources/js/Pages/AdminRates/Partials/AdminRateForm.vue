<script setup>
import { useForm } from "laravel-precognition-vue-inertia";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useToast } from "vue-toastification";

const toast = useToast();
const props = defineProps({
    adminRate: {
        type: Object,
    },
});
const method = props.adminRate ? "put" : "post";
const url = props.adminRate
    ? `/admin-rates/${props.adminRate.id}`
    : "/admin-rates";
const form = useForm(method, url, {
    name: props.adminRate?.name ?? "",
    description: props.adminRate?.description ?? "",
});
const submit = () => {
    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit("showForm", false);
            toast.success("Admin Rate created");
        },
    });
};
const emit = defineEmits(["showForm"]);
const cancel = () => {
    form.clearErrors();
    form.reset();
    emit("showForm", false);
};
</script>

<template>
    <h2 v-if="props.adminRate" class="my-2">Edit Rate</h2>
    <h2 v-else class="my-2">New Rate</h2>
    <form class="bg-white p-4" @submit.prevent="submit">
        <div>
            <InputLabel for="name">Name</InputLabel>
            <input
                id="name"
                v-model="form.name"
                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="code"
                required
                type="text"
                @change="form.validate('name')"
            />
            <InputError v-if="form.errors.name" :message="form.errors.name" />
        </div>

        <div>
            <InputLabel for="description">Description</InputLabel>
            <input
                id="description"
                v-model="form.description"
                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="description"
                required
                type="text"
                @change="form.validate('description')"
            />
            <InputError
                v-if="form.errors.description"
                :message="form.errors.description"
            />
        </div>

        <div
            class="flex flex-row justify-items-center items-center space-x-7 my-2"
        >
            <PrimaryButton v-if="form.isDirty" type="submit"
                >Save
            </PrimaryButton>
            <SecondaryButton @click="cancel">Cancel</SecondaryButton>
        </div>
    </form>
</template>

<style scoped></style>
