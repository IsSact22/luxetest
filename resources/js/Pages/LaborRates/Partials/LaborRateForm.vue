<script setup>
import { useForm } from "laravel-precognition-vue-inertia";
import { onMounted, ref } from "vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { route } from "ziggy-js";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    laborRate: {
        type: Object,
    },
});
const engineTypes = ref([]);
const getEngineTypes = async () => {
    try {
        const response = await axios.get(route("engine-types.select"));
        engineTypes.value = response.data;
    } catch (e) {
        console.error(e);
    }
};
const method = props.laborRate ? "put" : "post";
const url = props.laborRate
    ? `/labor-rates/${props.laborRate.id}`
    : "/labor-rates";
const form = useForm(method, url, {
    engine_type_id: props.laborRate?.rateable.id ?? null,
    code: props.laborRate?.code ?? null,
    name: props.laborRate?.name ?? null,
    mount: props.laborRate?.mount ?? null,
});
onMounted(getEngineTypes);
const submit = async () => {
    form.submit({
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
const cancel = () => {
    form.clearErrors();
    form.reset();
    router.get(route("labor-rates.index"));
};
</script>
<template>
    <form
        class="flex flex-col justify-items-center items-stretch space-y-3"
        @submit.prevent="submit"
    >
        <div>
            <InputLabel for="engine_type_id"
                >{{ $t("Engine Type") }}
            </InputLabel>
            <select
                id="engine_type_id"
                v-model="form.engine_type_id"
                class="w-full mt-1 block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="engine_type_id"
                required
            >
                <option :value="null">{{ $t("Select") }}</option>
                <option
                    v-for="(item, idx) in engineTypes"
                    :value="item.value"
                    class="uppercase"
                >
                    {{ item.label }}
                </option>
            </select>
            <InputError
                v-if="form.errors.engine_type_id"
                :message="form.errors.engine_type_id"
            />
        </div>
        <div>
            <InputLabel for="code">{{ $t("Code") }}</InputLabel>
            <input
                id="code"
                v-model="form.code"
                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="code"
                required
                type="text"
                @change="form.validate('code')"
            />
            <InputError v-if="form.errors.code" :message="form.errors.code" />
        </div>
        <div>
            <InputLabel for="name">{{ $t("Name") }}</InputLabel>
            <input
                id="name"
                v-model="form.name"
                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="name"
                required
                type="text"
                @change="form.validate('name')"
            />
            <InputError v-if="form.errors.name" :message="form.errors.name" />
        </div>
        <div>
            <InputLabel for="mount">Monto</InputLabel>
            <input
                id="mount"
                v-model="form.mount"
                class="w-full text-right border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="mount"
                required
                type="number"
            />
            <InputError v-if="form.errors.mount" :message="form.errors.mount" />
        </div>
        <div
            class="flex flex-row justify-items-center items-center space-x-7 my-2"
        >
            <SecondaryButton @click="cancel">Cancelar</SecondaryButton>
            <PrimaryButton type="submit">Guardar</PrimaryButton>
        </div>
    </form>
</template>
