<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import SmallCardComponent from "@/Components/SmallCardComponent.vue";
import {route} from "ziggy-js";
import {onMounted, ref, computed} from "vue";

const camos = ref({})
const getCamos = async() => {
    try {
        const response = await axios.get(route('camos.dashboard'));
        camos.value = response.data.data
    } catch (err) {
        console.error(err)
    }
}

onMounted(getCamos)
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2>Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg px-2">
                    <h1 class="text-xl p-6 text-gray-900">
                        Welcome {{ $page.props.auth.user.name }}
                        <small class="text-amber-700 capitalize">{{$page.props.auth.user.roles[0].name}}</small>
                    </h1>
                    <h2 class="text-xl">Continuing Airworthiness Management Control</h2>

                    <div class="grid grid-cols-3 gap-4 my-2">
                        <div v-for="(camo, idx) in camos" :key="idx">
                            <SmallCardComponent
                                :id="camo.id"
                                :title="camo.customer"
                                :subtitle="camo.contract"
                                :owner="camo.owner"
                                :manager="camo.cam"
                                :aircraft="camo.aircraft"
                                :activities="camo.activities"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
