<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { Role, RoleValue, UserWithRole } from '@/types';
import { router } from '@inertiajs/vue3';
import {
    Check,
    ChevronDown,
    Edit,
    PenTool,
    Shield,
    User as UserIcon,
} from 'lucide-vue-next';
import { ref } from 'vue';

interface Props {
    user: UserWithRole;
    roles: Role[];
}

const props = defineProps<Props>();
const emit = defineEmits<{
    'role-updated': [
        userId: number,
        newRole: { name: RoleValue; label: string },
    ];
}>();

const isUpdating = ref(false);

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

const getRoleColor = (roleValue: RoleValue) => {
    switch (roleValue) {
        case 'admin':
            return 'text-red-600';
        case 'editor':
            return 'text-blue-600';
        case 'author':
            return 'text-green-600';
        case 'user':
        default:
            return 'text-gray-600';
    }
};

const updateRole = async (newRoleValue: RoleValue) => {
    if (newRoleValue === props.user.role.name) {
        return; // No change needed
    }

    isUpdating.value = true;

    try {
        await router.patch(
            route('admin.users.role', props.user.id),
            {
                role: newRoleValue,
            },
            {
                preserveScroll: true,
                onSuccess: (page) => {
                    const newRole = props.roles.find(
                        (role) => role.value === newRoleValue,
                    );
                    if (newRole) {
                        emit('role-updated', props.user.id, {
                            name: newRole.value,
                            label: newRole.label,
                        });
                    }

                    // Show success message if available
                    if (
                        page.props.flash &&
                        typeof page.props.flash === 'object' &&
                        page.props.flash !== null &&
                        'message' in page.props.flash
                    ) {
                        // You could use a toast notification here instead
                        console.log(
                            'Success:',
                            (page.props.flash as any).message,
                        );
                    }
                },
                onError: (errors: any) => {
                    console.error('Failed to update user role:', errors);
                    // Handle validation errors
                    if (errors.role) {
                        alert(errors.role);
                    }
                },
            },
        );
    } catch (error) {
        console.error('Error updating role:', error);
    } finally {
        isUpdating.value = false;
    }
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="outline" size="sm" :disabled="isUpdating">
                {{ isUpdating ? $t('Updating...') : $t('Change Role') }}
                <ChevronDown class="ml-2 h-4 w-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-48">
            <DropdownMenuItem
                v-for="role in roles"
                :key="role.value"
                @click="updateRole(role.value)"
                :class="{
                    'bg-accent': role.value === user.role.name,
                    'cursor-not-allowed opacity-50': isUpdating,
                }"
                :disabled="isUpdating"
            >
                <component
                    :is="getRoleIcon(role.value)"
                    class="mr-2 h-4 w-4"
                    :class="getRoleColor(role.value)"
                />
                <span class="flex-1">{{ role.label }}</span>
                <Check
                    v-if="role.value === user.role.name"
                    class="ml-2 h-4 w-4 text-green-600"
                />
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
