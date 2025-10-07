<script setup lang="ts">
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

import ApplicationLogo from '@/components/ApplicationLogo.vue';
import Footer from '@/components/Footer.vue';
import PageTransition from '@/components/PageTransition.vue';

import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="bg-muted">
        <div
            class="flex flex-col items-center justify-center gap-6 p-6 md:p-10"
        >
            <div class="flex w-full max-w-md flex-col gap-6">
                <Link
                    :href="route('index')"
                    class="flex items-center gap-2 self-center font-medium"
                >
                    <div class="flex h-full w-full items-center justify-center">
                        <ApplicationLogo
                            size="lg"
                            :showText="true"
                            :centered="true"
                        />
                    </div>
                </Link>

                <div class="flex flex-col gap-6">
                    <Card class="rounded-xl">
                        <CardHeader class="px-10 pt-4 text-center">
                            <CardTitle class="text-2xl">{{ title }}</CardTitle>
                            <CardDescription>
                                {{ description }}
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="px-10">
                            <PageTransition>
                                <slot />
                            </PageTransition>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>
