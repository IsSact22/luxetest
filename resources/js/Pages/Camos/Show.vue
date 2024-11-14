<script setup>
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { route } from "ziggy-js";
import _ from "lodash";
import { computed, onMounted, ref, watch } from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import Paginator2 from "@/Components/Paginator2.vue";
import useFormatCurrency from "@/Composables/formatCurrency";
import { useToast } from "vue-toastification";
import CamoActivityForm from "@/Pages/CamoActivities/Partials/CamoActivityForm.vue";

const toast = useToast();

const { formatCurrency } = useFormatCurrency();

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});

const waitingApproval = computed(() => {
    if (props.resource.data && props.resource.data.activities) {
        const activities = props.resource.data.activities;
        return activities.filter((act) => {
            return act.approval_status === "pending";
        });
    } else {
        return [];
    }
});

const pendingExecution = computed(() => {
    if (props.resource.data && props.resource.data.activities) {
        const activities = props.resource.data.activities;
        return activities.filter((act) => {
            return act.status === "pending";
        });
    } else {
        return [];
    }
});

const inProgress = computed(() => {
    if (props.resource.data && props.resource.data.activities) {
        const activities = props.resource.data.activities;
        return activities.filter((act) => {
            return act.status === "in_progress";
        });
    } else {
        return [];
    }
});

const completed = computed(() => {
    if (props.resource.data && props.resource.data.activities) {
        const activities = props.resource.data.activities;
        return activities.filter((act) => {
            return act.status === "completed";
        });
    } else {
        return [];
    }
});

const camoId = ref(props.resource.data.id);
const activities = ref(null);
const currentPage = ref(null);
const lastPage = ref(null);
const search = ref("");
const handlePageChange = (page) => {
    console.log(page);
    currentPage.value = page;
};

const filter = ref(null);
onMounted(() => {
    if (
        usePage().props.auth.user.is_owner ||
        usePage().props.auth.user.is_crew
    ) {
        filter.value = "approval_status.pending";
    }
});
const getActivities = async () => {
    try {
        const response = await axios.get(route("camos.activities"), {
            params: {
                camo_id: camoId.value,
                page: currentPage.value,
                search: search.value,
                filter: filter.value,
            },
        });
        activities.value = response.data;
        currentPage.value = response.data.current_page;
        lastPage.value = response.data.last_page;
    } catch (e) {
        console.error(e);
    }
};

watch(filter, (newValue) => {
    getActivities();
});

onMounted(getActivities);
const fireSearch = _.throttle(function () {
    getActivities();
}, 200);

watch([currentPage], getActivities);
// Propiedad computada para calcular el total de labor_mount
const totalLaborMount = computed(() => {
    const total = props.resource.data.activities.reduce((total, activity) => {
        return total + Number(activity.labor_mount);
    }, 0);
    return total.toFixed(2);
});

// Propiedad computada para calcular el total de material_mount
const totalMaterialMount = computed(() => {
    const total = props.resource.data.activities.reduce((total, activity) => {
        return total + Number(activity.material_mount);
    }, 0);
    return total.toFixed(2);
});

const destroy = (id) => {
    if (confirm("Seguro desea eliminar el Usuario")) {
        form.delete(route("camos.destroy", id), { preserveState: true });
    }
};
const showModal = ref(false);
const activityId = ref(null);

const formActivity = useForm({
    name: "",
    date: "",
    description: "",
    comments: "",
    awr: "",
    labor_mount: "",
    material_mount: "",
    material_information: "",
    status: "",
    approval_status: "",
});
const closeModal = () => {
    activityId.value = null;
    showModal.value = false;
};
const handleClickTr = (obj) => {
    router.get(route("camo_activities.edit", obj.id));
};

const submit = async () => {
    try {
        const response = await axios.patch(
            route("camo_activities.handle", activityId.value),
            formActivity.data(),
        );
        toast.success(response.data.message);
        await getActivities();
        closeModal();
    } catch (e) {
        console.error(e);
    }
};
const addActivity = ref(false);
const handleAddActivity = (e) => {
    addActivity.value = e;
};
const badgeClass = (priority) => {
    switch (priority) {
        case 3:
            return "badge-info";
        case 2:
            return "badge-pending";
        case 1:
            return "badge-alert";
    }
};
// Ref para manejar la visibilidad del botón
const showGallery = ref(false);

// Función para verificar si alguna actividad tiene imágenes
const checkIfActivitiesHaveImages = async () => {
    try {
        const response = await axios.get(
            `/camo/${props.resource.data.id}/has-images-in-activities`,
        );
        showGallery.value = response.data.hasImages; // Se establece el valor del botón
    } catch (error) {
        console.error("Error fetching images", error);
    }
};

// Llamar a la función cuando el componente se monta
onMounted(() => {
    checkIfActivitiesHaveImages();
});
</script>
<template>
    <Head title="Camos" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Camos</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center mx-auto">
            <div class="my-4 border rounded-md px-4 py-4">
                <div class="inline-flex space-x-5">
                    <Link :href="route('camos.index')" class="btn-primary"
                        >Regresar
                    </Link>
                    <button
                        v-if="
                            $page.props.auth.user.is_cam ||
                            $page.props.auth.user.is_super
                        "
                        class="btn-primary"
                        @click="addActivity = true"
                    >
                        Agregar Actividad
                    </button>
                    <Link
                        v-if="showGallery"
                        :href="route('camos.images', props.resource.data.id)"
                        class="btn-primary"
                        title="Gallery of Camo"
                        @click.passive.prevent
                    >
                        Ver Galeria
                    </Link>
                </div>

                <div
                    class="flex flex-row justify-items-center items-start space-x-5 mb-7"
                >
                    <div class="flex flex-col">
                        <!-- customer -->
                        <div
                            class="px-4 py-2 my-2 shadow-lg border rounded-md border-gray-200"
                        >
                            <table class="table-auto">
                                <thead>
                                    <tr>
                                        <th style="color: #b58a00 !important">
                                            {{ $t("Customer") }}
                                        </th>
                                        <th style="color: #b58a00 !important">
                                            {{ $t("Contract") }}
                                        </th>
                                        <th style="color: #b58a00 !important">
                                            {{ $t("Project Manager") }}
                                        </th>
                                        <th style="color: #b58a00 !important">
                                            {{ $t("Location") }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ resource.data.customer }}</td>
                                        <td>{{ resource.data.contract }}</td>
                                        <td>{{ resource.data.cam }}</td>
                                        <td>{{ resource.data.location }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- aircraft -->
                        <div
                            class="px-4 py-2 my-2 shadow-lg border rounded-md border-gray-200"
                        >
                            <table>
                                <thead>
                                    <tr>
                                        <th style="color: #b58a00 !important">
                                            {{ $t("Airplane") }}
                                        </th>
                                        <th style="color: #b58a00 !important">
                                            {{ $t("Description") }}
                                        </th>
                                        <th style="color: #b58a00 !important">
                                            {{ $t("Start Date") }}
                                        </th>
                                        <th style="color: #b58a00 !important">
                                            {{ $t("Estimate Finish Date") }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            {{
                                                resource.data.aircraft
                                                    .model_aircraft.name
                                            }}
                                            /
                                            {{
                                                resource.data.aircraft.register
                                            }}
                                        </td>
                                        <td>
                                            {{ resource.data.description }}
                                            &nbsp;
                                        </td>
                                        <td>{{ resource.data.start_date }}</td>
                                        <td>
                                            {{
                                                resource.data
                                                    .estimate_finish_date
                                            }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="w-1/2 flex flex-col px-4">
                        <div
                            class="px-4 py-2 my-2 shadow-lg border rounded-md border-gray-200"
                        >
                            <h1 class="text-gray-700">Sumario</h1>
                            <hr class="h-0.5 my-2 bg-neutral-400" />

                            <div
                                class="flex flex-row justify-between text-xl my-2"
                            >
                                <span>Mano de Obra</span>
                                <span
                                    class="px-2 py-1 bg-yellow-500 rounded-md"
                                    >{{ formatCurrency(totalLaborMount) }}</span
                                >
                            </div>

                            <div
                                class="flex flex-row justify-between text-xl my-2"
                            >
                                <span>Materiales</span>
                                <span
                                    class="px-2 py-1 bg-yellow-500 rounded-md"
                                    >{{
                                        formatCurrency(totalMaterialMount)
                                    }}</span
                                >
                            </div>

                            <hr class="my-2" />
                            <div class="flex flex-row justify-between text-xl">
                                <span>Total</span>
                                <span
                                    class="px-2 py-1 bg-yellow-500 rounded-md"
                                    >{{
                                        formatCurrency(
                                            Number(totalLaborMount) +
                                                Number(totalMaterialMount),
                                        )
                                    }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- progress information -->
                <div
                    class="flex flex-row justify-between px-2 py-2 my-5 shadow border rounded-md border-gray-200"
                >
                    <p v-if="waitingApproval" class="text-right">
                        Esperando Aprobación
                        <span class="badge-alert">{{
                            waitingApproval.length
                        }}</span>
                    </p>
                    <p v-if="pendingExecution" class="text-right">
                        Pendiente
                        <span class="badge-pending">{{
                            pendingExecution.length
                        }}</span>
                    </p>
                    <p v-if="completed" class="text-right">
                        en Progreso
                        <span class="badge-progress">{{
                            inProgress.length
                        }}</span>
                    </p>
                    <p v-if="completed" class="text-right">
                        Completado
                        <span class="badge-completed">{{
                            completed.length
                        }}</span>
                    </p>
                    <p v-if="activities && activities.total" class="text-right">
                        Total de Actividades
                        <span class="badge-info">{{ activities.total }}</span>
                    </p>
                </div>

                <div>
                    <form v-show="!addActivity" class="my-2">
                        <InputLabel for="search">Buscar</InputLabel>
                        <input
                            id="search"
                            v-model="search"
                            class="px-4 py-2 rounded-md border-gray-300 w-1/3"
                            name="search"
                            placeholder="Escriba para buscar y borre para restablecer"
                            type="text"
                            @keyup="fireSearch"
                        />
                    </form>
                    <!-- add activity -->
                    <Transition appear name="fade" @after-enter="addActivity">
                        <CamoActivityForm
                            v-if="addActivity"
                            :camo="props.resource.data"
                            :user="usePage().props.auth.user"
                            @add-activity="handleAddActivity"
                        />
                    </Transition>
                    <!-- add activity -->

                    <Transition appear name="fade" @after-enter="!addActivity">
                        <div v-if="!addActivity">
                            <!-- pending approval -->
                            <div
                                class="flex items-center -mx-4 space-x-2 overflow-x-auto overflow-y-hidden sm:justify-center flex-nowrap my-4"
                            >
                                <button
                                    :class="{
                                        'border-b-blue-400': filter === null,
                                    }"
                                    class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                                    href="#"
                                    preserve-scroll
                                    rel="noopener noreferrer"
                                    @click="filter = null"
                                >
                                    Todas
                                </button>
                                <button
                                    :class="{
                                        'border-b-blue-400':
                                            filter ===
                                            'approval_status.approved',
                                    }"
                                    class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                                    href="#"
                                    preserve-scroll
                                    rel="noopener noreferrer"
                                    @click="filter = 'approval_status.approved'"
                                >
                                    Aprobadas
                                </button>
                                <button
                                    :class="{
                                        'border-b-blue-400':
                                            filter ===
                                            'approval_status.pending',
                                    }"
                                    class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                                    href="#"
                                    preserve-scroll
                                    rel="noopener noreferrer"
                                    @click="filter = 'approval_status.pending'"
                                >
                                    Aprobación pendiente
                                </button>
                                <button
                                    :class="{
                                        'border-b-blue-400':
                                            filter === 'status.pending',
                                    }"
                                    class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                                    href="#"
                                    preserve-scroll
                                    rel="noopener noreferrer"
                                    @click="filter = 'status.pending'"
                                >
                                    Pendiente
                                </button>
                                <button
                                    :class="{
                                        'border-b-blue-400':
                                            filter === 'status.in_progress',
                                    }"
                                    class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                                    href="#"
                                    preserve-scroll
                                    rel="noopener noreferrer"
                                    @click="filter = 'status.in_progress'"
                                >
                                    en Progreso
                                </button>
                                <button
                                    :class="{
                                        'border-b-blue-400':
                                            filter === 'status.completed',
                                    }"
                                    class="flex items-center flex-shrink-0 px-5 py-2 border-b-4"
                                    href="#"
                                    preserve-scroll
                                    rel="noopener noreferrer"
                                    @click="filter = 'status.completed'"
                                >
                                    Completado
                                </button>
                            </div>
                            <!-- pending approval -->
                            <table class="table-fixed w-full">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Prioridad</th>
                                        <th>Fecha</th>
                                        <th>Inicia</th>
                                        <th>Estimado</th>
                                        <th>Estatus</th>
                                        <th>H/H</th>
                                        <th>Material</th>
                                        <th>Aprobado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(act, idx) in activities &&
                                        activities.resource"
                                        :key="idx"
                                    >
                                        <td>{{ act.id }}</td>
                                        <td>
                                            <span
                                                v-tooltip="act.name"
                                                :class="
                                                    badgeClass(act.priority)
                                                "
                                            >
                                                {{ act.priority }}
                                            </span>
                                        </td>
                                        <td class="text-xs">{{ act.date }}</td>
                                        <td class="text-xs">
                                            <span v-if="act.started_at"></span>
                                            {{ act.started_at ?? "undefined" }}
                                        </td>
                                        <td class="text-center">
                                            {{ act.estimate_time }}
                                            <small>H/m</small>
                                        </td>
                                        <td class="flex place-content-center">
                                            <span
                                                v-if="act.status === 'pending'"
                                                class="badge-pending"
                                                >{{ $t(act.status) }}</span
                                            >
                                            <span
                                                v-else-if="
                                                    act.status === 'in_progress'
                                                "
                                                class="badge-progress"
                                            >
                                                {{ $t(act.status) }}
                                            </span>
                                            <span
                                                v-else
                                                class="badge-completed"
                                                >{{ $t(act.status) }}</span
                                            >
                                        </td>
                                        <td class="text-right">
                                            {{
                                                formatCurrency(act.labor_mount)
                                            }}
                                        </td>
                                        <td class="text-right">
                                            {{
                                                formatCurrency(
                                                    act.material_mount,
                                                )
                                            }}
                                        </td>
                                        <td class="flex place-content-center">
                                            <span
                                                v-if="
                                                    act.approval_status ===
                                                    'pending'
                                                "
                                                class="badge-pending"
                                                >{{
                                                    $t(act.approval_status)
                                                }}</span
                                            >
                                            <span
                                                v-else
                                                class="badge-approval"
                                                >{{
                                                    $t(act.approval_status)
                                                }}</span
                                            >
                                        </td>
                                        <td class="col-actions">
                                            <Link
                                                :href="
                                                    route(
                                                        'camo_activities.show',
                                                        act.id,
                                                    )
                                                "
                                                class="btn-show"
                                            >
                                                <span>
                                                    <svg
                                                        class="size-5 stroke-yellow-700"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="1.5"
                                                        viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                        <path
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                    </svg>
                                                </span>
                                            </Link>

                                            <Link
                                                :href="
                                                    route(
                                                        'camo_activities.edit',
                                                        act.id,
                                                    )
                                                "
                                                class="btn-edit"
                                            >
                                                <span>
                                                    <svg
                                                        class="size-5 stroke-yellow-700"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="1.5"
                                                        viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                    </svg>
                                                </span>
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="flex float-right">
                                <!-- Paginator -->
                                <Paginator2
                                    v-if="activities && activities.total > 9"
                                    :current-page="currentPage"
                                    :last-page="lastPage"
                                    @page-change="handlePageChange"
                                />
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
/* ---------------------------------- */
.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: 0.2s opacity ease-out;
}

.scale-enter-active,
.scale-leave-active {
    transition: transform 0.3s;
}

.scale-enter-from,
.scale-leave-to {
    transform: scaleY(0);
}

.scale-enter-to,
.scale-leave-from {
    transform: scaleY(1);
}
</style>
