<script setup>
import {computed} from 'vue';
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    data: {
        type: Object,
        default: () => ({})
    }
})
const showPaginate = computed(() => Object.keys(props.data).length > 0 && (props.data.links.next !== null || props.data.links.prev !== null))
</script>
<template>
    <div v-if="showPaginate" class="flex flex-row items-center mt-4">
        <div v-for="(link, idx) in props.data.meta.links" :key="idx">
            <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="link.label"></div>
            <Link v-else
                  class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                  :class="{ 'bg-sky-400/50 text-white': link.active }"
                  :href="link.url"
            v-html="link.label"/>
        </div>
    </div>
</template>
