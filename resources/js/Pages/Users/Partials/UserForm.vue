<template>
    <div>
        <h2 class="text-lg font-medium text-gray-900">Update User</h2>
        <p class="mt-1 text-sm text-gray-600">
            Update your user's information and email address.
        </p>
        <form class="mt-6 space-y-6" @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name"></InputLabel>
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-1/3"
                    required
                    autofocus
                    autocomplete="name"
                    v-model="form.name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div>
                <InputLabel for="email" value="Email"></InputLabel>
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-1/3"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError :message="form.errors.email" class="mt-2" />
            </div>
            <div class="flex flex-col my-2 space-y-2 rounded-md border border-gray-300 px-2 py-2 w-1/2">
                <label for="avatar">Avatar:</label>
                <input id="avatar" name="avatar" type="file" @input="form.avatar = $event.target.files[0]" />
                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                    {{ form.progress.percentage }}%
                </progress>
            </div>
            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </div>
</template>
<script setup>
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {route} from "ziggy-js";
import {ref} from "vue";
import { router } from '@inertiajs/vue3'

const props = defineProps({
    user: Object
})
const userId = ref(props.user.id)
const form = useForm({
    name: props.user.name,
    email: props.user.email,
    avatar: null,
    _method: 'PATCH'
})
const submit = () => {
    router.post(route('users.update', userId.value), {
        _method: 'patch',
        name: form.name,
        email: form.email,
        avatar: form.avatar,
    });
}
</script>
