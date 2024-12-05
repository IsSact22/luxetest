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
                    class="flex flex-col justify-items-center items-center mx-auto mt-16"
                >
                    <form
                        class="flex flex-col justify-items-center items-center mx-auto space-y-9 bg-black bg-opacity-60 p-10 rounded-lg my-2"
                        @submit.prevent="submit"
                    >
                        <!--                        <ApplicationLogo />-->
                        <h2
                            class="text-yellow-400 text-3xl font-poppins font-medium leading-normal"
                        >
                            Iniciar Sesión
                        </h2>
                        <p
                            class="text-black-50 font-poppins not-italic leading-normal"
                        >
                            Ingresa tu usuario y contraseña para entrar en tu
                            cuenta
                        </p>
                        <div>
                            <label class="block text-black-50" for="email">
                                Usuario
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                class="w-96 py-3 bg-black-700 rounded-md text-white focus:ring-yellow-50"
                                name="email"
                                placeholder="Correo Electrónico"
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
                                placeholder="Contraseña"
                                type="password"
                            />
                            <InputError
                                :message="form.errors.password"
                                class="mt-2"
                            />
                        </div>

                        <div class="w-10/12">
                            <label class="inline-block" for="remember">
                                <Checkbox
                                    v-model:checked="form.remember"
                                    class="transparent-checkbox"
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
<style scoped>
.transparent-checkbox {
    appearance: none; /* Elimina el estilo predeterminado del navegador */
    background-color: transparent; /* Fondo transparente por defecto */
    border: 2px solid #ccc; /* Borde gris claro */
    padding: 0.25rem; /* Ajusta el padding si es necesario */
    display: inline-block;
    width: 1.5rem; /* Tamaño del checkbox */
    height: 1.5rem; /* Tamaño del checkbox */
    position: relative;
    cursor: pointer;
}

/* Estilo para el checkbox cuando está marcado */
.transparent-checkbox:checked {
    background-color: transparent; /* Color amarillo para el checkbox marcado */
    border-color: #ccc; /* Color del borde igual al fondo */
}

/* Estilo para el símbolo de verificación dentro del checkbox */
.transparent-checkbox:checked::after {
    content: "";
    position: absolute;
    left: 50%;
    top: 50%;
    width: 0.5rem; /* Ajusta el tamaño del símbolo de verificación */
    height: 0.75rem; /* Ajusta el tamaño del símbolo de verificación */
    border: solid #fbbf24; /* Color del símbolo de verificación */
    border-width: 0 0.2rem 0.2rem 0; /* Ajusta el grosor del símbolo */
    transform: translate(-50%, -50%) rotate(45deg); /* Posiciona y rota el símbolo */
}
</style>
