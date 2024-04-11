<script setup>
import {Head, Link} from "@inertiajs/vue3";
import {useForm} from "laravel-precognition-vue-inertia";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import {onMounted, ref} from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    resource: {
        type: Object,
        required: true,
    }
})

const permissions = ref(null)
const getPermissions = async () => {
    try {
        const response = await axios.get(route('permissions.select'));
        permissions.value = response.data.permissions
    } catch (e) {
        console.error(e)
    }
}
onMounted(getPermissions)

const form = useForm('patch', route('roles.update'),{
    name: props.resource.data.name,
    guard_name: props.resource.data.guard_name,
    permissions: props.resource.data.permissions,
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
                        </div>
                        <div>
                            <InputLabel for="name">Name</InputLabel>
                            <TextInput id="name" name="name" v-model="form.name"></TextInput>
                            <InputError :message="form.errors.name" class="mt-2"/>
                        </div>
                        <div>
                            <InputLabel for="guard_name">Guard Name</InputLabel>
                            <TextInput id="guard_name" name="guard_name" v-model="form.guard_name"></TextInput>
                            <InputError :message="form.errors.guard_name" class="mt-2"/>
                        </div>
                        <div v-if="form.isDirty" class="flex flex-row justify-items-center items-center space-x-3 my-2">
                            <button class="b-submit">Save</button>
                            <button @click="form.reset()" class="b-cancel">Reset</button>
                        </div>
                    </div>
                    <div class="p-2 border rounded-md">
                        <h2>Permissions</h2>
                        <div>
                            <InputLabel for="permissions">List of Permissions</InputLabel>
                            <select
                                name="permissions"
                                id="permissions"
                                class="rounded-md border-gray-300 w-full"
                                size="10"
                                multiple
                                v-model="form.permissions">
                                <option v-for="(p,idx) in permissions" :key="idx" :value="p">{{p}}</option>
                            </select>
                            <small class="text-orange-700">To Add or Remove a permission you must press the control key + click</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

