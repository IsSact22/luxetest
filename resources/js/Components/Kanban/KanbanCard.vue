<template>
    <div class="bg-gray-200/50 rounded-lg shadow-md p-4 mb-4" draggable="true" @dragstart="startDrag">
        <div class="float-right hover:cursor-move">
            <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="currentColor" fill-rule="evenodd"><path d="m7 5h2v2h-2z"/><path d="m12 5h2v2h-2z"/><path d="m7 9h2v2h-2z"/><path d="m12 9h2v2h-2z"/><path d="m7 13h2v2h-2z"/><path d="m12 13h2v2h-2z"/></g></svg>
        </div>
        <div class="flex flex-row justify-between items-center mb-2">
            <span class="badge-gray">Name:</span>
            <p class="text-sm font-bold">{{ data.name }}</p>
        </div>
        <div class="flex flex-row justify-between items-center mb-2">
            <span class="badge-gray">Service:</span>
            <p class="text-sm">{{ data.service.name }}</p>
        </div>
        <div class="mb-2">
            <div class="flex flex-row justify-between items-center">
                <span class="badge-purple">Estimate Time:</span>
                <span class="text-xs">{{ data.service.estimate_time }}</span>
            </div>
        </div>
        <div class="mb-2">
            <div class="flex flex-col">
                <span class="text-xs font-bold mr-2">Description:</span>
                <span class="text-xs">{{ data.description }}</span>
            </div>
        </div>
        <div class="mb-2">
            <div class="flex flex-row justify-between items-center">
                <span class="badge-red">Due Date:</span>
                <span class="text-xs">{{ data.due_date }}</span>
            </div>
        </div>
        <div class="flex flex-row justify-between items-center">
            <span class="badge-green">Update Date:</span>
            <span class="text-xs">{{ data.updated_at }}</span>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    data: {
        type: Object,
        default: () => ({})
    }
})
const startDrag = event => {
    console.log('startDrag', event)
}
const onDrop = (event) => {
    console.log('onDrop', event)
}
</script>
