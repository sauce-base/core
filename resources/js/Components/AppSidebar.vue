<script setup lang="ts">
import type { SidebarProps } from '@/Components/ui/sidebar';

import NavMain from '@/Components/NavMain.vue';
import NavUser from '@/Components/NavUser.vue';
import TeamSwitcher from '@/Components/TeamSwitcher.vue';
import { GalleryVerticalEnd, SquareTerminal } from 'lucide-vue-next';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
} from '@/Components/ui/sidebar';

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
        avatar: '/avatars/default.jpg',
    },
    teams: [
        {
            name: 'Tadone',
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
