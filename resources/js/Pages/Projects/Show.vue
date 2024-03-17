<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { Link } from '@inertiajs/vue3';
import {computed} from 'vue';
import {route} from "ziggy-js";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({})
    }
})
const columns = [
    { status: 'backlog', label: 'backlog' },
    { status: 'ready', label: 'ready' },
    { status: 'in_progress', label: 'in Progress' },
    { status: 'in_review', label: 'in Review' },
    { status: 'done', label: 'done' }
];
const getTasksByStatus = (status) => {
    return props.resource.data.task.filter(task => task.status === status)
};
</script>
<template>
    <Head title="Project" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Projects</h2>
        </template>
        <div class="flex flex-col mx-auto px-4 mt-4">
            <div class="flex rounded-md border">
                <div v-for="column in columns" :key="column.status" class="flex-1 p-4 border">
                    <h2 class="text-lg text-neutral-600 font-bold px-2 mb-4 capitalize bg-white">
                        {{ column.label }} <br>
                        <small v-show="column.status === 'backlog'" class="text-xs text-gray-700">This item hasn't been started</small>
                        <small v-show="column.status === 'ready'" class="text-xs text-blue-700">This is ready to be picked up</small>
                        <small v-show="column.status === 'in_progress'" class="text-xs text-amber-700">This is actively being worked on</small>
                        <small v-show="column.status === 'in_review'" class="text-xs text-violet-700">This item is in review</small>
                        <small v-show="column.status === 'done'" class="text-xs text-emerald-700">This has been completed</small>
                    </h2>
                    <div class="space-y-4">
                        <div v-for="task in getTasksByStatus(column.status)" :key="task.id" class="text-sm p-2 rounded shadow hover:bg-sky-100 space-y-0.5">
                            <p class="flex flex-row justify-items-center items-center space-x-2">
                                <span class="badge-gray">Task Name</span>
                                <span class="text-xs">{{ task.name }}</span>
                            </p>
                            <p class="flex flex-row justify-items-center items-center space-x-2">
                                <span class="badge-gray">Service Name</span>
                                <span class="text-xs">{{ task.service.name }}</span>
                            </p>
                            <p class="flex flex-row justify-items-center items-center space-x-2">
                                <span class="badge-purple">Estimate time</span>
                                 <span>{{task.service.estimate_time}}</span>
                            </p>
                            <p class="text-xs">
                                <span class="font-semibold">Description:</span>
                                {{ task.description }}
                            </p>
                            <p v-show="task.status !== 'done'" class="flex flex-row justify-items-center items-center space-x-2">
                                <span class="badge-red">Due Date</span>
                                <span>{{task.due_date}}</span>
                            </p>
                            <p v-show="task.status !== 'backlog'" class="flex flex-row justify-items-center items-center space-x-2">
                                <span class="badge-green">Updated Date</span>
                                <span>{{task.updated_at}}</span></p>
                        </div>
                    </div>
                    <div v-show="column.status === 'backlog'" class="my-4">
                        <div class="flex flex-row justify-between items-center text-xs">
                            <div class="flex flex-row justify-items-center items-center">
                                <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m5.5 10.5h10"/><path d="m10.5 5.5v10"/></g></svg>
                                <span>Add Task</span>
                            </div>
                            <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(5 5)"><path d="m3.49859901.50058486-2 .00245141c-.55180573.00067635-.9987743.4481931-.9987743.99999924v1.99673096c0 .51283584.38604019.93550716.88337888.99327227l.11784682.00672698 2-.00245141c.55180573-.00067635.9987743-.4481931.9987743-.99999925v-1.9967302c0-.51283659-.38604019-.93550791-.88337887-.99327302zm6 0-2 .00245141c-.55180573.00067635-.9987743.4481931-.9987743.99999924v1.99673096c0 .51283584.38604019.93550716.88337888.99327227l.11784682.00672698 2-.00245141c.55180569-.00067635.99877429-.4481931.99877429-.99999925v-1.9967302c0-.51283659-.3860402-.93550791-.88337886-.99327302zm-6 6-2 .00245141c-.55180573.00067635-.9987743.4481931-.9987743.99999924v1.99673096c0 .51283583.38604019.93550713.88337888.99327223l.11784682.006727 2-.0024514c.55180573-.0006763.9987743-.4481931.9987743-.99999924v-1.9967302c0-.51283659-.38604019-.93550791-.88337887-.99327302z"/><path d="m8.5 6.5v4"/><path d="m10.5 8.5h-4"/></g></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
