<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex flex-col justify-center items-center mx-auto min-h-screen welcome-background">
        <div>
            <Link href="/">
                <div class="flex-1">
                    <h1 class="text-2xl lg:text-4xl font-bold">LUXE <span class="text-amber-400 font-semibold">PLUS</span></h1>
                </div>
            </Link>
        </div>

        <div
            class="w-auto rounded-md p-6 background-gradient shadow-md overflow-hidden"
        >
            <slot />
        </div>
    </div>
</template>
<style scoped>
.background-gradient {
    background-color: #FBAB7E;
    background-image: linear-gradient(0deg, #FBAB7E 0%, #F7CE68 100%);

}
.welcome-background {
    background-color: #f4f4fb;
    opacity: 0.8;
    background-image:  url('/storage/img/background.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
</style>
