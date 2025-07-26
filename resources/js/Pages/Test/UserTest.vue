<template>
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl leading-tight font-semibold text-gray-800 dark:text-gray-200"
            >
                User Test Area
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
                                            User Access Verified!
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
                                class="rounded-md bg-green-50 p-4 dark:bg-green-900/20"
                            >
                                <h3
                                    class="mb-3 text-lg font-medium text-green-900 dark:text-green-200"
                                >
                                    Current User Information
                                </h3>
                                <dl
                                    class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2"
                                >
                                    <div>
                                        <dt
                                            class="text-sm font-medium text-green-700 dark:text-green-300"
                                        >
                                            Name
                                        </dt>
                                        <dd
                                            class="text-sm text-green-900 dark:text-green-100"
                                        >
                                            {{ user.name }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt
                                            class="text-sm font-medium text-green-700 dark:text-green-300"
                                        >
                                            Email
                                        </dt>
                                        <dd
                                            class="text-sm text-green-900 dark:text-green-100"
                                        >
                                            {{ user.email }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt
                                            class="text-sm font-medium text-green-700 dark:text-green-300"
                                        >
                                            Role
                                        </dt>
                                        <dd
                                            class="text-sm text-green-900 dark:text-green-100"
                                        >
                                            <span
                                                class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-800/20 dark:text-gray-300"
                                            >
                                                👤
                                                {{
                                                    user.roles[0]?.name?.toUpperCase() ||
                                                    'No Role'
                                                }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt
                                            class="text-sm font-medium text-green-700 dark:text-green-300"
                                        >
                                            Role Info
                                        </dt>
                                        <dd
                                            class="text-sm text-green-900 dark:text-green-100"
                                        >
                                            {{ role?.label }} -
                                            {{ role?.description }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Access Level Info -->
                            <div
                                class="rounded-md bg-blue-50 p-4 dark:bg-blue-900/20"
                            >
                                <h3
                                    class="mb-3 text-lg font-medium text-blue-900 dark:text-blue-200"
                                >
                                    Your Access Permissions
                                </h3>
                                <div class="space-y-3">
                                    <!-- Admin Check -->
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <span
                                            class="text-sm text-blue-900 dark:text-blue-100"
                                            >Admin Test Area</span
                                        >
                                        <span
                                            v-if="
                                                user.roles[0]?.name === 'admin'
                                            "
                                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-800/20 dark:text-green-300"
                                        >
                                            ✅ ALLOWED
                                        </span>
                                        <span
                                            v-else
                                            class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-800/20 dark:text-red-300"
                                        >
                                            ❌ BLOCKED
                                        </span>
                                    </div>

                                    <!-- Editor Check -->
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <span
                                            class="text-sm text-blue-900 dark:text-blue-100"
                                            >Editor Test Area</span
                                        >
                                        <span
                                            v-if="
                                                ['admin', 'editor'].includes(
                                                    user.roles[0]?.name,
                                                )
                                            "
                                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-800/20 dark:text-green-300"
                                        >
                                            ✅ ALLOWED
                                        </span>
                                        <span
                                            v-else
                                            class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-800/20 dark:text-red-300"
                                        >
                                            ❌ BLOCKED
                                        </span>
                                    </div>

                                    <!-- User Check -->
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <span
                                            class="text-sm text-blue-900 dark:text-blue-100"
                                            >User Test Area</span
                                        >
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-800/20 dark:text-green-300"
                                        >
                                            ✅ ALLOWED (Current Page)
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Test Navigation (conditional based on role) -->
                            <div
                                class="rounded-md bg-gray-50 p-4 dark:bg-gray-700"
                            >
                                <h3
                                    class="mb-3 text-lg font-medium text-gray-900 dark:text-gray-200"
                                >
                                    Test Access
                                </h3>
                                <div class="space-y-2">
                                    <!-- Admin link (only if admin) -->
                                    <div v-if="user.roles[0]?.name === 'admin'">
                                        <Link
                                            :href="route('admin.test')"
                                            class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500"
                                        >
                                            Test Admin Area
                                        </Link>
                                        <span
                                            class="ml-2 text-sm text-gray-600 dark:text-gray-400"
                                        >
                                            (You have admin access!)
                                        </span>
                                    </div>

                                    <!-- Editor link (if admin or editor) -->
                                    <div
                                        v-if="
                                            ['admin', 'editor'].includes(
                                                user.roles[0]?.name,
                                            )
                                        "
                                    >
                                        <Link
                                            :href="route('editor.test')"
                                            class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500"
                                        >
                                            Test Editor Area
                                        </Link>
                                        <span
                                            class="ml-2 text-sm text-gray-600 dark:text-gray-400"
                                        >
                                            (You have editor access!)
                                        </span>
                                    </div>

                                    <!-- Dashboard (always available) -->
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

interface Role {
    name: string;
    label: string;
    description: string;
}

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
    role: Role;
}

defineProps<Props>();
</script>
