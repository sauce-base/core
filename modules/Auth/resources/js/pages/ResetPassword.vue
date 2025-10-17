<script setup lang="ts">
import AlertMessage from '@/components/AlertMessage.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Form } from '@inertiajs/vue3';
import AuthCardLayout from '../layouts/AuthCardLayout.vue';

defineProps<{
    email: string;
    token: string;
}>();
</script>

<template>
    <AuthCardLayout
        :title="$t('Reset Password')"
        :description="$t('Enter your new password below')"
    >
        <AlertMessage
            :message="$page.props.errors.status"
            variant="error"
            class="mt-4"
        />

        <Form
            :action="route('password.store')"
            method="post"
            class="min-w-sm space-y-3"
            data-testid="reset-password-form"
            disable-while-processing
            :reset-on-error="['password', 'password_confirmation']"
            #default="{ errors }"
        >
            <input
                type="hidden"
                name="token"
                :value="token"
                data-testid="token"
            />

            <!-- Email -->
            <Field>
                <FieldLabel for="email">
                    {{ $t('Email') }}
                </FieldLabel>
                <Input
                    id="email"
                    name="email"
                    type="email"
                    :model-value="email"
                    readonly
                    required
                    data-testid="email"
                />
            </Field>

            <!-- Password -->
            <Field :data-invalid="!!errors?.password">
                <FieldLabel for="password">
                    {{ $t('New Password') }}
                </FieldLabel>
                <PasswordInput
                    id="password"
                    name="password"
                    :placeholder="$t('Enter your new password')"
                    :aria-invalid="!!errors?.password"
                    autocomplete="new-password"
                    required
                    data-testid="password"
                />
                <FieldError
                    v-if="errors?.password"
                    data-testid="password-error"
                    aria-live="polite"
                >
                    {{ errors?.password }}
                </FieldError>
            </Field>

            <!-- Password Confirmation -->
            <Field :data-invalid="!!errors?.password_confirmation">
                <FieldLabel for="password_confirmation">
                    {{ $t('Confirm Password') }}
                </FieldLabel>
                <PasswordInput
                    id="password_confirmation"
                    name="password_confirmation"
                    :placeholder="$t('Confirm your new password')"
                    :aria-invalid="!!errors?.password_confirmation"
                    autocomplete="new-password"
                    required
                    data-testid="password_confirmation"
                />
                <FieldError
                    v-if="errors?.password_confirmation"
                    data-testid="password-confirmation-error"
                    aria-live="polite"
                >
                    {{ errors?.password_confirmation }}
                </FieldError>
            </Field>

            <Button type="submit" class="mt-3 w-full">
                {{ $t('Reset Password') }}
            </Button>
        </Form>
    </AuthCardLayout>
</template>
