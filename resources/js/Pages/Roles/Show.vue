<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { route } from "ziggy-js";

const props = defineProps({
    resource: Object,
});
</script>
<template>
    <Head title="Roles" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Rol</h2>
        </template>
        <div class="max-w-7xl mx-auto justify-items-center items-center">
            <div class="my-4 border rounded-md px-4 py-4">
                <div class="my-2">
                    <Link :href="route('roles.index')" class="b-goto"
                        >Regresa
                    </Link>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-2 border rounded-md">
                        <h2>Datos</h2>
                        <div>
                            <p>
                                ID:
                                <span class="font-semibold">{{
                                    resource.data.id
                                }}</span>
                            </p>
                            <p>
                                Nombre:
                                <span class="font-semibold">{{
                                    resource.data.name
                                }}</span>
                            </p>
                            <p>
                                Nombre de Guardia:
                                <span class="font-semibold">{{
                                    resource.data.guard_name
                                }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="p-2 border rounded-md">
                        <h2>Permisos</h2>
                        <ul class="flex flex-wrap">
                            <li
                                v-for="(p, idx) in resource.data.permissions"
                                :key="idx"
                                class="badge-info my-2"
                            >
                                {{ p }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
