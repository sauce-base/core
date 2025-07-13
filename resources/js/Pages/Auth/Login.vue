<script setup lang="ts">
import PrimaryButton from '@/Components/ui/button/PrimaryButton.vue';
import FormControl from '@/Components/ui/form/FormControl.vue';
import FormField from '@/Components/ui/form/FormField.vue';
import FormItem from '@/Components/ui/form/FormItem.vue';
import FormLabel from '@/Components/ui/form/FormLabel.vue';
import FormMessage from '@/Components/ui/form/FormMessage.vue';
import Input from '@/Components/ui/Input.vue';
import PasswordInput from '@/Components/ui/PasswordInput.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { loginSchema, type LoginFormData } from '@/validation';
import { Head, Link, useForm as useInertiaForm } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';

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
        });
    }
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <!-- Title and Subtitle -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Welcome back
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Login to your Tadone account to continue
            </p>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="onSubmit" class="space-y-4">
            <FormField name="email" v-slot="{ componentField }">
                <FormItem>
                    <FormLabel :error="inertiaForm.errors.email"
                        >Email</FormLabel
                    >
                    <FormControl>
                        <Input
                            v-bind="componentField"
                            type="email"
                            placeholder="Enter your email"
                            :error="inertiaForm.errors.email"
                        />
                    </FormControl>
                    <FormMessage :inertia-error="inertiaForm.errors.email" />
                </FormItem>
            </FormField>

            <FormField name="password" v-slot="{ componentField }">
                <FormItem>
                    <FormLabel :error="inertiaForm.errors.password"
                        >Password</FormLabel
                    >
                    <FormControl>
                        <PasswordInput
                            v-bind="componentField"
                            placeholder="Enter your password"
                            :error="inertiaForm.errors.password"
                        />
                    </FormControl>
                    <FormMessage :inertia-error="inertiaForm.errors.password" />
                </FormItem>
            </FormField>

            <FormField name="remember" v-slot="{ componentField }">
                <FormItem>
                    <FormControl>
                        <label class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                :checked="componentField.modelValue === true"
                                @change="
                                    (e) =>
                                        componentField['onUpdate:modelValue'](
                                            (e.target as HTMLInputElement)
                                                .checked,
                                        )
                                "
                                @blur="componentField.onBlur"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-indigo-600"
                            />
                            <span
                                class="text-sm text-gray-600 dark:text-gray-400"
                            >
                                Remember me
                            </span>
                        </label>
                    </FormControl>
                </FormItem>
            </FormField>

            <div class="flex items-center justify-end space-x-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton
                    type="submit"
                    :class="{ 'opacity-25': inertiaForm.processing }"
                    :disabled="inertiaForm.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>

        <template #outside>
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Don't have an account?
                    <Link
                        :href="route('register')"
                        class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                    >
                        Sign up
                    </Link>
                </p>
            </div>
        </template>
    </GuestLayout>
</template>
