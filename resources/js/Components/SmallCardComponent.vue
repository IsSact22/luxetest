<template>
    <div class="max-w-md p-8 sm:flex sm:space-x-6 rounded-md border hover:bg-amber-100/50">
        <div class="flex flex-col space-y-4">
            <div>
                <h2 class="text-base font-semibold">{{title}}</h2>
                <span class="text-sm dark:text-gray-400">{{subtitle}}</span>
            </div>
            <div class="space-y-1">
				<span class="flex items-center space-x-2">
					<svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(1 2)"><path d="m.5 7 16-6.535-2.8 14.535z"/><path d="m16.5.5-11 10"/><path d="m5.5 10.5v5l3-3"/></g></svg>
					<span class="dark:text-gray-400">{{aircraft}}</span>
				</span>
                <span class="flex items-center space-x-2">
					<svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><path d="m7.5.5c1.65685425 0 3 1.34314575 3 3v2c0 1.65685425-1.34314575 3-3 3s-3-1.34314575-3-3v-2c0-1.65685425 1.34314575-3 3-3zm7 14v-.7281753c0-3.1864098-3.6862915-5.2718247-7-5.2718247s-7 2.0854149-7 5.2718247v.7281753c0 .5522847.44771525 1 1 1h12c.5522847 0 1-.4477153 1-1z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(3 2)"/></svg>
					<span class="dark:text-gray-400">{{owner}}</span>
				</span>
                <span class="flex items-center space-x-2">
					<svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(1 2)"><path d="m7.5.5c1.65685425 0 3 1.34314575 3 3v2c0 1.65685425-1.34314575 3-3 3s-3-1.34314575-3-3v-2c0-1.65685425 1.34314575-3 3-3zm7 14v-.7281753c0-3.1864098-3.6862915-5.2718247-7-5.2718247s-7 2.0854149-7 5.2718247v.7281753c0 .5522847.44771525 1 1 1h12c.5522847 0 1-.4477153 1-1z"/><path d="m11.5199327.67783074c1.1547685.41741154 1.9800673 1.52341097 1.9800673 2.82216926v1c0 1.29707884-.8475766 2.5813505-2 3 .6742649-.91876977 1.0109204-2.0857069 1.0099664-3.50081137s-.3309652-2.52222377-.9900337-3.32135789zm4.9800673 14.82216926h1c.5522847 0 1-.4477153 1-1 0-.2427251 0-.4854502 0-.7281753 0-2.1698712-1.7094418-3.82917861-3.8465775-4.66705336 0 0 2.8465775 2.39522866 1.8465775 6.39522866z" fill="currentColor"/></g></svg>
					<span class="dark:text-gray-400">{{manager}}</span>
				</span>
            </div>
        </div>
    </div>
</template>
<script setup>
const props = defineProps({
    title: String,
    subtitle: String,
    owner: String,
    manager: String,
    aircraft: String,
})
</script>
