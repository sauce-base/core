<script setup lang="ts">
import ErrorMessage from '@/components/ErrorMessage.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Form, Link } from '@inertiajs/vue3';
import AuthCardLayout from '../layouts/AuthCardLayout.vue';
</script>

<template>
    <AuthCardLayout
        :title="$t('Confirm Password')"
        :description="$t('Confirm your password before continuing.')"
    >
        <ErrorMessage field="status" variant="error" class="mt-4" />
        <Form
            :action="route('password.confirm')"
            method="post"
            class="space-y-3"
            data-testid="password-confirm-form"
            disable-while-processing
            :reset-on-error="['password']"
            #default="{ errors }"
        >
            <Field :data-invalid="!!errors?.password">
                <FieldLabel for="password">
                    {{ $t('Password') }}
                </FieldLabel>
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

            <Button type="submit" class="mt-3 w-full">
                {{ $t('Confirm') }}
            </Button>
        </Form>
        <template #outside>
            <Link
                :href="route('login')"
                class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
            >
                {{ $t('Back to login') }}
            </Link>
        </template>
    </AuthCardLayout>
</template>
