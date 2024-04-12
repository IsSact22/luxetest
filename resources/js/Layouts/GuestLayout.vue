<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 mt-4 rounded-md bg-gray-100">
        <div>
            <Link href="/">
                <div class="flex-1">
                    <h1 class="text-2xl lg:text-4xl font-bold">LUXE <span class="text-amber-400 font-semibold">PLUS</span></h1>
                </div>
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md md:max-w-lg lg:max-w-md xl:max-w-lg mt-6 px-4 sm:px-6 py-4 bg-brand-yellow shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
