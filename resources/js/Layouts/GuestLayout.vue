<script setup lang="ts">
import Footer from '@/Components/layout/Footer.vue';
import PageTransition from '@/Components/PageTransition.vue';
import TadoneLogo from '@/Components/ui/TadoneLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col bg-gray-100 transition-opacity dark:bg-gray-900"
    >
        <div class="flex flex-1 items-center justify-center px-4 py-8">
            <div class="w-full max-w-md">
                <!-- Logo Section -->
                <div class="mb-8 text-center">
                    <Link href="/">
                        <TadoneLogo
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
