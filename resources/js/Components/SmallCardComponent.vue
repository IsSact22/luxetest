<template>
    <div class="border p-2 shadow">
        <div class="flex items-center bg-white border rounded-sm overflow-hidden shadow">
            <div class="p-4 bg-emerald-200">
                <svg class="h-20 w-20 fill-emerald-400 stroke-emerald-600" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
                    <g fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" transform="translate(1 2)">
                        <path d="m.5 7 16-6.535-2.8 14.535z"/>
                        <path d="m16.5.5-11 10"/>
                        <path d="m5.5 10.5v5l3-3"/>
                    </g>
                </svg>
            </div>
            <div class="px-4 text-gray-700">
                <h3 class="text-sm font-semibold tracking-wider"v-tooltip="'Customer Name'">{{title}}</h3>
                <p class="text-3xl text-neutral-500 font-bold" v-tooltip="'Aircraft'">{{aircraft}}</p>
                <span class="flex items-center space-x-2">
					<svg class="h-6 w-6 fill-blue-400" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg"><path d="m7.5.5c1.65685425 0 3 1.34314575 3 3v2c0 1.65685425-1.34314575 3-3 3s-3-1.34314575-3-3v-2c0-1.65685425 1.34314575-3 3-3zm7 14v-.7281753c0-3.1864098-3.6862915-5.2718247-7-5.2718247s-7 2.0854149-7 5.2718247v.7281753c0 .5522847.44771525 1 1 1h12c.5522847 0 1-.4477153 1-1z" stroke-linecap="round" stroke-linejoin="round" transform="translate(3 2)"/></svg>
					<span class="text-neutral-500 font-semibold" v-tooltip="'Owner'">{{owner}}</span>
				</span>
                <span class="flex items-center space-x-2">
					<svg class="h-6 w-6 fill-purple-400 stroke-purple-400" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg"><g fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" transform="translate(1 2)"><path d="m7.5.5c1.65685425 0 3 1.34314575 3 3v2c0 1.65685425-1.34314575 3-3 3s-3-1.34314575-3-3v-2c0-1.65685425 1.34314575-3 3-3zm7 14v-.7281753c0-3.1864098-3.6862915-5.2718247-7-5.2718247s-7 2.0854149-7 5.2718247v.7281753c0 .5522847.44771525 1 1 1h12c.5522847 0 1-.4477153 1-1z"/><path d="m11.5199327.67783074c1.1547685.41741154 1.9800673 1.52341097 1.9800673 2.82216926v1c0 1.29707884-.8475766 2.5813505-2 3 .6742649-.91876977 1.0109204-2.0857069 1.0099664-3.50081137s-.3309652-2.52222377-.9900337-3.32135789zm4.9800673 14.82216926h1c.5522847 0 1-.4477153 1-1 0-.2427251 0-.4854502 0-.7281753 0-2.1698712-1.7094418-3.82917861-3.8465775-4.66705336 0 0 2.8465775 2.39522866 1.8465775 6.39522866z" fill="currentColor"/></g></svg>
					<span class="text-neutral-500 font-semibold" v-tooltip="'Project Manager'">{{manager}}</span>
				</span>
            </div>
        </div>
        <div>
            <a class="b-goto my-2" :href="route('camos.show', id)">Got to CAMO</a>
        </div>
        <div>
            <h3 class="text-base font-semibold text-center uppercase">Progress statics</h3>

            <div class="w-full bg-gray-200 overflow-hidden my-2">
                <div class="flex">
                    <div class="bg-violet-500 text-white py-2 text-xs text-center h-4 place-content-center" :style="{ width: `${getPercentage(statusCounts.in_progress)}%` }">{{getPercentage(statusCounts.in_progress)}}%</div>
                    <div class="bg-green-500 text-white py-2 text-xs text-center h-4 place-content-center" :style="{ width: `${getPercentage(statusCounts.completed)}%` }">{{getPercentage(statusCounts.completed)}}%</div>
                    <div class="bg-orange-500 text-white py-2 text-xs text-center h-4 place-content-center" :style="{ width: `${getPercentage(statusCounts.pending)}%` }">{{getPercentage(statusCounts.pending)}}%</div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-1">
                <div class="flex flex-col justify-items-center items-center border rounded-md p-4">
                    <span class="inline-block h-8 w-8 bg-violet-500 rounded-full p-1">
                        <svg class="h-6 w-6 stroke-white" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" transform="matrix(-1 0 0 1 19 2)"><circle cx="8.5" cy="8.5" r="8"/><path d="m8.5 5.5v4h-3.5"/></g></svg>
                    </span>
                    <span class="my-1 text-xl">{{statusCounts.in_progress}}</span>
                    <span>In Progress</span>
                </div>
                <div class="flex flex-col justify-items-center items-center border rounded-md p-4">
                    <span class="inline-block h-8 w-8 bg-green-500 rounded-full p-1">
                        <svg class="h-6 w-6 stroke-white" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" transform="translate(2 2)"><circle cx="8.5" cy="8.5" r="8"/><path d="m5.5 9.5 2 2 5-5"/></g></svg>
                    </span>
                    <span class="my-1 text-xl">{{statusCounts.completed}}</span>
                    <span>Completed</span>
                </div>
                <div class="flex flex-col justify-items-center items-center border rounded-md p-4">
                    <span class="inline-block h-8 w-8 bg-orange-500 rounded-full p-1">
                        <svg class="h-6 w-6 stroke-white" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" transform="translate(2 2)"><path d="m2.5.5h12c1.1045695 0 2 .8954305 2 2v12c0 1.1045695-.8954305 2-2 2h-12c-1.1045695 0-2-.8954305-2-2v-12c0-1.1045695.8954305-2 2-2z"/><path d="m.5 4.5h16"/><path d="m8.5 7.5v6.056"/><path d="m8.5 7.5v6" transform="matrix(0 1 -1 0 19 2)"/></g></svg>
                    </span>
                    <span class="my-1 text-xl">{{statusCounts.pending}}</span>
                    <span>Pending</span>
                </div>
            </div>
        </div>
<!--        <div>
            <div class="flex items-center -mx-4 space-x-2 overflow-x-auto overflow-y-hidden sm:justify-center flex-nowrap my-4">
                <button
                        :class="{ 'border-b-blue-400' : typeChart === 1}"
                        class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                        @click="typeChart = 1"
                >PieChart</button>
                <button
                        :class="{ 'border-b-blue-400' : typeChart === 2}"
                        class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                        @click="typeChart = 2"
                >LineChart</button>
                <button
                        :class="{ 'border-b-blue-400' : typeChart === 3}"
                        class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                        @click="typeChart = 3"
                >BarChart</button>
            </div>
        </div>-->
<!--        <div class="my-4">
            <PieChart v-if="typeChart === 1" :chartData="dataSet" :options="options" />
            <LineChart v-else-if="typeChart === 2" :chartData="dataSet" :options="options" />
            <BarChart v-else :chartData="dataSet" :options="options" />
        </div>-->
    </div>

</template>
<script setup>
import {computed, onMounted, ref} from 'vue'
import {Chart, registerables} from "chart.js";
import {PieChart, LineChart, BarChart} from "vue-chart-3";
import {route} from "ziggy-js";

Chart.register(...registerables);

const props = defineProps({
    id: Number,
    title: String,
    subtitle: String,
    owner: String,
    manager: String,
    aircraft: String,
    activities: Object,
})

const statusCounts = {
    pending: 0,
    in_progress: 0,
    completed: 0
}
props.activities.forEach(camo => {
    statusCounts[camo.status]++;
})

const getPercentage = count => {
    const totalCount = statusCounts.pending + statusCounts.in_progress + statusCounts.completed;
    return Math.round((count / totalCount) * 100);
};

const typeChart = ref(1)

const options = ref({
    responsive: true,
    plugins: {
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: 'Activities Status',
        }
    },
});

const dataSet = computed(() => {
    const statuses = ['pending', 'in_progress', 'completed'];
    const statusCounts = {
        pending: 0,
        in_progress: 0,
        completed: 0
    }
    props.activities.forEach(camo => {
        statusCounts[camo.status]++;
    })
    const data = Object.values(statusCounts);
    const total = data.reduce((sum, count) => sum + count, 0)
    return {
        labels: ['pending', 'in_progress', 'completed'],
        datasets: [
            {
                data: data.map(count => Math.round((count / total) * 100)),
                backgroundColor: ['#fdba74', '#818cf8', '#a3e635']
            }
        ],
    }
})
</script>
