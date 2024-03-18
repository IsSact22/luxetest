<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <h1 class="p-6 text-gray-900">Welcome {{ $page.props.auth.user.name }}</h1>
                    <div class="flex flex-row justify-around">
                        <div class="max-w-xs p-6 rounded-md shadow-md border border-rounded border-gray-300 dark:bg-gray-900 dark:text-gray-50">
                            <div class="mt-6 mb-2">
                                <span class="block text-xs font-medium tracking-widest uppercase dark:text-green-400">Project</span>
                                <h2 class="text-xl font-semibold tracking-wide">Register Client</h2>
                            </div>
                            <p class="dark:text-gray-100">Mauris et lorem at elit.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
