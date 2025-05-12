<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message" class="relative">
        <p class="text-sm text-red-500 absolute left-0 top-0">
            {{ message }}
        </p>
    </div>
</template>
