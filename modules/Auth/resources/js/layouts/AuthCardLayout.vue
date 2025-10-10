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
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div>
        <Head :title="title" />
        <div class="flex flex-col items-center justify-center gap-6 p-3 md:p-7">
            <div class="flex w-full max-w-md flex-col gap-6">
                <Link
                    :href="route('index')"
                    class="flex items-center gap-2 self-center font-medium"
                >
                    <div class="flex h-full w-full items-center justify-center">
                        <ApplicationLogo size="md" :showText="true" />
                    </div>
                </Link>
                <div class="flex flex-col gap-6">
                    <Card class="py-8">
                        <CardHeader class="px-8 text-center">
                            <CardTitle class="text-2xl">
                                {{ title }}
                            </CardTitle>
                            <CardDescription>
                                {{ description }}
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="px-8">
                            <PageTransition>
                                <slot />
                            </PageTransition>
                        </CardContent>
                    </Card>
                    <slot name="outside" />
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>
