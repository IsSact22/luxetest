<script setup>
import { computed, onMounted, ref, watch } from "vue";
import { useForm } from "laravel-precognition-vue-inertia";
import { route } from "ziggy-js";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    role: {
        type: Object,
    },
});

const form = useForm("post", route("roles.store"), {
    name: props.role?.name ?? "",
    guard_name: "web",
    permissions: props.role?.permissions ?? [],
});
const submit = () => {
    form.submit();
};
const permissions = ref([]);
const getPermissions = async () => {
    try {
        const response = await axios.get(route("permissions.select"));
        permissions.value = response.data.permissions;
    } catch (e) {
        console.error(e);
    }
};
onMounted(getPermissions);
const createPermission = computed(() => {
    if (permissions) {
        return permissions.value.filter((item) => item.includes("create-"));
    }
    return [];
});
const readPermission = computed(() => {
    if (permissions) {
        return permissions.value.filter((item) => item.includes("read-"));
    }
    return [];
});
const updatePermission = computed(() => {
    if (permissions) {
        return permissions.value.filter((item) => item.includes("update-"));
    }
    return [];
});
const deletePermission = computed(() => {
    if (permissions) {
        return permissions.value.filter((item) => item.includes("delete-"));
    }
    return [];
});

const selectedPermissions = ref([]);

watch(
    () => props.role?.permissions,
    (newValue) => {
        selectedPermissions.value = newValue || [];
    },
    {
        immediate: true,
    },
);

/*onMounted(() => {
    if (props.role && props.role.permissions) {
        selectedPermissions.value = props.role.permissions;
    }
});*/

const togglePermission = (permission) => {
    const index = selectedPermissions.value.indexOf(permission);
    if (index === -1) {
        selectedPermissions.value.push(permission);
    } else {
        selectedPermissions.value.splice(index, 1);
    }
    form.permissions = selectedPermissions.value;
};

watch(
    () => selectedPermissions.value,
    (newValue) => {
        form.permissions = newValue;
    },
);

const emit = defineEmits(["cancelNewRole", "cancelEditRole"]);
const cancelNewRole = () => {
    form.reset();
    emit("cancelNewRole", false);
};
const cancelEditRole = () => {
    form.reset();
    emit("cancelEditRole", false);
};
</script>
<template>
    <div class="bg-white rounded-md border border-gray-300 px-4 py-2 my-7">
        <h2>New Role</h2>
        <form @submit.prevent="submit">
            <div>
                <label class="block" for="name">Name</label>
                <input
                    id="name"
                    v-model="form.name"
                    class="border-gray-300 rounded-md shadow-md placeholder-custom"
                    name="name"
                    placeholder="name of role"
                    type="text"
                    @change="form.validate('name')"
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>
            <div class="my-7">
                <h3 v-if="props.role">Edit a Permissions</h3>
                <h3 v-else>Set a Permissions</h3>

                <hr class="my-2" />
                <label for="permissions">
                    <input
                        id="permissions-p"
                        v-model="selectedPermissions"
                        :value="`profile-user`"
                        name="permissions-p"
                        type="checkbox"
                        @chage="togglePermission('profile-user')"
                    />
                    profile-user
                </label>
                <hr class="my-2" />

                <div class="flex flex-wrap space-x-5">
                    <div
                        class="bg-amber-100 border border-gray-300 rounded-md px-4 py-2"
                    >
                        <h4 class="font-semibold text-sm">
                            Create Permissions
                        </h4>
                        <hr class="my-2 bg-amber-400 p-0.5" />
                        <ul>
                            <li v-for="(c, idx) in createPermission" :key="idx">
                                <label for="permissions">
                                    <input
                                        :id="`permissions-c${idx}`"
                                        v-model="selectedPermissions"
                                        :name="`permissions-c${idx}`"
                                        :value="c"
                                        type="checkbox"
                                        @chage="togglePermission(c)"
                                    />
                                    {{ c }}
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div
                        class="bg-green-100 border border-gray-300 rounded-md px-4 py-2"
                    >
                        <h4 class="font-semibold text-sm">Read Permissions</h4>
                        <hr class="my-2 bg-green-400 p-0.5" />
                        <ul>
                            <li v-for="(r, idx) in readPermission" :key="idx">
                                <label for="permissions">
                                    <input
                                        :id="`permissions-r${idx}`"
                                        v-model="selectedPermissions"
                                        :name="`permissions-r${idx}`"
                                        :value="r"
                                        type="checkbox"
                                        @chage="togglePermission(r)"
                                    />
                                    {{ r }}
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div
                        class="bg-cyan-100 border border-gray-300 rounded-md px-4 py-2"
                    >
                        <h4 class="font-semibold text-sm">Update Permission</h4>
                        <hr class="my-2 bg-cyan-400 p-0.5" />
                        <ul>
                            <li v-for="(u, idx) in updatePermission" :key="idx">
                                <label for="permissions">
                                    <input
                                        :id="`permissions-u${idx}`"
                                        v-model="selectedPermissions"
                                        :name="`permissions-u${idx}`"
                                        :value="u"
                                        type="checkbox"
                                        @chage="togglePermission(u)"
                                    />
                                    {{ u }}
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div
                        class="bg-red-100 border border-gray-300 rounded-md px-4 py-2"
                    >
                        <h4 class="font-semibold text-sm">Delete Permission</h4>
                        <hr class="my-2 bg-red-400 p-0.5" />
                        <ul>
                            <li v-for="(d, idx) in deletePermission" :key="idx">
                                <label for="permissions">
                                    <input
                                        :id="`permissions-d${idx}`"
                                        v-model="selectedPermissions"
                                        :name="`permissions-d${idx}`"
                                        :value="d"
                                        type="checkbox"
                                        @chage="togglePermission(d)"
                                    />
                                    {{ d }}
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div
                class="flex flex-row justify-items-center items-center mx-auto space-x-7 my-7"
            >
                <PrimaryButton>Save</PrimaryButton>
                <SecondaryButton v-if="!props.role" @click="cancelNewRole"
                    >Cancel
                </SecondaryButton>
                <SecondaryButton v-else @click="cancelEditRole"
                    >Cancel
                </SecondaryButton>
            </div>
        </form>
    </div>
</template>
