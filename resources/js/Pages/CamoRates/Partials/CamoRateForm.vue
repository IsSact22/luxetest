<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import {useForm} from "laravel-precognition-vue-inertia";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {computed, onMounted, ref, watchEffect} from "vue";
import {route} from "ziggy-js";
import NumberInput from "@/Components/NumberInput.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    method: {
        type: String,
        required: true,
    }
});

const formRoute = computed(() => {
    if (props.method === 'update') {
        const id = props.data.id
        return route('camo-rates.update', id);
    } else {
        return route('camo-rates.store');
    }
});

const submit = () => {
    form.submit({
        onFinish: () => console.log('submit success')
    });
}
const form = useForm(props.method, formRoute.toString(), {
    engine_type_id: props.data.engine_type.id,
    code: props.data.code,
    name: props.data.name,
    mount: props.data.mount,
});

const engineTypes = ref([]);
const getEngineTypes = async () => {
    try {
        const response = await axios.get(route('camo-rates.select'));
        engineTypes.value = response.data
    } catch (e) {
        console.error(e);
    }
}
onMounted(getEngineTypes);
</script>
<template>
    <form @submit.prevent="submit" class="flex flex-col justify-items-center space-y-5 px-4">
        <div>
            <InputLabel for="engine_type_id">Engine Type</InputLabel>
            <select
                name="engine_type_id"
                id="engine_type_id"
                class="w-full mt-1 block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.engine_type_id">
                <option value="">Select</option>
                <option v-for="(item, idx) in engineTypes" :value="item.value">{{item.label}}</option>
            </select>
            <InputError v-if="form.errors.engine_type_id" :message="form.errors.engine_type_id" />
        </div>
        <div>
            <InputLabel for="code">Code</InputLabel>
            <TextInput name="code" id="code" class="w-full" :model-value="form.code" @input="form.validate('code')"/>
            <InputError v-if="form.errors.code" :message="form.errors.code" />
        </div>
        <div>
            <InputLabel for="name">Name</InputLabel>
            <TextInput name="name" id="name" :model-value="form.name" @input="form.validate('name')" />
            <InputError v-if="form.errors.name" :message="form.errors.name" />
        </div>
        <div>
            <InputLabel for="mount">Mount</InputLabel>
            <NumberInput name="mount" id="mount" :model-value="form.mount" />
            <InputError v-if="form.errors.mount" :message="form.errors.mount" />
        </div>
        <div class="flex flex-row justify-items-center items-center space-x-7 my-2">
            <PrimaryButton v-if="form.isDirty">Save</PrimaryButton>
            <SecondaryButton>Cancel</SecondaryButton>
        </div>
    </form>
</template>
