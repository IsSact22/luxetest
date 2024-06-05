<script setup>
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
</script>
<template>
    <div class="flex flex-row-reverse">
        <Link :href="route('dashboard')" class="btn-goto">
            <div class="flex justify-items-center items-center space-x-1">
                <span>Dashboard</span>
            </div>
        </Link>
    </div>
</template>
