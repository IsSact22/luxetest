<script setup>
import { usePage } from "@inertiajs/vue3";
</script>
<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ $t("Role & Permissions Information") }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ $t("Your Permission's profile information") }}
            </p>
        </header>
        <div class="flex flex-col space-y-3">
            <div class="flex space-x-3">
                <span>{{ $t("Role") }}:</span>
                <span class="uppercase">{{
                    usePage().props.auth.userRoles[0]
                }}</span>
            </div>
            <div class="flex flex-row space-x-3">
                <span>{{ $t("Permissions") }}</span>
                <ul class="flex flex-wrap">
                    <li
                        v-for="(permission, idx) in usePage().props.auth
                            .userPermissions"
                        class="badge-info m-1"
                    >
                        {{ permission }}
                    </li>
                </ul>
            </div>
        </div>
    </section>
</template>
