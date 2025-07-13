<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import Input from '@/Components/ui/Input.vue';
import FormControl from '@/Components/ui/form/FormControl.vue';
import FormField from '@/Components/ui/form/FormField.vue';
import FormItem from '@/Components/ui/form/FormItem.vue';
import FormLabel from '@/Components/ui/form/FormLabel.vue';
import FormMessage from '@/Components/ui/form/FormMessage.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="mt-6 space-y-6"
        >
            <FormField name="name">
                <FormItem>
                    <FormLabel :error="form.errors.name">Name</FormLabel>
                    <FormControl>
                        <Input
                            v-model="form.name"
                            type="text"
                            autocomplete="name"
                            :error="form.errors.name"
                            required
                            autofocus
                        />
                    </FormControl>
                    <FormMessage :inertia-error="form.errors.name" />
                </FormItem>
            </FormField>

            <FormField name="email">
                <FormItem>
                    <FormLabel :error="form.errors.email">Email</FormLabel>
                    <FormControl>
                        <Input
                            v-model="form.email"
                            type="email"
                            autocomplete="username"
                            :error="form.errors.email"
                            required
                        />
                    </FormControl>
                    <FormMessage :inertia-error="form.errors.email" />
                </FormItem>
            </FormField>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <Button variant="default" :disabled="form.processing">Save</Button>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
