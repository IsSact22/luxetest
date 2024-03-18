<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {computed, ref} from 'vue';
import {route} from "ziggy-js";
import draggable from 'vuedraggable'
import KanbanComponent from "@/Components/Kanban/KanbanComponent.vue";
import KanbanColumn from "@/Components/Kanban/KanbanColumn.vue";
import KanbanCard from "@/Components/Kanban/KanbanCard.vue";

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

const backlog = ref(props.resource.data.task.filter(task => task.status === 'backlog'));
backlog.value.sort((a, b) => b.position - a.position);
const ready = ref(props.resource.data.task.filter(task => task.status === 'ready'));
ready.value.sort((a, b) => b.position - a.position);
const inProgress = ref(props.resource.data.task.filter(task => task.status === 'in_progress'));
inProgress.value.sort((a, b) => b.position - a.position);
const inReview = ref(props.resource.data.task.filter(task => task.status === 'in_review'));
inReview.value.sort((a, b) => b.position - a.position);
const done = ref(props.resource.data.task.filter(task => task.status === 'done'));
done.value.sort((a, b) => b.position - a.position);
</script>

<template>
    <Head title="Project" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Projects</h2>
        </template>

        <div class="flex flex-col mx-auto px-4 mt-4">
            <div class="flex flex-row space-x-12 mb-2">
                <span>Client: {{resource.data.client.customer_name}}</span>
                <span>Project-Name: {{resource.data.name}}</span>
                <span>Aircraft: {{resource.data.aircraft.name}}</span>
            </div>
            <KanbanComponent>
                <KanbanColumn title="backlog" subtitle="This item hasn't been started" :tasks="backlog" :add-button="true" />
                <KanbanColumn title="Ready" subtitle="This is ready to be picked up" :tasks="ready" />
                <KanbanColumn title="in Progress" subtitle="This is actively being worked on" :tasks="inProgress" />
                <KanbanColumn title="in Preview" subtitle="This item is in review" :tasks="inReview" />
                <KanbanColumn title="done" subtitle="This has been completed" :tasks="done" />
            </KanbanComponent>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
.drag > div {
    transform: rotate(5deg);
}
.ghost {
    background: lightgray;
    border-radius: 6px;
}
.ghost > div {
    visibility: hidden;
}
</style>
