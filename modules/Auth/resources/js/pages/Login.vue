<script setup lang="ts">
import ErrorMessage from '@/components/ErrorMessage.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Form, Link } from '@inertiajs/vue3';
import SocialiteProviders from '../components/SocialiteProviders.vue';
import AuthCardLayout from '../layouts/AuthCardLayout.vue';
</script>

<template>
    <AuthCardLayout
        :title="$t('Welcome back')"
        :description="$t('Login to your Sauce Base account to continue')"
    >
        <SocialiteProviders />
        <ErrorMessage field="status" variant="error" class="mt-4" />
        <Form
            :action="route('login')"
            method="post"
            class="space-y-3"
            data-testid="login-form"
            disable-while-processing
            :reset-on-error="['password']"
            #default="{ errors }"
        >
            <!-- Email -->
            <Field :data-invalid="!!errors?.email">
                <FieldLabel for="email">
                    {{ $t('Email') }}
                </FieldLabel>
                <Input
                    id="email"
                    name="email"
                    type="email"
                    :placeholder="$t('Enter your email')"
                    :aria-invalid="!!errors?.email"
                    autocomplete="email"
                    required
                />
                <FieldError v-if="errors?.email">
                    {{ errors?.email }}
                </FieldError>
            </Field>

            <!-- Password -->
            <Field :data-invalid="!!errors?.password">
                <div className="flex items-center">
                    <FieldLabel for="password">
                        {{ $t('Password') }}
                    </FieldLabel>
                    <Link
                        v-if="route().has('password.request')"
                        :href="route('password.request')"
                        class="ml-auto inline-block text-sm underline-offset-4 hover:underline"
                        data-testid="forgot-password-link"
                        :data-invalid="false"
                    >
                        {{ $t('Forgot your password?') }}
                    </Link>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    :placeholder="$t('Enter your password')"
                    autocomplete="current-password"
                    :aria-invalid="!!errors?.password"
                    required
                />
                <FieldError v-if="errors?.password">
                    {{ errors?.password }}
                </FieldError>
            </Field>

            <!-- Remember-me -->
            <Field>
                <Field orientation="horizontal">
                    <Checkbox id="remember" name="remember" />
                    <FieldLabel for="remember" class="font-normal">
                        {{ $t('Remember-me') }}
                    </FieldLabel>
                </Field>
            </Field>

            <Button type="submit" class="mt-3 w-full">
                {{ $t('Log in') }}
            </Button>

            <p
                class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400"
            >
                {{ $t("Don't have an account?") }}
                <Link
                    :href="route('register')"
                    class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline dark:text-indigo-400 dark:hover:text-indigo-300"
                    data-testid="sign-up-link"
                >
                    {{ $t('Sign up') }}
                </Link>
            </p>
        </Form>
    </AuthCardLayout>
</template>
