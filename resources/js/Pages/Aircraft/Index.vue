<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({})
    }
})
</script>
<template>
    <Head title="Aircraft" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Aircraft/Project</h2>
        </template>
        <div class="flex flex-col mx-auto px-4 mt-4">
            <div class="grid grid-cols-4 gap-4">
                <div v-for="(item, idx) in resource.data" class="max-w-xs p-6 rounded-md shadow-md dark:bg-gray-900 dark:text-gray-50">
                    <img :src="`https://loremflickr.com/300/300/aircraft?random=${idx+1}`" alt="" class="object-cover object-center w-full rounded-md h-72 dark:bg-gray-500">
                    <div class="mt-6 mb-2">
                        <span class="block text-xs font-medium tracking-widest uppercase dark:text-green-400">Model</span>
                        <h2 class="text-xl font-semibold tracking-wide">Short description</h2>
                    </div>
                    <p class="dark:text-gray-100">.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
