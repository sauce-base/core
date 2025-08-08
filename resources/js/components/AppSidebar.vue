<script setup lang="ts">
import type { SidebarProps } from '@/components/ui/sidebar';

import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import TeamSwitcher from '@/components/TeamSwitcher.vue';
import { GalleryVerticalEnd, SquareTerminal, Users } from 'lucide-vue-next';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
} from '@/components/ui/sidebar';

import { useAuthStore } from '@/stores/auth';
import { computed } from 'vue';

const props = withDefaults(defineProps<SidebarProps>(), {
    collapsible: 'icon',
});

const authStore = useAuthStore();

// Reactive user data that updates when auth store changes
const userData = computed(() => ({
    name: authStore.user?.name || '',
    email: authStore.user?.email || '',
    avatar: authStore.user?.avatar || '',
}));

// Application data with real user context
const data = {
    teams: [
        {
            name: 'Sauce Base',
            logo: GalleryVerticalEnd,
            plan: 'SaaS',
        },
    ],
    navMain: [
        {
            title: 'Dashboard',
            url: '/dashboard',
            icon: SquareTerminal,
            isActive: route().current('dashboard'),
        },
        // Only show User Management for admins
        ...(authStore.isAdmin
            ? [
                  {
                      title: 'User Management',
                      url: '/admin/users',
                      icon: Users,
                      isActive: route().current('admin.users'),
                  },
              ]
            : []),
    ],
};
</script>

<template>
    <Sidebar v-bind="props">
        <SidebarHeader>
            <TeamSwitcher :teams="data.teams" />
        </SidebarHeader>
        <SidebarContent>
            <NavMain :items="data.navMain" />
        </SidebarContent>
        <SidebarFooter>
            <NavUser :user="userData" />
        </SidebarFooter>
        <SidebarRail />
    </Sidebar>
</template>
