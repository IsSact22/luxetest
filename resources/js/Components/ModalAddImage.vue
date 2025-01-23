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

const showModalImage = ref(false);
const closeModalImage = () => {
    showModalImage.value = false
}

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

<template>
    <Modal :closeable="false" :show="showModalImage" @close="closeModalImage = false" backdrop="static">
        <div class="px-6 py-4 space-y-3">
            <form @submit.prevent="submit" @keydown.enter.prevent>
                <div>
                    <InputLabel for="images">Images</InputLabel>
                    <input id="images" name="images" type="file" multiple @input="onInput">

                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                        {{ form.progress.percentage }}%
                    </progress>
                    <div v-if="form.errors.images">
                        <span>{{form.errors.images}}</span>
                    </div>
                </div>
                <div class="flex justify-end space-x-4">
                    <PrimaryButton type="submit">Save</PrimaryButton>
                    <SecondaryButton @close="closeModalImage">Close</SecondaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
