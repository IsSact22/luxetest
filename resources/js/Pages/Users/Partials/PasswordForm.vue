<template>
    <div>
        <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
        <p class="mt-1 text-sm text-gray-600">
            Ensure your account is using a long, random password to stay secure.
        </p>
        <form class="mt-6 space-y-6" @submit.prevent="submit">
            <div>
                <InputLabel for="password" value="Password"></InputLabel>
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-1/3"
                    v-model="form.password"
                    required
                />
                <InputError :message="form.errors.password" class="mt-2" />
            </div>
            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-1/3"
                    autocomplete="new-password"
                />
                <InputError :message="form.errors.password_confirmation" class="mt-2" />
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
import {route} from "ziggy-js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
const props =defineProps({
    user: Object
})
const form = useForm({
    password: '',
    password_confirmation: ''
})


const submit = async () => {
    form.patch(route('users.update', props.user.id));
}
</script>
