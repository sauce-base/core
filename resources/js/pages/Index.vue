<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import Footer from '@/components/Footer.vue';
import Header from '@/components/Header.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';

import IconGitHub from '~icons/heroicons/code-bracket';
import IconDashboard from '~icons/heroicons/squares-2x2';
import IconUserPlus from '~icons/heroicons/user-plus';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();

const page = usePage();
const user = computed(() => page.props.auth?.user);

// Mouse tracking for parallax effect
const mouseX = ref(0);
const mouseY = ref(0);

const isServer = typeof window === 'undefined';

const handleMouseMove = (e: MouseEvent) => {
    if (isServer) return;
    mouseX.value = (e.clientX / window.innerWidth - 0.5) * 60;
    mouseY.value = (e.clientY / window.innerHeight - 0.5) * 60;
};

onMounted(() => {
    if (isServer) return;
    window.addEventListener('mousemove', handleMouseMove);
});

onUnmounted(() => {
    if (isServer) return;
    window.removeEventListener('mousemove', handleMouseMove);
});
</script>

<template>
    <Head :title="$t('Sauce Base - Modern Laravel SaaS Starter Kit')" />
    <div class="relative isolate flex min-h-screen flex-col overflow-x-hidden">
        <!-- Header with theme toggle -->
        <Header :canLogin="canLogin" :canRegister="canRegister" />

        <!-- Top gradient blob -->
        <div
            class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
            aria-hidden="true"
        >
            <div
                class="from-secondary to-primary relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr opacity-30 transition-transform duration-300 ease-out sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                :style="`clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%); transform: translate(${mouseX}px, ${mouseY}px)`"
            ></div>
        </div>

        <!-- Bottom gradient blob -->
        <div
            class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 transform-gpu overflow-hidden blur-3xl"
            aria-hidden="true"
        >
            <div
                class="from-secondary to-primary relative left-[calc(50%-10rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 translate-y-1/4 bg-gradient-to-tr opacity-30 transition-transform duration-300 ease-out sm:left-[calc(50%+10rem)] sm:w-[72.1875rem]"
                :style="`clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%); transform: translate(${-mouseX}px, ${-mouseY}px)`"
            ></div>
        </div>

        <!-- Hero Section -->
        <section class="flex flex-1 flex-col items-center justify-center px-6">
            <div class="mx-auto max-w-4xl text-center">
                <!-- Logo -->
                <div class="mb-12 flex justify-center">
                    <ApplicationLogo
                        showText
                        size="xxl"
                        showSubtitle
                        centered
                    />
                </div>

                <!-- Main Headline -->
                <h1 class="mb-6 text-4xl font-bold tracking-tight sm:text-6xl">
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
                        v-if="canRegister && !user"
                        :href="route('register')"
                        class="bg-primary text-primary-foreground hover:bg-primary/90 focus:ring-primary inline-flex items-center justify-center rounded-full px-8 py-4 text-lg font-semibold transition-all duration-200 hover:scale-105 focus:ring-2 focus:ring-offset-2 focus:outline-hidden dark:focus:ring-offset-gray-950"
                    >
                        <IconUserPlus class="mr-2 h-5 w-5" />
                        {{ $t('Get Started Free') }}
                    </Link>

                    <!-- Dashboard Button (if logged in) -->
                    <Link
                        v-if="route().has('dashboard') && user"
                        :href="route('dashboard')"
                        class="bg-primary text-primary-foreground hover:bg-primary/90 focus:ring-primary inline-flex items-center justify-center rounded-full px-8 py-4 text-lg font-semibold transition-all duration-200 hover:scale-105 focus:ring-2 focus:ring-offset-2 focus:outline-hidden dark:focus:ring-offset-gray-950"
                    >
                        <IconDashboard class="mr-2 h-5 w-5" />
                        {{ $t('Go to Dashboard') }}
                    </Link>

                    <!-- GitHub Button -->
                    <a
                        href="https://github.com/sauce-base/saucebase"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="focus:ring-primary inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-8 py-4 text-lg font-semibold text-gray-700 transition-all duration-200 hover:scale-105 hover:bg-gray-50 focus:ring-2 focus:ring-offset-2 focus:outline-hidden dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-950"
                    >
                        <IconGitHub class="mr-2 h-5 w-5" />
                        {{ $t('View on GitHub') }}
                    </a>

                    <!-- Sign In Button -->
                    <Link
                        v-if="canLogin && !user"
                        :href="route('login')"
                        class="text-primary hover:text-primary/90 dark:text-white dark:hover:text-white/80"
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
