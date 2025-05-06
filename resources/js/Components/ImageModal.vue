<script setup>
import { ref, watch } from 'vue';
import { useDateFormatter } from '@/Composables/formatDate.js';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    imageUrl: {
        type: String,
        required: true
    },
    imageName: {
        type: String,
        required: true
    },
    createdAt: {
        type: String,
        required: true
    }
});

const emit = defineEmits(['close']);
const { formattedDateTime } = useDateFormatter();

const handleEscape = (e) => {
    if (e.key === 'Escape') {
        emit('close');
    }
};

watch(() => props.show, (value) => {
    if (value) {
        document.addEventListener('keydown', handleEscape);
    } else {
        document.removeEventListener('keydown', handleEscape);
    }
});
</script>

<template>
    <Transition name="modal-fade">
        <div v-if="show" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
             @click="$emit('close')">
            <div class="relative w-[90vw] h-[70vh] md:w-[700px] md:h-[500px] bg-white rounded-lg shadow-2xl overflow-hidden transform transition-all"
                 @click.stop>
                <!-- Header -->
                <div class="absolute top-0 left-0 right-0 flex justify-between items-center p-4 z-10">
                    <h3 class="text-sm font-medium text-white truncate pl-2 flex items-center space-x-2 bg-black/50 backdrop-blur-sm rounded-lg py-2 px-3">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ imageName }}</span>
                    </h3>
                    <button @click="$emit('close')" 
                            class="p-2 rounded-full bg-black/50 hover:bg-black/70 text-white/90 hover:text-white transition-all backdrop-blur-sm">
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Imagen -->
                <div class="w-full h-full flex items-center justify-center p-4">
                    <img :src="imageUrl" 
                         :alt="imageName"
                         class="max-w-full max-h-[calc(70vh-120px)] md:max-h-[400px] w-auto h-auto object-contain rounded-md"
                    >
                </div>

                <!-- Footer -->
                <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
                    <div class="flex items-center justify-center">
                        <span class="flex items-center bg-black/50 backdrop-blur-sm rounded-lg py-2 px-3 text-sm text-white/90">
                            <svg class="h-4 w-4 mr-2 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Subido el {{ formattedDateTime(createdAt) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.3s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}
</style>
