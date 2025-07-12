<script setup lang="ts">
import FormControl from '@/Components/Form/FormControl.vue';
import FormField from '@/Components/Form/FormField.vue';
import FormItem from '@/Components/Form/FormItem.vue';
import FormLabel from '@/Components/Form/FormLabel.vue';
import FormMessage from '@/Components/Form/FormMessage.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Input from '@/Components/ui/Input.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { forgotPasswordSchema, type ForgotPasswordFormData } from '@/validation';
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
        <Head title="Forgot Password" />

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
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
                    <FormLabel>Email</FormLabel>
                    <FormControl>
                        <Input
                            v-bind="componentField"
                            type="email"
                            placeholder="Enter your email address"
                        />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>

            <div class="flex items-center justify-end">
                <PrimaryButton
                    type="submit"
                    :class="{ 'opacity-25': inertiaForm.processing }"
                    :disabled="inertiaForm.processing"
                >
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>

        <template #outside>
            <div class="mt-6 text-center">
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                >
                    ← Back to login
                </Link>
            </div>
        </template>
    </GuestLayout>
</template>
