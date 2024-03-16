<script setup>
import { computed } from 'vue'
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const props = defineProps({ status: Number })

const title = computed(() => {
    return {
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden',
    }[props.status]
})

const description = computed(() => {
    return {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
        422: 'Sorry, there was an error processing your request.'
    }[props.status]
})
</script>

<template>
    <div class="grid h-screen place-content-center bg-white px-4">
        <div class="flex flex-col justify-items-center items-center text-center">
            <ApplicationLogo />
            <h1 class="mt-6 text-2xl font-bold tracking-tight text-gray-900 sm:text-4xl">{{ title }}</h1>

            <p class="mt-4 text-gray-500">{{description}}</p>
        </div>
    </div>
</template>
