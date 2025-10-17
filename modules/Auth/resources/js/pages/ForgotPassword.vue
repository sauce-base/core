<script setup lang="ts">
import AlertMessage from '@/components/AlertMessage.vue';
import { Button } from '@/components/ui/button';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Form, Link } from '@inertiajs/vue3';
import AuthCardLayout from '../layouts/AuthCardLayout.vue';
</script>

<template>
    <AuthCardLayout
        :title="$t('Forgot Password')"
        :description="
            $t(
                'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.',
            )
        "
    >
        <AlertMessage
            :message="$page.props.status"
            variant="success"
            class="mt-4"
        />

        <Form
            :action="route('password.email')"
            method="post"
            class="min-w-sm space-y-3"
            data-testid="forgot-password-form"
            disable-while-processing
            :reset-on-success="['email']"
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
                    data-testid="email"
                    :model-value="String($page.props.email)"
                    :placeholder="$t('Enter your email address')"
                    :aria-invalid="!!errors?.email"
                    autocomplete="email"
                    required
                />
                <FieldError
                    v-if="errors?.email"
                    data-testid="email-error"
                    aria-live="polite"
                >
                    {{ errors?.email }}
                </FieldError>
            </Field>

            <div class="flex items-center justify-between pt-1">
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                    data-testid="back-to-login-link"
                >
                    {{ $t('Back to login') }}
                </Link>
                <Button type="submit" data-testid="reset-button">
                    {{ $t('Email Password Reset Link') }}
                </Button>
            </div>
        </Form>
    </AuthCardLayout>
</template>
