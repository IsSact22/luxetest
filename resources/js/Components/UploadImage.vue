<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { useToast } from "vue-toastification";

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

form.transform((data) => {
    const formData = new FormData();
    formData.append('id', props.id);
    images.value.forEach((img, index) => {
        formData.append(`images[]`, img.file);
    });
    return formData;
});

const submit = async () => {
    if (!images.value.length) {
        toast.error('Por favor selecciona al menos una imagen');
        return;
    }

    form.post(route("camo_activities.add_images"), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            // toast.success('Imágenes subidas exitosamente');
            emit("uploaded", true);
            form.reset();
            images.value = [];
            preview.value = [];
        },
        // onError: (errors) => {
        //     toast.error('Error al subir las imágenes');
        //     console.error(errors);
        // }
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
            <p>Arrastrar y soltar imágenes</p>
        </div>
        <div
            class="drag-area"
            @dragover.prevent="onDragOver"
            @dragleave.prevent="onDragLeave"
            @drop.prevent="onDrop"
        >
            <span v-if="!isDragging">
                Arrastrar y soltar imágenes aquí o
                <span class="select" role="button" @click="selectFiles">
                    seleccionar
                </span>
            </span>
            <div v-else class="select">Soltar imágenes aquí</div>
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
                Archivos agregados <span class="badge-info">{{ images.length }}</span>
            </div>
        </div>
        <button v-if="form.isDirty" type="submit" @click="submit">
            Subir
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
