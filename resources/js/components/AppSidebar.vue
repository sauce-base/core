<script setup lang="ts">
import type { SidebarProps } from '@/components/ui/sidebar';

import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import TeamSwitcher from '@/components/TeamSwitcher.vue';
import { GalleryVerticalEnd, SquareTerminal } from 'lucide-vue-next';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
} from '@/components/ui/sidebar';

// import { useAuthStore } from '@modules/Auth/resources/js/stores'; //TODO: remove this link
import { usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';

const props = withDefaults(defineProps<SidebarProps>(), {
    collapsible: 'icon',
});

// const authStore = useAuthStore();

// Reactive user data that updates when auth store changes
const page = usePage();

const user = computed(() => page.props.auth.user);

// Application data with real user context - using computed for reactivity with translations
const data = computed(() => ({
    teams: [
        {
            name: 'Sauce Base',
            logo: GalleryVerticalEnd,
            plan: 'SaaS',
        },
    ],
    navMain: [
        {
            title: trans('Dashboard'),
            url: '/dashboard',
            icon: SquareTerminal,
            isActive: route().current('dashboard'),
        },
    ],
}));
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
            <NavUser :user="user" />
        </SidebarFooter>
        <SidebarRail />
    </Sidebar>
</template>
