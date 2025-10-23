<script setup lang="ts">
import PasswordInput from '@/components/PasswordInput.vue';
import Field from '@/components/ui/field/Field.vue';
import FieldError from '@/components/ui/field/FieldError.vue';
import FieldLabel from '@/components/ui/field/FieldLabel.vue';
import Input from '@/components/ui/input/Input.vue';
import { computed } from 'vue';
import { usePageErrors } from '../composables/usePageErrors';

const { hasError, getError } = usePageErrors();

const props = withDefaults(
    defineProps<{
        name: string;
        type?: string;
        label?: string;
        placeholder?: string;
        id?: string;
        testId?: string;
    }>(),
    {
        type: 'text',
    },
);

defineOptions({ inheritAttrs: false });

const isInvalid = computed(() => hasError(props.name));

// canonical id used for the input/label/error. Prefer provided id, fallback to name.
const id = computed(() => props.id || props.name);

// derived ids for label and error to keep ARIA references consistent
const labelId = computed(() => `${id.value}-label`);
const describedBy = computed(() =>
    isInvalid.value ? `${id.value}-error` : undefined,
);

// test id fallbacks to name so we never render `undefined` into attributes
const testIdComputed = computed(() => props.testId || props.name);
const errorTestId = computed(() => `${testIdComputed.value}-error`);

const component = computed(() =>
    props.type === 'password' ? PasswordInput : Input,
);
</script>

<template>
    <Field :data-invalid="isInvalid">
        <FieldLabel v-if="label" :id="labelId" :for="id">
            {{ label }}
        </FieldLabel>
        <component
            :is="component"
            :id="id"
            :name="name"
            :type="type"
            :data-testid="testIdComputed"
            :placeholder="placeholder"
            :aria-invalid="isInvalid"
            :aria-labelledby="labelId"
            :aria-describedby="describedBy"
            v-bind="$attrs"
        />
        <FieldError
            v-if="isInvalid"
            :id="describedBy"
            :data-testid="errorTestId"
            aria-live="polite"
        >
            {{ getError(name) }}
        </FieldError>
    </Field>
</template>
