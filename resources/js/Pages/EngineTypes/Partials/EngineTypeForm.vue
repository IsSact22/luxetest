<script setup>
import { useForm } from "laravel-precognition-vue-inertia";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { useToast } from "vue-toastification";
import { onMounted } from "vue";

const toast = useToast();
const props = defineProps({
    engineType: {
        type: Object,
    },
});
const method = props.engineType ? "put" : "post";
const url = props.engineType
    ? `/engine-types/${props.engineType.id}`
    : `/engine-types`;
const form = useForm(method, url, {
    name: props.engineType?.name ?? "",
});

const submit = () => {
    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            const message = props.engineType ? "Tipo de motor actualizado" : "Tipo de motor creado";
            toast.success(message);
            form.reset();
            router.visit(route("engine-types.index"));
        },
        onError: (errors) => {
            toast.error('Por favor verifica los datos ingresados');
        }
    });
};
const cancel = () => {
    form.clearErrors();
    form.reset();
    router.get(route("engine-types.index"));
};

onMounted(() => {
    console.log(props.engineType);
});
</script>
<template>
    <form @submit.prevent="submit">
        <div>
            <label class="block" for="name">{{ $t("Name") }}</label>
            <input
                id="name"
                v-model="form.name"
                :autocomplete="false"
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
                >{{ $t("Save") }}
            </PrimaryButton>
            <SecondaryButton @click="cancel">{{ $t("Cancel") }}</SecondaryButton>
        </div>
    </form>
</template>
