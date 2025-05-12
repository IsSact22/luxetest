<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from "ziggy-js";

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        preserveScroll: true
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="flex flex-row justify-between items-center mx-auto bg-[url('../../storage/app/public/img/background.png')] bg-center bg-cover bg-no-repeat relative">
            <!-- Overlay oscuro para reducir la opacidad del fondo -->
            <div class="absolute inset-0 bg-black opacity-50"></div>

            
            <!-- Contenido del formulario -->
            <div class="w-1/2 h-screen relative z-10">
                <div class="flex flex-col justify-items-center items-center mx-auto mt-16">
                    <form @submit.prevent="submit" class="flex flex-col justify-items-center items-center mx-auto space-y-6 bg-black bg-opacity-60 p-10 rounded-lg my-2">
                        <h2 class="text-yellow-400 text-3xl font-poppins font-medium leading-normal">
                            Crear Cuenta
                        </h2>
                        <p class="text-black-50 font-poppins not-italic leading-normal mb-4">
                            Ingresa tus datos para registrarte en la plataforma
                        </p>

                        <div class="relative mb-6 h-24">
                            <label class="block text-black-50 mb-1" for="name">
                                Nombre
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                class="w-96 py-3 bg-black-700 rounded-md text-white focus:ring-yellow-50"
                                type="text"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Tu nombre completo"
                            />
                            <InputError class="mt-1" :message="form.errors.name" />
                        </div>

                        <div class="relative mb-6 h-24">
                            <label class="block text-black-50 mb-1" for="email">
                                Correo Electrónico
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                class="w-96 py-3 bg-black-700 rounded-md text-white focus:ring-yellow-50"
                                type="email"
                                required
                                autocomplete="username"
                                placeholder="ejemplo@correo.com"
                            />
                            <InputError class="mt-1" :message="form.errors.email" />
                        </div>

                        <div class="relative mb-6 h-24">
                            <label class="block text-black-50 mb-1" for="password">
                                Contraseña
                            </label>
                            <input
                                id="password"
                                v-model="form.password"
                                class="w-96 py-3 bg-black-700 rounded-md text-white focus:ring-yellow-50"
                                type="password"
                                required
                                autocomplete="new-password"
                                placeholder="Contraseña"
                            />
                            <InputError class="mt-1" :message="form.errors.password" />
                        </div>

                        <div class="relative mb-6 h-24">
                            <label class="block text-black-50 mb-1" for="password_confirmation">
                                Confirmar Contraseña
                            </label>
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                class="w-96 py-3 bg-black-700 rounded-md text-white focus:ring-yellow-50"
                                type="password"
                                required
                                autocomplete="new-password"
                                placeholder="Confirmar contraseña"
                            />
                            <InputError class="mt-1" :message="form.errors.password_confirmation" />
                        </div>

                        <div class="flex flex-col items-center space-y-4 w-full">
                            <button
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                                class="w-96 px-4 py-2 rounded-full bg-yellow-500 font-medium font-poppins hover:bg-yellow-400 transition-colors duration-200"
                                type="submit"
                            >
                                Crear Cuenta
                            </button>

                            <Link
                                :href="route('login')"
                                class="text-yellow-500 font-poppins font-medium hover:text-yellow-400 transition-colors duration-200"
                            >
                                ¿Ya tienes una cuenta? Inicia Sesión
                            </Link>
                        </div>
                    </form>
                </div>
            </div>

            <div class="relative flex items-center w-1/2 h-screen">
                <div class="flex flex-col justify-items-center items-center mx-auto">
                    <ApplicationLogo
                        alt="Logo Luxe Plus"
                        class="mb-6 w-30 h-30"
                    />
                    <h2 class="text-5xl font-bold mb-5 text-center text-white">
                    Crea tu cuenta a 
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
