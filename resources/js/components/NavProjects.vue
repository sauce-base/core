<script setup lang="ts">
import {
    Folder,
    Forward,
    type LucideIcon,
    MoreHorizontal,
    Trash2,
} from 'lucide-vue-next';

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuAction,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';

defineProps<{
    projects: {
        name: string;
        url: string;
        icon: LucideIcon;
    }[];
}>();

const { isMobile } = useSidebar();
</script>

<template>
    <SidebarGroup class="group-data-[collapsible=icon]:hidden">
        <SidebarGroupLabel>{{ $t('Projects') }}</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in projects" :key="item.name">
                <SidebarMenuButton as-child>
                    <a :href="item.url">
                        <component :is="item.icon" />
                        <span>{{ item.name }}</span>
                    </a>
                </SidebarMenuButton>
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <SidebarMenuAction show-on-hover>
                            <MoreHorizontal />
                            <span class="sr-only">{{ $t('More') }}</span>
                        </SidebarMenuAction>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent
                        class="w-48 rounded-lg"
                        :side="isMobile ? 'bottom' : 'right'"
                        :align="isMobile ? 'end' : 'start'"
                    >
                        <DropdownMenuItem>
                            <Folder class="text-muted-foreground" />
                            <span>{{ $t('View Project') }}</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem>
                            <Forward class="text-muted-foreground" />
                            <span>{{ $t('Share Project') }}</span>
                        </DropdownMenuItem>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem>
                            <Trash2 class="text-muted-foreground" />
                            <span>{{ $t('Delete Project') }}</span>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </SidebarMenuItem>
            <SidebarMenuItem>
                <SidebarMenuButton class="text-sidebar-foreground/70">
                    <MoreHorizontal class="text-sidebar-foreground/70" />
                    <span>{{ $t('More') }}</span>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
