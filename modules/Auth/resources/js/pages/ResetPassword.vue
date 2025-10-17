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
            :message="$page.props.status"
            variant="error"
            class="mt-4"
        />

        <Form
            :action="route('password.store')"
            method="post"
            class="space-y-3"
            data-testid="reset-password-form"
            disable-while-processing
            :reset-on-error="['password', 'password_confirmation']"
            #default="{ errors }"
        >
            <input type="hidden" name="token" :value="token" />

            <!-- Email -->
            <Field :data-invalid="!!errors?.email">
                <FieldLabel for="email">
                    {{ $t('Email') }}
                </FieldLabel>
                <Input
                    id="email"
                    name="email"
                    type="email"
                    :value="email"
                    :placeholder="$t('Enter your email')"
                    :aria-invalid="!!errors?.email"
                    autocomplete="email"
                    readonly
                    required
                />
                <FieldError v-if="errors?.email">
                    {{ errors?.email }}
                </FieldError>
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
                    autocomplete="new-password"
                    :aria-invalid="!!errors?.password"
                    required
                />
                <FieldError v-if="errors?.password">
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
                    autocomplete="new-password"
                    :aria-invalid="!!errors?.password_confirmation"
                    required
                />
                <FieldError v-if="errors?.password_confirmation">
                    {{ errors?.password_confirmation }}
                </FieldError>
            </Field>

            <Button type="submit" class="mt-3 w-full">
                {{ $t('Reset Password') }}
            </Button>
        </Form>
    </AuthCardLayout>
</template>
