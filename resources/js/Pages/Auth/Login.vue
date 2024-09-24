<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { route } from "ziggy-js";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

onMounted(() => {
    document.getElementById("login").classList.add("loaded");
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout id="login">
        <Head title="Log in" />

        <div v-if="status" class="font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <div
            class="flex flex-row justify-between items-center mx-auto bg-[url('../../storage/app/public/img/background.png')] bg-center bg-cover bg-no-repeat"
        >
            <div class="w-1/2 h-screen">
                <div
                    class="flex flex-col justify-items-center items-center mx-auto"
                >
                    <h2
                        class="text-yellow-400 text-3xl font-poppins font-medium leading-normal mt-32"
                    >
                        Iniciar Sesión
                    </h2>
                    <p
                        class="text-black-50 font-poppins not-italic leading-[80px]"
                    >
                        Ingresa tu usuario y contraseña para entrar en tu cuenta
                    </p>

                    <form
                        class="flex flex-col justify-items-center items-center mx-auto space-y-9 bg-black bg-opacity-60 p-10 rounded-lg my-2"
                        @submit.prevent="submit"
                    >
                        <div>
                            <label class="block text-black-50" for="email">
                                Usuario
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                class="w-96 py-3 bg-black-700 rounded-md text-white focus:ring-yellow-50"
                                name="email"
                                type="text"
                            />
                            <InputError
                                :message="form.errors.email"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <label class="block text-black-50" for="password">
                                Contraseña
                            </label>
                            <input
                                id="password"
                                v-model="form.password"
                                class="w-96 py-3 bg-black-700 rounded-md text-white focus:ring-yellow-50"
                                name="password"
                                type="password"
                            />
                            <InputError
                                :message="form.errors.password"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <label class="flex items-center" for="remember">
                                <Checkbox
                                    v-model:checked="form.remember"
                                    name="remember"
                                />
                                <span
                                    class="ms-2 text-sm text-yellow-600 font-poppins font-medium"
                                    >Recordar Usuario</span
                                >
                            </label>
                        </div>

                        <div>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-yellow-500 font-poppins font-medium underline"
                            >
                                Recuperar Contraseña
                            </Link>
                        </div>

                        <div>
                            <button
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                                class="w-96 px-4 py-2 rounded-full bg-yellow-500 font-medium font-poppins"
                                type="submit"
                            >
                                Iniciar Sesión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="relative flex items-center w-1/2 h-screen">
                <div
                    class="flex flex-col justify-items-center items-center mx-auto"
                >
                    <img
                        alt="Logo Luxe Plus"
                        class="mb-6 w-30 h-30"
                        src="storage/img/Logo.png"
                    />
                    <h2 class="text-5xl font-bold mb-5 text-center text-white">
                        Bienvenido a
                        <span class="text-yellow-500">LUXE PLUS</span>
                    </h2>
                    <p class="text-white text-center py-3 text-2xl">
                        Airworthiness tracker
                    </p>
                    <p class="text-white text-center pt-2 px-10 text-2xl">
                        Nuestra aplicación CAMO facilita la gestión eficiente de
                        la aeronavegabilidad continua para flotas de aeronaves
                    </p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
<style scoped></style>
