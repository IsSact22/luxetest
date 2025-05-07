<script setup>
import { ref, watch } from 'vue'
import { useDateFormatter } from '@/Composables/formatDate.js'

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
})

const emit = defineEmits(['close'])
const { formattedDateTime } = useDateFormatter()

const handleEscape = (e) => {
    if (e.key === 'Escape') {
        emit('close')
    }
}

watch(
    () => props.show,
    (value) => {
        if (value) {
            document.addEventListener('keydown', handleEscape)
        } else {
            document.removeEventListener('keydown', handleEscape)
        }
    }
)
</script>

<template>
    <Transition name="modal-fade">
        <div
            v-if="show"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-image-title"
            @click="$emit('close')"
        >
            <!-- Contenedor del modal -->
            <div
                class="relative w-full max-w-[90vw] h-[80vh] md:max-w-[700px] md:h-[70vh] bg-white rounded-lg shadow-2xl overflow-hidden transform transition-all"
                @click.stop
            >
                <!-- Header -->
                <div class="absolute top-0 left-0 right-0 flex justify-between items-center p-3 z-10 bg-gradient-to-b from-black/70 to-transparent">
                    <h3
                        id="modal-image-title"
                        class="text-xs sm:text-sm font-medium text-white truncate pl-3 pr-2 py-2 bg-black/50 rounded-md backdrop-blur-sm flex items-center space-x-2"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ imageName }}</span>
                    </h3>

                    <button
                        @click="$emit('close')"
                        class="p-2 rounded-full bg-black/60 hover:bg-black/80 text-white transition-all focus:outline-none focus:ring-2 focus:ring-white"
                        aria-label="Cerrar modal"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Imagen -->
                <div class="w-full h-full flex items-center justify-center p-4">
                    <img
                        :src="imageUrl"
                        :alt="imageName"
                        loading="lazy"
                        class="max-w-full max-h-[calc(80vh-100px)] object-contain rounded-md shadow-md"
                    />
                </div>

                <!-- Footer -->
                <div class="absolute bottom-0 left-0 right-0 p-3 z-10">
                    <div class="flex items-center justify-center">
                        <span class="flex items-center bg-black/60 backdrop-blur-sm rounded-md py-1.5 px-3 text-xs sm:text-sm text-white/90">
                            <svg class="h-4 w-4 mr-2 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
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