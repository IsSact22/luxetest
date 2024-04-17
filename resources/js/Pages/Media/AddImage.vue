<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UploadImage from '@/Components/UploadImage.vue';

const props = defineProps({
    modelName: {
        type: String,
        required: true,
    },
    id: {
        type: Number,
        required: true,
    },
    record: {
        type: {
            type: Object,
            default: () => ({})
        }
    }
});

</script>
<template>
    <Head title="Add Images"/>
    <AuthenticatedLayout>
        <template #header>
            <h2>Add Images to activity</h2>
        </template>

        <div class="max-w-7xl mx-auto mt-6">
            <div class="grid grid-cols-2 gap-4">
                <!-- col information -->
                <div class="border border-brand-neutral-500 rounded-md p-2 col-auto">
                    <div class="px-4 rounded-md">
                        <h2 class="text-brand-accent">Activity</h2>
                        <div>
                            <p><b>Camo</b>: {{record.camo_id}}</p>
                            <p><b>Date</b>: {{record.date}}</p>
                            <p><b>Name</b>: {{record.name}}</p>
                            <p class="flex flex-row justify-between items-center">
                                <span><b>Status</b>: <span :class="`badge-${record.status}`">{{record.status}}</span></span>
                                <span><b>Approval Status</b>: <span :class="`badge-${record.approval_status}`">{{record.approval_status}}</span></span>
                            </p>
                        </div>
                        <hr>
                        <div>
                            <p class="border rounded-md p-1 my-1 bg-neutral-200/50"><b class="text-brand-accent">Description</b>: <br> <small>{{record.description}}</small></p>
                            <p class="border rounded-md p-1 my-1 bg-neutral-200/50"><b class="text-brand-accent">Comments</b>: <br> <small>{{record.comments}}</small></p>
                            <p class="border rounded-md p-1 my-1 bg-neutral-200/50"><b class="text-brand-accent">Material Information</b>: <br> <small>{{record.material_information}}</small></p>
                        </div>
                    </div>
                </div>
                <!-- col form images -->
                <div class="p-2">
                    <div @submit.prevent @keydown.enter.prevent class="flex flex-col">
                        <div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
