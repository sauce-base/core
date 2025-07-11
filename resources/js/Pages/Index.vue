<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import IconLogin from '~icons/heroicons/arrow-right-on-rectangle';
import IconZap from '~icons/heroicons/bolt';
import IconCheckCircle from '~icons/heroicons/check-circle';
import IconClipboard from '~icons/heroicons/clipboard-document-list';
import IconDashboard from '~icons/heroicons/squares-2x2';
import IconUserPlus from '~icons/heroicons/user-plus';
import Footer from '../Components/Footer.vue';
import Header from '../Components/Header.vue';
import TadoneLogo from '../Components/TadoneLogo.vue';

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
                    class="inline-flex w-full items-center justify-center rounded-md bg-blue-600 px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-950 sm:w-auto"
                >
                    <IconLogin class="mr-2 h-4 w-4" />
                    Sign In
                </Link>

                <!-- Sign Up Button -->
                <Link
                    v-if="canRegister && !$page.props.auth.user"
                    :href="route('register')"
                    class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-950 sm:w-auto"
                >
                    <IconUserPlus class="mr-2 h-4 w-4" />
                    Sign Up
                </Link>

                <!-- Dashboard Button (if logged in) -->
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-flex w-full items-center justify-center rounded-md bg-blue-600 px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-950 sm:w-auto"
                >
                    <IconDashboard class="mr-2 h-4 w-4" />
                    Dashboard
                </Link>
            </div>

            <!-- Feature Highlights -->
            <div
                class="mt-16 grid w-full max-w-sm grid-cols-1 gap-6 md:max-w-4xl md:grid-cols-3"
            >
                <!-- Simple Card -->
                <div
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-100 p-6 shadow-lg transition-all duration-300 hover:scale-[1.02] hover:shadow-xl dark:from-blue-900/20 dark:to-indigo-900/20"
                >
                    <div class="relative z-10">
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg"
                        >
                            <IconClipboard class="h-6 w-6" />
                        </div>
                        <h3
                            class="mb-2 text-xl font-semibold text-gray-900 dark:text-white"
                        >
                            Simple & Clean
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Intuitive interface designed for productivity
                            without complexity
                        </p>
                    </div>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-indigo-600/10 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                    ></div>
                </div>

                <!-- Fast Card -->
                <div
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-pink-100 p-6 shadow-lg transition-all duration-300 hover:scale-[1.02] hover:shadow-xl dark:from-purple-900/20 dark:to-pink-900/20"
                >
                    <div class="relative z-10">
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-purple-600 text-white shadow-lg"
                        >
                            <IconZap class="h-6 w-6" />
                        </div>
                        <h3
                            class="mb-2 text-xl font-semibold text-gray-900 dark:text-white"
                        >
                            Lightning Fast
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Built for speed with modern web technologies and
                            optimized performance
                        </p>
                    </div>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-purple-600/10 to-pink-600/10 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                    ></div>
                </div>

                <!-- Focused Card -->
                <div
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-50 to-emerald-100 p-6 shadow-lg transition-all duration-300 hover:scale-[1.02] hover:shadow-xl dark:from-green-900/20 dark:to-emerald-900/20"
                >
                    <div class="relative z-10">
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-green-600 text-white shadow-lg"
                        >
                            <IconCheckCircle class="h-6 w-6" />
                        </div>
                        <h3
                            class="mb-2 text-xl font-semibold text-gray-900 dark:text-white"
                        >
                            Stay Focused
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Distraction-free environment to help you accomplish
                            your goals
                        </p>
                    </div>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-green-600/10 to-emerald-600/10 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                    ></div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <Footer />
    </div>
</template>
