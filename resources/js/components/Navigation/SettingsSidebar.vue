<script setup lang="ts">
import type { SidebarProps } from '@/components/ui/sidebar';
import { Sidebar, SidebarContent, useSidebar } from '@/components/ui/sidebar';
import { PageProps } from '@/types';
import type { Navigation } from '@/types/navigation';
import { usePage } from '@inertiajs/vue3';
import { computed, provide } from 'vue';
import NavGroup from './NavGroup.vue';

withDefaults(defineProps<SidebarProps>(), {
    collapsible: 'none',
    variant: 'inset',
});

const page = usePage<PageProps<{ navigation: Navigation }>>();
const { isMobile } = useSidebar();

// Show settings navigation
const items = computed(() => page.props.navigation?.settings || []);

// Disable tooltips for settings sidebar (it's never collapsible on desktop)
provide('showTooltip', false);
</script>

<template>
    <!-- Hide settings sidebar on mobile - use SettingsMobileMenu instead -->
    <Sidebar
        v-if="!isMobile"
        :variant="variant"
        :collapsible="collapsible"
        data-sidebar="settings-sidebar"
        class="round-rb-md bg-surface-50 ml-2 !h-full w-48 self-stretch"
        v-bind="$attrs"
    >
        <SidebarContent data-sidebar="content" class="">
            <NavGroup :items="items" />
        </SidebarContent>
    </Sidebar>
</template>
