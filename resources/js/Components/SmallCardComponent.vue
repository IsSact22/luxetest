<script setup>
import { route } from "ziggy-js";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    id: Number,
    title: String,
    subtitle: String,
    owner: String,
    manager: String,
    aircraft: {
        type: Object,
        required: true,
    },
    activities: {
        type: Object,
        required: true,
    },
});

const statusCounts = {
    pending: 0,
    in_progress: 0,
    completed: 0,
};
props.activities.forEach((camo) => {
    statusCounts[camo.status]++;
});

const getPercentage = (count) => {
    const totalCount =
        statusCounts.pending +
        statusCounts.in_progress +
        statusCounts.completed;
    return Math.round((count / totalCount) * 100);
};

const goToCamo = () => {
    router.get(route("camos.show", props.id));
};
</script>
<template>
    <div
        class="border rounded-md p-2 shadow hover:cursor-pointer hover:drop-shadow-md"
        @click="goToCamo"
    >
        <div
            class="flex items-center bg-white border rounded-md overflow-hidden shadow"
        >
            <div class="p-4 bg-emerald-100">
                <svg
                    class="h-20 w-20 fill-emerald-400 stroke-emerald-600"
                    viewBox="0 0 21 21"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <g
                        fill-rule="evenodd"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        transform="translate(1 2)"
                    >
                        <path d="m.5 7 16-6.535-2.8 14.535z" />
                        <path d="m16.5.5-11 10" />
                        <path d="m5.5 10.5v5l3-3" />
                    </g>
                </svg>
            </div>
            <div class="px-4 text-gray-700">
                <h3
                    v-tooltip="'Customer Name'"
                    class="text-sm font-semibold tracking-wider"
                >
                    {{ title }}
                </h3>
                <p
                    v-tooltip="'Aircraft'"
                    class="text-xl text-neutral-500 font-bold"
                >
                    {{ aircraft.model_aircraft.name }} / {{ aircraft.register }}
                </p>
                <span class="flex items-center space-x-2">
                    <svg
                        class="h-6 w-6 fill-blue-400"
                        viewBox="0 0 21 21"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="m7.5.5c1.65685425 0 3 1.34314575 3 3v2c0 1.65685425-1.34314575 3-3 3s-3-1.34314575-3-3v-2c0-1.65685425 1.34314575-3 3-3zm7 14v-.7281753c0-3.1864098-3.6862915-5.2718247-7-5.2718247s-7 2.0854149-7 5.2718247v.7281753c0 .5522847.44771525 1 1 1h12c.5522847 0 1-.4477153 1-1z"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            transform="translate(3 2)"
                        />
                    </svg>
                    <span
                        v-tooltip="'Owner'"
                        class="text-neutral-500 font-semibold"
                        >{{ owner }}</span
                    >
                </span>
                <span class="flex items-center space-x-2">
                    <svg
                        class="h-6 w-6 fill-purple-400 stroke-purple-400"
                        viewBox="0 0 21 21"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <g
                            fill-rule="evenodd"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            transform="translate(1 2)"
                        >
                            <path
                                d="m7.5.5c1.65685425 0 3 1.34314575 3 3v2c0 1.65685425-1.34314575 3-3 3s-3-1.34314575-3-3v-2c0-1.65685425 1.34314575-3 3-3zm7 14v-.7281753c0-3.1864098-3.6862915-5.2718247-7-5.2718247s-7 2.0854149-7 5.2718247v.7281753c0 .5522847.44771525 1 1 1h12c.5522847 0 1-.4477153 1-1z"
                            />
                            <path
                                d="m11.5199327.67783074c1.1547685.41741154 1.9800673 1.52341097 1.9800673 2.82216926v1c0 1.29707884-.8475766 2.5813505-2 3 .6742649-.91876977 1.0109204-2.0857069 1.0099664-3.50081137s-.3309652-2.52222377-.9900337-3.32135789zm4.9800673 14.82216926h1c.5522847 0 1-.4477153 1-1 0-.2427251 0-.4854502 0-.7281753 0-2.1698712-1.7094418-3.82917861-3.8465775-4.66705336 0 0 2.8465775 2.39522866 1.8465775 6.39522866z"
                                fill="currentColor"
                            />
                        </g>
                    </svg>
                    <span
                        v-tooltip="'Project Manager'"
                        class="text-neutral-500 font-semibold"
                        >{{ manager }}</span
                    >
                </span>
            </div>
        </div>

        <div>
            <div class="w-full bg-gray-200 overflow-hidden my-2">
                <div class="flex">
                    <div
                        :style="{
                            width: `${getPercentage(statusCounts.in_progress)}%`,
                        }"
                        class="bg-violet-400 text-white py-2 text-xs text-center h-8 place-content-center"
                    >
                        {{ getPercentage(statusCounts.in_progress) }}%
                    </div>
                    <div
                        :style="{
                            width: `${getPercentage(statusCounts.completed)}%`,
                        }"
                        class="bg-green-400 text-white py-2 text-xs text-center h-8 place-content-center"
                    >
                        {{ getPercentage(statusCounts.completed) }}%
                    </div>
                    <div
                        :style="{
                            width: `${getPercentage(statusCounts.pending)}%`,
                        }"
                        class="bg-orange-400 text-white py-2 text-xs text-center h-8 place-content-center"
                    >
                        {{ getPercentage(statusCounts.pending) }}%
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-1">
                <div
                    class="flex flex-col justify-items-center items-center border rounded-md p-4"
                >
                    <span
                        class="inline-block h-8 w-8 bg-violet-400 rounded-full p-1"
                    >
                        <svg
                            class="h-6 w-6 stroke-white"
                            viewBox="0 0 21 21"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <g
                                fill="none"
                                fill-rule="evenodd"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                transform="matrix(-1 0 0 1 19 2)"
                            >
                                <circle cx="8.5" cy="8.5" r="8" />
                                <path d="m8.5 5.5v4h-3.5" />
                            </g>
                        </svg>
                    </span>
                    <span class="my-1 text-xl">{{
                        statusCounts.in_progress
                    }}</span>
                    <span>In Progress</span>
                </div>
                <div
                    class="flex flex-col justify-items-center items-center border rounded-md p-4"
                >
                    <span
                        class="inline-block h-8 w-8 bg-green-400 rounded-full p-1"
                    >
                        <svg
                            class="h-6 w-6 stroke-white"
                            viewBox="0 0 21 21"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <g
                                fill="none"
                                fill-rule="evenodd"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                transform="translate(2 2)"
                            >
                                <circle cx="8.5" cy="8.5" r="8" />
                                <path d="m5.5 9.5 2 2 5-5" />
                            </g>
                        </svg>
                    </span>
                    <span class="my-1 text-xl">{{
                        statusCounts.completed
                    }}</span>
                    <span>Completed</span>
                </div>
                <div
                    class="flex flex-col justify-items-center items-center border rounded-md p-4"
                >
                    <span
                        class="inline-block h-8 w-8 bg-orange-400 rounded-full p-1"
                    >
                        <svg
                            class="h-6 w-6 stroke-white"
                            viewBox="0 0 21 21"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <g
                                fill="none"
                                fill-rule="evenodd"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                transform="translate(2 2)"
                            >
                                <path
                                    d="m2.5.5h12c1.1045695 0 2 .8954305 2 2v12c0 1.1045695-.8954305 2-2 2h-12c-1.1045695 0-2-.8954305-2-2v-12c0-1.1045695.8954305-2 2-2z"
                                />
                                <path d="m.5 4.5h16" />
                                <path d="m8.5 7.5v6.056" />
                                <path
                                    d="m8.5 7.5v6"
                                    transform="matrix(0 1 -1 0 19 2)"
                                />
                            </g>
                        </svg>
                    </span>
                    <span class="my-1 text-xl">{{ statusCounts.pending }}</span>
                    <span>Pending</span>
                </div>
            </div>
        </div>
    </div>
</template>
