<template>
    <div>
        <h2 class="text-lg font-medium text-gray-900">
            {{ $t("Update User") }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ $t("Please update your user information and email address.") }}
        </p>
        <form class="mt-6 space-y-6" @submit.prevent="submit">
            <div>
                <InputLabel :value="$t('Name')" for="name"></InputLabel>
                <TextInput
                    id="name"
                    v-model="form.name"
                    autocomplete="name"
                    autofocus
                    class="mt-1 block w-1/3"
                    required
                    type="text"
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>
            <div>
                <InputLabel :value="$t('Email')" for="email"></InputLabel>
                <TextInput
                    id="email"
                    v-model="form.email"
                    autocomplete="username"
                    class="mt-1 block w-1/3"
                    required
                    type="email"
                />
                <InputError :message="form.errors.email" class="mt-2" />
            </div>
            <div>
                <InputLabel :value="$t('Language')" for="email"></InputLabel>
                <select
                    class="rounded-md border border-gray-300"
                    name="locale"
                    id="locale"
                    v-model="form.locale"
                >
                    <option :value="null">{{ $t("Select") }}</option>
                    <option
                        v-for="(item, idx) in locale"
                        :key="idx"
                        :value="item.value"
                        v-html="item.label"
                    ></option>
                </select>
                <InputError :message="form.errors.locale" class="mt-2" />
            </div>
            <div
                class="flex flex-col my-2 space-y-2 rounded-md border border-gray-300 px-2 py-2 w-1/2"
            >
                <label for="avatar">Avatar:</label>
                <input
                    id="avatar"
                    name="avatar"
                    type="file"
                    accept="image/*"
                    @change="handleFileChange"
                />
                <progress
                    v-if="form.progress"
                    :value="form.progress.percentage"
                    max="100"
                >
                    {{ form.progress.percentage }}%
                </progress>
                <InputError :message="form.errors.avatar" class="mt-2" />
            </div>
            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">{{
                    $t("Save")
                }}</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </div>
</template>
<script setup>
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { router, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { route } from "ziggy-js";
import { ref } from "vue";
import { useToast } from "vue-toastification";

const toast = useToast();

const props = defineProps({
    user: Object,
});
const userId = ref(props.user.id);
const locale = ref([
    { value: "es", label: "Español" },
    { value: "en", label: "English" },
]);
const form = useForm({
    name: props.user.name,
    email: props.user.email,
    avatar: null,
    _method: "PATCH"
});
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Validar tamaño (2MB)
        if (file.size > 2 * 1024 * 1024) {
            form.errors.avatar = 'El archivo es demasiado grande. El tamaño máximo es 2MB.';
            event.target.value = ''; // Limpiar el input
            return;
        }

        // Validar tipo de archivo
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            form.errors.avatar = 'Formato de archivo no válido. Use JPG, PNG, GIF o WEBP.';
            event.target.value = ''; // Limpiar el input
            return;
        }

        form.errors.avatar = ''; // Limpiar error si todo está bien
        form.avatar = file;
    }
};

const submit = () => {
    form.post(route("users.update", userId.value), {
        onSuccess: () => {
            toast.success('Usuario actualizado exitosamente');
            form.reset('password', 'password_confirmation');
            form.avatar = null;
            router.visit(route('users.index'));
        },
        onError: (errors) => {
            toast.error('Error al actualizar el usuario');
            console.error('Error updating user:', errors);
        },
        preserveScroll: true,
        preserveState: false,
        forceFormData: true
    });
};
</script>
