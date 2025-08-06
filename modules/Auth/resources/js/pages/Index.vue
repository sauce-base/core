<script setup lang="ts">
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
const title = 'Auth Module';
const message = 'Welcome to the ' + title;
</script>
<template>
    <Head :title="title" />
    <AuthenticatedLayout
        :title="title"
        :breadcrumbs="[{ url: '/', label: 'Dashboard' }, { label: title }]"
    >
        <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
            <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h2
                            class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                        >
                            🔐 {{ message }}
                        </h2>
                    </div>
                </div>
            </div>

            <!-- Module Content -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                    <h3
                        class="mb-2 text-lg font-medium text-gray-900 dark:text-gray-100"
                    >
                        Module Features
                    </h3>
                    <ul
                        class="space-y-2 text-sm text-gray-600 dark:text-gray-400"
                    >
                        <li>✅ Uses AuthenticatedLayout</li>
                        <li>✅ Breadcrumb navigation</li>
                        <li>✅ Global UI components</li>
                        <li>✅ TypeScript support</li>
                        <li>✅ Inertia.js integration</li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
