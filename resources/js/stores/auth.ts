import type { User } from '@/types';
import { defineStore } from 'pinia';
import { computed, ref } from 'vue';

export const useAuthStore = defineStore(
    'auth',
    () => {
        const user = ref<User | null>(null);

        // Computed
        const isAuthenticated = computed(() => !!user.value);
        const userRoles = computed(() => user.value?.roles || []);
        const isAdmin = computed(() =>
            userRoles.value.some((role) => role.name === 'admin'),
        );

        // Actions
        const setUser = (userData: User) => {
            user.value = userData;
        };

        const clearUser = () => {
            user.value = null;
        };

        const hasRole = (roleName: string): boolean => {
            return userRoles.value.some((role) => role.name === roleName);
        };

        const hasPermission = (): boolean => {
            // Simple permission check - roles don't have permissions in current types
            return isAdmin.value;
        };

        return {
            user,
            isAuthenticated,
            userRoles,
            isAdmin,
            setUser,
            clearUser,
            hasRole,
            hasPermission,
        };
    },
    {
        persist: true,
    },
);
