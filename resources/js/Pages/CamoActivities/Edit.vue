<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { route } from "ziggy-js";
import { useToast } from "vue-toastification";
import UploadImage from "@/Components/UploadImage.vue";
import CamoActivityForm from "@/Pages/CamoActivities/Partials/CamoActivityForm.vue";

const toast = useToast();

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    _method: "patch",
    camo_id: props.resource.data.camo_id,
    name: props.resource.data.name,
    date: props.resource.data.date,
    description: props.resource.data.description,
    comments: props.resource.data.comments,
    awr: props.resource.data.awr,
    labor_mount: props.resource.data.labor_mount,
    material_mount: props.resource.data.material_mount,
    material_information: props.resource.data.material_information,
    status: props.resource.data.status,
    add_images: null,
});

const handleImages = (images) => {
    form.add_images.push(...images);
};

const statusSelect = ref(false);
const handleStatusSelect = () => {
    statusSelect.value = !statusSelect.value;
};
const statusList = ref([
    { value: "pending", label: "Pending" },
    { value: "in_progress", label: "in Progress" },
    { value: "completed", label: "Completed" },
]);

const approvalStatusList = ref([
    { value: "pending", label: "Pending" },
    { value: "approved", label: "Approved" },
]);
const submit = () => {
    form.patch(route("camo_activities.update", props.resource.data.id));
};
const goBack = () => {
    const camoId = props.resource.data.camo_id;
    router.get(route("camos.show", camoId), {}, { replace: false });
};
const handleUpload = (eventData) => {
    if (eventData) {
        const toastOptions = {
            timeout: 2000,
            onClose: () => {
                const camoId = props.resource.data.camo_id;
                goBack();
            },
        };
        toast.success(
            "The images have been uploaded successfully.",
            toastOptions,
        );
    }
};
</script>

<template>
    <Head title="Edit Activity" />
    <AuthenticatedLayout>
        <template #header>
            <h2>
                Edit Activity <small>{{ props.resource.data.id }}</small>
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mt-6">
            <CamoActivityForm
                :cam-id="usePage().props.auth.user.id"
                :camo-activity="props.resource.data"
                :user="usePage().props.auth.user"
            />
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mt-6">
            <form @submit.prevent="submit" @keydown.enter.prevent>
                <div>
                    <InputLabel for="labor_rate_id">Type Activity</InputLabel>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <!-- col 1 -->
                    <div>
                        <div class="flex flex-row space-x-3">
                            <div>
                                <InputLabel for="name">Activity</InputLabel>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    class="rounded-md border-gray-100 bg-gray-200"
                                    disabled
                                    name="name"
                                    readonly
                                    type="text"
                                />
                            </div>
                            <div v-if="form.date">
                                <InputLabel for="date">Date</InputLabel>
                                <input
                                    id="date"
                                    v-model="form.date"
                                    class="rounded-md border-gray-100 bg-gray-200"
                                    disabled
                                    name="date"
                                    readonly
                                    type="text"
                                />
                            </div>
                        </div>

                        <div class="flex flex-row justify-around space-x-3">
                            <div>
                                <InputLabel for="description"
                                    >Description
                                </InputLabel>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    class="rounded-md border-gray-300 w-full"
                                    cols="30"
                                    name="description"
                                    rows="5"
                                ></textarea>
                            </div>
                            <div>
                                <InputLabel for="comments">Comments</InputLabel>
                                <div>
                                    <textarea
                                        id="comments"
                                        v-model="form.comments"
                                        class="rounded-md border-gray-300 w-full"
                                        cols="30"
                                        name="comments"
                                        rows="5"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="w-1/2">
                            <InputLabel for="material_mount">AWR</InputLabel>
                            <input
                                id="awr"
                                v-model="form.awr"
                                class="rounded-md border-gray-300 w-full"
                                name="awr"
                                type="text"
                            />
                        </div>

                        <div
                            class="flex flex-row justify-items-center space-x-3"
                        >
                            <div class="my-1">
                                <InputLabel for="labor_mount"
                                    >Labor Mount
                                </InputLabel>
                                <input
                                    id="labor_mount"
                                    v-model="form.labor_mount"
                                    class="text-right rounded-md border-gray-300"
                                    name="labor_mount"
                                    step="0.01"
                                    type="number"
                                />
                            </div>
                            <div class="my-1">
                                <InputLabel for="material_mount"
                                    >Material Mount
                                </InputLabel>
                                <input
                                    id="labor_mount"
                                    v-model="form.material_mount"
                                    class="text-right rounded-md border-gray-300"
                                    name="labor_mount"
                                    step="0.01"
                                    type="number"
                                />
                            </div>
                        </div>

                        <div>
                            <InputLabel>Material Information</InputLabel>
                            <textarea
                                id="description"
                                v-model="form.material_information"
                                class="rounded-md border-gray-300 w-full"
                                cols="30"
                                name="description"
                                rows="2"
                            ></textarea>
                        </div>

                        <div>
                            <InputLabel for="status">Status</InputLabel>
                            <select
                                id="status"
                                v-model="form.status"
                                :disabled="!$page.props.auth.user.is_cam"
                                class="rounded-md border-gray-300"
                                name="status"
                            >
                                <option
                                    v-for="(status, idx) in statusList"
                                    :key="idx"
                                    :value="status.value"
                                >
                                    {{ status.label }}
                                </option>
                            </select>
                        </div>

                        <div v-if="$page.props.auth.user.is_owner" class="my-2">
                            <InputLabel for="approval_status"
                                >Approval Status
                            </InputLabel>
                            <select
                                id="status"
                                v-model="form.status"
                                class="rounded-md border-gray-300"
                                name="status"
                            >
                                <option
                                    v-for="(item, idx) in approvalStatusList"
                                    :key="idx"
                                    :value="item.value"
                                >
                                    {{ item.label }}
                                </option>
                            </select>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <PrimaryButton v-if="form.isDirty" type="submit"
                                >Save
                            </PrimaryButton>
                            <SecondaryButton @click="goBack"
                                >Go Back
                            </SecondaryButton>
                        </div>

                        <div
                            v-if="Object.keys(form.errors).length > 0"
                            class="border rounded-md px-7 py-2 my-2"
                        >
                            <ul class="list-decimal">
                                <li
                                    v-for="(item, idx) in form.errors"
                                    class="text-sm text-red-500"
                                >
                                    {{ item }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- col 2 -->
                    <div>
                        <div v-if="props.resource.data.images.length > 0">
                            <h2>Activity Images</h2>
                            <div
                                class="flex flex-wrap justify-items-center items-center"
                            >
                                <div
                                    v-for="(img, index) in props.resource.data
                                        .images"
                                    :key="index"
                                    class="p-0.5"
                                >
                                    <a
                                        v-tooltip="`${img.name}`"
                                        :href="img.original_url"
                                        target="_blank"
                                    >
                                        <img
                                            :alt="img.file_name"
                                            :src="img.preview_url"
                                            class="object-contain rounded-md"
                                        />
                                    </a>
                                </div>
                            </div>
                        </div>
                        <UploadImage
                            :id="props.resource.data.id"
                            @uploaded="handleUpload"
                        />
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
