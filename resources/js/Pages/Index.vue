<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import IconLogin from '~icons/heroicons/arrow-right-on-rectangle';
import IconDashboard from '~icons/heroicons/squares-2x2';
import IconUserPlus from '~icons/heroicons/user-plus';
import Footer from '@/Components/layout/Footer.vue';
import Header from '@/Components/layout/Header.vue';
import TadoneLogo from '@/Components/ui/TadoneLogo.vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();
</script>

<template>
    <Head title="Welcome to Tadone" />
    <div class="flex min-h-screen flex-col bg-white dark:bg-gray-950">
        <!-- Header with theme toggle -->
        <Header />

        <div
            class="flex flex-1 flex-col items-center justify-center px-6 py-12"
        >
            <!-- Logo Section -->
            <div class="mb-12 text-center">
                <div class="mb-6 flex justify-center">
                    <TadoneLogo size="xl" :showText="true" />
                </div>

                <!-- Tagline -->
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Task management made simple
                </p>
            </div>

            <!-- Action Buttons -->
            <div
                class="flex w-full max-w-sm flex-col items-center justify-center gap-3 sm:flex-row sm:gap-4"
            >
                <!-- Sign In Button -->
                <Link
                    v-if="canLogin && !$page.props.auth.user"
                    :href="route('login')"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-hidden sm:w-auto dark:focus:ring-offset-gray-950"
                >
                    <IconLogin class="mr-2 h-4 w-4" />
                    Sign In
                </Link>

                <!-- Sign Up Button -->
                <Link
                    v-if="canRegister && !$page.props.auth.user"
                    :href="route('register')"
                    class="inline-flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-hidden sm:w-auto dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-950"
                >
                    <IconUserPlus class="mr-2 h-4 w-4" />
                    Sign Up
                </Link>

                <!-- Dashboard Button (if logged in) -->
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-hidden sm:w-auto dark:focus:ring-offset-gray-950"
                >
                    <IconDashboard class="mr-2 h-4 w-4" />
                    Dashboard
                </Link>
            </div>
        </div>

        <!-- Footer -->
        <Footer />
    </div>
</template>
