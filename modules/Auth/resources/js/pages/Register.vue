<script setup lang="ts">
import { Button } from '@/components/ui/button';
import ErrorMessage from '@/components/ui/ErrorMessage.vue';
import FormControl from '@/components/ui/form/FormControl.vue';
import FormField from '@/components/ui/form/FormField.vue';
import FormItem from '@/components/ui/form/FormItem.vue';
import FormLabel from '@/components/ui/form/FormLabel.vue';
import FormMessage from '@/components/ui/form/FormMessage.vue';
import Input from '@/components/ui/Input.vue';
import PasswordInput from '@/components/ui/PasswordInput.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { registerSchema, type RegisterFormData } from '@/validation';
import { Head, Link, useForm as useInertiaForm } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import SocialLoginButton from '../components/SocialLoginButton.vue';
import { useSocialLogin } from '../composables/useSocialLogin';

const formSchema = toTypedSchema(registerSchema);

const form = useForm({
    validationSchema: formSchema,
    initialValues: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    },
});

const inertiaForm = useInertiaForm<RegisterFormData>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const {
    providers,
    isLoading: providersLoading,
    getEnabledProviders,
} = useSocialLogin();

const onSubmit = form.handleSubmit((values: RegisterFormData) => {
    Object.assign(inertiaForm, values);

    inertiaForm.post(route('register'), {
        onFinish: () => {
            inertiaForm.reset('password', 'password_confirmation');
        },
    });
});
</script>

<template>
    <GuestLayout>
        <Head :title="$t('Register')" />

        <!-- Title and Subtitle -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $t('Create your account') }}
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ $t('Sign up for Sauce Base to start building your SaaS') }}
            </p>
        </div>

        <!-- Social Login Error -->
        <ErrorMessage field="social" variant="error" />

        <!-- Social Login Section -->
        <div
            v-if="!providersLoading && getEnabledProviders().length > 0"
            class="mb-6 space-y-3"
        >
            <SocialLoginButton
                v-for="providerKey in getEnabledProviders()"
                :key="providerKey"
                :provider-key="providerKey"
                :provider-config="providers[providerKey]"
            />
        </div>

        <!-- Divider -->
        <div
            v-if="!providersLoading && getEnabledProviders().length > 0"
            class="relative mb-6"
        >
            <div class="absolute inset-0 flex items-center">
                <div
                    class="w-full border-t border-gray-300 dark:border-gray-600"
                ></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span
                    class="bg-white px-2 text-gray-500 dark:bg-gray-800 dark:text-gray-400"
                    >{{ $t('Or sign up with email') }}</span
                >
            </div>
        </div>

        <form @submit.prevent="onSubmit" class="space-y-4">
            <FormField name="name" v-slot="{ componentField }">
                <FormItem>
                    <FormLabel :error="inertiaForm.errors.name">{{
                        $t('Name')
                    }}</FormLabel>
                    <FormControl>
                        <Input
                            v-bind="componentField"
                            type="text"
                            :placeholder="$t('Enter your full name')"
                            autocomplete="name"
                            :error="inertiaForm.errors.name"
                        />
                    </FormControl>
                    <FormMessage :inertia-error="inertiaForm.errors.name" />
                </FormItem>
            </FormField>

            <FormField name="email" v-slot="{ componentField }">
                <FormItem>
                    <FormLabel :error="inertiaForm.errors.email">{{
                        $t('Email')
                    }}</FormLabel>
                    <FormControl>
                        <Input
                            v-bind="componentField"
                            type="email"
                            :placeholder="$t('Enter your email address')"
                            autocomplete="email"
                            :error="inertiaForm.errors.email"
                        />
                    </FormControl>
                    <FormMessage :inertia-error="inertiaForm.errors.email" />
                </FormItem>
            </FormField>

            <FormField name="password" v-slot="{ componentField }">
                <FormItem>
                    <FormLabel :error="inertiaForm.errors.password">{{
                        $t('Password')
                    }}</FormLabel>
                    <FormControl>
                        <PasswordInput
                            v-bind="componentField"
                            :placeholder="$t('Create a password')"
                            autocomplete="new-password"
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
                            :placeholder="$t('Confirm your password')"
                            autocomplete="new-password"
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

            <div class="flex items-center justify-end space-x-4">
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    {{ $t('Already registered?') }}
                </Link>

                <Button
                    type="submit"
                    :class="{ 'opacity-25': inertiaForm.processing }"
                    :disabled="inertiaForm.processing"
                >
                    {{ $t('Register') }}
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
