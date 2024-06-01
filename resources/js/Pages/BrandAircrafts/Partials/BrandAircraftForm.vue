<script setup>
import { useForm } from "laravel-precognition-vue-inertia";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    brandAircraft: {
        type: Object,
    },
});
const method = props.brandAircraft ? "put" : "post";
const url = props.brandAircraft
    ? `/brand-aircrafts/${props.brandAircraft.id}`
    : `/brand-aircrafts`;
const form = useForm(method, url, {
    name: props.brandAircraft?.name ?? "",
});
const submit = () => {
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
            <label class="block" for="name">Name</label>
            <input
                id="name"
                v-model="form.name"
                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm uppercase"
                name="name"
                type="text"
                @change="form.validate('name')"
            />
            <InputError :message="form.errors.name" class="mt-2" />
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
