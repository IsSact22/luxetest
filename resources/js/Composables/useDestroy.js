import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";

export function useDestroy() {
    const form = useForm({});

    const destroy = (routeName, id) => {
        if (confirm("¿Seguro desea eliminar el registro?")) {
            form.delete(route(routeName, id), {
                preserveState: true,
                onError: () => console.log(error),
                onSuccess: (data) => console.log(data),
            });
        }
    };

    return { destroy };
}
