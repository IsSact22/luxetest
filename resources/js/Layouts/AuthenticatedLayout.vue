<script setup>
import { computed, ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import { route } from "ziggy-js";

const toast = useToast();
const flash = usePage().props.flash.message;
if (flash) {
    toast(flash.message, flash.type);
}
const showingNavigationDropdown = ref(false);
const showCamos = computed(() => {
    const userRoles = usePage().props.auth.userRoles;
    return ["super-admin", "admin", "cam", "owner", "crew"].includes(
        userRoles[0],
    );
});
const showUsers = computed(() => {
    const userRoles = usePage().props.auth.userRoles;
    return ["super-admin", "admin"].includes(userRoles[0]);
});
const showBackoffice = computed(() => {
    const user = usePage().props.auth.user;
    return user.is_super || user.is_admin || user.is_cam;
});
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex">
        <nav class="bg-black-700 border-r border-gray-100 w-64">
            <!-- Logo -->
            <div class="shrink-0 flex items-center p-4">
                <Link :href="route('dashboard')">
                    <ApplicationLogo
                        class="block h-9 w-auto fill-current text-white"
                    />
                </Link>
            </div>

            <!-- User Info -->
            <div class="p-4 text-white">
                <div class="font-medium text-base">
                    {{ $page.props.auth.user.name }}
                </div>
                <div class="text-sm">
                    <Link
                        :href="route('profile.edit')"
                        class="text-gray-300 hover:underline"
                        >Profile
                    </Link>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="p-4">
                <div class="flex flex-col space-y-2">
                    <NavLink
                        :active="route().current('dashboard')"
                        :href="route('dashboard')"
                        class="text-white"
                    >
                        Dashboard
                    </NavLink>
                    <NavLink
                        v-show="showBackoffice"
                        :active="route().current('aircrafts.index')"
                        :href="route('aircrafts.index')"
                        class="text-white"
                    >
                        Aircraft
                    </NavLink>
                    <NavLink
                        v-show="showBackoffice && showCamos"
                        :active="route().current('camos.index')"
                        :href="route('camos.index')"
                        class="text-white"
                    >
                        Camos
                    </NavLink>
                </div>

                <div v-show="showBackoffice" class="mt-4">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                class="w-full text-left p-2 text-white hover:bg-gray-600"
                            >
                                System
                            </button>
                        </template>
                        <template #content>
                            <div class="flex flex-col">
                                <DropdownLink
                                    v-show="showUsers"
                                    :href="route('users.index')"
                                    class="text-black"
                                >
                                    Users
                                </DropdownLink>
                                <hr />
                                <DropdownLink
                                    :href="route('engine-types.index')"
                                    class="text-black"
                                    >Engine Type
                                </DropdownLink>
                                <DropdownLink
                                    :href="route('brand-aircrafts.index')"
                                    class="text-black"
                                    >Brand Aircraft
                                </DropdownLink>
                                <DropdownLink
                                    :href="route('model-aircrafts.index')"
                                    class="text-black"
                                    >Model Aircraft
                                </DropdownLink>
                                <hr />
                                <DropdownLink
                                    :href="route('admin-rates.index')"
                                    class="text-black"
                                    >Admin Rates
                                </DropdownLink>
                                <hr />
                                <DropdownLink
                                    :href="route('labor-rates.index')"
                                    class="text-black"
                                    >Labor Rates
                                </DropdownLink>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </div>
        </nav>

        <div class="flex-1">
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main class="p-4">
                <slot />
            </main>
        </div>
    </div>
</template>
