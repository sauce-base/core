import type { ComputedRef, Ref } from 'vue';

export interface FieldContext {
    name: Ref<string>;
    id: ComputedRef<string>;
    errorMessage: Ref<string | undefined>;
    value: Ref<any>;
    meta: Ref<any>;
}
