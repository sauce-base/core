<script setup lang="ts">
import AlertMessage from '@/components/AlertMessage.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Form, Link } from '@inertiajs/vue3';
import SocialiteProviders from '../components/SocialiteProviders.vue';
import AuthCardLayout from '../layouts/AuthCardLayout.vue';
</script>

<template>
    <AuthCardLayout
        :title="$t('Create your account')"
        :description="$t('Sign up for Sauce Base to start building your SaaS')"
    >
        <SocialiteProviders />

        <AlertMessage
            :message="$page.props.errors?.status"
            variant="error"
            class="mt-4"
        />

        <Form
            :action="route('register')"
            method="post"
            class="space-y-3"
            data-testid="register-form"
            disable-while-processing
            :reset-on-error="['password']"
            #default="{ errors }"
        >
            <!-- Name -->
            <Field :data-invalid="!!errors?.name">
                <FieldLabel for="name">
                    {{ $t('Name') }}
                </FieldLabel>
                <Input
                    id="name"
                    name="name"
                    type="text"
                    :placeholder="$t('Enter your full name')"
                    :aria-invalid="!!errors?.name"
                    data-testid="name"
                    autocomplete="name"
                    required
                />
                <FieldError v-if="errors?.name">
                    {{ errors?.name }}
                </FieldError>
            </Field>

            <!-- Email -->
            <Field :data-invalid="!!errors?.email">
                <FieldLabel for="email">
                    {{ $t('Email') }}
                </FieldLabel>
                <Input
                    id="email"
                    name="email"
                    type="email"
                    data-testid="email"
                    :placeholder="$t('Enter your email')"
                    :aria-invalid="!!errors.email"
                    aria-labelledby="email-label"
                    :aria-describedby="
                        errors?.email ? 'email-error' : undefined
                    "
                    autocomplete="email"
                    required
                />
                <FieldError
                    v-if="errors?.email"
                    id="email-error"
                    data-testid="email-error"
                    aria-live="polite"
                >
                    {{ errors?.email }}
                </FieldError>
            </Field>

            <!-- Password -->
            <Field :data-invalid="!!errors?.password">
                <FieldLabel for="password">
                    {{ $t('Password') }}
                </FieldLabel>
                <PasswordInput
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    data-testid="password"
                    :placeholder="$t('Enter your password')"
                    :aria-invalid="!!errors.password"
                    aria-labelledby="password-label"
                    :aria-describedby="
                        errors?.password ? 'password-error' : undefined
                    "
                    required
                />
                <FieldError
                    v-if="errors?.password"
                    id="password-error"
                    data-testid="password-error"
                    aria-live="polite"
                >
                    {{ errors?.password }}
                </FieldError>
            </Field>

            <Button
                type="submit"
                class="mt-3 w-full"
                data-testid="register-button"
            >
                {{ $t('Register') }}
            </Button>

            <p
                class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400"
            >
                {{ $t('Already registered?') }}
                <Link
                    :href="route('login')"
                    class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline dark:text-indigo-400 dark:hover:text-indigo-300"
                    data-testid="login-link"
                >
                    {{ $t('Log in') }}
                </Link>
            </p>
        </Form>
    </AuthCardLayout>
</template>
