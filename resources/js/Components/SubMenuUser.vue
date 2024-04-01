<script setup>
import {route} from 'ziggy-js';
import {router} from '@inertiajs/vue3';
import {ref} from 'vue';

const goToUsers = () => {
    router.visit(route('users.index'));
};

const goToRoles = () => {
    router.visit(route('roles.index'));
};

const goToPermissions = () => {
    router.visit(route('permissions.index'));
};

const isUserRoute = ref(route().current('users.index'));
const isRoleRoute = ref(route().current('roles.index'));
const isPermissionRoute = ref(route().current('permissions.index'));
const emits = defineEmits(['new-permission']);
const emitNewPermission = () => {
    emits('new-permission');
}
</script>

<template>
    <div class="flex flex-row justify-items-center items-center space-x-4 px-4 py-2">
        <button
            :class="{ 'hidden': isUserRoute }"
            class="b-goto"
            @click="goToUsers"
        >
            Users
        </button>
        <button
            :class="{ 'hidden': isRoleRoute }"
            class="b-goto"
            @click="goToRoles"
        >
            Roles
        </button>
        <button
            :class="{ 'hidden': isPermissionRoute }"
            class="b-goto"
            @click="goToPermissions"
        >
            Permissions
        </button>
        <button
            :class="{ 'hidden': !isPermissionRoute }"
            class="b-goto"
            @click="emitNewPermission"
        >
            New Permission
        </button>
    </div>
</template>
