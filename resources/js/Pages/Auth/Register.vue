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
import { registerSchema, type RegisterFormData } from '@/validation';
import { Head, Link, useForm as useInertiaForm } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';

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
        <Head title="Register" />

        <!-- Title and Subtitle -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Create your account
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Sign up for Tadone to start managing your tasks
            </p>
        </div>

        <form @submit.prevent="onSubmit" class="space-y-4">
            <FormField name="name" v-slot="{ componentField }">
                <FormItem>
                    <FormLabel :error="inertiaForm.errors.name">Name</FormLabel>
                    <FormControl>
                        <Input
                            v-bind="componentField"
                            type="text"
                            placeholder="Enter your full name"
                            :error="inertiaForm.errors.name"
                        />
                    </FormControl>
                    <FormMessage :inertia-error="inertiaForm.errors.name" />
                </FormItem>
            </FormField>

            <FormField name="email" v-slot="{ componentField }">
                <FormItem>
                    <FormLabel :error="inertiaForm.errors.email"
                        >Email</FormLabel
                    >
                    <FormControl>
                        <Input
                            v-bind="componentField"
                            type="email"
                            placeholder="Enter your email address"
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
                            placeholder="Create a password"
                            :error="inertiaForm.errors.password"
                        />
                    </FormControl>
                    <FormMessage :inertia-error="inertiaForm.errors.password" />
                </FormItem>
            </FormField>

            <FormField name="password_confirmation" v-slot="{ componentField }">
                <FormItem>
                    <FormLabel :error="inertiaForm.errors.password_confirmation"
                        >Confirm Password</FormLabel
                    >
                    <FormControl>
                        <PasswordInput
                            v-bind="componentField"
                            placeholder="Confirm your password"
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
                    Already registered?
                </Link>

                <PrimaryButton
                    type="submit"
                    :class="{ 'opacity-25': inertiaForm.processing }"
                    :disabled="inertiaForm.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
