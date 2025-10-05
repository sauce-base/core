<script setup lang="ts">
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import FormControl from '@/components/ui/form/FormControl.vue';
import FormField from '@/components/ui/form/FormField.vue';
import FormItem from '@/components/ui/form/FormItem.vue';
import FormLabel from '@/components/ui/form/FormLabel.vue';
import FormMessage from '@/components/ui/form/FormMessage.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import {
    confirmPasswordSchema,
    type ConfirmPasswordFormData,
} from '@/validation';
import { Head, useForm as useInertiaForm } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';

const formSchema = toTypedSchema(confirmPasswordSchema);

const form = useForm({
    validationSchema: formSchema,
    initialValues: {
        password: '',
    },
});

const inertiaForm = useInertiaForm<ConfirmPasswordFormData>({
    password: '',
});

const onSubmit = form.handleSubmit((values: ConfirmPasswordFormData) => {
    Object.assign(inertiaForm, values);

    inertiaForm.post(route('password.confirm'), {
        onFinish: () => {
            inertiaForm.reset();
        },
    });
});
</script>

<template>
    <GuestLayout>
        <Head :title="$t('Confirm Password')" />

        <!-- Title and Subtitle -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $t('Confirm Password') }}
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{
                    $t(
                        'This is a secure area of the application. Please confirm your password before continuing.',
                    )
                }}
            </p>
        </div>

        <form @submit.prevent="onSubmit" class="space-y-4">
            <FormField name="password" v-slot="{ componentField }">
                <FormItem>
                    <FormLabel :error="inertiaForm.errors.password">{{
                        $t('Password')
                    }}</FormLabel>
                    <FormControl>
                        <PasswordInput
                            v-bind="componentField"
                            :placeholder="$t('Enter your password')"
                            :error="inertiaForm.errors.password"
                        />
                    </FormControl>
                    <FormMessage :inertia-error="inertiaForm.errors.password" />
                </FormItem>
            </FormField>

            <div class="mt-6 flex justify-end">
                <Button
                    type="submit"
                    :class="{ 'opacity-25': inertiaForm.processing }"
                    :disabled="inertiaForm.processing"
                >
                    {{ $t('Confirm') }}
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
