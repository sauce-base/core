<script setup lang="ts">
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout title="Dashboard">
        <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
            <!-- Role Testing Section -->
            <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                <h3
                    class="mb-4 text-lg font-medium text-gray-900 dark:text-gray-100"
                >
                    🍯 Role Permission Testing
                </h3>
                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    Test the role-based access control system by visiting
                    different protected areas.
                </p>
                <div class="grid gap-3 sm:grid-cols-3">
                    <Link
                        :href="route('user.test')"
                        class="flex items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500"
                    >
                        👤 User Test Area
                    </Link>
                    <Link
                        :href="route('editor.test')"
                        class="flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500"
                    >
                        ✍️ Editor Test Area
                    </Link>
                    <Link
                        :href="route('admin.test')"
                        class="flex items-center justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500"
                    >
                        🔥 Admin Test Area
                    </Link>
                </div>
                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                    Access depends on your assigned role. Try different areas to
                    see permission controls in action!
                </p>
            </div>

            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="bg-muted/50 aspect-video rounded-xl" />
                <div class="bg-muted/50 aspect-video rounded-xl" />
                <div class="bg-muted/50 aspect-video rounded-xl" />
            </div>
            <div
                class="bg-muted/50 min-h-[100vh] flex-1 rounded-xl md:min-h-min"
            />
        </div>
    </AuthenticatedLayout>
</template>
