/* jshint esversion: 8 */
import {defineStore} from "pinia";
import {ref} from "vue";

export const useActivityStore = defineStore("activities", () => {
    const camoId = ref(null);
    const camo = ref(null);
    const activities = ref(null);

    const setCamo = async (id) => {
        camoId.value = id;
    };
    return {
        setCamo,
        camoId,
        camo,
        activities,
    };
});
