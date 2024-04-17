<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {route} from "ziggy-js";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
const handleLogoClick = () => {
    const logoContainer = document.querySelector('.logo-transition');
    const windowHeight = window.innerHeight;

    if (logoContainer) {
        logoContainer.style.transform = `translateY(-${windowHeight}px)`;

        setTimeout(() => {
            router.get(route('login'));
        }, 500);
    }
}
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-200/50">
        <div class="min-h-screen flex flex-col items-center justify-center">
            <div class="w-full max-w-2xl px-6 lg:max-w-7xl">
                <main class="flex justify-center">
                    <div class="flex flex-col items-center">
                        <div class="my-3 logo-transition">
                            <ApplicationLogo @click="handleLogoClick"/>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-4xl lg:text-7xl font-bold">LUXE <span class="text-amber-400 font-semibold">PLUS</span></h1>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
<style scoped>
.logo-transition {
    transition: transform 0.5s ease-out;
}
</style>
