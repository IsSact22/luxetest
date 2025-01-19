<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import RoleForm from "@/Pages/Users/Partials/RoleForm.vue";
import PasswordForm from "@/Pages/Users/Partials/PasswordForm.vue";
import UserForm from "@/Pages/Users/Partials/UserForm.vue";
import { route } from "ziggy-js";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});
</script>
<template>
    <Head :title="`${$t('Users')}`" />
    <AuthenticatedLayout>
        <template #header>
            <h2>{{ $t("Users") }}</h2>
        </template>
        <div class="py-7">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <Link :href="route('users.index')" class="btn-goto"
                    >{{ $t("Go back") }}
                </Link>
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
