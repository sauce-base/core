<script setup lang="ts">
import Input from '@/components/Input.vue';
import { Button } from '@/components/ui/button';
import FormControl from '@/components/ui/form/FormControl.vue';
import FormField from '@/components/ui/form/FormField.vue';
import FormItem from '@/components/ui/form/FormItem.vue';
import FormLabel from '@/components/ui/form/FormLabel.vue';
import FormMessage from '@/components/ui/form/FormMessage.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import {
    forgotPasswordSchema,
    type ForgotPasswordFormData,
} from '@/validation';
import { Head, Link, useForm as useInertiaForm } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';

defineProps<{
    status?: string;
}>();

const formSchema = toTypedSchema(forgotPasswordSchema);

const form = useForm({
    validationSchema: formSchema,
    initialValues: {
        email: '',
    },
});

const inertiaForm = useInertiaForm<ForgotPasswordFormData>({
    email: '',
});

const onSubmit = form.handleSubmit((values: ForgotPasswordFormData) => {
    Object.assign(inertiaForm, values);
    inertiaForm.post(route('password.email'));
});
</script>

<template>
    <GuestLayout>
        <Head :title="$t('Forgot Password')" />

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{
                $t(
                    'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.',
                )
            }}
        </div>

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
        >
            {{ status }}
        </div>

        <form @submit.prevent="onSubmit" class="space-y-4">
            <FormField name="email" v-slot="{ componentField }">
                <FormItem>
                    <FormLabel>{{ $t('Email') }}</FormLabel>
                    <FormControl>
                        <Input
                            v-bind="componentField"
                            type="email"
                            :placeholder="$t('Enter your email address')"
                        />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>

            <div class="flex items-center justify-end">
                <Button
                    type="submit"
                    :class="{ 'opacity-25': inertiaForm.processing }"
                    :disabled="inertiaForm.processing"
                >
                    {{ $t('Email Password Reset Link') }}
                </Button>
            </div>
        </form>

        <template #outside>
            <div class="mt-6 text-center">
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                >
                    {{ $t('← Back to login') }}
                </Link>
            </div>
        </template>
    </GuestLayout>
</template>
