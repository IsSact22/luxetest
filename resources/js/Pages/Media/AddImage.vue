<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import { useForm, router } from '@inertiajs/vue3';
import {ref} from "vue";
import {useToast} from "vue-toastification";

const toast = useToast();
const props = defineProps({
    modalName: {
        type: String,
        required: true,
    }
})
const form = useForm({
    modelName: '',
    images: null
});

const limitFiles = (files) => {
    if (files.length > 10) {
        toast.warning('You can only select up to 10 files.');
        return Array.from(files).slice(0, 10);
    }
    return files;
}

const onInput = (event) => {
    const files = event.target.files;
    form.images = limitFiles(files);
}

const submit = () => {
    router.post(`/add-image-to-model/${props.modalName}`, {
        _method: 'post',
        images: form.images
    })
}
</script>
<template></template>
