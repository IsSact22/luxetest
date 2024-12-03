<script setup>
import { ref } from "vue";

const props = defineProps({
    title: {
        type: String,
        default: "¿Estás seguro?",
    },
    message: {
        type: String,
        default: "Esta acción no se puede deshacer.",
    },
    confirmText: {
        type: String,
        default: "Confirmar",
    },
    cancelText: {
        type: String,
        default: "Cancelar",
    },
    onConfirm: {
        type: Function,
        required: true,
    },
    buttonCancelStyle: {
        type: String,
        default: "bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600",
    },
    buttonConfirmStyle: {
        type: String,
        default: "bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600",
    },
});
const isVisible = ref(false); // Controla la visibilidad del diálogo
const show = () => {
    isVisible.value = true; // Muestra el diálogo
};
const hide = () => {
    isVisible.value = false; // Oculta el diálogo
};
const handleConfirm = () => {
    props.onConfirm(); // Ejecuta la función de confirmación pasada como prop
    hide(); // Oculta el diálogo después de confirmar
};
const handleCancel = () => {
    console.log("action cancelled");
    hide(); // Oculta el diálogo si se cancela
};
defineExpose({ show }); // Exponer la función `show` para ser llamada desde el padre
</script>

<template>
    <div
        v-if="isVisible"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-lg font-semibold mb-4">{{ title }}</h2>
            <p class="mb-6">{{ message }}</p>
            <div class="flex justify-end space-x-4">
                <button
                    :class="`${buttonCancelStyle} ${cancelText}`"
                    @click="handleCancel"
                >
                    {{ cancelText }}
                </button>
                <button :class="`${buttonConfirmStyle}`" @click="handleConfirm">
                    {{ confirmText }}
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
