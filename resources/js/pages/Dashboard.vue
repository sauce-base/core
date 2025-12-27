<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const title = 'Dashboard';
</script>
<template>
    <Head :title="$t(title)" />
    <AppLayout :title="title" :breadcrumbs="[{ title }]">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 pt-0">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="bg-muted/50 aspect-video rounded-xl" />
                <div class="bg-muted/50 aspect-video rounded-xl" />
                <div class="bg-muted/50 aspect-video rounded-xl" />
            </div>
            <div
                class="bg-muted/50 min-h-[100vh] flex-1 rounded-xl md:min-h-min"
            />
        </div>
    </AppLayout>
</template>
