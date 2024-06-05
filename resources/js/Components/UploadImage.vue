<script setup>
import { ref } from "vue";
import { useToast } from "vue-toastification";
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const toast = useToast();
const props = defineProps({
    id: {
        type: Number,
        required: true,
    },
});
const emit = defineEmits(["uploaded"]);
const form = useForm({
    id: props.id,
    images: null,
});
const submit = async () => {
    form.post(route("camo_activities.add_images"), {
        onSuccess: () => {
            emit("uploaded", true);
            form.reset();
        },
    });
};

const isDragging = ref(false);
const fileInput = ref(null);
const images = ref([]);
const preview = ref([]);

const selectFiles = () => {
    fileInput.value.click();
};
const onFileSelect = (event) => {
    const files = event.target.files;
    if (files.length === 0) return;
    for (let i = 0; i < files.length; i++) {
        if (files[i].type.split("/")[0] !== "image") continue;
        if (!images.value.some((e) => e.name === files[i].name)) {
            images.value.push({ name: files[i].name, file: files[i] });
            preview.value.push({
                name: files[i].name,
                url: URL.createObjectURL(files[i]),
            });
        }
    }
    form.images = event.target.files;
};
const deleteImage = (index) => {
    preview.value.splice(index, 1);
    images.value.splice(index, 1);
};
const onDragOver = (event) => {
    event.preventDefault();
    isDragging.value = true;
    event.dataTransfer.dropEffect = "copy";
};
const onDragLeave = (event) => {
    event.preventDefault();
    isDragging.value = false;
};
const onDrop = (event) => {
    event.preventDefault();
    isDragging.value = false;
    const files = event.dataTransfer.files;
    form.images = files;
    for (let i = 0; i < files.length; i++) {
        if (files[i].type.split("/")[0] !== "image") continue;
        if (!images.value.some((e) => e.name === files[i].name)) {
            images.value.push({ name: files[i].name, file: files[i] });
            preview.value.push({
                name: files[i].name,
                url: URL.createObjectURL(files[i]),
            });
        }
    }
};
</script>

<template>
    <form class="card" @submit.prevent="submit" @keydown.enter.prevent>
        <div class="top">
            <p>Drag & drop images</p>
        </div>
        <div
            class="drag-area"
            @dragover.prevent="onDragOver"
            @dragleave.prevent="onDragLeave"
            @drop.prevent="onDrop"
        >
            <span v-if="!isDragging">
                Drag & drop image here or
                <span class="select" role="button" @click="selectFiles">
                    choose
                </span>
            </span>
            <div v-else class="select">Drop images here</div>
            <input
                id="file"
                ref="fileInput"
                multiple
                name="file"
                type="file"
                @change="onFileSelect"
                @input="form.images = $event.target.files"
            />
        </div>
        <div class="container">
            <div v-for="(image, index) in images" :key="index" class="image">
                <span
                    class="bg-white inline-flex items-center justify-center w-6 h-6 rounded-full cursor-pointer"
                    @click="deleteImage(index)"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </span>
                <img :alt="preview.name" :src="preview[index].url" />
            </div>
        </div>
        <div>
            <div>
                Added files <span class="badge-info">{{ images.length }}</span>
            </div>
        </div>
        <button v-if="form.isDirty" type="submit" @click="submit">
            Upload
        </button>
        <div class="my-2">
            <progress
                v-if="form.progress"
                :value="form.progress.percentage"
                max="100"
            >
                {{ form.progress.percentage }}%
            </progress>
        </div>
    </form>
</template>
