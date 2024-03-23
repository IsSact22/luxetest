<script setup>
import {Head, Link, router, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from '@/Components/TextInput.vue';
import InputError from "@/Components/InputError.vue";
import RoleForm from "@/Pages/Users/Partials/RoleForm.vue";
import PasswordForm from "@/Pages/Users/Partials/PasswordForm.vue";
import UserForm from "@/Pages/Users/Partials/UserForm.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {route} from "ziggy-js";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({})
    }
})


</script>
<template>
    <Head title="Users"/>
    <AuthenticatedLayout>
        <template #header>
            <h2>Users</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <Link class="b-goto" :href="route('users.index')">back to Users</Link>
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UserForm :user="props.resource.data" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <PasswordForm :user="props.resource.data" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <RoleForm :user="props.resource.data" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
