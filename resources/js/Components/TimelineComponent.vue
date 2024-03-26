<template>
    <section class="dark:bg-gray-100 dark:text-gray-800">
        <div class="container max-w-5xl px-4 py-12 mx-auto">
            <div class="grid gap-4 mx-4 sm:grid-cols-12">
                <div class="col-span-12 sm:col-span-3">
                    <div class="text-center sm:text-left mb-14 before:block before:w-24 before:h-3 before:mb-5 before:rounded-md before:mx-auto sm:before:mx-0 before:dark:bg-green-600">
                        <h3 class="text-3xl font-semibold capitalize">{{title}}</h3>
                        <span class="text-sm font-bold tracking-wider uppercase dark:text-gray-600">Total Activities <span class="badge-info">{{subtitle}}</span></span>
                        <div class="flex flex-col">
                            <span>Total Labor: <b>{{formatCurrency(laborMountTotal)}}</b></span>
                            <span>Total Material: <b>{{formatCurrency(materialMountTotal)}}</b></span>
                        </div>
                    </div>
                </div>
                <div class="relative col-span-12 px-4 space-y-6 sm:col-span-9">
                    <div class="col-span-12 space-y-12 relative px-4 sm:col-span-8 sm:space-y-8 sm:before:absolute sm:before:top-2 sm:before:bottom-0 sm:before:w-0.5 sm:before:-left-3 before:dark:bg-gray-300">
                        <div v-for="(item, idx) in data" :key="idx"
                            class="flex flex-col sm:relative sm:before:absolute sm:before:top-2 sm:before:w-4 sm:before:h-4 sm:before:rounded-full sm:before:left-[-35px] sm:before:z-[1] before:dark:bg-green-600">
                            <div class="flex flex-row justify-items-center items-center space-x-3 text-xl font-semibold tracking-wide capitalize">
                                <button class="inline-block" @click="storeCamo.removeActivity(idx)">
                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(2 3)"><path d="m8 16c4.4380025 0 8-3.5262833 8-7.96428571 0-4.43800246-3.5619975-8.03571429-8-8.03571429-4.43800245 0-8 3.59771183-8 8.03571429 0 4.43800241 3.56199755 7.96428571 8 7.96428571z"/><path d="m4 8h8"/></g></svg>
                                </button>
                                <span class="inline-block">{{item.name}}</span>
                            </div>
                            <time class="text-xs tracking-wide uppercase dark:text-gray-600">{{item.date}}</time>
                            <p class="mt-3">{{item.description}}</p>
                            <div class="flex space-x-7">
                                <span v-if="item.labor_mount">Labor/Mount: <b>{{formatCurrency(item.labor_mount)}}</b></span>
                                <span v-else>Labor/Mount: <b>Refer to quote</b></span>
                                <span v-if="item.material_mount">Material/Mount: <b>{{formatCurrency(item.material_mount)}}</b></span>
                                <span v-else>Labor/Mount: <b>Refer to quote</b></span>
                            </div>
                            <div>
                                <span class="text-xs uppercase">Status</span>
                                <span v-if="item.status ==='pending'" class="badge-pending">{{item.status}}</span>
                                <span v-else-if="item.status ==='in_progress'" class="badge-progress">{{item.status}}</span>
                                <span v-else class="badge-approval">{{item.status}}</span>
                            </div>
                            <div>
                                <span class="text-xs uppercase">Approval Status</span>
                                <span v-if="item.approval_status ==='pending'" class="badge-pending">{{item.approval_status}}</span>
                                <span v-else class="badge-approval">{{item.approval_status}}</span>
                            </div>
                            <div>
                                <PrimaryButton @click="storeCamo.editActivity(idx)">Edit</PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup>
import {useCamoStore} from "@/Stores/useCamoStore.js";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import useFormatCurrency from '@/Composables/formatCurrency';
import {computed} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const storeCamo = useCamoStore();
const props = defineProps({
    title: String,
    subtitle: String,
    data: Object
})
const { formatCurrency } = useFormatCurrency();
const laborMountTotal = computed(() => {
    return props.data.reduce((total, activity) => total + Number(activity.labor_mount), 0);
})
const materialMountTotal = computed(() => {
    return props.data.reduce((total, activity) => total + Number(activity.material_mount), 0);
})
</script>
