<script setup>
import { ref } from "vue";

const props = defineProps({
    images: {
        type: Array,
    },
});

const selectedImage = ref(null);

const showModal = (image) => {
    selectedImage.value = image;
};

const closeModal = () => {
    selectedImage.value = null;
};
</script>

<template>
    <div class="gallery">
        <div
            v-for="image in images"
            :key="image.id"
            class="image-container bg-black-50 p-4 rounded-md border border-black-100"
        >
            <img
                :alt="image.title"
                :src="image.url"
                @click="showModal(image)"
            />
        </div>
        <div v-if="selectedImage" class="modal">
            <div class="modal-content">
                <span class="close-button" @click="closeModal">&times;</span>
                <img :alt="selectedImage.title" :src="selectedImage.url" />
                <p>{{ selectedImage.title }}</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    grid-gap: 20px;
}

.image-container {
    cursor: pointer;
}

.image-container img {
    width: 100%;
    height: auto;
}

.modal {
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close-button {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
</style>
