<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? "inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-yellow-500 focus:outline-none focus:border-yellow-700 transition duration-150 ease-in-out border-r-2 border-yellow-500"
        : "inline-flex items-center px-4 pt-2 text-sm font-medium leading-5 text-white hover:text-yellow-500 hover:border-yellow-300 focus:outline-none focus:text-yellow-700 focus:border-yellow-300 transition duration-150 ease-in-out",
);
</script>

<template>
    <Link :class="classes" :href="href">
        <slot />
    </Link>
</template>
