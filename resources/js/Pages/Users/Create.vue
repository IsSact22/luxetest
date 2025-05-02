<script setup>
import { Head, router } from "@inertiajs/vue3";
import { useForm } from "laravel-precognition-vue-inertia";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { onMounted, ref } from "vue";
import { route } from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { useToast } from "vue-toastification";

const form = useForm("post", route("users.store"), {
    role: null,
    owner_id: null,
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    //avatar: null,
});

const toast = useToast();

const submit = async () => {
    form.submit({
        onSuccess: () => {
            toast.success('Usuario creado exitosamente');
            form.reset();
            router.get(route('users.index'), {}, {
                preserveState: true,
                preserveScroll: true
            });
        },
        onError: () => {
            toast.error('Error al crear el usuario');
            // Los errores específicos ya son manejados automáticamente por el componente InputError
        }
    });
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
onMounted(getOwners);


const avatar = ref(null);

function handleFileChange(event) {
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
}
const cancelForm = () => {
    form.reset();
    router.get(route("users.index"));
};
</script>
<template>
    <Head title="Users" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Usuarios</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center">
            <div class="my-4 border rounded-md px-4 py-1 bg-white">
                <h1 class="text-xl text-neutral-600">Crear Usuario</h1>
                <form @submit.prevent="submit">
                    <div
                        class="flex flex-col my-2 space-y-2 px-2 py-2 border rounded-md bg-white"
                    >
                        <label for="isOwner">
                            Si se trata de una tripulante, por favor verificar
                            el propietario.
                        </label>
                        <div
                            class="flex flex-row justify-items-center items-center space-x-2"
                        >
                            <div
                                class="flex-1 justify-items-center items-center space-x-2"
                            >
                                <input
                                    id="cam"
                                    v-model="form.role"
                                    type="radio"
                                    value="cam"
                                />
                                <label for="cam">Cam</label>
                            </div>
                            <div
                                class="flex-1 justify-items-center items-center space-x-2"
                            >
                                <input
                                    id="owner"
                                    v-model="form.role"
                                    type="radio"
                                    value="owner"
                                />
                                <label class="text-xs" for="owner"
                                    >Owner (Propietario)</label
                                >
                            </div>
                                                       <div
                                                            class="flex-1 justify-items-center items-center space-x-2"
                                                        >
                                                            <input
                                                                id="crew"
                                                                v-model="form.role"
                                                                type="radio"
                                                                value="crew"
                                                            />
                                                            <label class="text-xs" for="owner"
                                                                >Crew (Tripulante)</label
                                                            >
                                                        </div>
                        </div>
                    </div>
                    <div
                        v-if="form.role === 'crew'"
                        class="flex flex-col my-2 space-y-2"
                    >
                        <InputLabel
                            for="owner_id"
                            value="Propietario"
                        ></InputLabel>
                        <select
                            id="owner_id"
                            v-model="form.owner_id"
                            class="rounded-md border border-md border-gray-300"
                            name="owner_id"
                        >
                            <option :value="null">Select</option>
                            <option
                                v-for="(owner, idx) in owners"
                                :key="idx"
                                :value="owner.id"
                            >
                                {{ owner.name }}
                            </option>
                        </select>
                        <InputError
                            :message="form.errors.owner_id"
                            class="mt-2"
                        />
                    </div>
                    <div class="flex flex-col my-2 space-y-2">
                        <InputLabel :value="$t('Name')" for="name"></InputLabel>
                        <TextInput
                            id="name"
                            v-model="form.name"
                            autocomplete="name"
                            autofocus
                            class="mt-1 block"
                            required
                            type="text"
                        />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                    <div class="flex flex-col my-2 space-y-2">
                        <InputLabel
                            :value="$t('Email')"
                            for="email"
                        ></InputLabel>
                        <TextInput
                            id="email"
                            v-model="form.email"
                            autocomplete="username"
                            class="mt-1 block"
                            required
                            type="email"
                            @change="form.validate('email')"
                        />
                        <InputError :message="form.errors.email" class="mt-2" />
                    </div>
                    <div class="flex flex-col my-2 space-y-2">
                        <InputLabel
                            :value="$t('Password')"
                            for="password"
                        ></InputLabel>
                        <TextInput
                            id="password"
                            v-model="form.password"
                            class="mt-1 block"
                            required
                            type="password"
                        />
                        <InputError
                            :message="form.errors.password"
                            class="mt-2"
                        />
                    </div>
                    <div class="flex flex-col my-2 space-y-2">
                        <InputLabel
                            :value="$t('Confirm Password')"
                            for="password_confirmation"
                        />
                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            autocomplete="new-password"
                            class="mt-1 block"
                            required
                            type="password"
                        />
                        <InputError
                            :message="form.errors.password_confirmation"
                            class="mt-2"
                        />
                    </div>
                    <div class="flex flex-col my-2 space-y-2 border rounded-md px-4 py-3 bg-white">
                        <label for="avatar" class="text-sm font-medium text-gray-700">Avatar:</label>
                        <div class="mt-1 flex items-center space-x-4">
                            <input
                                id="avatar"
                                ref="avatar"
                                type="file"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100"
                                @input="handleFileChange"
                            />
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo: 2MB
                        </p>
                        <InputError :message="form.errors.avatar" class="mt-2" />
                    </div>
                    <div class="flex flex-row justify-around my-4">
                        <button 
                            class="btn-submit"
                            type="submit"
                            @click="submit"
                        >
                            Registrar
                        </button>
                        <button
                            class="btn-cancel"
                            type="button"
                            @click="cancelForm"
                        >
                            Cancelar
                        </button>
                    </div>
                    <progress
                        v-if="form.progress"
                        :value="form.progress.percentage"
                        max="100"
                    >
                        {{ form.progress.percentage }}%
                    </progress>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
