<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { router, useForm } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import { onMounted, ref } from "vue";
import { route } from "ziggy-js";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
const id = ref(props.resource.data.id);
const engineTypes = ref([]);
const getEngineTypes = async () => {
    try {
        const response = await axios.get(route("camo-rates.select"));
        engineTypes.value = response.data;
    } catch (e) {
        console.error(e);
    }
};
onMounted(getEngineTypes);
const form = useForm({
    engine_type_id: props.resource.data.engine_type.id,
    code: props.resource.data.code,
    name: props.resource.data.name,
    mount: props.resource.data.mount,
});
const submit = () => {
    form.patch(route("camo-rates.update", id.value));
};
const cancel = () => {
    router.get(route("camo-rates.index"));
};
</script>
<template>
    <Head title="Camo Rates" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Camo Rates</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center">
            <div class="w-3/12 my-4 border rounded-md p-4">
                <form
                    class="flex flex-col justify-items-center space-y-5 px-4"
                    @submit.prevent="submit"
                >
                    <div>
                        <InputLabel for="engine_type_id"
                            >Engine Type
                        </InputLabel>
                        <select
                            id="engine_type_id"
                            v-model="form.engine_type_id"
                            class="w-full mt-1 block border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            name="engine_type_id"
                        >
                            <option value="">Select</option>
                            <option
                                v-for="(item, idx) in engineTypes"
                                :value="item.value"
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
                        <InputLabel for="code">Code</InputLabel>
                        <input
                            id="code"
                            v-model="form.code"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            name="code"
                            type="text"
                        />
                        <InputError
                            v-if="form.errors.code"
                            :message="form.errors.code"
                        />
                    </div>
                    <div>
                        <InputLabel for="name">Name</InputLabel>
                        <input
                            id="name"
                            v-model="form.name"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            name="name"
                            type="text"
                        />
                        <InputError
                            v-if="form.errors.name"
                            :message="form.errors.name"
                        />
                    </div>
                    <div>
                        <InputLabel for="mount">Mount</InputLabel>
                        <input
                            id="mount"
                            v-model="form.mount"
                            class="w-full text-right border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            name="mount"
                            type="number"
                        />
                        <InputError
                            v-if="form.errors.mount"
                            :message="form.errors.mount"
                        />
                    </div>
                    <div
                        class="flex flex-row justify-items-center items-center space-x-7 my-2"
                    >
                        <PrimaryButton v-if="form.isDirty">Save</PrimaryButton>
                        <SecondaryButton @click="cancel"
                            >Cancel
                        </SecondaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
