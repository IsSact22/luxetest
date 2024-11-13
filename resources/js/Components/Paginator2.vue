<template>
    <div>
        <div class="flex flex-row items-center mt-4">
            <button
                v-if="currentPage !== 1"
                :class="[
                    'mr-1 mb-1 px-4 py-3 text-sm leading-4 text-blue-700 border rounded cursor-pointer hover:text-blue-400 hover:bg-cyan-200',
                    { disabled: currentPage === 1 },
                ]"
                aria-label="Previous"
                @click="goToPage(currentPage - 1)"
            >
                <span aria-hidden="true">&laquo;</span>
            </button>
            <button
                v-for="page in lastPage"
                :key="page"
                :class="[
                    'mr-1 mb-1 px-4 py-3 text-sm leading-4 text-blue-700 border rounded cursor-pointer',
                    {
                        'hover:text-blue-400 hover:bg-cyan-200':
                            currentPage !== page,
                    },
                    { 'text-blue-700 bg-cyan-200': currentPage === page },
                ]"
                @click="goToPage(page)"
            >
                {{ page }}
            </button>
            <button
                v-if="currentPage !== lastPage"
                :class="{ disabled: currentPage === lastPage }"
                aria-label="Next"
                class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-blue-700 border rounded cursor-pointer hover:text-blue-400 hover:bg-cyan-200"
                @click="goToPage(currentPage + 1)"
            >
                <span aria-hidden="true">&raquo;</span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { defineEmits, defineProps } from "vue";

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
const emit = defineEmits(["page-change"]);

// Función para ir a una página específica
const goToPage = (page) => {
    if (page >= 1 && page <= props.lastPage) {
        emit("page-change", page);
    }
};
</script>
