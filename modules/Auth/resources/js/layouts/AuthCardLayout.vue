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
    cardClass?: string | object;
}>();
</script>

<template>
    <div class="flex h-screen flex-col items-center gap-6">
        <div class="mt-6">
            <Head :title="title" />
            <Link :href="route('index')" class="mt-6 font-medium">
                <ApplicationLogo size="md" :showText="true" />
            </Link>
        </div>

        <div class="min-w-md flex-grow">
            <Card>
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
        <Footer class="mt-6 w-full" />
    </div>
</template>
