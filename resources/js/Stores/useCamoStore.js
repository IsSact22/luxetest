import {defineStore} from "pinia";
import {computed, nextTick, onMounted, reactive, ref, watch} from "vue";
import {useForm as useFormPrecognition} from "laravel-precognition-vue-inertia";
import { useForm as useFormInertia } from "@inertiajs/vue3";
import {route} from "ziggy-js";
import moment from 'moment';
import {useToast} from "vue-toastification";
import { router } from '@inertiajs/vue3'

export const useCamoStore = defineStore('camos', () => {
    const toast = useToast();
    const currentStep = ref(1);
    const now = ref(moment())
    const camId = ref(null);
    const camoForm = useFormPrecognition('post', route('camos.store'),{
        customer: '',
        owner_id: null,
        contract: '',
        cam_id: null,
        aircraft: '',
        description: '',
        start_date: '',
        finish_date: '',
        location: 'OMZ',
        activities: null
    });

    watch(camId, (newValue) => {
        camoForm.cam_id = newValue
    })


    const customer = computed(() => camoForm.customer)

    const afterTo = computed(() => {
        const fecha = moment(camoForm.start_date).toDate();
        return moment(fecha).format('LL');
    });

    const duration = computed(() => {
        if (camoForm.start_date && camoForm.finish_date) {
            const ini = camoForm.start_date;
            const end = camoForm.finish_date;
            const duration = moment.duration(moment(end).diff(moment(ini)));
            return duration.asDays();
        } else {
            return null;
        }

    })

     const activity = useFormInertia({
         required: false,
         date: '',
         name: '',
         description: '',
         status: 'pending',
         comments: '',
         labor_mount: '',
         material_mount: '',
         material_information: '',
         awr: '',
         approval_status: ''
     })
    const computedApprovalStatus = computed(() => {
        return activity.required ? 'approved' : 'pending';
    });

    watch(() => activity.required, (newValue) => {
        activity.approval_status = newValue ? 'approved' : 'pending';
    });

    const camoActivities = ref([]);

    watch(camoActivities, (newValue) => {
        camoForm.activities = newValue
    })

    const addActivity = (data) => {
        camoActivities.value.push(data)
    }

    const editId = ref(null)
    const editEnable = ref(false)

    const editActivity = (index) => {
        editEnable.value = true
        editId.value = index
        const data = camoActivities.value[index];
        activity.required = data.required
        activity.date = data.date
        activity.name = data.name
        activity.description = data.description
        activity.status = data.status
        activity.comments = data.comments
        activity.labor_mount = data.labor_mount
        activity.material_mount = data.material_mount
        activity.material_information = data.material_information
        activity.awr = data.awr
        currentStep.value = 2
    }
    const handleEdit = (data) => {
        console.log('handleEdit')
        const index = editId.value;
        camoActivities.value[index] = data
        activity.reset();
        editId.value = null;
        editEnable.value = false
        currentStep.value = 3
    }
    const removeActivity = (index) => {
        camoActivities.value.splice(index, 1);
    }

    const nextStep = () => {
        if (currentStep.value < 3) {
            currentStep.value += 1;
        }
    }

    const previousStep = () => {
        if (currentStep.value > 1) {
            currentStep.value -= 1;
        }
    }
    const setCamId = (id) => {
        camId.value = id
    }
    onMounted(() => {
        camoForm.setValidationTimeout(3000);
    })

    const finish = () => {
        camoForm.submit({
            onFinish: () => {
                localStorage.removeItem('camos')
                toast.success('Registered', { timeout: 2000})
                setTimeout(() => {
                    router.get(route('camos.index'))
                }, 1000)
            },
        })
    }

    return {
        customer,
        now,
        duration,
        afterTo,
        currentStep,
        nextStep,
        previousStep,
        setCamId,
        camId,
        camoForm,
        activity,
        camoActivities,
        addActivity,
        removeActivity,
        editEnable,
        editId,
        editActivity,
        handleEdit,
        finish,
    }
}, {
    persist: false
})
