<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useDateFormatter } from '@/Composables/formatDate.js'
import { Link } from '@inertiajs/vue3'
import ImageModal from '@/Components/ImageModal.vue'


const { formattedDateTime } = useDateFormatter()

defineProps({
    media: {
        type: Array,
        required: true
    },
    camo: {
        type: Object,
        required: true
    }
})

const showModal = ref(false);
const selectedImage = ref(null);
const collapsedActivities = ref(new Set());

const openModal = (image) => {
    selectedImage.value = image
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false;
    selectedImage.value = null;
};

const deleteImage = async (activity, image) => {
    if (!confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
        return;
    }

    try {
        await axios.delete('/api/v1/media/delete', {
            data: {
                model_name: 'CamoActivity',
                model_id: activity.activity_id,
                media_id: image.id
            }
        });

        // Eliminar la imagen del array local
        const index = activity.media.findIndex(img => img.id === image.id);
        if (index !== -1) {
            activity.media.splice(index, 1);
        }

        // Si no quedan imágenes, colapsar la actividad
        if (activity.media.length === 0) {
            collapsedActivities.value.add(activity.activity_id);
        }
    } catch (error) {
        console.error('Error al eliminar la imagen:', error);
        alert('Error al eliminar la imagen. Por favor, inténtalo de nuevo.');
    }
};

const toggleActivity = (activityId) => {
    if (collapsedActivities.value.has(activityId)) {
        collapsedActivities.value.delete(activityId);
    } else {
        collapsedActivities.value.add(activityId);
    }
}
</script>

<template>
    <Head title="Galería de Imágenes" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center space-x-2">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Galería de Imágenes - CAMO {{ camo.id }}</span>
                </h2>
                <Link :href="route('camos.show', camo.id)" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md shadow-sm transition-all duration-200 space-x-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Volver al CAMO</span>
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-4 sm:p-6">
                        <div v-if="media.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay imágenes disponibles</h3>
                            <p class="mt-1 text-sm text-gray-500">Las imágenes aparecerán aquí cuando se suban a las actividades.</p>
                        </div>
                        <div v-else v-for="activity in media" :key="activity.activity_id" class="mb-8 last:mb-0">
                            <div @click="toggleActivity(activity.activity_id)" 
                                 class="flex items-center justify-between mb-6 pb-3 border-b border-gray-200 cursor-pointer hover:bg-gray-50 rounded-lg p-3 transition-colors duration-200">
                                <div class="flex items-center space-x-2">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ activity.activity_name }}
                                    </h3>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-500">{{ activity.media.length }} imágenes</span>
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                         :class="{ 'rotate-180': !collapsedActivities.has(activity.activity_id) }"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            <div v-show="!collapsedActivities.has(activity.activity_id)"
                                 class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 justify-items-center transition-all duration-300">

                                <div v-for="image in activity.media" :key="image.id" class="relative group w-full max-w-[252px]">
                                    <div class="aspect-[3/2] rounded-md overflow-hidden bg-gray-100 shadow-sm transition-all duration-300 group-hover:shadow-md border border-gray-200 hover:border-blue-400 flex items-center justify-center relative">
                                        <!-- Contenedor de acciones -->
                                        <div class="absolute top-0 right-0 p-2 z-20 flex gap-2">
                                            <!-- Botón de eliminar -->
                                            <button @click.stop="deleteImage(activity, image)"
                                                    class="p-1.5 rounded-md bg-red-500 hover:bg-red-600 text-white shadow-sm hover:shadow-md transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Imagen con cursor pointer -->
                                        <div class="w-full h-full cursor-pointer" @click="openModal(image)">
                                            <img 
                                                :src="image.thumbnail" 
                                                :alt="image.name"
                                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                            >
                                        </div>

                                        <!-- Overlay con nombre -->
                                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                                            <p class="text-white text-sm font-medium truncate">{{ image.name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ImageModal
            v-if="selectedImage"
            :show="showModal"
            :image-url="selectedImage?.url"
            :image-name="selectedImage?.name"
            :created-at="selectedImage?.created_at"
            @close="closeModal"
        />
    </AuthenticatedLayout>
</template>
