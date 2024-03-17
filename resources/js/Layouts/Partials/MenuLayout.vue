<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3'
import {route} from "ziggy-js";

const showMenu = ref(true);

const toggleMenu = () => {
    showMenu.value = !showMenu.value;
};
</script>

<template>
    <div class="mt-6 w-48">
        <div class="flex justify-center mb-4">
            <Link :href="route('dashboard')">
                <ApplicationLogo
                    class="block h-9 w-auto fill-current text-gray-800"
                />
            </Link>
        </div>
        <div class="px-4">
            <nav class="flex flex-col justify-between items-start space-y-2">
                <!-- Navigation Links -->
                <Link :href="route('dashboard')" :active="route().current('dashboard')" class="flex flex-row justify-items-center items-center space-x-2">
                    <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(3 3)"><path d="m14.4978951 12.4978973-.0105089-9.99999996c-.0011648-1.10374784-.8962548-1.99789734-2-1.99789734h-9.99999995c-1.0543629 0-1.91816623.81587779-1.99451537 1.85073766l-.00548463.151365.0105133 10.00000004c.0011604 1.1037478.89625045 1.9978973 1.99999889 1.9978973h9.99999776c1.0543618 0 1.9181652-.8158778 1.9945143-1.8507377z"/><path d="m4.5 4.5v9.817"/><path d="m7-2v14" transform="matrix(0 1 -1 0 12.5 -2.5)"/></g></svg>
                    <span>Dashboard</span>
                </Link>
                <Link :href="route('clients.index')" :active="route().current('clients.index')" class="flex flex-row justify-items-center items-center space-x-2">
                    <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><path d="m7.5.5c1.65685425 0 3 1.34314575 3 3v2c0 1.65685425-1.34314575 3-3 3s-3-1.34314575-3-3v-2c0-1.65685425 1.34314575-3 3-3zm7 14v-.7281753c0-3.1864098-3.6862915-5.2718247-7-5.2718247s-7 2.0854149-7 5.2718247v.7281753c0 .5522847.44771525 1 1 1h12c.5522847 0 1-.4477153 1-1z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(3 2)"/></svg>
                    <span>Clients</span>
                </Link>
                <Link :href="route('projects.index')" :active="route().current('projects.index')" class="flex flex-row justify-items-center items-center space-x-2">
                    <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(2 2)"><path d="m2.5.5h12c1.1045695 0 2 .8954305 2 2v12c0 1.1045695-.8954305 2-2 2h-12c-1.1045695 0-2-.8954305-2-2v-12c0-1.1045695.8954305-2 2-2z"/><path d="m.5 4.5h16"/><path d="m8.5 4.5v12"/></g></svg>
                    <span>Projects</span>
                </Link>
                <Link :href="route('aircraft.index')" :active="route().current('users.index')" class="flex flex-row justify-items-center items-center space-x-2">
                    <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(1 2)"><path d="m.5 7 16-6.535-2.8 14.535z"/><path d="m16.5.5-11 10"/><path d="m5.5 10.5v5l3-3"/></g></svg>
                    <span>Aircraft/Project</span>
                </Link>
                <Link :href="route('services.index')" :active="route().current('services.index')" class="flex flex-row justify-items-center items-center space-x-2">
                    <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(2 2)"><path d="m.5 8.5 8 4 8.017-4"/><path d="m.5 12.5 8 4 8.017-4"/><path d="m.5 4.657 8.008 3.843 8.009-3.843-8.009-4.157z"/></g></svg>
                    <span>Services</span>
                </Link>
                <Link class="flex flex-row justify-items-center items-center space-x-2">
                    <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(1 4)"><path d="m3.5 3.165h9"/><path d="m4.48122025 6.20339509 1-.01878307c.5127454-.00963092.94259192.36840352 1.00968673.86456969l.00909302.13525035v.9832835c0 .54496455-.4363518.98958934-.98122025.99982364l-1 .01878306c-.5127454.00963093-.94259192-.36840352-1.00968673-.86456968l-.00909302-.13525396v-.98327989c0-.54496455.4363518-.98958935.98122025-.99982364z"/><path d="m2.5.1507576h11c1.1045695 0 2 .8954305 2 2v10.0147186h-13c-1.1045695 0-2-.8954305-2-2v-8.0147186c0-1.1045695.8954305-2 2-2z"/><path d="m8.5 6.165h4"/><path d="m8.5 9.165h4"/><path d="m15 12.1654762c1.3807119 0 2.5-1.1192881 2.5-2.49999998v-6.5h-2"/></g></svg>
                    <span>Reports</span>
                </Link>
                <Link :href="route('users.index')" :active="route().current('users.index')" class="flex flex-row justify-between items-center">
                    <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(1 2)"><path d="m7.5.5c1.65685425 0 3 1.34314575 3 3v2c0 1.65685425-1.34314575 3-3 3s-3-1.34314575-3-3v-2c0-1.65685425 1.34314575-3 3-3zm7 14v-.7281753c0-3.1864098-3.6862915-5.2718247-7-5.2718247s-7 2.0854149-7 5.2718247v.7281753c0 .5522847.44771525 1 1 1h12c.5522847 0 1-.4477153 1-1z"/><path d="m11.5199327.67783074c1.1547685.41741154 1.9800673 1.52341097 1.9800673 2.82216926v1c0 1.29707884-.8475766 2.5813505-2 3 .6742649-.91876977 1.0109204-2.0857069 1.0099664-3.50081137s-.3309652-2.52222377-.9900337-3.32135789zm4.9800673 14.82216926h1c.5522847 0 1-.4477153 1-1 0-.2427251 0-.4854502 0-.7281753 0-2.1698712-1.7094418-3.82917861-3.8465775-4.66705336 0 0 2.8465775 2.39522866 1.8465775 6.39522866z" fill="currentColor"/></g></svg>
                    <span>Users</span>
                </Link>
                <!-- Hamburger -->
            </nav>
        </div>
    </div>
</template>
