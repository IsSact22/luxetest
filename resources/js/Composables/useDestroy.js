import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";

export function useDestroy(routeName) {
    const form = useForm({});
    const destroy = (id) => {
        if (confirm("Are you sure you want to delete?")) {
            form.delete(route(routeName, id), {
                preserveState: true,
            });
        }
    };

    return { destroy };
}
