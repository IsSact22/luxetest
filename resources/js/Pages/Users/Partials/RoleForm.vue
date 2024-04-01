<template>
    <div>
        <h2 class="text-lg font-medium text-gray-900">Update Role</h2>
        <p class="mt-1 text-sm text-gray-600">
            Make sure the user has a role assigned.
        </p>
        <form  class="mt-6 space-y-6" @submit.prevent="submit">
            <div>
                <InputLabel for="role" value="Role" />
                <select name="role" id="role" class="mt-1 rounded-md border-gray-300 block w-1/4" v-model="form.role">
                    <option
                        v-for="(r, idx) in roles"
                        :key="idx"
                        :value="r"
                    >
                        {{r}}
                    </option>
                </select>
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
import InputLabel from "@/Components/InputLabel.vue";
import {onMounted, ref} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
const props = defineProps({
    user: Object
})
const form = useForm({
    role: props.user.role,
})
const submit = async () => {
    alert(route('users.update', props.user.id))
    form.patch(route('users.update', props.user.id))
}
const roles = ref(null);
const getRoles = async() => {
    try {
        const response = await axios.get(route('roles.select'))
        roles.value = response.data.roles
    }catch (e) {
        console.error(e)
    }
}
onMounted(getRoles)
</script>
