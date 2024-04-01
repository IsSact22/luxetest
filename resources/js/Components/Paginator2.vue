<template>
    <div>
        <div class="flex flex-row items-center mt-4">
            <button
                :class="[
          'mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-500 border rounded cursor-pointer',
          { 'disabled': currentPage === 1 },
        ]"
                v-if="currentPage !== 1"
                @click="goToPage(currentPage - 1)"
                aria-label="Previous"
            >
                <span aria-hidden="true">&laquo;</span>
            </button>
            <button
                v-for="page in lastPage"
                :key="page"
                :class="[
          'mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-500 border rounded cursor-pointer',
          { 'hover:text-blue-700 hover:bg-blue-300': currentPage !== page },
          { 'text-blue-700 bg-blue-300': currentPage === page },
        ]"
                @click="goToPage(page)"
            >
                {{ page }}
            </button>
            <button
                v-if="currentPage !== lastPage"
                class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-500 border rounded cursor-pointer hover:text-blue-700 hover:bg-blue-300"
                :class="{ 'disabled': currentPage === lastPage }"
                @click="goToPage(currentPage + 1)"
                aria-label="Next"
            >
                <span aria-hidden="true">&raquo;</span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';

// Props
const props = defineProps({
    currentPage: {
        type: Number,
        required: true,
    },
    lastPage: {
        type: Number,
        required: true,
    },
});

// Emits
const emit = defineEmits(['page-change']);

// Función para ir a una página específica
const goToPage = (page) => {
    if (page >= 1 && page <= props.lastPage) {
        emit('page-change', page);
    }
};
</script>
