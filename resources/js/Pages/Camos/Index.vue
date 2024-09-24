<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import { route } from "ziggy-js";
import _ from "lodash";

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    search: "",
});
const fireSearch = _.throttle(function () {
    form.get(route("camos.index"), { preserveState: true });
}, 200);

const destroy = (id) => {
    if (confirm("Seguro desea eliminar el Usuario")) {
        form.delete(route("camos.destroy", id), { preserveState: true });
    }
};
</script>
<template>
    <Head title="Camos" />
    <AuthenticatedLayout>
        <template #header>
            <h2>Camos</h2>
        </template>
        <div class="flex flex-col justify-items-center items-center">
            <div v-if="props.resource.data.length > 0" class="my-4 px-4 py-4">
                <form
                    class="my-2 flex flex-row justify-items-center items-center space-x-7"
                >
                    <div>
                        <input
                            id="search"
                            v-model="form.search"
                            class="px-2 py-1 rounded-md border-gray-300"
                            name="search"
                            placeholder="search"
                            type="text"
                            @keyup="fireSearch"
                        />
                    </div>
                    <Link
                        v-if="$page.props.auth.user.is_cam"
                        :href="route('camos.create')"
                        class="btn-primary"
                        >Nuevo CAMO
                    </Link>
                </form>
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>{{ $t("Customer") }}</th>
                            <th>{{ $t("Owner") }}</th>
                            <th>{{ $t("Contract") }}</th>
                            <th>{{ $t("Cam") }}</th>
                            <th>{{ $t("Airplane") }}</th>
                            <th>{{ $t("Activities") }}</th>
                            <th>{{ $t("Start Date") }}</th>
                            <th>{{ $t("Estimate Finish Date") }}</th>
                            <th>{{ $t("Finish Date") }}</th>
                            <th>{{ $t("Location") }}</th>
                            <th>{{ $t("Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(camo, idx) in resource.data" :key="idx">
                            <td>{{ camo.id }}</td>
                            <td class="uppercase">{{ camo.customer }}</td>
                            <td>{{ camo.owner }}</td>
                            <td>{{ camo.contract }}</td>
                            <td>{{ camo.cam }}</td>
                            <td>
                                {{ camo.aircraft.model_aircraft.name }} /
                                {{ camo.aircraft.register }}
                            </td>
                            <td class="text-center">
                                {{ camo.activities.length }}
                            </td>
                            <td class="text-center">{{ camo.start_date }}</td>
                            <td class="text-center">
                                {{ camo.estimate_finish_date }}
                            </td>
                            <td class="text-center">{{ camo.finish_date }}</td>
                            <td class="text-center">{{ camo.location }}</td>
                            <td class="col-actions">
                                <Link
                                    :href="route('camos.show', camo.id)"
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
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">
                                <Paginator :data="resource" />
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div v-else class="my-12">
                <h1 class="text-center text-2xl my-2">
                    Aun no tiene registros para listar
                </h1>
                <p class="text-center">
                    ¿Quieres crear tu primer
                    <a :href="route('camos.create')">
                        <span class="text-blue-700">CAMO?</span>
                    </a>
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
