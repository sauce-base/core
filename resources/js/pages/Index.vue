<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import Footer from '@/components/Footer.vue';
import Header from '@/components/Header.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useAuthStore } from '@modules/Auth/resources/js/stores';

import IconGitHub from '~icons/heroicons/code-bracket';
import IconDashboard from '~icons/heroicons/squares-2x2';
import IconStar from '~icons/heroicons/star';
import IconUserPlus from '~icons/heroicons/user-plus';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();

const authStore = useAuthStore();
</script>

<template>
    <Head :title="$t('Sauce Base - Modern Laravel SaaS Starter Kit')" />
    <div class="flex min-h-screen flex-col">
        <!-- Header with theme toggle -->
        <Header :canLogin="canLogin" :canRegister="canRegister" />

        <!-- Hero Section -->
        <section
            class="flex flex-1 flex-col items-center justify-center px-6 py-24 pt-32"
        >
            <div class="mx-auto max-w-4xl text-center">
                <!-- Free Badge -->
                <div
                    class="mb-6 inline-flex items-center rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-800 dark:bg-green-900/20 dark:text-green-300"
                >
                    <IconStar class="mr-2 h-4 w-4" />
                    100% Free & Open Source
                </div>

                <!-- Logo -->
                <div class="mb-8 flex justify-center">
                    <ApplicationLogo size="xl" :showText="true" />
                </div>

                <!-- Main Headline -->
                <h1
                    class="mb-6 text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl dark:text-white"
                >
                    {{ $t('Modern Laravel SaaS Starter Kit') }}
                </h1>

                <!-- Subheadline -->
                <p class="mb-8 text-xl text-gray-600 dark:text-gray-400">
                    {{
                        $t(
                            'Clone the repo, start building scalable and maintainable SaaS applications quickly. Built with the VILT stack - completely free and open source.',
                        )
                    }}
                </p>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col items-center justify-center gap-4 sm:flex-row"
                >
                    <!-- Primary CTA -->
                    <Link
                        v-if="canRegister && !authStore.isAuthenticated"
                        :href="route('register')"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-8 py-4 text-lg font-semibold text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-hidden dark:focus:ring-offset-gray-950"
                    >
                        <IconUserPlus class="mr-2 h-5 w-5" />
                        {{ $t('Get Started Free') }}
                    </Link>

                    <!-- Dashboard Button (if logged in) -->
                    <Link
                        v-if="authStore.isAuthenticated"
                        :href="route('dashboard')"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-8 py-4 text-lg font-semibold text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-hidden dark:focus:ring-offset-gray-950"
                    >
                        <IconDashboard class="mr-2 h-5 w-5" />
                        Go to Dashboard
                    </Link>

                    <!-- GitHub Button -->
                    <a
                        href="https://github.com/sauce-base/core"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-8 py-4 text-lg font-semibold text-gray-700 transition-colors hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-hidden dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-950"
                    >
                        <IconGitHub class="mr-2 h-5 w-5" />
                        {{ $t('View on GitHub') }}
                    </a>

                    <!-- Sign In Button -->
                    <Link
                        v-if="canLogin && !authStore.isAuthenticated"
                        :href="route('login')"
                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                        {{ $t('Already have an account? Sign In') }}
                    </Link>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <Footer />
    </div>
</template>
