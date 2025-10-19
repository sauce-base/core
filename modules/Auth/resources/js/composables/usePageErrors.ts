import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function usePageErrors() {
    const page = usePage();

    const errors = computed(() => {
        return page.props.errors || {};
    });

    const hasError = (field: string) => {
        return !!errors.value[field];
    };

    const hasErrors = computed(() => {
        return Object.keys(errors.value).length > 0;
    });

    const getError = (field: string) => {
        return errors.value[field];
    };

    return {
        errors,
        hasError,
        hasErrors,
        getError,
    };
}
