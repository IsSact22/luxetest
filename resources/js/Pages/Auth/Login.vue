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

        <div class="flex flex-row justify-between items-center mx-auto">
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
                        class="flex flex-col justify-items-center items-center mx-auto space-y-9"
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
            <div
                class="relative flex items-center w-1/2 h-screen bg-[url('../storage/img/a836e04e2cb0c2958a1df350314addaa.jpg')] bg-cover bg-no-repeat bg-right"
            >
                <div
                    class="flex flex-col justify-items-center items-center mx-auto"
                >
                    <h1
                        class="text-[75px] font-poppins font-medium leading-normal text-black-50 tracking-[1px]"
                    >
                        Bienvenido a
                    </h1>
                    <h1
                        class="text-[75px] font-poppins font-medium mt-3 space-x-5"
                    >
                        <span class="text-black-600">LUXE</span>
                        <span class="text-yellow-500">PLUS</span>
                    </h1>
                    <p class="text-white tracking-normal mt-7">
                        Indícanos tus datos para verificarte y <br />
                        comenzar a organizar tu proyecto.
                    </p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
<style scoped></style>
