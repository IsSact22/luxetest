<script setup>
import { Head, router } from "@inertiajs/vue3";
import { useForm } from "laravel-precognition-vue-inertia";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { onMounted, ref } from "vue";
import { route } from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const form = useForm("post", route("users.store"), {
    role: null,
    owner_id: null,
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    avatar: null,
    locale: null,
});

const locale = ref([
    { value: "es", label: "Español" },
    { value: "en", label: "English" },
]);

const submit = async () => {
    form.submit({
        onFinish: () => form.reset(),
    });
};

const owners = ref(null);
const getOwners = async () => {
    try {
        const response = await axios.get(route("owners.select"));
        owners.value = response.data.owners;
    } catch (e) {
        console.error(e);
    }
};
onMounted(getOwners);

const avatar = ref(null);

function handleFileChange(event) {
    form.avatar = event.target.files[0];
}

const cancelForm = () => {
    form.reset();
    router.get(route("users.index"));
};
</script>
<template>
    <Head :title="`${$t('Users')}`" />
    <AuthenticatedLayout>
        <template #header>
            <h2>{{ $t("Users") }}</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center">
            <div class="my-4 border rounded-md px-4 py-1 bg-white">
                <h1 class="text-xl text-neutral-600">
                    {{ $t("Create User") }}
                </h1>
                <form @submit.prevent="submit">
                    <div
                        class="flex flex-col my-2 space-y-2 px-2 py-2 border rounded-md bg-white"
                    >
                        <label for="isOwner">
                            {{
                                $t(
                                    "If it is a crew member, please verify the owner.",
                                )
                            }}
                        </label>
                        <div
                            class="flex flex-row justify-items-center items-center space-x-2"
                        >
                            <div
                                class="flex-1 justify-items-center items-center space-x-2"
                            >
                                <input
                                    id="cam"
                                    v-model="form.role"
                                    type="radio"
                                    value="cam"
                                />
                                <label class="text-sm" for="cam">Cam</label>
                            </div>
                            <div
                                class="flex-1 justify-items-center items-center space-x-2"
                            >
                                <input
                                    id="owner"
                                    v-model="form.role"
                                    type="radio"
                                    value="owner"
                                />
                                <label class="text-sm" for="owner">{{
                                    $t("Owner")
                                }}</label>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="form.role === 'crew'"
                        class="flex flex-col my-2 space-y-2"
                    >
                        <InputLabel
                            for="owner_id"
                            value="Propietario"
                        ></InputLabel>
                        <select
                            id="owner_id"
                            v-model="form.owner_id"
                            class="rounded-md border border-md border-gray-300"
                            name="owner_id"
                        >
                            <option :value="null">{{ $t("Select") }}</option>
                            <option
                                v-for="(owner, idx) in owners"
                                :key="idx"
                                :value="owner.id"
                            >
                                {{ owner.name }}
                            </option>
                        </select>
                        <InputError
                            :message="form.errors.owner_id"
                            class="mt-2"
                        />
                    </div>
                    <div class="flex flex-col my-2 space-y-2">
                        <InputLabel
                            :value="$t('Language')"
                            for="locale"
                        ></InputLabel>
                        <select
                            class="rounded-md border border-gray-300"
                            name="locale"
                            id="locale"
                            v-model="form.locale"
                            @change="form.validate('locale')">
                            <option :value="null">{{ $t("Select") }}</option>
                            <option v-for="(item, idx) in locale" :key="idx" :value="item.value" v-html="item.label"></option>
                        </select>
                        <InputError
                            :message="form.errors.locale"
                            class="mt-2"
                        />
                    </div>
                    <div class="flex flex-col my-2 space-y-2">
                        <InputLabel :value="$t('Name')" for="name"></InputLabel>
                        <TextInput
                            id="name"
                            v-model="form.name"
                            autocomplete="name"
                            autofocus
                            class="mt-1 block"
                            required
                            type="text"
                        />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                    <div class="flex flex-col my-2 space-y-2">
                        <InputLabel
                            :value="$t('Email')"
                            for="email"
                        ></InputLabel>
                        <TextInput
                            id="email"
                            v-model="form.email"
                            autocomplete="username"
                            class="mt-1 block"
                            required
                            type="email"
                            @change="form.validate('email')"
                        />
                        <InputError :message="form.errors.email" class="mt-2" />
                    </div>
                    <div class="flex flex-col my-2 space-y-2">
                        <InputLabel
                            :value="$t('Password')"
                            for="password"
                        ></InputLabel>
                        <TextInput
                            id="password"
                            v-model="form.password"
                            class="mt-1 block"
                            required
                            type="password"
                        />
                        <InputError
                            :message="form.errors.password"
                            class="mt-2"
                        />
                    </div>
                    <div class="flex flex-col my-2 space-y-2">
                        <InputLabel
                            :value="$t('Confirm Password')"
                            for="password_confirmation"
                        />
                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            autocomplete="new-password"
                            class="mt-1 block"
                            required
                            type="password"
                        />
                        <InputError
                            :message="form.errors.password_confirmation"
                            class="mt-2"
                        />
                    </div>
                    <div
                        class="flex flex-col my-2 space-y-2 border rounded-md px-2 py-2 bg-white"
                    >
                        <label for="avatar">{{ $t("Avatar") }}</label>
                        <input
                            id="avatar"
                            ref="avatar"
                            type="file"
                            @input="form.avatar = $event.target.files[0]"
                        />
                    </div>
                    <div class="flex flex-row justify-around my-4">
                        <button
                            class="btn-submit"
                            type="submit"
                            @click="submit"
                        >
                            {{ $t("Register") }}
                        </button>
                        <button
                            class="btn-cancel"
                            type="button"
                            @click="cancelForm"
                        >
                            {{ $t("Cancel") }}
                        </button>
                    </div>
                    <progress
                        v-if="form.progress"
                        :value="form.progress.percentage"
                        max="100"
                    >
                        {{ form.progress.percentage }}%
                    </progress>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
