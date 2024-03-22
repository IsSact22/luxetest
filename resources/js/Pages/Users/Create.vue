<script setup>
import {Head, router, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {onMounted, ref} from "vue";
import {route} from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const form = useForm({
    role: null,
    owner_id: null,
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    avatar: null,
})

const submit = async() => {
    router.post(route('users.store'), {
        _method: 'post',
        role: form.role,
        owner_id: form.owner_id,
        name: form.name,
        email: form.email,
        password: form.password,
        password_confirmation: form.password_confirmation,
        avatar: form.avatar,
    })
}

const owners = ref(null);
const getOwners = async () => {
    try {
        const response = await axios.get(route('owners.select'));
        owners.value = response.data.owners
    } catch (e) {
        console.error(e)
    }
}
onMounted(getOwners)

const avatar = ref(null);

function handleFileChange(event) {
    form.avatar = event.target.files[0];
}

</script>
<template>
    <Head title="Users"/>
    <AuthenticatedLayout>
        <template #header>
            <h2>Users</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center">
            <div class="my-4 border rounded-md px-4 py-4">
                <div class="flex flex-col justify-items-center items-start">
                    <h1 class="text-xl">Create User</h1>
                    <form @submit.prevent="submit">
                        <div class="flex flex-col my-2 space-y-2">
                            <label for="isOwner">
                                If it is a crew, please check the owner.
                            </label>
                            <div class="flex flex-row justify-items-center items-center space-x-2">
                                <div class="flex-1 justify-items-center items-center space-x-2">
                                    <input type="radio" id="cam" value="cam" v-model="form.role">
                                    <label for="cam">Cam</label>
                                </div>
                                <div class="flex-1 justify-items-center items-center space-x-2">
                                    <input type="radio" id="owner" value="owner" v-model="form.role">
                                    <label for="owner">Owner</label>
                                </div>
                                <div class="flex-1 justify-items-center items-center space-x-2">
                                    <input type="radio" id="crew" value="crew" v-model="form.role">
                                    <label for="owner">Crew</label>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.role === 'crew'" class="flex flex-col my-2 space-y-2">
                            <InputLabel for="owner_id" value="Owner"></InputLabel>
                            <select
                                name="owner_id"
                                id="owner_id"
                                class="rounded-md border border-md border-gray-300"
                                v-model="form.owner_id"
                            >
                                <option :value="null">Select</option>
                                <option v-for="(o,idx) in owners" :key="idx" :value="o.id">{{o.name}}</option>
                            </select>
                        </div>
                        <div class="flex flex-col my-2 space-y-2">
                            <InputLabel for="name" value="Name"></InputLabel>
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block"
                                required
                                autofocus
                                autocomplete="name"
                                v-model="form.name"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div class="flex flex-col my-2 space-y-2">
                            <InputLabel for="email" value="Email"></InputLabel>
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block"
                                v-model="form.email"
                                required
                                autocomplete="username"
                            />
                            <InputError :message="form.errors.email" class="mt-2" />
                        </div>
                        <div class="flex flex-col my-2 space-y-2">
                            <InputLabel for="password" value="Password"></InputLabel>
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block"
                                v-model="form.password"
                                required
                            />
                            <InputError :message="form.errors.password" class="mt-2" />
                        </div>
                        <div class="flex flex-col my-2 space-y-2">
                            <InputLabel for="password_confirmation" value="Confirm Password" />
                            <TextInput
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="mt-1 block"
                                required
                                autocomplete="new-password"
                            />
                            <InputError :message="form.errors.password_confirmation" class="mt-2" />
                        </div>
                        <div class="flex flex-col my-2 space-y-2">
                            <label for="avatar">Avatar:</label>
                            <input type="file" id="avatar" ref="avatar" @input="form.avatar = $event.target.files[0]" />
                        </div>
                        <div class="flex flex-row justify-around my-4">
                            <button
                                class="b-submit"
                                type="submit"
                            >Create User</button>
                            <button class="b-cancel">Cancel</button>
                        </div>
                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                            {{ form.progress.percentage }}%
                        </progress>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
