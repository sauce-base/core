<template>
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl leading-tight font-semibold text-gray-800 dark:text-gray-200"
            >
                Admin Test Area
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="space-y-6">
                            <!-- Success Message -->
                            <div
                                class="rounded-md bg-green-50 p-4 dark:bg-green-900/20"
                            >
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg
                                            class="h-5 w-5 text-green-400"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3
                                            class="text-sm font-medium text-green-800 dark:text-green-200"
                                        >
                                            Admin Access Verified!
                                        </h3>
                                        <div
                                            class="mt-2 text-sm text-green-700 dark:text-green-300"
                                        >
                                            <p>{{ message }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Info -->
                            <div
                                class="rounded-md bg-blue-50 p-4 dark:bg-blue-900/20"
                            >
                                <h3
                                    class="mb-3 text-lg font-medium text-blue-900 dark:text-blue-200"
                                >
                                    Current User Information
                                </h3>
                                <dl
                                    class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2"
                                >
                                    <div>
                                        <dt
                                            class="text-sm font-medium text-blue-700 dark:text-blue-300"
                                        >
                                            Name
                                        </dt>
                                        <dd
                                            class="text-sm text-blue-900 dark:text-blue-100"
                                        >
                                            {{ user.name }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt
                                            class="text-sm font-medium text-blue-700 dark:text-blue-300"
                                        >
                                            Email
                                        </dt>
                                        <dd
                                            class="text-sm text-blue-900 dark:text-blue-100"
                                        >
                                            {{ user.email }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt
                                            class="text-sm font-medium text-blue-700 dark:text-blue-300"
                                        >
                                            Role
                                        </dt>
                                        <dd
                                            class="text-sm text-blue-900 dark:text-blue-100"
                                        >
                                            <span
                                                class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-800/20 dark:text-red-300"
                                            >
                                                🔥
                                                {{
                                                    user.roles[0]?.name?.toUpperCase() ||
                                                    'No Role'
                                                }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt
                                            class="text-sm font-medium text-blue-700 dark:text-blue-300"
                                        >
                                            Access Level
                                        </dt>
                                        <dd
                                            class="text-sm text-blue-900 dark:text-blue-100"
                                        >
                                            Administrator (Full Access)
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Test Links -->
                            <div
                                class="rounded-md bg-gray-50 p-4 dark:bg-gray-700"
                            >
                                <h3
                                    class="mb-3 text-lg font-medium text-gray-900 dark:text-gray-200"
                                >
                                    Test Other Permission Levels
                                </h3>
                                <div class="space-y-2">
                                    <div>
                                        <Link
                                            :href="route('editor.test')"
                                            class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500"
                                        >
                                            Test Editor Area
                                        </Link>
                                        <span
                                            class="ml-2 text-sm text-gray-600 dark:text-gray-400"
                                        >
                                            (Admin can access editor areas)
                                        </span>
                                    </div>
                                    <div>
                                        <Link
                                            :href="route('user.test')"
                                            class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500"
                                        >
                                            Test User Area
                                        </Link>
                                        <span
                                            class="ml-2 text-sm text-gray-600 dark:text-gray-400"
                                        >
                                            (All authenticated users can access)
                                        </span>
                                    </div>
                                    <div>
                                        <Link
                                            :href="route('dashboard')"
                                            class="inline-flex items-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500"
                                        >
                                            Back to Dashboard
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <!-- Admin-Only Features Demo -->
                            <div
                                class="rounded-md bg-yellow-50 p-4 dark:bg-yellow-900/20"
                            >
                                <h3
                                    class="mb-3 text-lg font-medium text-yellow-900 dark:text-yellow-200"
                                >
                                    Admin-Only Features (Demo)
                                </h3>
                                <div class="space-y-3">
                                    <button
                                        class="w-full rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500"
                                    >
                                        🚨 Manage Users (Admin Only)
                                    </button>
                                    <button
                                        class="w-full rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500"
                                    >
                                        ⚙️ System Settings (Admin Only)
                                    </button>
                                    <button
                                        class="w-full rounded-md bg-purple-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-500"
                                    >
                                        📊 Analytics Dashboard (Admin Only)
                                    </button>
                                </div>
                                <p
                                    class="mt-3 text-xs text-yellow-700 dark:text-yellow-300"
                                >
                                    These buttons are just for demo - they don't
                                    actually do anything yet!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string;
    email: string;
    roles: Array<{
        id: number;
        name: string;
    }>;
}

interface Props {
    user: User;
    message: string;
}

defineProps<Props>();
</script>
