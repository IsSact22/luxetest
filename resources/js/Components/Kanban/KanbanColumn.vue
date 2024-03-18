<template>
    <div class="flex-1 p-4 border">
        <h2 class="flex flex-row justify-between items-center text-lg text-neutral-600 font-bold px-2 mb-4 capitalize bg-white">
            <span>{{title}}</span>
            <span class="badge-indigo">
                {{Object.keys(tasks).length}}
            </span>
        </h2>
        <small class="text-xs text-gray-700">{{subtitle}}</small>
        <ul>
            <li v-for="(task, idx) in tasks">
                <KanbanCard
                    :key="idx"
                    :data="task"
                />
            </li>
            <li v-if="addButton">
                <div class="flex flex-row justify-between items-center text-xs">
                    <div class="flex flex-row justify-items-center items-center">
                        <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m5.5 10.5h10"/><path d="m10.5 5.5v10"/></g></svg>
                        <span>Add Task</span>
                    </div>
                    <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(5 5)"><path d="m3.49859901.50058486-2 .00245141c-.55180573.00067635-.9987743.4481931-.9987743.99999924v1.99673096c0 .51283584.38604019.93550716.88337888.99327227l.11784682.00672698 2-.00245141c.55180573-.00067635.9987743-.4481931.9987743-.99999925v-1.9967302c0-.51283659-.38604019-.93550791-.88337887-.99327302zm6 0-2 .00245141c-.55180573.00067635-.9987743.4481931-.9987743.99999924v1.99673096c0 .51283584.38604019.93550716.88337888.99327227l.11784682.00672698 2-.00245141c.55180569-.00067635.99877429-.4481931.99877429-.99999925v-1.9967302c0-.51283659-.3860402-.93550791-.88337886-.99327302zm-6 6-2 .00245141c-.55180573.00067635-.9987743.4481931-.9987743.99999924v1.99673096c0 .51283583.38604019.93550713.88337888.99327223l.11784682.006727 2-.0024514c.55180573-.0006763.9987743-.4481931.9987743-.99999924v-1.9967302c0-.51283659-.38604019-.93550791-.88337887-.99327302z"/><path d="m8.5 6.5v4"/><path d="m10.5 8.5h-4"/></g></svg>
                </div>
            </li>
        </ul>
    </div>
</template>
<script setup>
import KanbanCard from "@/Components/Kanban/KanbanCard.vue";

const props = defineProps({
    title: String,
    subtitle: String,
    tasks: Object,
    addButton: {
        type: Boolean,
        default: false,
    },
})
</script>
