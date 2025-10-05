<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import Footer from '@/components/Footer.vue';
import PageTransition from '@/components/PageTransition.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col transition-opacity">
        <div class="flex flex-1 items-center justify-center px-4 py-8">
            <div class="w-full max-w-md">
                <!-- Logo Section -->
                <div class="mb-8 text-center">
                    <Link href="/">
                        <ApplicationLogo
                            size="lg"
                            :showText="true"
                            :centered="true"
                        />
                    </Link>
                </div>

                <!-- Form Container -->
                <div
                    class="rounded-xl border border-gray-200 bg-white p-8 shadow-lg dark:border-gray-700 dark:bg-gray-800"
                >
                    <PageTransition>
                        <slot />
                    </PageTransition>
                </div>

                <!-- Outside content slot -->
                <slot name="outside" />
            </div>
        </div>

        <!-- Footer -->
        <Footer />
    </div>
</template>
