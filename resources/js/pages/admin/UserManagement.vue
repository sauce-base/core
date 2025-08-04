<script setup lang="ts">
import RoleSelect from '@/components/RoleSelect.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import type { Role, RoleValue, UserWithRole } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Edit, PenTool, Shield, User as UserIcon } from 'lucide-vue-next';
import { ref } from 'vue';

interface Props {
    users: UserWithRole[];
    roles: Role[];
    currentUserId: number;
}

const props = defineProps<Props>();
const users = ref([...props.users]);

const updateUserRole = (
    userId: number,
    newRole: { name: RoleValue; label: string },
) => {
    const userIndex = users.value.findIndex((user) => user.id === userId);
    if (userIndex !== -1) {
        users.value[userIndex].role = newRole;
    }
};

const getRoleIcon = (roleValue: RoleValue) => {
    switch (roleValue) {
        case 'admin':
            return Shield;
        case 'editor':
            return Edit;
        case 'author':
            return PenTool;
        case 'user':
        default:
            return UserIcon;
    }
};

const getRoleBadgeColor = (roleValue: RoleValue) => {
    switch (roleValue) {
        case 'admin':
            return 'bg-red-100 text-red-800';
        case 'editor':
            return 'bg-blue-100 text-blue-800';
        case 'author':
            return 'bg-green-100 text-green-800';
        case 'user':
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const getInitials = (name: string) => {
    return name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase();
};
</script>

<template>
    <Head title="User Management" />

    <AuthenticatedLayout title="User Management">
        <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
            <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                <div class="mb-6">
                    <h2
                        class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                    >
                        User Management
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Manage user roles and permissions for your application.
                    </p>
                </div>

                <div
                    class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700"
                >
                    <table
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                    >
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                                >
                                    User
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Role
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Joined
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900"
                        >
                            <tr
                                v-for="user in users"
                                :key="user.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-800"
                                :class="{
                                    'bg-blue-50 dark:bg-blue-900/20':
                                        user.id === currentUserId,
                                }"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <Avatar class="h-10 w-10">
                                            <AvatarImage
                                                :src="user.avatar_url || ''"
                                                :alt="user.name"
                                            />
                                            <AvatarFallback>
                                                {{ getInitials(user.name) }}
                                            </AvatarFallback>
                                        </Avatar>
                                        <div class="ml-4">
                                            <div
                                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                            >
                                                {{ user.name }}
                                                <span
                                                    v-if="
                                                        user.id ===
                                                        currentUserId
                                                    "
                                                    class="ml-2 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800"
                                                >
                                                    You
                                                </span>
                                            </div>
                                            <div
                                                class="text-sm text-gray-500 dark:text-gray-400"
                                            >
                                                {{ user.email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold"
                                        :class="
                                            getRoleBadgeColor(user.role.name)
                                        "
                                    >
                                        <component
                                            :is="getRoleIcon(user.role.name)"
                                            class="mr-1 h-3 w-3"
                                        />
                                        {{ user.role.label }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400"
                                >
                                    {{ user.created_at }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <RoleSelect
                                        v-if="user.id !== currentUserId"
                                        :user="user"
                                        :roles="roles"
                                        @role-updated="updateUserRole"
                                    />
                                    <span
                                        v-else
                                        class="text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        Cannot change own role
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        Showing {{ users.length }} users
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
