<script setup lang="ts">
import ErrorMessage from '@/components/ErrorMessage.vue';
import Input from '@/components/Input.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import FormControl from '@/components/ui/form/FormControl.vue';
import FormField from '@/components/ui/form/FormField.vue';
import FormItem from '@/components/ui/form/FormItem.vue';
import FormLabel from '@/components/ui/form/FormLabel.vue';
import FormMessage from '@/components/ui/form/FormMessage.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { loginSchema, type LoginFormData } from '@/validation';
import { Head, Link, useForm as useInertiaForm } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import SocialiteProviders from '../components/SocialiteProviders.vue';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const formSchema = toTypedSchema(loginSchema);

const form = useForm({
    validationSchema: formSchema,
    initialValues: {
        email: '',
        password: '',
        remember: false,
    },
});

const inertiaForm = useInertiaForm<LoginFormData>({
    email: '',
    password: '',
    remember: false,
});

const onSubmit = async () => {
    const { valid } = await form.validate();

    if (valid) {
        Object.assign(inertiaForm, form.values);

        inertiaForm.post(route('login'), {
            onFinish: () => {
                inertiaForm.reset('password');
            },
            onError: (e) => {
                console.log(e);
            },
        });
    }
};
</script>

<template>
    <GuestLayout>
        <Head :title="$t('Log in')" />

        <!-- Title and Subtitle -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $t('Welcome back') }}
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ $t('Login to your Sauce Base account to continue') }}
            </p>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <ErrorMessage field="social" variant="error" />

        <SocialiteProviders />

        <form
            @submit.prevent="onSubmit"
            class="space-y-4"
            data-testid="login-form"
        >
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
                            :placeholder="$t('Enter your password')"
                            autocomplete="current-password"
                            :error="inertiaForm.errors.password"
                        />
                    </FormControl>
                    <FormMessage :inertia-error="inertiaForm.errors.password" />
                </FormItem>
            </FormField>

            <FormField name="remember" v-slot="{ componentField }">
                <FormItem>
                    <FormControl>
                        <div class="flex items-center space-x-2">
                            <Checkbox
                                id="remember"
                                name="remember"
                                :checked="componentField.modelValue"
                                @update:checked="
                                    componentField['onUpdate:modelValue']
                                "
                                @blur="componentField.onBlur"
                                data-testid="remember-me"
                            />
                            <label
                                for="remember"
                                class="cursor-pointer text-sm text-gray-600 dark:text-gray-400"
                            >
                                {{ $t('Remember me') }}
                            </label>
                        </div>
                    </FormControl>
                </FormItem>
            </FormField>

            <div class="flex items-center justify-end space-x-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    data-testid="forgot-password-link"
                >
                    {{ $t('Forgot your password?') }}
                </Link>

                <Button
                    type="submit"
                    :class="{ 'opacity-25': inertiaForm.processing }"
                    :disabled="inertiaForm.processing"
                    data-testid="login-button"
                >
                    {{ $t('Log in') }}
                </Button>
            </div>
        </form>

        <template #outside>
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $t("Don't have an account?") }}
                    <Link
                        :href="route('register')"
                        class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                        data-testid="sign-up-link"
                    >
                        {{ $t('Sign up') }}
                    </Link>
                </p>
            </div>
        </template>
    </GuestLayout>
</template>
