<script setup>
import {Head, Link} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import {onMounted, ref} from "vue";

const props = defineProps({
    resource: Object
})

</script>
<template>
    <Head title="Roles"/>
    <AuthenticatedLayout>
        <template #header>
            <h2>Role</h2>
        </template>
        <div class="max-w-7xl mx-auto justify-items-center items-center">
            <div class="my-4 border rounded-md px-4 py-4">
                <div class="my-2">
                    <Link :href="route('roles.index')" class="b-goto">Back To Roles</Link>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-2 border rounded-md">
                        <h2>Data</h2>
                        <div>
                            <p>ID: <span class="font-semibold">{{resource.data.id}}</span></p>
                            <p>Name: <span class="font-semibold">{{resource.data.name}}</span></p>
                            <p>Guard Name: <span class="font-semibold">{{resource.data.guard_name}}</span></p>
                        </div>
                    </div>
                    <div class="p-2 border rounded-md">
                        <h2>Permissions</h2>
                        <ul class="flex flex-wrap">
                            <li
                                v-for="(p, idx) in resource.data.permissions"
                                :key="idx"
                                class="badge-info my-2"
                            >{{p}}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
