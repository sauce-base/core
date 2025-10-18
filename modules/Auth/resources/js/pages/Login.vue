<script setup lang="ts">
import AlertMessage from '@/components/AlertMessage.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Form, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import SocialiteProviders from '../components/SocialiteProviders.vue';
import AuthCardLayout from '../layouts/AuthCardLayout.vue';

const emailRef = ref('');

// compute forgot password url so the link updates as user types
const forgotUrl = computed(() => {
    // if route helper is available at runtime, use it; otherwise return '#'
    try {
        return route('password.request', { email: emailRef.value });
    } catch {
        return '#';
    }
});
</script>

<template>
    <AuthCardLayout
        :title="$t('Welcome back')"
        :description="$t('Login to your Sauce Base account to continue')"
    >
        <SocialiteProviders />

        <AlertMessage
            :message="$page.props.status || $page.props.error"
            :variant="$page.props.status ? 'success' : 'error'"
            class="mt-4"
        />

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
                <FieldLabel id="email-label" for="email">
                    {{ $t('Email') }}
                </FieldLabel>
                <Input
                    id="email"
                    name="email"
                    type="email"
                    data-testid="email"
                    :placeholder="$t('Enter your email')"
                    :aria-invalid="!!errors?.email"
                    aria-labelledby="email-label"
                    :aria-describedby="
                        errors?.email ? 'email-error' : undefined
                    "
                    autocomplete="email"
                    v-model="emailRef"
                    required
                    tabindex="1"
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
                <!-- Forgot password -->
                <div className="flex items-center">
                    <FieldLabel id="password-label" for="password">
                        {{ $t('Password') }}
                    </FieldLabel>
                    <Link
                        v-if="route().has('password.request')"
                        :href="forgotUrl"
                        class="ml-auto inline-block text-sm underline-offset-4 hover:underline"
                        data-testid="forgot-password-link"
                        :data-invalid="false"
                        tabindex="5"
                    >
                        {{ $t('Forgot your password?') }}
                    </Link>
                </div>

                <PasswordInput
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    data-testid="password"
                    :placeholder="$t('Enter your password')"
                    :aria-invalid="!!errors?.password"
                    aria-labelledby="password-label"
                    :aria-describedby="
                        errors?.password ? 'password-error' : undefined
                    "
                    required
                    tabindex="2"
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

            <!-- Remember-me -->
            <Field>
                <Field orientation="horizontal">
                    <Checkbox
                        id="remember"
                        name="remember"
                        data-testid="remember-me"
                        tabindex="3"
                    />
                    <FieldLabel for="remember" class="font-normal">
                        {{ $t('Remember-me') }}
                    </FieldLabel>
                </Field>
            </Field>

            <Button
                type="submit"
                class="mt-3 w-full"
                data-testid="login-button"
                tabindex="4"
            >
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
