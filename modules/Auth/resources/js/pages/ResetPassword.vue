<script setup lang="ts">
import { Button } from '@/components/ui/button';
import FormControl from '@/components/ui/form/FormControl.vue';
import FormField from '@/components/ui/form/FormField.vue';
import FormItem from '@/components/ui/form/FormItem.vue';
import FormLabel from '@/components/ui/form/FormLabel.vue';
import FormMessage from '@/components/ui/form/FormMessage.vue';
import Input from '@/components/ui/Input.vue';
import PasswordInput from '@/components/ui/PasswordInput.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { resetPasswordSchema, type ResetPasswordFormData } from '@/validation';
import { Head, useForm as useInertiaForm } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';

const props = defineProps<{
    email: string;
    token: string;
}>();

const formSchema = toTypedSchema(resetPasswordSchema);

const form = useForm({
    validationSchema: formSchema,
    initialValues: {
        token: props.token,
        email: props.email,
        password: '',
        password_confirmation: '',
    },
});

const inertiaForm = useInertiaForm<ResetPasswordFormData>({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const onSubmit = form.handleSubmit((values: ResetPasswordFormData) => {
    Object.assign(inertiaForm, values);

    inertiaForm.post(route('password.store'), {
        onFinish: () => {
            inertiaForm.reset('password', 'password_confirmation');
        },
    });
});
</script>

<template>
    <GuestLayout>
        <Head :title="$t('Reset Password')" />

        <!-- Title and Subtitle -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $t('Reset Password') }}
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ $t('Enter your new password below') }}
            </p>
        </div>

        <form @submit.prevent="onSubmit" class="space-y-4">
            <FormField name="email" v-slot="{ componentField }">
                <FormItem>
                    <FormLabel :error="inertiaForm.errors.email">{{
                        $t('Email')
                    }}</FormLabel>
                    <FormControl>
                        <Input
                            v-bind="componentField"
                            type="email"
                            :placeholder="$t('Enter your email')"
                            :error="inertiaForm.errors.email"
                            readonly
                        />
                    </FormControl>
                    <FormMessage :inertia-error="inertiaForm.errors.email" />
                </FormItem>
            </FormField>

            <FormField name="password" v-slot="{ componentField }">
                <FormItem>
                    <FormLabel :error="inertiaForm.errors.password">{{
                        $t('New Password')
                    }}</FormLabel>
                    <FormControl>
                        <PasswordInput
                            v-bind="componentField"
                            :placeholder="$t('Enter your new password')"
                            :error="inertiaForm.errors.password"
                        />
                    </FormControl>
                    <FormMessage :inertia-error="inertiaForm.errors.password" />
                </FormItem>
            </FormField>

            <FormField name="password_confirmation" v-slot="{ componentField }">
                <FormItem>
                    <FormLabel
                        :error="inertiaForm.errors.password_confirmation"
                        >{{ $t('Confirm Password') }}</FormLabel
                    >
                    <FormControl>
                        <PasswordInput
                            v-bind="componentField"
                            :placeholder="$t('Confirm your new password')"
                            :error="inertiaForm.errors.password_confirmation"
                        />
                    </FormControl>
                    <FormMessage
                        :inertia-error="
                            inertiaForm.errors.password_confirmation
                        "
                    />
                </FormItem>
            </FormField>

            <div class="mt-6">
                <Button
                    type="submit"
                    class="w-full"
                    :class="{ 'opacity-25': inertiaForm.processing }"
                    :disabled="inertiaForm.processing"
                >
                    {{ $t('Reset Password') }}
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
