<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="w-56 h-screen bg-black-700 px-7 py-7">
        <div
            class="flex flex-col justify-items-center items-start space-y-2 text-white"
        >
            <Link>Dashboard</Link>
            <Link>Aircraft</Link>
            <Link>Camo</Link>
            <Link>System</Link>
        </div>
    </div>
</template>

<style scoped></style>
