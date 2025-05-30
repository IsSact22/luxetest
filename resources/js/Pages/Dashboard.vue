<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import SmallCardComponent from "@/Components/SmallCardComponent.vue";
import { route } from "ziggy-js";
import { onMounted, ref } from "vue";

const camos = ref({});
const getCamos = async () => {
    try {
        const response = await axios.get(route("camos.dashboard"));
        camos.value = response.data.data || {};
    } catch (err) {
        console.error('Error al obtener camos:', err);
        camos.value = {};
    }
};

const hasPendingRate = ref(0);
const queryPendingRate = async () => {
    try {
        const response = await axios.get(route("has-pending-rates"));
        hasPendingRate.value = response.data || 0;
    } catch (e) {
        console.error('Error al obtener pending rates:', e);
        hasPendingRate.value = 0;
    }
};

// Verificar que el usuario esté completamente cargado antes de hacer las llamadas
if (usePage().props.auth.user) {
    onMounted(() => {
        getCamos();
        queryPendingRate();
    });
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2>{{ $t("Dashboard") }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg px-2">
                    <h2 class="text-xl p-6 text-black-900">
                        <span>Bienvenido</span> {{ $page.props.auth.user.name }}
                        <small v-if="$page.props.auth.user.roles && $page.props.auth.user.roles.length > 0" class="text-yellow-700 capitalize">
                            {{ $page.props.auth.user.roles[0].name }}
                        </small>
                    </h2>

                    <div
                        v-if="hasPendingRate && hasPendingRate.length > 0"
                        class="bg-yellow-100 rounded-md px-4 py-2 my-5"
                    >
                        <h3 class="flex justify-items-start text-amber-700 space-x-1">
                            <svg
                                class="size-6"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            <span> Actividades pendientes por tasa </span>
                        </h3>
                    </div>

                    <div
                        v-if="$page.props.auth.user && !$page.props.auth.user.is_super"
                        class="grid grid-cols-3 gap-4 my-2"
                    >
                        <div v-for="(camo, idx) in camos" :key="idx">
                            <SmallCardComponent
                                v-if="!camo.finish_date"
                                :id="camo.id"
                                :activities="camo.activities"
                                :aircraft="camo.aircraft"
                                :manager="camo.cam"
                                :owner="camo.owner"
                                :subtitle="camo.contract"
                                :title="camo.customer"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
