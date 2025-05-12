<script setup>
import { useForm } from "laravel-precognition-vue-inertia";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useToast } from "vue-toastification";
import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js";

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
const emit = defineEmits(["showForm"]);
const submit = () => {
    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            const message = props.adminRate ? "Tarifa actualizada" : "Tarifa creada";
            toast.success(message);
            form.reset();
           router.visit(route("admin-rates.index"));
        },
        onError: (errors) =>{
            toast.error('Por favor verifica los datos ingresados');
        }
    });
};
const cancel = () => {
    form.clearErrors();
    form.reset();
    router.get(route("admin-rates.index"));
};
</script>

<template>
    <form class="bg-white p-4" @submit.prevent="submit">
        <div class="my-2">
            <InputLabel for="name">Nombre</InputLabel>
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

        <div class="my-2">
            <InputLabel for="description">Descripción</InputLabel>
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
                >Guardar
            </PrimaryButton>
            <SecondaryButton @click="cancel">Cancelar</SecondaryButton>
        </div>
    </form>
</template>

<style scoped></style>
