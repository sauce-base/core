<script setup lang="ts">
import type { SidebarProps } from '@/components/ui/sidebar';

import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import TeamSwitcher from '@/components/TeamSwitcher.vue';
import {
    GalleryVerticalEnd,
    ShieldAlert,
    SquareTerminal,
    Users,
} from 'lucide-vue-next';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
} from '@/components/ui/sidebar';

import type { User } from '@/types';
import { usePage } from '@inertiajs/vue3';

const props = withDefaults(defineProps<SidebarProps>(), {
    collapsible: 'icon',
});

const page = usePage();

// Application data with real user context
const data = {
    user: {
        name: page.props.auth.user.name,
        email: page.props.auth.user.email,
        avatar: page.props.auth.user.avatar,
    },
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
        {
            title: 'Auth',
            url: '/auth',
            icon: ShieldAlert,
            isActive: route().current('auth'),
        },
        // Only show User Management for admins
        ...((page.props.auth.user as User).roles?.some(
            (role) => role.name === 'admin',
        )
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
            <NavUser :user="data.user" />
        </SidebarFooter>
        <SidebarRail />
    </Sidebar>
</template>
